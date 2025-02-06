<?php

namespace App\Controllers;
use App\Models\KategoriModel;
use App\Models\SettingModel;
use App\Models\PenjualanModel;

class Payment extends BaseController
{
    protected $helpers = ['form'];
    
    
    public function notification()
    {

        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        $order_id = $result->order_id;
        $payment_type = $result->payment_type;
        $transaction_status = $result->transaction_status;
        $fraud_status = $result->fraud_status;
        $transaction_id = $result->transaction_id;
        $order_status = 'pending';

        if($transaction_status == 'capture') {
            if($payment_type == 'credit_card') {
                if($fraud_status == 'accept') {
                    $order_status = 'lunas';
                }
            }
        } else {
            if($transaction_status == 'settlement') {
                $order_status = 'lunas';
            }
        }

        $penjualan_model = new PenjualanModel();
        $penjualan_data = $penjualan_model->where('midtrans_id', $order_id)->first();
        if($penjualan_data) {
            $dataPayment = [
                'status_pembayaran' => $order_status,
                'midtrans_status' => $transaction_status
            ];

            $penjualan_model->update($penjualan_data['penjualan_id'], $dataPayment);
        }

    }

    public function recurring()
    {
        return view('payment/index', array(
            'page' => 'recurring'
        ));
    }

    public function account()
    {
        return view('payment/index', array(
            'page' => 'account'
        ));
    }

    public function success()
    {
        
        return view('payment/index', array(
            'page' => 'success',
        ));
            
    }

    public function failed()
    {
        return view('payment/index', array(
            'page' => 'failed'
        ));
    }

    public function error()
    {
        echo "Payment using ";
    }
}
