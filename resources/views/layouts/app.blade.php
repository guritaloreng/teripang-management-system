<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Teripang Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        *{
            font-family:Segoe UI,Arial,sans-serif;
        }

        body{
            background:#f4f6f9;
            margin:0;
            color:#212529;
            font-size:14px;
        }

        .navbar{
            height:60px;
            background:#1f2937;
            color:white;
            display:flex;
            align-items:center;
            padding:0 25px;
            font-size:22px;
            font-weight:bold;
        }

        .container-app{
            display:flex;
            min-height:calc(100vh - 60px);
        }

        .sidebar{
            width:270px;
            background:#27374D;
            color:white;
            padding:25px;
        }

        .sidebar h6{
            margin-top:25px;
            margin-bottom:10px;
            color:#A5D7E8;
            font-weight:bold;
        }

        .sidebar ul{
            list-style:none;
            padding:0;
            margin:0;
        }

        .sidebar li{
            margin:10px 0;
        }

        .sidebar a{
            color:white;
            text-decoration:none;
            display:block;
            padding:10px 12px;
            border-radius:8px;
            transition:.25s;
        }

        .sidebar a:hover{
            background:#526D82;
        }

        .content{
            flex:1;
            padding:30px;
        }

        h2{
            font-size:1.55rem;
            font-weight:700;
        }

        .card{
            border-radius:8px;
        }

        .card-body{
            padding:18px;
        }

        .btn{
            min-height:38px;
            padding-left:14px;
            padding-right:14px;
            border-radius:6px;
        }

        .btn-sm{
            min-height:31px;
            padding-left:10px;
            padding-right:10px;
        }

        .table > :not(caption) > * > *{
            padding:10px 12px;
            vertical-align:middle;
        }

        .form-label,
        label{
            font-weight:600;
            margin-bottom:6px;
        }

        .form-control,
        .form-select{
            min-height:40px;
        }

    </style>

</head>

<body>

<div class="navbar">

    Teripang Management System / 海参管理系统

</div>

<div class="container-app">

    <div class="sidebar">

        <ul>

            <li>

                <a href="{{ route('home') }}">

                    Dashboard / 仪表盘
                </a>

            </li>

        </ul>

        <h6>

            Master Data / 主数据
        </h6>

        <ul>

            <li>

                <a href="{{ route('suppliers.index') }}">

                    Supplier / 供应商
                </a>

            </li>

            <li>

                <a href="{{ route('sea-cucumber-types.index') }}">

                    Jenis Teripang / 海参种类

                </a>

            </li>

            <li>

                <a href="{{ route('investors.index') }}">

                    Investor / 投资人
                </a>

            </li>

            <li>

                <a href="{{ route('investor-ledgers.index') }}">

                    Investor Ledger / 投资记录
                </a>

            </li>

        </ul>

        <h6>

            Transaksi / 交易

        </h6>

        <ul>

            <li>

                <a href="{{ route('purchases.index') }}">

                    Purchase / 采购

                </a>

            </li>

            <li>

                <a href="{{ route('sales.index') }}">

                    Sale / 销售
                </a>

            </li>

            <li>

                <a href="{{ route('expenses.index') }}">

                    Expense / 支出

                </a>

            </li>

        </ul>

        <h6>

            Laporan / 报表

        </h6>

        <ul>

            <li>

                <a href="{{ route('cash-book.index') }}">

                    Cash Book / 现金簿
                </a>

            </li>

            <li>

                <a href="{{ route('stock-overview.index') }}">

                    Stock Overview / 库存概览
                </a>

            </li>

        </ul>

        <h6>

            Pengaturan / 设置

        </h6>

        <ul>

            <li>

                <a href="{{ route('backups.index') }}">

                    Backup Data / 数据备份

                </a>

            </li>

        </ul>

    </div>

    <div class="content">

        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
