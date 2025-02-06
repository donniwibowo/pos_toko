<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'tbl_setting';
    protected $primaryKey = 'setting_id';
    protected $allowedFields = ['setting_name', 'setting_value', 'tgl_diupdate', 'diupdate_oleh'];


    public function getFormRules() {
        $rules = [
            'setting_name' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Nama pengaturan wajib diisi!'
                ]
            ],
            'setting_value' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Isi pengaturan wajib diisi!'
                ]
            ],
        ];

        return $rules;
    }
   
}