<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanFreeItemModel extends Model
{
    protected $table = 'tbl_penjualan_free_item';
    protected $primaryKey = 'penjualan_free_item_id';
    protected $allowedFields = ['penjualan_id', 'free_product_id', 'free_product_name', 'harga_beli', 'harga_jual', 'qty', 'is_deleted'];

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