# CHANGELOG

Project : Teripang Management System

Repository : GitHub

==================================================================
## 2026-07-05

### Sale Module

Business History Rule LOCKED.

Decision:

Latest Sale is determined by:

- sale_date DESC
- sales.id DESC

Never determine history using:

- sale_items.id
- sale_items.created_at
- sale_items.updated_at

Reason:

Editing an invoice must never change business history.

ShipmentItem must always follow the latest Sale invoice.

STATUS

LOCK
## 2026-07-05

### Initial Development

- Membuat struktur project Laravel 12.
- Konfigurasi Bootstrap.
- Konfigurasi database.
- Membuat struktur MVC.
- Menyiapkan Service Layer Architecture.

STATUS

DONE

==================================================================

## Master Module

- Master Supplier
- Master Sea Cucumber Type
- Master Investor

CRUD selesai.

Validation selesai.

Controller selesai.

Service selesai.

STATUS

LOCK

==================================================================

## Purchase Module

Selesai membuat:

- Purchase
- Purchase Item
- Purchase CRUD
- Purchase Service
- Purchase Controller
- Purchase Validation
- Purchase Relationship

STATUS

LOCK

==================================================================

## Shipment Module

Selesai membuat:

- Shipment
- Shipment Item
- Shipment CRUD
- Shipment Service
- Shipment Controller
- Shipment Status

Business Rule dikunci:

Shipment bukan Inventory.

Shipment adalah kumpulan Purchase.

Shipment siap dijual setelah sampai Gudang Makassar.

STATUS

LOCK

==================================================================

## Business Rule Locked

Business Flow

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

Tidak boleh diubah tanpa persetujuan user.

==================================================================

Business Rule

- Shipment bukan inventory.
- Sale berasal dari Shipment.
- Tidak ada Stock In.
- Tidak ada Stock Out.
- Tidak ada Available Stock.
- Berat final berasal dari transaksi Sale.
- ShipmentItem mengikuti status Sale terakhir.
- Shipment selesai jika seluruh ShipmentItem selesai.

STATUS

LOCK

==================================================================

## BUSINESS HISTORY RULE (LOCKED)

Business history is determined by Sale, not SaleItem.

Latest Sale MUST be determined using:

1. sale_date DESC
2. sales.id DESC (tie breaker)

The following MUST NEVER be used to determine business history:

- sale_items.id
- sale_items.created_at
- sale_items.updated_at

Editing a Sale invoice only changes the invoice content.

Editing a Sale invoice MUST NEVER change business history.

ShipmentItem.status MUST ALWAYS follow the latest Sale invoice according to this rule.

STATUS

LOCK

==================================================================
## UX Locked

Flow Sale

Menu Sale

↓

Pilih Shipment

↓

Buat Sale

↓

Input Item

↓

Input Berat

↓

Input Harga

↓

Input Status

↓

Save

STATUS

LOCK

==================================================================

## Current Development

Sprint 4

Sale Module

Sedang dikerjakan.

Target saat ini:

- SaleService
- Testing
- Lock Sale

==================================================================

## Next Module

Cash

Expense

Profit

Dashboard

Belum dikerjakan.

==================================================================

END OF FILE