<?php 

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProdukModel;
use App\Models\ProdukHargaModel;
use App\Models\ProdukDiskonModel;
use App\Models\UserApiLoginModel;
use App\Models\SupplierModel;
use App\Models\TagihanModel;


class SupplierApi extends ResourceController
{
	use ResponseTrait;
   

    public function getSupplier($user_token, $keyword) {
        $response = array(
            'status' => 404,
            'data' => []
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            $db      = \Config\Database::connect();
            $builder = $db->table('tbl_supplier s');
            $builder->select('s.supplier_id, s.nama_supplier');
            $builder->where('s.is_deleted', 0);
            
            if($keyword != '') {
                $builder->like('s.nama_supplier', $keyword, 'both');
            }
            
            $builder->orderBy('s.nama_supplier');
            $query   = $builder->get();
            $query_result = $query->getResult();

            $data = [];
            foreach($query_result as $q) {
                $data[] = array(
                    'supplier_id' => $q->supplier_id,
                    'nama_supplier' => strtoupper($q->nama_supplier)
                );
            }

            if($query) {
                $response = array(
                    'status' => 200,
                    'data' => $data
                );
            }
        } else {
            $response = array(
                'status' => 403,
                'msg' => 'Token tidak valid',
                'data' => []
            );
        }
            
        return $this->respond($response);

    }

    public function inputTagihan($user_token) {
        $response = array(
            'status' => 404,
            'data' => []
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            $user_id = $this->request->getVar('user_id');
            $supplier_id = $this->request->getVar('supplier_id');
            $no_nota = $this->request->getVar('no_nota');
            $jumlah_tagihan = $this->request->getVar('jumlah_tagihan');
            $tempo_pembayaran = $this->request->getVar('tempo_pembayaran');
            $tgl_datang = $this->request->getVar('tgl_datang');
            $tgl_jatuh_tempo = date_create($tgl_datang);
            $remarks = $this->request->getVar('remarks');
            date_add($tgl_jatuh_tempo, date_interval_create_from_date_string($tempo_pembayaran." days"));
            
            $dataToSave = [
                'supplier_id' => $supplier_id,
                'no_nota' => $no_nota,
                'jumlah_tagihan' => $jumlah_tagihan,
                'tempo_pembayaran' => $tempo_pembayaran,
                'tgl_datang' => date('Y-m-d', strtotime($tgl_datang)),
                'tgl_jatuh_tempo' => date_format($tgl_jatuh_tempo, "Y-m-d"),
                'status' => 0,
                'remarks' => $remarks,
                'tgl_bayar' => null,
                'tgl_dibuat' => date('Y-m-d H:i:s'),
                'dibuat_oleh' => $user_id,
                'is_deleted' => 0,
            ];

            $tagihan_model = new TagihanModel();
            $tagihan_model->insert($dataToSave);

            if($tagihan_model) {
                $tagihan_id = $tagihan_model->insertID;
                $response = array(
                    'status' => 200,
                    'tagihan_id' => $tagihan_id.'',
                );
            } else {
                 $response = array(
                    'status' => 204,
                    'msg' => 'Database input failed.',
                );
            }
            
        } else {
            $response = array(
                'status' => 403,
                'msg' => 'Token tidak valid',
                'data' => []
            );
        }

        return $this->respond($response);
    }

    public function getTagihan($user_token, $status, $searchBySupplier = 0, $searchByNoNota = 0, $searchByTotal = 0, $keyword= '') {
        $response = array(
            'status' => 404,
            'data' => []
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            // $keyword = '';
            
            $db      = \Config\Database::connect();
            $builder = $db->table('tbl_tagihan t');
            $builder->select('t.*, s.nama_supplier');
            $builder->where('t.is_deleted', 0);
            $builder->where('t.status', $status);
            $builder->join('tbl_supplier s', 't.supplier_id = s.supplier_id');

            if(isset($keyword) && $keyword != '') {
                if($searchBySupplier == 1 || $searchByNoNota == 1 || $searchByTotal == 1) {
                    if($searchBySupplier) {
                        $builder->like('s.nama_supplier', $keyword);
                    }

                    if($searchByNoNota) {
                        $builder->like('t.no_nota', $keyword);
                    }

                    if($searchByTotal) {
                        $builder->where('t.jumlah_tagihan', $keyword);
                    }
                } else {
                    $builder->like('s.nama_supplier', $keyword);
                    // $builder->like('t.no_nota', $keyword);
                    // $builder->where('t.jumlah_tagihan', $keyword);
                }
            }

            if($status == '0') {
                $builder->orderBy('t.tgl_jatuh_tempo', 'asc');
            } else {
                $builder->orderBy('t.tgl_dibuat', 'asc');
            }
            
            $query   = $builder->get();
            $data = $query->getResult();

           
            if($data) {
                $tmp_data = [];
                foreach($data as $d) {
                    $tmp_data[] = array(
                        "tagihan_id" => $d->tagihan_id,
                        "nama_supplier" => $d->nama_supplier,
                        "no_nota" => $d->no_nota,
                        "jumlah_tagihan" => $d->jumlah_tagihan,
                        "tempo_pembayaran" => $d->tempo_pembayaran,
                        "tgl_datang" => date("d M Y", strtotime($d->tgl_datang)),
                        "tgl_jatuh_tempo" => date("d M Y", strtotime($d->tgl_jatuh_tempo)),
                        "status" => $d->status,
                        "tgl_bayar" => $d->tgl_bayar == "" ? "-" : date("d M Y", strtotime($d->tgl_bayar)),
                        "metode_pembayaran" => $d->metode_pembayaran,
                        "rekening" => $d->rekening,
                        "remarks" => $d->remarks,
                        "jumlah_bayar" => $d->jumlah_bayar,
                        "tgl_dibuat" => date("d M Y", strtotime($d->tgl_dibuat)),
                    );
                }

                $response = array(
                    'status' => 200,
                    'data' => $tmp_data
                );
            }
        } else {
            $response = array(
                'status' => 403,
                'msg' => 'Token tidak valid',
                'data' => []
            );
        }

        return $this->respond($response);

        
    }

    public function inputPayment($user_token) {
        $response = array(
            'status' => 404,
            'data' => []
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            $tagihan_id = $this->request->getVar('tagihan_id');
            $metode_pembayaran = $this->request->getVar('metode_pembayaran');
            $rekening = $this->request->getVar('rekening');
            $tgl_bayar = $this->request->getVar('tgl_bayar');
            $jumlah_bayar = $this->request->getVar('jumlah_bayar');
            
            $dataToSave = [
                'metode_pembayaran' => $metode_pembayaran,
                'rekening' => $rekening,
                'tgl_bayar' => date('Y-m-d', strtotime($tgl_bayar)),
                'jumlah_bayar' => $jumlah_bayar,
                'status' => 1
            ];

            $tagihan_model = new TagihanModel();
            $tagihan_model->update($tagihan_id, $dataToSave);

            if($tagihan_model) {
                $response = array(
                    'status' => 200,
                    
                );
            } else {
                 $response = array(
                    'status' => 204,
                    'msg' => 'Database input failed.',
                );
            }
            
        } else {
            $response = array(
                'status' => 403,
                'msg' => 'Token tidak valid',
                'data' => []
            );
        }

        return $this->respond($response);
    }
}