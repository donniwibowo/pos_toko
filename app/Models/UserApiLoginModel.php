<?php

namespace App\Models;

use CodeIgniter\Model;

class UserApiLoginModel extends Model
{
    protected $table = 'tbl_user_api_login';
    protected $primaryKey = 'api_login_id';
    protected $allowedFields = ['user_id', 'user_token', 'tgl_login', 'tgl_logout', 'status'];


    public function checkIn($user_id) {
        $user_token = md5($user_id);
        $data = [
            'user_id' => $user_id,
            'user_token' => '',
            'tgl_login' => date('Y-m-d H:i:s'),
            'tgl_logout' => null,
            'status' => 1
        ];

        if($this->insert($data)) {
            $user_token = md5($this->insertID);
            $this->update($this->insertID, ['user_token' => $user_token]);
            return $user_token;
        } else {
            return '';
        }
        
    }

    public function checkOut($user_token) {
        $user_data = $this->where('user_token', $user_token)
                            ->where('status', 1)
                            ->first();
        if($user_data) {
            $data = [
                'tgl_logout' => date('Y-m-d H:i:s'),
                'status' => 0,
            ];

            if($this->update($user_data['api_login_id'], $data)) {
                return true;
            }
        }

        return false;
    }

    public function isLogin($user_id) {
        $user_data = $this->where('user_id', $user_id)
                            ->where('status', 1)
                            ->first();
        if($user_data) {
            return true;
        }

        return false;
    }

    public function isTokenValid($user_token) {
        $user_data = $this->where('user_token', $user_token)
                            ->where('status', 1)
                            ->first();
        if($user_data) {
            return true;
        } else {
            return false;
        }
    }
   
}