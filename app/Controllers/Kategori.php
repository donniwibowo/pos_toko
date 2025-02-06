<?php

namespace App\Controllers;
use App\Models\KategoriModel;
use App\Models\ProdukModel;

class Kategori extends BaseController
{
    protected $helpers = ['form'];
    
    
    public function add()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        
        $kategori_model = new KategoriModel();
        $rules = $kategori_model->getFormRules();
        
        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                $data = [
                    'nama_kategori' => $_POST['nama_kategori'],
                    'tgl_dibuat' => date('Y-m-d H:i:s'),
                    'dibuat_oleh' => session()->user_id,
                    'tgl_diupdate' => date('Y-m-d H:i:s'),
                    'diupdate_oleh' => session()->user_id,
                ];

                $hasil = $kategori_model->insert($data);

                if($hasil) {
                    session()->setFlashData('success', 'Data kategori berhasil ditambahkan');
                    return redirect()->to(base_url('kategori/list'));
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
            }
        }
        
        return view('kategori/form', array(
            'form_action' => base_url().'kategori/create',
            'is_new_data' => true,
            'data' => $kategori_model
        ));
    }

    public function update($id)
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        
        $id = pos_decrypt($id);
        $kategori_model = new KategoriModel();
        $kategori_data = $kategori_model->find($id);

        $rules = $kategori_model->getFormRules();


        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                $data = [
                    'nama_kategori' => $_POST['nama_kategori'],
                    'tgl_diupdate' => date('Y-m-d H:i:s'),
                    'diupdate_oleh' => session()->user_id
                ];

                $hasil = $kategori_model->update($id, $data);

                if($hasil) {
                    session()->setFlashData('success', 'Data kategori berhasil diubah');
                    return redirect()->to(base_url('kategori/list'));
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
            }
        }
        
        return view('kategori/form', array(
            'form_action' => base_url().'kategori/update/'.pos_encrypt($id),
            'is_new_data' => false,
            'data' => (object) $kategori_data
        ));
    }

    public function delete($id) {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $kategori_model = new KategoriModel();
        $data = [
            'is_deleted' => 1,
        ];

        $produk_model = new ProdukModel();
        $produk_data  = $produk_model->where('is_deleted', 0)
                                        ->where('kategori_id', pos_decrypt($id))
                                        ->findAll();
        
        if($produk_data) {
            session()->setFlashData('danger', 'Kategori tidak dapat dihapus karena terdapat produk yang masih aktif.');
        } else {
            if($kategori_model->update(pos_decrypt($id), $data)) {
                session()->setFlashData('success', 'Data kategori berhasil dihapus!');      
            } else {
                session()->setFlashData('danger', 'Internal server error');
            }
        }

        return redirect()->to(base_url('kategori/list')); 
    }

    public function list()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }
        
        $kategori_model = new KategoriModel();
        $kategori_data = $kategori_model->where('is_deleted', 0)
                                        ->orderBy('tgl_diupdate', 'desc')
                                        ->findAll();

        return view('kategori/list', array(
            'data' => $kategori_data
        ));
    }
}
