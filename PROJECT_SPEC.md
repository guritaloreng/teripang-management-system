# PROJECT SPECIFICATION

# Teripang Management System

Version : 1.0

Status : ACTIVE DEVELOPMENT

Repository : GitHub

Framework : Laravel 12

Architecture : MVC + Service Layer

==================================================================

## PROJECT STATUS

Project ini SUDAH BERJALAN.

JANGAN membuat project baru.

JANGAN membuat ulang source code.

JANGAN mengubah database tanpa persetujuan user.

Repository GitHub adalah SOURCE OF TRUTH.

Selalu audit source code terlebih dahulu.

==================================================================

## PROJECT PURPOSE

Membangun ERP Internal untuk bisnis pembelian dan penjualan teripang.

Aplikasi ini BUKAN inventory.

Fokus ERP :

Supplier

Purchase

Shipment

Sale

Cash

Expense

Profit

Dashboard

==================================================================

## BUSINESS FLOW

Supplier

↓

Purchase

↓

Shipment

↓

Sale

↓

Cash

↓

Expense

↓

Profit

↓

Dashboard

Business Flow ini FINAL.

Tidak boleh diubah tanpa persetujuan user.

==================================================================

## MASTER DATA

Master Supplier

Master Sea Cucumber Type

Master Investor

Semua Master hanya CRUD biasa.

==================================================================

## PURCHASE

Purchase mencatat transaksi pembelian.

Purchase mempunyai:

- purchase_number

- supplier

- investor

- purchase_date

- note

Purchase Item:

- sea_cucumber_type

- weight

- price

- subtotal

Purchase BUKAN inventory.

==================================================================

## SHIPMENT

Shipment adalah kumpulan Purchase.

Contoh:

Purchase 001

Purchase 002

Purchase 003

↓

Shipment 001

Shipment berarti barang sudah berada di Gudang Makassar.

Shipment BUKAN inventory.

Shipment memiliki:

shipment_number

shipment_date

destination

shipping_cost

note

ShipmentItem memiliki:

sea_cucumber_type

weight

status

Status ShipmentItem:

Belum Dijual

Terjual Sebagian

Selesai

Status Shipment:

Draft

Berjalan

Selesai

==================================================================

## SALE

Sale SELALU berasal dari Shipment.

BUKAN dari Purchase.

Satu Shipment dapat memiliki banyak Sale.

Satu Sale boleh memiliki banyak jenis.

Satu jenis tidak boleh muncul dua kali dalam invoice yang sama.

==================================================================

## BERAT

Berat Purchase bukan acuan.

Karena bisnis teripang mengalami susut.

Berat final berasal dari timbang saat Sale.

Tidak ada stock calculation.

Tidak ada stock in.

Tidak ada stock out.

Tidak ada available stock.

==================================================================

## RULE PALING PENTING

ShipmentItem.status

SELALU mengikuti status Sale TERAKHIR.

Contoh:

Sale 001

Pasir

Terjual Sebagian

↓

ShipmentItem

Terjual Sebagian

↓

Sale 002

Pasir

Selesai

↓

ShipmentItem

Selesai

↓

Delete Sale 002

↓

ShipmentItem kembali menjadi

Terjual Sebagian

==================================================================

## EDIT SALE

Invoice boleh diedit.

Yang boleh diedit:

Buyer

Tanggal

Harga

Berat

Status

Item

Note

Tidak ada batas waktu edit.

==================================================================

## DELETE SALE

Delete Sale harus:

hapus SaleItem

hapus Sale

refresh ShipmentItem

refresh Shipment

rollback Cash (nantinya)

refresh Profit (nantinya)

==================================================================

## CASH

Sale dibuat

↓

Cash In

Sale dihapus

↓

Cash Rollback

==================================================================

## EXPENSE

Expense dibagi menjadi:

Shipment Expense

General Expense

==================================================================

## PROFIT

Profit dihitung setiap Sale.

Dashboard boleh menampilkan Profit Sementara.

Shipment selesai

↓

Profit Final.

==================================================================

## DASHBOARD

Dashboard menampilkan:

Total Purchase

Total Sale

Cash

Expense

Profit Sementara

Profit Final

Shipment Berjalan

Shipment Selesai

==================================================================

## UX SALE

Menu Sale

↓

Pilih Shipment

↓

Buat Invoice

↓

Input Buyer

↓

Input Item

↓

Input Berat

↓

Input Harga

↓

Pilih Status

↓

Save

==================================================================

## SERVICE LAYER

Semua Business Logic berada di Service.

Controller hanya:

- Validate Request

- Call Service

- Redirect

Service:

PurchaseService

ShipmentService

SaleService

CashService

ExpenseService

ProfitService

DashboardService

==================================================================

## DEVELOPMENT RULE

Selalu audit file terlebih dahulu.

Gunakan source code yang sudah ada.

Revisi seminimal mungkin.

Jangan mengubah business flow.

Jangan mengubah UX.

Jangan mengubah database tanpa persetujuan user.

Selalu menjaga kompatibilitas project.

==================================================================

## CURRENT TARGET

Fokus sekarang HANYA menyelesaikan:

Sale Module

Setelah Sale selesai dan LOCK,

baru lanjut:

Cash

Expense

Profit

Dashboard

==================================================================

## Design Philosophy

This ERP is designed for internal operational use.

Priority:

1. Simplicity
2. Readability
3. Maintainability
4. Business accuracy
5. Performance

Avoid over-engineering.

Avoid unnecessary abstraction.

Avoid unnecessary database tables.

Every feature must solve a real business problem.

If a simpler solution exists and still satisfies the business requirements, prefer the simpler solution.

==================================================================
## Cash Transaction Rule (LOCKED)

CashTransaction represents historical cash movement.

CashTransaction must never be edited manually.

If a business transaction changes:

- Sale updated

→ update through CashService

If a business transaction is cancelled:

- create reversal transaction

Never edit historical cash records manually.

Never delete financial history.
==================================================================

END OF DOCUMENT