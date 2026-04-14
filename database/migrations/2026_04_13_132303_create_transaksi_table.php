<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_transaksi');
            $table->string('rekening_debet', 20);
            $table->string('rekening_kredit', 20);
            $table->text('keterangan_transaksi')->nullable();
            $table->bigInteger('jumlah');
            $table->foreignId('id_user')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->index('tgl_transaksi', 'idx_tgl');
            $table->index('rekening_debet', 'idx_debet');
            $table->index('rekening_kredit', 'idx_kredit');
        });

        // Foreign key ke chart_of_accounts
        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreign('rekening_debet')->references('kode_akun')->on('chart_of_accounts');
            $table->foreign('rekening_kredit')->references('kode_akun')->on('chart_of_accounts');
        });

        // Triggers untuk auto-update saldo
        DB::unprepared("
        CREATE TRIGGER create_saldo_debet AFTER INSERT ON transaksi FOR EACH ROW BEGIN
            INSERT INTO saldo (id, kode_akun, tahun, bulan, debet, kredit)
            VALUES (
                CONCAT(REPLACE(NEW.rekening_debet,'.',''), YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi),2,'0')),
                NEW.rekening_debet,
                YEAR(NEW.tgl_transaksi),
                LPAD(MONTH(NEW.tgl_transaksi),2,'0'),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_debet = NEW.rekening_debet AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_kredit = NEW.rekening_debet AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi))
            )
            ON DUPLICATE KEY UPDATE debet = VALUES(debet), kredit = VALUES(kredit);

            INSERT INTO saldo (id, kode_akun, tahun, bulan, debet, kredit)
            VALUES (
                CONCAT(REPLACE(NEW.rekening_kredit,'.',''), YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi),2,'0')),
                NEW.rekening_kredit,
                YEAR(NEW.tgl_transaksi),
                LPAD(MONTH(NEW.tgl_transaksi),2,'0'),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_debet = NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_kredit = NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi))
            )
            ON DUPLICATE KEY UPDATE debet = VALUES(debet), kredit = VALUES(kredit);
        END
        ");

        DB::unprepared("
        CREATE TRIGGER delete_saldo_debet AFTER DELETE ON transaksi FOR EACH ROW BEGIN
            INSERT INTO saldo (id, kode_akun, tahun, bulan, debet, kredit)
            VALUES (
                CONCAT(REPLACE(OLD.rekening_debet,'.',''), YEAR(OLD.tgl_transaksi), LPAD(MONTH(OLD.tgl_transaksi),2,'0')),
                OLD.rekening_debet,
                YEAR(OLD.tgl_transaksi),
                LPAD(MONTH(OLD.tgl_transaksi),2,'0'),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_debet = OLD.rekening_debet AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi)),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_kredit = OLD.rekening_debet AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi))
            )
            ON DUPLICATE KEY UPDATE debet = VALUES(debet), kredit = VALUES(kredit);

            INSERT INTO saldo (id, kode_akun, tahun, bulan, debet, kredit)
            VALUES (
                CONCAT(REPLACE(OLD.rekening_kredit,'.',''), YEAR(OLD.tgl_transaksi), LPAD(MONTH(OLD.tgl_transaksi),2,'0')),
                OLD.rekening_kredit,
                YEAR(OLD.tgl_transaksi),
                LPAD(MONTH(OLD.tgl_transaksi),2,'0'),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_debet = OLD.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi)),
                (SELECT SUM(jumlah) FROM transaksi WHERE rekening_kredit = OLD.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi))
            )
            ON DUPLICATE KEY UPDATE debet = VALUES(debet), kredit = VALUES(kredit);
        END
        ");

        DB::unprepared("
        CREATE TRIGGER update_saldo_debet AFTER UPDATE ON transaksi FOR EACH ROW BEGIN
            INSERT INTO saldo (id, kode_akun, tahun, bulan, debet, kredit)
            SELECT
                CONCAT(REPLACE(kode,'.',''), YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi),2,'0')),
                kode,
                YEAR(NEW.tgl_transaksi),
                LPAD(MONTH(NEW.tgl_transaksi),2,'0'),
                SUM(CASE WHEN rekening_debet  = kode THEN jumlah ELSE 0 END),
                SUM(CASE WHEN rekening_kredit = kode THEN jumlah ELSE 0 END)
            FROM transaksi
            CROSS JOIN (
                SELECT NEW.rekening_debet AS kode
                UNION
                SELECT NEW.rekening_kredit
            ) a
            WHERE tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)
            GROUP BY kode
            ON DUPLICATE KEY UPDATE debet = VALUES(debet), kredit = VALUES(kredit);
        END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS create_saldo_debet');
        DB::unprepared('DROP TRIGGER IF EXISTS delete_saldo_debet');
        DB::unprepared('DROP TRIGGER IF EXISTS update_saldo_debet');
        Schema::dropIfExists('transaksi');
    }
};
