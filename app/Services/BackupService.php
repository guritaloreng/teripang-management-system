<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\InvestorLedger;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SeaCucumberType;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class BackupService
{
    public function __construct(
        protected CashService $cashService,
        protected ProfitService $profitService
    ) {
    }

    public function createBackup(): array
    {
        $generatedAt = now('Asia/Shanghai');
        $date = $generatedAt->format('Y-m-d');
        $folderName = 'Backup_' . $generatedAt->format('Y-m-d_H-i');
        $folderPath = storage_path('app/backups/' . $folderName);

        $this->cleanupTemporaryFiles();

        File::ensureDirectoryExists($folderPath);

        $excelName = 'ERP_Backup_' . $date . '.xlsx';
        $sqlName = 'Database_Backup_' . $date . '.sql';

        $sheets = $this->sheets();

        $this->writeXlsx(
            $folderPath . DIRECTORY_SEPARATOR . $excelName,
            $sheets
        );

        $this->writeSql(
            $folderPath . DIRECTORY_SEPARATOR . $sqlName,
            $generatedAt
        );

        return [
            'folder_name' => $folderName,
            'folder_path' => $folderPath,
            'excel_name' => $excelName,
            'sql_name' => $sqlName,
            'sheets' => collect($sheets)
                ->mapWithKeys(fn ($sheet) => [$sheet['name'] => count($sheet['rows'])])
                ->toArray(),
        ];
    }

    protected function sheets(): array
    {
        return [
            $this->dashboardSheet(),
            $this->purchaseListSheet(),
            $this->saleListSheet(),
            $this->expenseListSheet(),
            $this->cashBookSheet(),
            $this->investorLedgerSheet(),
            $this->supplierListSheet(),
            $this->seaCucumberTypeListSheet(),
        ];
    }

    protected function dashboardSheet(): array
    {
        $cashSummary = $this->cashService->summary(
            $this->cashService->transactions()
        );

        $companyProfit = $this->profitService->companyProfit();

        $investorBalances = InvestorLedger::query()
            ->select('investor_id')
            ->selectRaw("
                SUM(
                    CASE
                        WHEN transaction_type = 'deposit' THEN amount
                        WHEN transaction_type = 'withdraw' THEN -amount
                        ELSE 0
                    END
                ) as balance
            ")
            ->groupBy('investor_id')
            ->get();

        $activeInvestorBalances = $investorBalances
            ->filter(fn ($ledger) => (float) $ledger->balance > 0);

        return [
            'name' => 'Dashboard / 仪表盘',
            'rows' => [
                ['Metrik / 指标', 'Nilai / 数值'],
                ['Current Cash / 当前现金', $cashSummary['ending_balance']],
                ['Total Purchase / 采购总额', $companyProfit['purchase']],
                ['Total Sale / 销售总额', $companyProfit['sale']],
                ['Gross Profit / 毛利润', $companyProfit['gross_profit']],
                ['Operational Expense / 运营费用', $companyProfit['operational_expense']],
                ['Net Profit / 净利润', $companyProfit['net_profit']],
                ['Investor Aktif / 活跃投资人', $activeInvestorBalances->count()],
                ['Modal Aktif / 当前投资金额', (float) $activeInvestorBalances->sum('balance')],
            ],
        ];
    }

    protected function purchaseListSheet(): array
    {
        $rows = [[
            'No Nota / 单号',
            'Nota Supplier / 供应商单据',
            'Tanggal / 日期',
            'Supplier / 供应商',
            'Jenis Teripang / 海参种类',
            'Berat / 重量',
            'Harga / 单价',
            'Subtotal / 小计',
            'Total / 总计',
            'Catatan / 备注',
        ]];

        Purchase::with(['supplier', 'items.type'])
            ->latest('purchase_date')
            ->latest('id')
            ->get()
            ->each(function (Purchase $purchase) use (&$rows) {
                if ($purchase->items->isEmpty()) {
                    $rows[] = [
                        $purchase->purchase_number,
                        $purchase->supplier_invoice,
                        $this->date($purchase->purchase_date),
                        $purchase->supplier->name ?? '',
                        '',
                        '',
                        '',
                        '',
                        (float) $purchase->grand_total,
                        $purchase->note,
                    ];

                    return;
                }

                foreach ($purchase->items as $index => $item) {
                    $rows[] = [
                        $purchase->purchase_number,
                        $purchase->supplier_invoice,
                        $this->date($purchase->purchase_date),
                        $purchase->supplier->name ?? '',
                        $item->type->name ?? '',
                        (float) $item->purchase_weight,
                        (float) $item->price_per_kg,
                        (float) $item->subtotal,
                        $index === 0 ? (float) $purchase->grand_total : '',
                        $purchase->note,
                    ];
                }
            });

        return [
            'name' => 'Purchase List / 采购列表',
            'rows' => $rows,
        ];
    }

    protected function saleListSheet(): array
    {
        $rows = [[
            'No / 序号',
            'No Invoice / 发票',
            'Tanggal / 日期',
            'Buyer / 买家',
            'Jenis Teripang / 海参种类',
            'Berat / 重量',
            'Harga / 单价',
            'Subtotal / 小计',
            'Total / 总计',
        ]];

        Sale::with('items.type')
            ->latest()
            ->get()
            ->each(function (Sale $sale, int $index) use (&$rows) {
                if ($sale->items->isEmpty()) {
                    $rows[] = [
                        $index + 1,
                        $sale->invoice_number,
                        $this->date($sale->sale_date),
                        $sale->buyer,
                        '',
                        '',
                        '',
                        '',
                        0,
                    ];

                    return;
                }

                foreach ($sale->items as $itemIndex => $item) {
                    $rows[] = [
                        $index + 1,
                        $sale->invoice_number,
                        $this->date($sale->sale_date),
                        $sale->buyer,
                        $item->type->name ?? '',
                        (float) $item->weight,
                        (float) $item->price,
                        (float) $item->subtotal,
                        $itemIndex === 0 ? (float) $sale->items->sum('subtotal') : '',
                    ];
                }
            });

        return [
            'name' => 'Sale List / 销售列表',
            'rows' => $rows,
        ];
    }

    protected function expenseListSheet(): array
    {
        $rows = [[
            'Tanggal / 日期',
            'Biaya Operasional / 运营费用',
            'Nominal / 金额',
            'Keterangan / 说明',
        ]];

        Expense::query()
            ->latest('expense_date')
            ->get()
            ->each(function (Expense $expense) use (&$rows) {
                $rows[] = [
                    $this->date($expense->expense_date),
                    $expense->expense_name,
                    (float) $expense->amount,
                    $expense->description,
                ];
            });

        return [
            'name' => 'Expense List / 费用列表',
            'rows' => $rows,
        ];
    }

    protected function cashBookSheet(): array
    {
        $transactions = $this->cashService->runningBalance(
            $this->cashService->transactions()
        );

        $rows = [[
            'Tanggal / 日期',
            'Jenis / 类型',
            'Keterangan / 说明',
            'Kas Masuk / 现金收入',
            'Kas Keluar / 现金支出',
            'Saldo / 余额',
        ]];

        foreach ($transactions as $transaction) {
            $rows[] = [
                $this->date($transaction->transaction_date),
                $transaction->transaction_type,
                $transaction->description,
                (float) $transaction->cash_in,
                (float) $transaction->cash_out,
                (float) $transaction->running_balance,
            ];
        }

        return [
            'name' => 'Cash Book / 现金簿',
            'rows' => $rows,
        ];
    }

    protected function investorLedgerSheet(): array
    {
        $rows = [[
            'No / 序号',
            'Tanggal / 日期',
            'Investor / 投资人',
            'Jenis / 类型',
            'Nominal / 金额',
            'Catatan / 备注',
        ]];

        InvestorLedger::with('investor')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get()
            ->each(function (InvestorLedger $ledger, int $index) use (&$rows) {
                $rows[] = [
                    $index + 1,
                    $this->date($ledger->transaction_date),
                    $ledger->investor->name ?? '',
                    $ledger->transaction_type === 'deposit'
                        ? 'Deposit / 存入'
                        : 'Withdraw / 提取',
                    (float) $ledger->amount,
                    $ledger->note ?: '-',
                ];
            });

        return [
            'name' => 'Investor Ledger / 投资记录',
            'rows' => $rows,
        ];
    }

    protected function supplierListSheet(): array
    {
        $rows = [[
            'No / 序号',
            'Supplier / 供应商',
            'Wilayah / 地区',
            'Telepon / 电话',
            'Catatan / 备注',
        ]];

        Supplier::orderBy('name')
            ->get()
            ->each(function (Supplier $supplier, int $index) use (&$rows) {
                $rows[] = [
                    $index + 1,
                    $supplier->name,
                    $supplier->region,
                    $supplier->phone,
                    $supplier->note,
                ];
            });

        return [
            'name' => 'Supplier List / 供应商列表',
            'rows' => $rows,
        ];
    }

    protected function seaCucumberTypeListSheet(): array
    {
        $rows = [[
            'No / 序号',
            'Jenis Teripang / 海参种类',
        ]];

        SeaCucumberType::orderBy('name')
            ->get()
            ->each(function (SeaCucumberType $type, int $index) use (&$rows) {
                $rows[] = [
                    $index + 1,
                    $type->name,
                ];
            });

        return [
            'name' => 'Sea Cucumber Type / 海参种类',
            'rows' => $rows,
        ];
    }

    protected function writeXlsx(string $path, array $sheets): void
    {
        $inputPath = storage_path('app/backups/backup_' . uniqid() . '.json');
        File::put(
            $inputPath,
            json_encode($sheets, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        $script = base_path('app/Support/backup_excel.py');
        $command = 'python '
            . escapeshellarg($script)
            . ' '
            . escapeshellarg($inputPath)
            . ' '
            . escapeshellarg($path)
            . ' 2>&1';

        exec($command, $output, $exitCode);

        File::delete($inputPath);

        if ($exitCode !== 0 || ! File::exists($path)) {
            throw new \RuntimeException(
                'Gagal membuat file Excel backup: ' . implode("\n", $output)
            );
        }
    }

    protected function writeSql(string $path, \DateTimeInterface $generatedAt): void
    {
        $pdo = DB::connection()->getPdo();
        $database = DB::connection()->getDatabaseName();
        $tables = collect(DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"'))
            ->map(fn ($row) => array_values((array) $row)[0])
            ->values();

        $sql = "-- Teripang ERP Database Backup\n";
        $sql .= "-- Database: {$database}\n";
        $sql .= "-- Generated at: " . $generatedAt->format('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $create = DB::selectOne('SHOW CREATE TABLE `' . str_replace('`', '``', $table) . '`');
            $createSql = (array) $create;

            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= $createSql['Create Table'] . ";\n\n";

            DB::table($table)
                ->orderBy($this->orderColumn(Schema::getColumnListing($table)))
                ->get()
                ->each(function ($row) use (&$sql, $table, $pdo) {
                    $data = (array) $row;
                    $columns = collect(array_keys($data))
                        ->map(fn ($column) => '`' . str_replace('`', '``', $column) . '`')
                        ->implode(', ');
                    $values = collect(array_values($data))
                        ->map(fn ($value) => $this->sqlValue($pdo, $value))
                        ->implode(', ');

                    $sql .= "INSERT INTO `{$table}` ({$columns}) VALUES ({$values});\n";
                });

            $sql .= "\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        File::put($path, $sql);
    }

    protected function cleanupTemporaryFiles(): void
    {
        $backupPath = storage_path('app/backups');

        if (! File::isDirectory($backupPath)) {
            return;
        }

        foreach (File::files($backupPath) as $file) {
            if (str_starts_with($file->getFilename(), 'backup_')
                && $file->getExtension() === 'json') {
                File::delete($file->getPathname());
            }
        }
    }

    protected function orderColumn(array $columns): string
    {
        return in_array('id', $columns, true)
            ? 'id'
            : $columns[0];
    }

    protected function date($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('d-m-Y');
        }

        return $value ? (string) $value : '';
    }

    protected function sqlValue(\PDO $pdo, $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        return $pdo->quote((string) $value);
    }
}
