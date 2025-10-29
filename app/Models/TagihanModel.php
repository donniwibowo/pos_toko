<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanModel extends Model
{
    protected $table = 'tbl_tagihan';
    protected $primaryKey = 'tagihan_id';
    protected $allowedFields = ['tagihan_id', 'supplier_id', 'no_nota', 'jumlah_tagihan', 'tempo_pembayaran', 'tgl_datang', 'tgl_jatuh_tempo', 'status', 'tgl_bayar', 'metode_pembayaran', 'rekening', 'jumlah_bayar', 'remarks', 'tgl_dibuat', 'dibuat_oleh', 'is_deleted'];


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