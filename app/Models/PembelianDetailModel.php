<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianDetailModel extends Model
{
    protected $table = 'tbl_pembelian_detail';
    protected $primaryKey = 'pembelian_detail_id';
    protected $allowedFields = ['pembelian_id', 'produk_id', 'qty', 'harga_beli', 'is_deleted'];


    public function getFormRules() {
        $rules = [];

        return $rules;
    }
   
}