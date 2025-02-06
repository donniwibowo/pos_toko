<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'tbl_supplier';
    protected $primaryKey = 'supplier_id';
    protected $allowedFields = ['nama_supplier', 'nama_sales', 'alamat', 'no_telp', 'email', 'tempo_pembayaran', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];

    public function getFormRules() {
        $rules = [
            'nama_supplier' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Nama supplier wajib diisi!'
                ]
            ],
            'nama_sales' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Nama sales wajib diisi!'
                ]
            ],
            // 'alamat' => [
            //     'rules'=> 'required',
            //     'errors' => [
            //         'required'=> 'Alamat wajib diisi!'
            //     ]
            // ],
            'no_telp' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'No Telp wajib diisi!'
                ]
            ],
            // 'email' => [
            //     'rules'=> 'valid_email',
            //     'errors' => [
            //         // 'required'=> 'Email wajib diisi!',
            //         'valid_email' => 'Format email tidak sesuai!'
            //     ]
            // ],
        ];

        return $rules;
    }
   
}