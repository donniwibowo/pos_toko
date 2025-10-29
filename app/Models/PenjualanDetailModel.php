<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanDetailModel extends Model
{
    protected $table = 'tbl_penjualan_detail';
    protected $primaryKey = 'penjualan_detail_id';
    protected $allowedFields = ['penjualan_detail_id', 'penjualan_id', 'produk_id', 'produk_harga_id', 'harga_beli', 'harga_jual', 'qty', 'diskon', 'tipe_diskon', 'is_deleted'];

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
    
    public function getFormRules() {
        $rules = [];

        return $rules;
    }
   
}