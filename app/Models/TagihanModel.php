<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanModel extends Model
{
    protected $table = 'tbl_tagihan';
    protected $primaryKey = 'tagihan_id';
    protected $allowedFields = ['tagihan_id', 'supplier_id', 'no_nota', 'jumlah_tagihan', 'tempo_pembayaran', 'tgl_datang', 'tgl_jatuh_tempo', 'status', 'tgl_bayar', 'metode_pembayaran', 'rekening', 'jumlah_bayar', 'remarks', 'tgl_dibuat', 'dibuat_oleh', 'is_deleted'];


    public function getFormRules() {
        $rules = [];

        return $rules;
    }
   
}