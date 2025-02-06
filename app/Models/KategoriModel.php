<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'tbl_kategori';
    protected $primaryKey = 'kategori_id';
    protected $allowedFields = ['nama_kategori', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];


    public function getFormRules() {
        $rules = [
            'nama_kategori' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Nama kategori wajib diisi!'
                ]
            ],
        ];

        return $rules;
    }
   
}