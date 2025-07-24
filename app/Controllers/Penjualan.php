<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\KategoriModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\ProdukModel;
use App\Models\RelatedProdukModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use App\Models\SettingModel;
use Phpml\Association\Apriori;

class Penjualan extends BaseController
{
    use ResponseTrait;
    protected $helpers = ['form'];

    public function testApriori()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $data = [];
        $data = [['amanda', 'cakra'], ['gogo coklat', 'fermipan', 'cakra', 'amanda'], ['amanda', 'gogo coklat', 'cakra', 'vatpro'], ['vatpro', 'gogo coklat', 'fermipan'], ['cakra', 'amanda', 'fermipan', 'gogo coklat'], ['amanda', 'gogo coklat', 'cakra'], ['amanda', 'gogo coklat', 'vatpro'], ['cakra', 'vatpro']];

        $support = 0.5;
        $confidence = 0.5;

        $labels  = [];
        $associator = new Apriori($support, $confidence);
        $associator->train($data, $labels);

        //mendapatkan rules
        $rules = $associator->getRules();
        

        return view('penjualan/test_apriori', array(
            'rules' => $rules,
        ));
        
    }
    
    public function report()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $produk_model = new ProdukModel();
        $produk_count = $produk_model->where('is_deleted', 0)
                                        ->findAll();

        $supplier_model = new SupplierModel();
        $supplier_count = $supplier_model->where('is_deleted', 0)
                                        ->findAll();

        $user_model = new UserModel();
        $admin_count = $user_model->where('is_deleted', 0)
                                    ->where('jabatan', 'admin')
                                    ->findAll();

        
        $kasir_count = $user_model->where('is_deleted', 0)
                                    ->where('jabatan', 'kasir')
                                    ->findAll();

        $setting_model = new SettingModel();
        $setting_thn_min = $setting_model->where('setting_name', 'tahun_awal')->first();
        $setting_thn_max = $setting_model->where('setting_name', 'tahun_akhir')->first();

        return view('penjualan/report', array(
            'produk_count' => count($produk_count),
            'supplier_count' => count($supplier_count),
            'admin_count' => count($admin_count),
            'kasir_count' => count($kasir_count),
            'setting_thn_min' => $setting_thn_min['setting_value'],
            'setting_thn_max' => $setting_thn_max['setting_value']

        ));
    }

    public function getReport($tahun_laporan) {
        $omset_penjualan = [];
        $jumlah_penjualan = [];
        $periode_penjualan = [];
        $profit_penjualan = [];
        $omset_tertinggi = 0;
        // $bulan_penjualan = [];

        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_penjualan');
        $builder->select('Month(tgl_dibuat) as periode_bulan, Year(tgl_dibuat) as periode_tahun');
        $builder->selectSum('total_bayar');
        $builder->selectCount('penjualan_id');
        $builder->where('tbl_penjualan.is_deleted', 0);
        $builder->where('Year(tbl_penjualan.tgl_dibuat)', $tahun_laporan);
        $builder->orderBy('tgl_dibuat', 'asc');
        $builder->groupBy('Month(tgl_dibuat)');
        $penjualan_data   = $builder->get();

        $builder = $db->table('tbl_penjualan_detail d');
        $builder->selectSum('((d.harga_jual * d.qty) - (d.harga_beli * d.qty))', 'profit');
        $builder->select('Month(tgl_dibuat) as periode_bulan, Year(tgl_dibuat) as periode_tahun');
        $builder->where('Year(p.tgl_dibuat)', $tahun_laporan);
        $builder->where('p.is_deleted', 0);
        $builder->where('d.is_deleted', 0);
        $builder->join('tbl_penjualan p', 'd.penjualan_id = p.penjualan_id');
        $builder->orderBy('p.tgl_dibuat', 'asc');
        $builder->groupBy('Month(p.tgl_dibuat)');
        $profit_data = $builder->get();

        $current_month = date('m');
        $profit_bulan_ini = 0;
        $profit_bulan_lalu = 0;

        if($penjualan_data) {
            foreach($penjualan_data->getResult() as $row) {
                $tmp_data = $row->periode_bulan.'/'.$row->periode_tahun;

                array_push($omset_penjualan, $row->total_bayar/1000);
                array_push($jumlah_penjualan, $row->penjualan_id);
                array_push($periode_penjualan, $tmp_data);
                // array_push($bulan_penjualan, $row->periode_bulan);
            }
        }

        if($profit_data) {
            foreach($profit_data->getResult() as $row) {
                array_push($profit_penjualan, $row->profit);

                if((int)$current_month == $row->periode_bulan) {
                    $profit_bulan_ini =$row->profit;
                }

                if((int)($current_month - 1) == $row->periode_bulan) {
                    $profit_bulan_lalu =$row->profit;
                }                
            }
        }

        $status_profit = 1;
        $persentase_profit = 0;
        $selisih_profit = 0;

        if($profit_bulan_lalu > 0 && $profit_bulan_ini > 0) {
            if($profit_bulan_ini < $profit_bulan_lalu) {
                $status_profit = 0;
                $selisih_profit = $profit_bulan_lalu - $profit_bulan_ini;
                $persentase_profit = $selisih_profit / $profit_bulan_lalu * 100;
            }

            if($profit_bulan_ini > $profit_bulan_lalu) {
                $status_profit = 1;
                $selisih_profit = $profit_bulan_ini - $profit_bulan_lalu;
                $persentase_profit = $selisih_profit / $profit_bulan_ini * 100;
            }
            
        }

        if(count($omset_penjualan) > 0) {
            $omset_tertinggi = max($omset_penjualan);
        }
        
        $response = array(
            'status' => 200,
            'data' => [
                'omset_penjualan' => $omset_penjualan,
                'jumlah_penjualan' => $jumlah_penjualan,
                'periode_penjualan' => $periode_penjualan,
                'omset_tertinggi' => $omset_tertinggi,
                'profit' => $profit_penjualan,
                'profit_bulan_ini' => number_format($profit_bulan_ini, 0),
                'status_profit' => $status_profit,
                'persentase_profit' => number_format($persentase_profit, 0),
                'tahun_laporan' => $tahun_laporan
            ]
        );

        return $this->respond($response);
    }

    public function list()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }
        
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_penjualan');
        $builder->select('tbl_penjualan.*, tbl_user.nama');
        $builder->where('tbl_penjualan.is_deleted', 0);
        $builder->join('tbl_user', 'tbl_penjualan.dibuat_oleh = tbl_user.user_id');
        $builder->orderBy('tbl_penjualan.tgl_dibuat', 'desc');
        $builder->limit(500);
        $penjualan_data   = $builder->get();

        return view('penjualan/list', array(
            'data' => $penjualan_data->getResult()
        ));
    }

    public function detail($id)
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $db      = \Config\Database::connect();

        $builder = $db->table('tbl_penjualan');
        $builder->select('tbl_penjualan.*, tbl_user.nama');
        $builder->where('tbl_penjualan.is_deleted', 0);
        $builder->where('tbl_penjualan.penjualan_id', $id);
        $builder->join('tbl_user', 'tbl_penjualan.dibuat_oleh = tbl_user.user_id');
        $penjualan_data   = $builder->get();

       
        $builder = $db->table('tbl_penjualan_detail');
        $builder->select('tbl_produk.nama_produk, tbl_produk.satuan_terkecil, tbl_penjualan_detail.*, tbl_produk_harga.satuan, tbl_produk_harga.netto');
        $builder->where('tbl_penjualan_detail.penjualan_id', $id);
        $builder->join('tbl_produk', 'tbl_penjualan_detail.produk_id = tbl_produk.produk_id');
        $builder->join('tbl_produk_harga', 'tbl_penjualan_detail.produk_harga_id = tbl_produk_harga.produk_harga_id');
        $penjualan_detail   = $builder->get();

        return view('penjualan/detail', array(
            'penjualan_data' => $penjualan_data->getResult(),
            'penjualan_detail' => $penjualan_detail->getResult(),
        ));
    }

    public function analisaTEMPORARYUNUSED()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }
        
        //mengambil semua nama produk
        $produk_model = new ProdukModel();
        $produk_data = $produk_model->where('is_deleted', 0)
                                    ->orderBy('nama_produk', 'asc')
                                    ->findAll();

        //parameter kosongan
        $rules = [];
        $prediksi = [];
        $target_prediksi = [];
        $produk_sebanding = [];
        $support = '';
        $confidence = '';

        //select data setting dari database
        $setting_model = new SettingModel();
        $setting_support = $setting_model->where('setting_name', 'support')->first();
        $setting_confidence = $setting_model->where('setting_name', 'confidence')->first();

        //untuk menampung nilai support dan confidence
        $support = $setting_support['setting_value'];
        $confidence = $setting_confidence['setting_value'];

        if ($this->request->is('post')) { // jika user menekan tombol submit
            $support = $_POST['support']; // menampung input user ke variabel support
            $confidence = $_POST['confidence']; // menampung input user ke variabel support
            $produk_ids = isset($_POST['produk_ids']) ? $_POST['produk_ids'] : []; // mengecek apakah ada prediksi produk

            //mengambil semua detail penjualan
            if($support > 0 && $confidence > 0) {
                $db      = \Config\Database::connect();
                $builder = $db->table('tbl_penjualan_detail');
                $builder->select('tbl_penjualan_detail.*, tbl_produk.nama_produk');
                $builder->where('tbl_penjualan_detail.is_deleted', 0);
                $builder->join('tbl_produk', 'tbl_penjualan_detail.produk_id = tbl_produk.produk_id');
                $builder->orderBy('tbl_penjualan_detail.penjualan_id', 'asc');
                $penjualan_detail   = $builder->get();

                //menampung semua detail penjualan
                if($penjualan_detail) {
                    $data = [];
                    $current_penjualan_id = 0;
                    $result = $penjualan_detail->getResult();    

                    //pengecekan agar tidak ada data redundant
                    if($result) {
                        $current_penjualan_id = $result[0]->penjualan_id;
                        $tmp_data = [];
                        foreach($result as $d) {
                            if($current_penjualan_id == $d->penjualan_id) {
                                if(!in_array(ucwords(strtolower($d->nama_produk)), $tmp_data)) {
                                    array_push($tmp_data, ucwords(strtolower($d->nama_produk)));
                                    
                                }
                                
                            } else {
                                array_push($data, $tmp_data);
                                $tmp_data = [];
                                $current_penjualan_id = $d->penjualan_id;

                                if(!in_array(ucwords(strtolower($d->nama_produk)), $tmp_data)) {
                                    array_push($tmp_data, ucwords(strtolower($d->nama_produk)));
                                }
                            }                         
                        }

                        if(count($tmp_data) > 0) {
                            array_push($data, $tmp_data);
                        }
                    }

                    //pengecekan jika ada produk yang akan diprediksi
                    if(count($produk_ids) > 0) {
                        foreach($produk_ids as $produk_id) {
                            $produk = $produk_model->find($produk_id);
                            array_push($target_prediksi, ucwords(strtolower($produk['nama_produk'])));
                        }
                    }
                    
                    //train data
                    if(count($data) > 0) {
                        $labels  = [];
                        $associator = new Apriori($support, $confidence);
                        $associator->train($data, $labels);

                        //mendapatkan rules
                        $rules = $associator->getRules();

                        //dari rules kita dapat memprediksi produk dan jika tidak ada ditemukan data maka akan dilempar ke produk sebanding
                        if(count($target_prediksi) > 0) {
                            $prediksi = $associator->predict($target_prediksi);

                            if(count($prediksi) < 1) {
                                $related_produk_model = new RelatedProdukModel();
                                foreach ($produk_ids as $produk_id) {
                                    $produk_parent = $produk_model->find($produk_id); 

                                    $builder = $db->table('tbl_related_produk');
                                    $builder->select('tbl_related_produk.*, tbl_produk.nama_produk');
                                    $builder->where('tbl_related_produk.is_deleted', 0);
                                    $builder->where('tbl_related_produk.produk_parent_id', $produk_id);
                                    $builder->join('tbl_produk', 'tbl_related_produk.produk_child_id = tbl_produk.produk_id');
                                    $related_produk_data   = $builder->get();                                           

                                    $tmp_data = [];
                                    if($related_produk_data) {
                                        foreach($related_produk_data->getResult() as $d) {
                                            array_push($tmp_data, $d->nama_produk);
                                        }

                                        $produk_sebanding[ucwords(strtolower($produk_parent['nama_produk']))] = $tmp_data;
                                    }                                         
                                }
                            }
                        }
                    }
                }
                
  
            } else {
                session()->setFlashData('danger', 'Nilai support dan confidence wajib diisi.');
            }
         }

        return view('penjualan/analisa_penjualan', array(
            'produk_data' => $produk_data,
            'rules' => $rules,
            'support' => $support,
            'confidence' => $confidence,
            'prediksi' => $prediksi,
            'target_prediksi' => $target_prediksi,
            'produk_sebanding' => $produk_sebanding,
            'rules_by_kategori' => []
        ));
    }

    public function analisa()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }
        
        //mengambil semua nama produk
        $produk_model = new ProdukModel();
        $produk_data = $produk_model->where('is_deleted', 0)
                                    ->orderBy('nama_produk', 'asc')
                                    ->findAll();

        $produk_data_nama = [];
        foreach($produk_data as $d) {
            $produk_data_nama[$d['produk_id']] = $d['nama_produk'];
        }                     
        

        //parameter kosongan
        $rules = [];
        $prediksi = [];
        $target_prediksi = [];
        $produk_sebanding = [];
        $support = 0;
        $confidence = 0;


        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_penjualan_detail');
        $builder->select('tbl_penjualan_detail.*, tbl_produk.nama_produk');
        $builder->where('tbl_penjualan_detail.is_deleted', 0);
        $builder->join('tbl_produk', 'tbl_penjualan_detail.produk_id = tbl_produk.produk_id');
        $builder->orderBy('tbl_penjualan_detail.penjualan_id', 'asc');
        $penjualan_detail   = $builder->get();

        //menampung semua detail penjualan
        if($penjualan_detail) {
            $data = [];
            $current_penjualan_id = 0;
            $result = $penjualan_detail->getResult();    

            //pengecekan agar tidak ada data redundant
            if($result) {
                $current_penjualan_id = $result[0]->penjualan_id;
                $tmp_data = [];
                foreach($result as $d) {
                    if($current_penjualan_id == $d->penjualan_id) {
                        if(!in_array(ucwords(strtolower($d->produk_id)), $tmp_data)) {
                            array_push($tmp_data, ucwords(strtolower($d->produk_id)));
                            
                        }
                        
                    } else {
                        array_push($data, $tmp_data);
                        $tmp_data = [];
                        $current_penjualan_id = $d->penjualan_id;

                        if(!in_array(ucwords(strtolower($d->produk_id)), $tmp_data)) {
                            array_push($tmp_data, ucwords(strtolower($d->produk_id)));
                        }
                    }                         
                }

                if(count($tmp_data) > 0) {
                    array_push($data, $tmp_data);
                }
            }

            
            if(count($data) > 0) {
                $labels  = [];
                $associator = new Apriori(0.1, 0.1);
                $associator->train($data, $labels);

                //mendapatkan rules
                $rules = $associator->getRules();
            }
        }
        

        if ($this->request->is('post')) {
            $support = $_POST['support']; // menampung input user ke variabel support
            $confidence = $_POST['confidence']; // menampung input user ke variabel support
            $produk_ids = isset($_POST['produk_ids']) ? $_POST['produk_ids'] : []; // mengecek apakah ada prediksi produk
            

            //pengecekan jika ada produk yang akan diprediksi
            if(count($produk_ids) > 0) {
                foreach($produk_ids as $produk_id) {
                    $produk = $produk_model->find($produk_id);
                    array_push($target_prediksi, ucwords(strtolower($produk_id)));
                }

                //dari rules kita dapat memprediksi produk dan jika tidak ada ditemukan data maka akan dilempar ke produk sebanding
                if(count($target_prediksi) > 0) {
                    $labels_predict  = [];
                    $associator_predict = new Apriori($support, $confidence);
                    $associator_predict->train($data, $labels_predict);

                    $prediksi = $associator_predict->predict($target_prediksi);
                   

                    if(count($prediksi) < 1) {
                        $related_produk_model = new RelatedProdukModel();
                        foreach ($produk_ids as $produk_id) {
                            $produk_parent = $produk_model->find($produk_id); 

                            $builder = $db->table('tbl_related_produk');
                            $builder->select('tbl_related_produk.*, tbl_produk.nama_produk');
                            $builder->where('tbl_related_produk.is_deleted', 0);
                            $builder->where('tbl_related_produk.produk_parent_id', $produk_id);
                            $builder->join('tbl_produk', 'tbl_related_produk.produk_child_id = tbl_produk.produk_id');
                            $related_produk_data   = $builder->get();                                           

                            $tmp_data = [];
                            if($related_produk_data) {
                                foreach($related_produk_data->getResult() as $d) {
                                    array_push($tmp_data, $d->nama_produk);
                                }

                                $produk_sebanding[ucwords(strtolower($produk_parent['nama_produk']))] = $tmp_data;
                            }                                         
                        }
                    }
                }
            } else {
                session()->setFlashData('danger', 'Silahkan pilih produk yang akan dianalisa terlebih dahulu.');
            }


        }
        

        return view('penjualan/analisa_penjualan', array(
            'produk_data' => $produk_data,
            'rules' => $rules,
            'support' => $support,
            'confidence' => $confidence,
            'prediksi' => $prediksi,
            'target_prediksi' => $target_prediksi,
            'produk_sebanding' => $produk_sebanding,
            'rules_by_kategori' => [],
            'produk_data_nama' => $produk_data_nama,
        ));
    }

    public function getReportHarian() {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $tgl_dipilih = date('d M Y');
        // $tgl_dipilih = '2023-11-20';
        $omset = 0;
        $jumlah_transaksi = 0;
        $profit = 0;
        $persentase_profit = 0;
        $most_wanted_produk = [];

        if ($this->request->is('post')) {
            $tgl_dipilih = $_POST['report_date'];
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_penjualan p');
        $builder->select('p.total_bayar');
        $builder->where('p.is_deleted', 0);
        $builder->where('DATE(p.tgl_dibuat)', date('Y-m-d', strtotime($tgl_dipilih)));
        $query   = $builder->get();
        $query_result = $query->getResult();

        if($query_result) {
            foreach($query_result as $q) {
                $jumlah_transaksi++;
                $omset += $q->total_bayar;
            }
        }

        $builder = $db->table('tbl_penjualan_detail d');
        $builder->select('d.harga_beli, d.harga_jual, d.qty');
        $builder->where('d.is_deleted', 0);
        $builder->where('p.is_deleted', 0);
        $builder->where('DATE(p.tgl_dibuat)', date('Y-m-d', strtotime($tgl_dipilih)));
        $builder->join('tbl_penjualan p', 'd.penjualan_id = p.penjualan_id');
        $query   = $builder->get();
        $query_result = $query->getResult();

        if($query_result) {
            $omset = 0;
            foreach($query_result as $q) {
                $profit += ($q->harga_jual - $q->harga_beli) * $q->qty;
                // $omset += $q->harga_jual * $q->qty;
            }
        }

        if($omset > 0 && $profit > 0) {
            $persentase_profit = $profit / $omset * 100;
        }


        $builder = $db->table('tbl_penjualan_detail d');
        $builder->select('pr.nama_produk');
        $builder->selectCount('pr.produk_id');
        $builder->where('d.is_deleted', 0);
        $builder->where('p.is_deleted', 0);
        $builder->where('DATE(p.tgl_dibuat)', date('Y-m-d', strtotime($tgl_dipilih)));
        $builder->join('tbl_penjualan p', 'd.penjualan_id = p.penjualan_id');
        $builder->join('tbl_produk pr', 'd.produk_id = pr.produk_id');
        $builder->groupBy('pr.produk_id');

        $query   = $builder->get();
        $query_result = $query->getResult();

        if($query_result) {
            foreach ($query_result as $key => $value) {
                $most_wanted_produk[] = array(
                    'nama_produk' => $value->nama_produk,
                    'jumlah' => $value->produk_id
                );
            }

            usort($most_wanted_produk, function ($a, $b) {return $a['jumlah'] < $b['jumlah'];});
        }
       

        return view('penjualan/report_harian', array(
            'form_action' => base_url().'penjualan/harian',
            'tgl_dipilih' => $tgl_dipilih,
            'omset' => number_format($omset),
            'jumlah_transaksi' => number_format($jumlah_transaksi),
            'profit' => number_format($profit),
            'persentase_profit' => $persentase_profit > 0 ? number_format($persentase_profit, 2) : 0,
            'terlaris' => $most_wanted_produk
        ));
    }
}
