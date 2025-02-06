<?php

namespace App\Controllers;
use App\Models\SettingModel;

class Setting extends BaseController
{
    protected $helpers = ['form'];
    

    public function update()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        
        $setting_model = new SettingModel();
       
        if ($this->request->is('post')) {

            for($i=0; $i<count($_POST['setting_id']); $i++) {
                $setting_model->update($_POST['setting_id'][$i], ['setting_value' => $_POST['setting_value'][$i]]);
            }
            session()->setFlashData('success', 'Setting Berhasil');

        }

        $setting_data = $setting_model->findAll();
        
        return view('setting/form', array(
            'form_action' => base_url().'setting/update',
            'setting_data' => $setting_data
        ));
    }

}
