<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanFreeItemModel extends Model
{
    protected $table = 'tbl_penjualan_free_item';
    protected $primaryKey = 'penjualan_free_item_id';
    protected $allowedFields = ['penjualan_id', 'free_product_id', 'free_product_name', 'harga_beli', 'harga_jual', 'qty', 'is_deleted'];

    public function getFormRules() {
        $rules = [];

        return $rules;
    }
   
}