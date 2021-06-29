<?php

namespace System\Console\Commands\Migrate;

defined('DS') or exit('No direct script access.');

use System\Database as DB;

class Database
{
    /**
     * Catat migrasi kedalam tabel migrasi.
     *
     * @param string $package
     * @param string $name
     * @param int    $batch
     *
     * @return void
     */
    public function log($package, $name, $batch)
    {
        $this->table()->insert(compact('package', 'name', 'batch'));
    }

    /**
     * Hapus sebuah baris dari tabel migrasi.
     *
     * @param string $package
     * @param string $name
     *
     * @return void
     */
    public function delete($package, $name)
    {
        $this->table()->where_package_and_name($package, $name)->delete();
    }

    /**
     * Me-return array berisi batch migrasi terbaru.
     *
     * @return array
     */
    public function last()
    {
        return $this->table()->where_batch($this->batch())->order_by('name', 'desc')->get();
    }

    /**
     * Ambil list migrasi yang telah dijalankan oleh paket tertentu.
     *
     * @param string $package
     *
     * @return array
     */
    public function ran($package)
    {
        return $this->table()->where_package($package)->lists('name');
    }

    /**
     * Ambil ID batch terbaru dari tabel migrasi.
     *
     * @return int
     */
    public function batch()
    {
        return $this->table()->max('batch');
    }

    /**
     * Ambil instance query builder untuk tabel migrasi.
     *
     * @return System\Database\Query
     */
    protected function table()
    {
        return DB::connection()->table('rakit_migrations');
    }
}
