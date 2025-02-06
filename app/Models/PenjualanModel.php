<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'tbl_penjualan';
    protected $primaryKey = 'penjualan_id';
    protected $allowedFields = ['penjualan_id', 'total_bayar', 'jumlah_bayar', 'metode_pembayaran', 'status_pembayaran', 'midtrans_id', 'midtrans_status', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];


    public function getFormRules() {
        $rules = [];

        return $rules;
    }
   
}