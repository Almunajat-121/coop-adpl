File duplikat migration transaksi dan ulasan dipindahkan ke _backup_migration_duplikat agar tidak bentrok saat migrate.

Pastikan hanya ada:
- 2025_06_16_000008_create_transaksi_table.php
- 2025_06_16_000009_create_ulasan_table.php

Lalu jalankan lagi: php artisan migrate:fresh --seed
