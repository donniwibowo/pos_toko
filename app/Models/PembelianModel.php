<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table = 'tbl_pembelian';
    protected $primaryKey = 'pembelian_id';
    protected $allowedFields = ['supplier_id', 'total_invoice', 'status', 'tgl_datang', 'tgl_jatuh_tempo', 'status_pembayaran', 'metode_pembayaran', 'tgl_bayar', 'bukti_pembayaran', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];

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