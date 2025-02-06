<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanDetailModel extends Model
{
    protected $table = 'tbl_penjualan_detail';
    protected $primaryKey = 'penjualan_detail_id';
    protected $allowedFields = ['penjualan_detail_id', 'penjualan_id', 'produk_id', 'produk_harga_id', 'harga_beli', 'harga_jual', 'qty', 'diskon', 'tipe_diskon', 'is_deleted'];

    public function getFormRules() {
        $rules = [];

        return $rules;
    }
   
}