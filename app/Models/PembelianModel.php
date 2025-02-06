<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table = 'tbl_pembelian';
    protected $primaryKey = 'pembelian_id';
    protected $allowedFields = ['supplier_id', 'total_invoice', 'status', 'tgl_datang', 'tgl_jatuh_tempo', 'status_pembayaran', 'metode_pembayaran', 'tgl_bayar', 'bukti_pembayaran', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];


    public function getFormRules() {
        $rules = [];

        return $rules;
    }
   
}