<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'tbl_setting';
    protected $primaryKey = 'setting_id';
    protected $allowedFields = ['setting_name', 'setting_value', 'tgl_diupdate', 'diupdate_oleh'];


    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
    
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