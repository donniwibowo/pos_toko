<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tbl_user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['no_telp', 'password', 'nama', 'jabatan', 'is_superadmin', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];
   
    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
    
   public function getFormRules($is_new_data = true) {
        if($is_new_data) {
             $rules = [
                'no_telp' => [
                    'rules'=> 'required|is_unique[tbl_user.no_telp]',
                    'errors' => [
                        'required'=> 'No Telp wajib diisi.',
                        'is_unique' => 'No Telp sudah terdaftar.',
                    ]
                ],
                'password' => [
                    'rules'=> 'required|min_length[8]',
                    'errors' => [
                        'required'=> 'Password wajib diisi.',
                        'min_length'=> 'Panjang minimal password adalah 8 karakter.'
                    ]
                ],
                'confirm_password' => [
                    'rules'=> 'required|matches[password]',
                    'errors' => [
                        'required'=> 'Confirm Password wajib diisi.',
                        'matches'=> 'Password tidak cocok.'
                    ]
                ],
                'nama' => [
                    'rules'=> 'required',
                    'errors' => [
                        'required'=> 'Nama wajib diisi.'
                    ]
                ],
            ];
        } else {
            $rules = [
                'password' => [
                    'rules'=> 'required|min_length[8]',
                    'errors' => [
                        'required'=> 'Password wajib diisi.',
                        'min_length'=> 'Panjang minimal password adalah 8 karakter.'
                    ]
                ],
                'confirm_password' => [
                    'rules'=> 'required|matches[password]',
                    'errors' => [
                        'required'=> 'Confirm Password wajib diisi.',
                        'matches'=> 'Password tidak cocok.'
                    ]
                ],
                'nama' => [
                    'rules'=> 'required',
                    'errors' => [
                        'required'=> 'Nama wajib diisi.'
                    ]
                ],
            ];
        }

        return $rules;
    }
}