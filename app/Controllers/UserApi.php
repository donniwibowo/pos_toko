<?php 

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\UserApiLoginModel;
use App\Models\SettingModel;

class UserApi extends ResourceController
{
	use ResponseTrait;
   
   	public function login(){
        $no_telp = $this->request->getVar('no_telp');
        $password = $this->request->getVar('password');

        $model = new UserModel();
        $user = $model->where('no_telp', $no_telp)
                        // ->where('password', pos_encrypt($password))
                        ->where('is_deleted', 0)->first();
                        // ->where('jabatan', 'kasir')->first();

        $response = array(
            'status' => 404,
            'data' => []
        );

        if($user) {
            $api_model = new UserApiLoginModel();
            if($api_model->isLogin($user['user_id'])) {
                $response = array(
                    'status' => 403,
                    'data' => []
                );
            } else {
                $nama_toko = "Nama Default";
                $alamat_toko = "Alamat Default";
                $telp_toko = "No Telp Default";

                $setting_model = new SettingModel();
                $setting_data = $setting_model->findAll();

                foreach($setting_data as $setting) {
                    if($setting['setting_name'] == 'nama_toko') {
                        $nama_toko = $setting['setting_value'];
                    }

                    if($setting['setting_name'] == 'alamat_toko') {
                        $alamat_toko = $setting['setting_value'];
                    }

                    if($setting['setting_name'] == 'telp_toko') {
                        $telp_toko = $setting['setting_value'];
                    }
                }

                $data = [
                    'user_id' => $user['user_id'],
                    'no_telp' => $user['no_telp'],
                    'nama' => $user['nama'],
                    'jabatan' => $user['jabatan'],
                    'is_superadmin' => $user['is_superadmin'],
                    'nama_toko' => strtoupper($nama_toko),
                    'alamat_toko' => strtoupper($alamat_toko),
                    'telp_toko' => strtoupper($telp_toko),
                    'logged_in' => true,
                ];

                $user_token = $api_model->checkIn($user['user_id']);

                $response = array(
                    'status' => 200,
                    'user_token' => $user_token,
                    'data' => $data
                );
            }
            
           
        } else {
            $response = array(
                'status' => 401,
                'data' => $no_telp,
                // 'error' => No telp dan password tidak cocok!
            );
        }

        return $this->respond($response);
    }

    public function logout($user_token) {
        $api_model = new UserApiLoginModel();
        $user_logout = $api_model->checkOut($user_token);

        $response = array(
            'status' => 404,
        );

        if($user_logout) {
            $response = array(
                'status' => 200,
            );
        }

        return $this->respond($response);
    }

    public function checkLogin($user_token) {
        $model = new UserApiLoginModel();
        $api_model = $model->where('user_token', $user_token)
                            ->where('status', 1)->first();

        $response = array(
            'loginStatus' => 0,
        );

        if($api_model) {
            $response = array(
                'loginStatus' => 1,
            );  
        }

        return $this->respond($response);
                        
    }

}