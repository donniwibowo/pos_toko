<?php

namespace App\Controllers;
use App\Models\UserModel;

class User extends BaseController
{
    protected $helpers = ['form'];
    public function login()
    {
        if(session()->logged_in) {
            return redirect()->to(base_url('user/list')); 
        }

        if ($this->request->is('post')) {
            $no_telp = $_POST['no_telp'];
            $password = $_POST['password'];

            $user_model = new UserModel();
            $login_berhasil = $user_model->where('is_deleted', 0)
                                ->where('no_telp', $no_telp)
                                // ->where('password', pos_encrypt($password))
                                ->where('jabatan', 'admin')
                                ->first();

            if($login_berhasil) {
                $data_login = [
                    'user_id' => $login_berhasil['user_id'],
                    'no_telp' => $login_berhasil['no_telp'],
                    'nama' => $login_berhasil['nama'],
                    'jabatan' => $login_berhasil['jabatan'],
                    'is_superadmin' => $login_berhasil['is_superadmin'],
                    'logged_in' => true,
                ];

                session()->set($data_login);
                return redirect()->to(base_url('penjualan/harian')); 
            } else {
                session()->setFlashData('danger', 'No telp dan password tidak cocok!');
            }
        }
        return view('user/login');
    }


    public function logout() {
        session()->destroy();
        return redirect()->to(base_url('user/login')); 
    }

    public function delete($id) {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $user_model = new UserModel();
        $user_data = $user_model->find($id);

        if(!session()->is_superadmin) {
            if($user_data['jabatan'] == 'admin') {
                session()->setFlashData('danger', 'Anda tidak memiliki akses ini!');
                return redirect()->to(base_url('user/list'));
            }
        }


        $data = ['is_deleted' => 1,];

        
        if($user_model->update(pos_decrypt($id), $data)) {
            session()->setFlashData('success', 'Data user berhasil dihapus!');      
        } else {
            session()->setFlashData('danger', 'Internal server error');
        }

        return redirect()->to(base_url('user/list')); 
    }

    public function update($id) {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $id = pos_decrypt($id);

        $user_model = new UserModel();
        $user_data = $user_model->find($id);
        $old_password = pos_decrypt($user_data['password']);

        if(!session()->is_superadmin) {
            if($user_data['jabatan'] == 'admin') {
                session()->setFlashData('danger', 'Akses ditolak');
                return redirect()->to(base_url('user/list'));
            }
        }

        $rules = $user_model->getFormRules(false);

        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                $current_password = $_POST['password'];
                if($current_password == $user_data['password']) {
                    $current_password = $old_password;
                }

                $data = [
                    'password' => pos_encrypt($current_password),
                    'nama' => $_POST['nama'],
                    'jabatan' => $_POST['jabatan'],
                    'tgl_diupdate' => date('Y-m-d H:i:s'),
                    'diupdate_oleh' => session()->user_id
                ];

                $hasil = $user_model->update($id, $data);

                if($hasil) {
                    session()->setFlashData('success', 'Data user berhasil diubah');
                    return redirect()->to(base_url('user/list')); 
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
            }
        }
        
        return view('user/form', array(
            'form_action' => base_url().'user/update/'.pos_encrypt($id),
            'is_new_data' => false,
            'data' => (object) $user_data
        ));
    }
    
    public function add()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $user_model = new UserModel();
        $rules = $user_model->getFormRules();

        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                
                $data = [
                    'no_telp' => $_POST['no_telp'],
                    'password' => pos_encrypt($_POST['password']),
                    'nama' => $_POST['nama'],
                    'jabatan' => $_POST['jabatan'],
                    'tgl_dibuat' => date('Y-m-d H:i:s'),
                    'dibuat_oleh' => session()->user_id,
                    'tgl_diupdate' => date('Y-m-d H:i:s'),
                    'diupdate_oleh' => session()->user_id,
                ];

                $hasil = $user_model->insert($data);

                if($hasil) {
                    session()->setFlashData('success', 'Data user berhasil ditambahkan');
                    return redirect()->to(base_url('user/list')); 
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
            }
        }
        
        return view('user/form', array(
            'form_action' => base_url().'user/create',
            'is_new_data' => true,
            'data' => $user_model
        ));
    }

    public function list()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $user_model = new UserModel();
        $user_model->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        
        $user_data = $user_model->where('is_deleted', 0)
                                ->orderBy('tgl_diupdate', 'desc')
                                ->findAll();
        return view('user/list', array(
            'data' => $user_data
        ));
    }
}
