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

    </style>

</head>

<body>

<div class="navbar">

    🐚 Teripang Management System 海参管理系统

</div>

<div class="container-app">

    <div class="sidebar">

        <ul>

            <li>

                <a href="{{ route('home') }}">

                    🏠 Dashboard / 仪表盘

                </a>

            </li>

        </ul>

        <h6>

            📋 Master Data / 主数据

        </h6>

        <ul>

            <li>

                <a href="{{ route('suppliers.index') }}">

                    👨 Supplier / 供应商

                </a>

            </li>

            <li>

                <a href="{{ route('sea-cucumber-types.index') }}">

                    🐚 Jenis Teripang / 海参种类

                </a>

            </li>

            <li>

                <a href="{{ route('investors.index') }}">

                    💰 Investor / 投资人

                </a>

            </li>

        </ul>
                <h6>

            🧾 Transaksi / 交易

        </h6>

        <ul>

            <li>

                <a href="{{ route('purchases.index') }}">

                    📥 Nota Pembelian / 采购单

                </a>

            </li>

            <li>

                <a href="{{ route('shipments.index') }}">

                    🚚 Pengiriman / 发货

                </a>

            </li>

            <li>

                <a href="#">

                    📤 Nota Penjualan / 销售单

                </a>

            </li>

            <li>

                <a href="{{ route('expenses.index') }}">

                    💸 Pengeluaran / 支出

                </a>

            </li>

        </ul>

        <h6>

            📊 Laporan / 报表

        </h6>

        <ul>

            <li>

                <a href="#">

                    💵 Buku Kas / 现金账

                </a>

            </li>

            <li>

                <a href="#">

                    💵 Cash Flow / 现金流

                </a>

            </li>

            <li>

                <a href="#">

                    📈 Profit per Jenis / 每种利润

                </a>

            </li>

            <li>

                <a href="#">

                    📦 Profit Pengiriman / 每批利润

                </a>

            </li>

            <li>

                <a href="#">

                    📉 Susut / 损耗

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