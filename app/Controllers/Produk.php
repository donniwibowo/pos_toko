<?php

namespace App\Controllers;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\SupplierModel;
use App\Models\ProdukStokModel;
use App\Models\ProdukHargaModel;
use App\Models\RelatedProdukModel;
use App\Models\ProdukDiskonModel;
use App\Models\ProdukBundlingModel;

class Produk extends BaseController
{
    protected $helpers = ['form'];
    
    public function test() {
        // $db      = \Config\Database::connect();
        // $builder = $db->table('tbl_produk');
        // $builder->select('tbl_produk.*, tbl_kategori.kategori_id, tbl_kategori.nama_kategori, tbl_supplier.supplier_id, tbl_supplier.nama_supplier, tbl_produk_stok.stok, tbl_produk_stok.tgl_kadaluarsa');
        // $builder->where('tbl_produk.is_deleted', 0);
        // $builder->where('tbl_produk_stok.is_deleted', 0);
        // $builder->where('tbl_produk_stok.stok <= tbl_produk.stok_min');
        // $builder->join('tbl_produk_stok', 'tbl_produk.produk_id = tbl_produk_stok.produk_id');
        // $builder->join('tbl_supplier', 'tbl_produk.supplier_id = tbl_supplier.supplier_id');
        // $builder->join('tbl_kategori', 'tbl_produk.kategori_id = tbl_kategori.kategori_id');
        // $query_stok   = $builder->get();



        // $db      = \Config\Database::connect();
        // $builder = $db->table('tbl_produk p');

        // $builder->select('p.*, s.stok');
        // $builder->selectSum('s.stok');
        
        // $subQuery = $db->table('tbl_produk_stok ps');
        // $subQuery->selectSum('ps.stok', false);
        // $subQuery->where('ps.produk_id = p.produk_id');
        // $subQuery->where('ps.is_deleted', '0');
        // $subQuery->groupBy('ps.produk_id');

        // $builder->where('p.is_deleted', 0);
        // $builder->where('s.is_deleted', 0);
        // $builder->where('p.stok_min >=', $subQuery);
        // $builder->join('tbl_produk_stok s', 's.produk_id = p.produk_id');
        // $builder->groupBy('p.produk_id');

        // // echo $builder->getCompiledSelect();

        // $produk   = $builder->get();

        // foreach($produk->getResult() as $p) {
        //     echo $p->nama_produk.' - '.$p->stok.' - '.$p->stok_min.' <br />';
        // }

        $produk_stok_model = new ProdukStokModel();
        $produk_stok = $produk_stok_model->where('is_deleted', 0)
                                        ->where('produk_id', 32)
                                        ->orderBy('tgl_kadaluarsa', 'asc')
                                        ->findAll();

        if($produk_stok) {
            foreach($produk_stok as $p) {
                echo $p['stok_id'].' - '.$p['produk_id'].' - '.$p['tgl_kadaluarsa'].' - '.$p['stok'].'<br />';
            }
        }
    }

    public function add()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $rules = [
            'nama_produk' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Nama produk wajib diisi!'
                ]
            ],
            'satuan_terkecil' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Satuan terkecil wajib diisi!'
                ]
            ],
            'netto' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Netto wajib diisi!'
                ]
            ],
        ];

        $produk_model = new ProdukModel();

        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                $data = [
                    'supplier_id' => $_POST['supplier_id'],
                    'kategori_id' => $_POST['kategori_id'],
                    'nama_produk' => $_POST['nama_produk'],
                    'satuan_terkecil' => $_POST['satuan_terkecil'],
                    'netto' => $_POST['netto'],
                    'stok_min' => $_POST['stok_min'],
                    'satuan_terbesar' => $_POST['satuan_terbesar'],
                    'tgl_dibuat' => date('Y-m-d H:i:s'),
                    'dibuat_oleh' => session()->user_id,
                    'tgl_diupdate' => date('Y-m-d H:i:s'),
                    'diupdate_oleh' => session()->user_id,
                ];

                $hasil = $produk_model->insert($data);

                if($hasil) {
                    // // input data ke tabel stok dan tgl kadaluarsa
                    // if(isset($_POST['tgl_kadaluarsa']) && isset($_POST['stok'])) {
                    //     $index = 0;
                    //     $produk_stok_model = new ProdukStokModel();
                    //     foreach($_POST['tgl_kadaluarsa'] as $tgl) {
                    //         $total_stok = $_POST['stok'][$index] * $_POST['netto'];
                    //         $data = [
                    //             'produk_id' => $produk_model->insertID,
                    //             'tgl_kadaluarsa' => date('Y-m-d', strtotime($tgl)),
                    //             'stok' => $total_stok,
                    //             'tgl_dibuat' => date('Y-m-d H:i:s'),
                    //             'dibuat_oleh' => session()->user_id,
                    //             'tgl_diupdate' => null,
                    //             'diupdate_oleh' => 0
                    //         ];

                    //         $produk_stok_model->insert($data);
                    //         $index++;
                    //     }    
                    // }

                    // // input data ke tabel harga
                    // if(isset($_POST['satuan_penjualan']) && isset($_POST['jumlah_penjualan']) && isset($_POST['harga_beli']) && isset($_POST['harga_jual'])) {
                    //     $index = 0;
                    //     $produk_harga_model = new ProdukHargaModel();
                    //     foreach($_POST['satuan_penjualan'] as $satuan) {
                    //         $data = [
                    //             'produk_id' => $produk_model->insertID,
                    //             'satuan' => $satuan,
                    //             'netto' => $_POST['jumlah_penjualan'][$index],
                    //             'harga_beli' => $_POST['harga_beli'][$index],
                    //             'harga_jual' => $_POST['harga_jual'][$index],
                    //             'tgl_dibuat' => date('Y-m-d H:i:s'),
                    //             'dibuat_oleh' => session()->user_id,
                    //             'tgl_diupdate' => null,
                    //             'diupdate_oleh' => 0
                    //         ];

                    //         $produk_harga_model->insert($data);
                    //         $index++;
                    //     }    
                    // }

                    // input produk sebanding
                    if(isset($_POST['related_produk_ids'])) {
                        $related_produk_model = new RelatedProdukModel();
                        foreach($_POST['related_produk_ids'] as $produk_child_id) {
                            $data = [
                                'produk_parent_id' => $produk_model->insertID,
                                'produk_child_id' => $produk_child_id,
                                'tgl_dibuat' => date('Y-m-d H:i:s'),
                                'dibuat_oleh' => session()->user_id,
                                'tgl_diupdate' => null,
                                'diupdate_oleh' => 0   
                            ];

                            $related_produk_model->insert($data);
                        }
                    }

                    session()->setFlashData('success', 'Data produk berhasil ditambahkan');
                    return redirect()->to(base_url('produk/detail/'.pos_encrypt($produk_model->insertID)));
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
            }
        }
        
        // get daftar kategori
        $kategori_model = new KategoriModel();
        $kategori_data = $kategori_model->where('is_deleted', 0)
                                        ->findAll();

        // get daftar supplier
        $supplier_model = new SupplierModel();
        $supplier_data = $supplier_model->where('is_deleted', 0)
                                        ->findAll();

        // get daftar produk
        $daftar_produk = $produk_model->where('is_deleted', 0)
                                    ->findAll();


        return view('produk/form', array(
            'form_action' => base_url().'produk/create',
            'is_new_data' => true,
            'data' => $produk_model,
            'supplier_data' => $supplier_data,
            'kategori_data' => $kategori_data,
            'daftar_produk'   => $daftar_produk,
        ));
    }

    public function manageStok($id)
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $id = pos_decrypt($id);

        $produk_model = new ProdukModel();
        $produk_data = $produk_model->find($id);
        
        // init koneksi ke dataase
        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        // get daftar stok produk
        $builder = $db->table('tbl_produk_stok');
        $builder->where('produk_id', $id);
        $builder->where('is_deleted', 0);
        $produk_stok_query   = $builder->get();

        if ($this->request->is('post')) {
           // input data ke tabel stok dan tgl kadaluarsa
            if(isset($_POST['tgl_kadaluarsa']) && isset($_POST['stok'])) {
                $builder = $db->table('tbl_produk_stok');
                $builder->where('produk_id', $id);
                $builder->delete();

                // $builder->set('is_deleted', 1);
                // $builder->where('produk_id', $id);
                // $builder->update();

                $index = 0;
                $produk_stok_model = new ProdukStokModel();
                foreach($_POST['tgl_kadaluarsa'] as $tgl) {
                    $total_stok = $_POST['stok'][$index] * $produk_data['netto'];
                    $data = [
                        'produk_id' => $id,
                        'tgl_kadaluarsa' => date('Y-m-d', strtotime($tgl)),
                        'stok' => $total_stok,
                        'tgl_dibuat' => date('Y-m-d H:i:s'),
                        'dibuat_oleh' => session()->user_id,
                        'tgl_diupdate' => null,
                        'diupdate_oleh' => 0
                    ];

                    if($produk_stok_model->insert($data)) {
                        $index++;    
                    }
                    
                }

                if($index == count($_POST['tgl_kadaluarsa'])) {
                    session()->setFlashData('success', 'Input stok produk berhasil.');
                } else {
                    $ctr_gagal_input = count($_POST['tgl_kadaluarsa']) - $index;
                    session()->setFlashData('danger', $ctr_gagal_input.' data gagal diinput. Silahkan periksa dan input ulang.');
                }
            }

            
            return redirect()->to(base_url('produk/detail/'.pos_encrypt($id)));
        
        }

        
        return view('produk/form_stok', array(
            'form_action' => base_url().'produk/managestok/'.pos_encrypt($id),
            'is_new_data' => false,
            'produk_data' => (object) $produk_data,
            'produk_stok' => $produk_stok_query->getResult(),
            'produk_stok_model' => new ProdukStokModel(),
        ));
    }

    public function manageHarga($id)
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $id = pos_decrypt($id);

        $produk_model = new ProdukModel();
        $produk_data = $produk_model->find($id);
        
        // init koneksi ke dataase
        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

       // get daftar harga produk
        $builder = $db->table('tbl_produk_harga');
        $builder->where('produk_id', $id);
        $builder->where('is_deleted', 0);
        $produk_harga_query   = $builder->get();

        if ($this->request->is('post')) {

            // input data ke tabel harga
            if(isset($_POST['satuan_penjualan']) && isset($_POST['jumlah_penjualan']) && isset($_POST['harga_beli']) && isset($_POST['harga_jual'])) {
                $builder = $db->table('tbl_produk_harga');
                $builder->where('produk_id', $id);
                $builder->delete();

                // $builder->set('is_deleted', 1);
                // $builder->where('produk_id', $id);
                // $builder->update();

                $index = 0;
                $produk_harga_model = new ProdukHargaModel();
                foreach($_POST['satuan_penjualan'] as $satuan) {
                    $data = [
                        'produk_id' => $id,
                        'satuan' => $satuan,
                        'netto' => $_POST['jumlah_penjualan'][$index],
                        'harga_beli' => $_POST['harga_beli'][$index],
                        'harga_jual' => $_POST['harga_jual'][$index],
                        'tgl_dibuat' => date('Y-m-d H:i:s'),
                        'dibuat_oleh' => session()->user_id,
                        'tgl_diupdate' => null,
                        'diupdate_oleh' => 0
                    ];

                    $produk_harga_model->insert($data);
                    $index++;
                }

                if($index == count($_POST['satuan_penjualan'])) {
                    if($produk_model->update($id, ['tgl_diupdate' => date('Y-m-d H:i:s')])) {
                        session()->setFlashData('success', 'Input harga produk berhasil.');
                    }
                    
                } else {
                    $ctr_gagal_input = count($_POST['satuan_penjualan']) - $index;
                    session()->setFlashData('danger', $ctr_gagal_input.' data gagal diinput. Silahkan periksa dan input ulang.');
                }    
            }

            
            return redirect()->to(base_url('produk/detail/'.pos_encrypt($id))); 
        
        }

        
        return view('produk/form_harga', array(
            'form_action' => base_url().'produk/manageharga/'.pos_encrypt($id),
            'is_new_data' => false,
            'produk_data' => (object) $produk_data,
            'produk_harga' => $produk_harga_query->getResult(),
            'produk_stok_model' => new ProdukStokModel(),
        ));
    }

    public function list()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        $builder = $db->table('tbl_produk');
        $builder->select('tbl_produk.*, tbl_kategori.kategori_id, tbl_kategori.nama_kategori, tbl_supplier.supplier_id, tbl_supplier.nama_supplier');
        $builder->where('tbl_produk.is_deleted', 0);
        $builder->join('tbl_supplier', 'tbl_produk.supplier_id = tbl_supplier.supplier_id');
        $builder->join('tbl_kategori', 'tbl_produk.kategori_id = tbl_kategori.kategori_id');
        $builder->orderBy('tbl_produk.tgl_diupdate', 'desc');
        $query   = $builder->get();

        return view('produk/list', array(
            'produk_data' => $query->getResult(),
            'produk_model' => new ProdukModel(),
            'produk_stok_model' => new ProdukStokModel(),
            'produk_harga_model' => new ProdukHargaModel()
        ));
    }

    public function detail($id)
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }


        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        $builder = $db->table('tbl_produk');
        // $builder = $db->select('tbl_produk.*');
        $builder->where('tbl_produk.produk_id', pos_decrypt($id));
        $builder->join('tbl_supplier', 'tbl_produk.supplier_id = tbl_supplier.supplier_id');
        $builder->join('tbl_kategori', 'tbl_produk.kategori_id = tbl_kategori.kategori_id');
        $produk_query   = $builder->get();

        // get daftar produk stok
        $builder = $db->table('tbl_produk_stok');
        $builder->where('produk_id', pos_decrypt($id));
        $builder->where('stok >', 0);
        $builder->where('is_deleted', 0);
        $produk_stok_query   = $builder->get();

        // get daftar harga produk
        $builder = $db->table('tbl_produk_harga');
        $builder->where('produk_id', pos_decrypt($id));
        $builder->where('is_deleted', 0);
        $produk_harga_query   = $builder->get();

        // get daftar related produk
        $builder = $db->table('tbl_related_produk');
        $builder->where('tbl_related_produk.produk_parent_id', pos_decrypt($id));
        $builder->where('tbl_related_produk.is_deleted', 0);
        $builder->join('tbl_produk', 'tbl_produk.produk_id = tbl_related_produk.produk_child_id');
        $related_produk_query   = $builder->get();

        // get daftar diskon
        $builder = $db->table('tbl_produk');
        $builder->select('tbl_produk.nama_produk, tbl_produk_diskon.*');
        $builder->where('tbl_produk.produk_id', pos_decrypt($id));
        $builder->where('tbl_produk_diskon.is_deleted', 0);
        // $builder->where('tbl_produk_diskon.start_diskon >=', date("Y-m-d"));
        // $builder->orWhere('tbl_produk_diskon.start_diskon <=', date("Y-m-d"));
        // $builder->where('tbl_produk_diskon.end_diskon >=', date("Y-m-d"));
        $builder->join('tbl_produk_diskon', 'tbl_produk.produk_id = tbl_produk_diskon.produk_id');
        $produk_diskon_query   = $builder->get();


        return view('produk/detail', array(
            'produk_model' => new ProdukModel(),
            'produk_stok_model' => new ProdukStokModel(),
            'produk_diskon_model' => new ProdukDiskonModel(),
            'produk_data' => $produk_query->getResult()[0],
            'produk_stok' => $produk_stok_query->getResult(),
            'produk_harga' => $produk_harga_query->getResult(),
            'related_produk' => $related_produk_query->getResult(),
            'produk_diskon' => $produk_diskon_query->getResult(),
        ));
    }

    public function update($id) {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $id = pos_decrypt($id);

        $produk_model = new ProdukModel();
        $produk_data = $produk_model->find($id);
        
        // get rule validation
        $rules = $produk_model->getFormRules();

        // get data kategori
        $kategori_model = new KategoriModel();
        $kategori_data = $kategori_model->where('is_deleted', 0)
                                        ->findAll();
        // get data supplier
        $supplier_model = new SupplierModel();
        $supplier_data = $supplier_model->where('is_deleted', 0)
                                        ->findAll();

        // init koneksi ke dataase
        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        // get daftar stok produk
        $builder = $db->table('tbl_produk_stok');
        $builder->where('produk_id', $id);
        $builder->where('is_deleted', 0);
        $produk_stok_query   = $builder->get();

        // get daftar harga produk
        $builder = $db->table('tbl_produk_harga');
        $builder->where('produk_id', $id);
        $builder->where('is_deleted', 0);
        $produk_harga_query   = $builder->get();

        // daftar semua produk
        $daftar_produk = $produk_model->where('is_deleted', 0)
                                    ->findAll();

        // get daftar related produk
        $related_produk_model = new RelatedProdukModel();
        $related_produk_data = $related_produk_model->where('is_deleted', 0)
                                                    ->where('produk_parent_id', $id)
                                                    ->findAll();

        // ubah data related produk menjadi array
        $related_produk_ids = [];
        if($related_produk_data) {
            foreach($related_produk_data as $d) {
                array_push($related_produk_ids, $d['produk_child_id']);
            }
        }

        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                $data = [
                    'supplier_id' => $_POST['supplier_id'],
                    'kategori_id' => $_POST['kategori_id'],
                    'nama_produk' => $_POST['nama_produk'],
                    'satuan_terkecil' => $_POST['satuan_terkecil'],
                    'netto' => $_POST['netto'],
                    'stok_min' => $_POST['stok_min'],
                    'satuan_terbesar' => $_POST['satuan_terbesar'],
                    'tgl_diupdate' => date('Y-m-d H:i:s'),
                    'diupdate_oleh' => session()->user_id,
                ];

                $hasil = $produk_model->update($id, $data);

                if($hasil) {
                    // if(isset($_POST['update_stock']) && $_POST['update_stock'] == 1) {
                    //     // input data ke tabel stok dan tgl kadaluarsa
                    //     if(isset($_POST['tgl_kadaluarsa']) && isset($_POST['stok'])) {
                    //         $builder = $db->table('tbl_produk_stok');
                    //         $builder->set('is_deleted', 1);
                    //         $builder->where('produk_id', $id);
                    //         $builder->update();

                    //         $index = 0;
                    //         $produk_stok_model = new ProdukStokModel();
                    //         foreach($_POST['tgl_kadaluarsa'] as $tgl) {
                    //             $total_stok = $_POST['stok'][$index] * $_POST['netto'];
                    //             $data = [
                    //                 'produk_id' => $id,
                    //                 'tgl_kadaluarsa' => date('Y-m-d', strtotime($tgl)),
                    //                 'stok' => $total_stok,
                    //                 'tgl_dibuat' => date('Y-m-d H:i:s'),
                    //                 'dibuat_oleh' => session()->user_id,
                    //                 'tgl_diupdate' => null,
                    //                 'diupdate_oleh' => 0
                    //             ];

                    //             $produk_stok_model->insert($data);
                    //             $index++;
                    //         }    
                    //     }

                    //     // input data ke tabel harga
                    //     if(isset($_POST['satuan_penjualan']) && isset($_POST['jumlah_penjualan']) && isset($_POST['harga_beli']) && isset($_POST['harga_jual'])) {
                    //         $builder = $db->table('tbl_produk_harga');
                    //         $builder->set('is_deleted', 1);
                    //         $builder->where('produk_id', $id);
                    //         $builder->update();

                    //         $index = 0;
                    //         $produk_harga_model = new ProdukHargaModel();
                    //         foreach($_POST['satuan_penjualan'] as $satuan) {
                    //             $data = [
                    //                 'produk_id' => $id,
                    //                 'satuan' => $satuan,
                    //                 'netto' => $_POST['jumlah_penjualan'][$index],
                    //                 'harga_beli' => $_POST['harga_beli'][$index],
                    //                 'harga_jual' => $_POST['harga_jual'][$index],
                    //                 'tgl_dibuat' => date('Y-m-d H:i:s'),
                    //                 'dibuat_oleh' => session()->user_id,
                    //                 'tgl_diupdate' => null,
                    //                 'diupdate_oleh' => 0
                    //             ];

                    //             $produk_harga_model->insert($data);
                    //             $index++;
                    //         }    
                    //     }

                    // }

                    // input produk sebanding
                    if(isset($_POST['related_produk_ids'])) {
                        $builder = $db->table('tbl_related_produk');
                        $builder->set('is_deleted', 1);
                        $builder->where('produk_parent_id', $id);
                        $builder->update();

                        $related_produk_model = new RelatedProdukModel();
                        foreach($_POST['related_produk_ids'] as $produk_child_id) {
                            $data = [
                                'produk_parent_id' => $id,
                                'produk_child_id' => $produk_child_id,
                                'tgl_dibuat' => date('Y-m-d H:i:s'),
                                'dibuat_oleh' => session()->user_id,
                                'tgl_diupdate' => null,
                                'diupdate_oleh' => 0   
                            ];

                            $related_produk_model->insert($data);
                        }
                    }

                    session()->setFlashData('success', 'Data produk berhasil diubah');
                    // return redirect()->to(base_url('produk/list')); 
                    return redirect()->to(base_url('produk/detail/'.pos_encrypt($id)));
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
            }
        }

        
        return view('produk/form', array(
            'form_action' => base_url().'produk/update/'.pos_encrypt($id),
            'is_new_data' => false,
            'data' => (object) $produk_data,
            'supplier_data' => $supplier_data,
            'kategori_data' => $kategori_data,
            'produk_stok' => $produk_stok_query->getResult(),
            'produk_harga' => $produk_harga_query->getResult(),
            'daftar_produk'   => $daftar_produk,
            'related_produk_ids' => $related_produk_ids,

        ));
    }

    public function delete($id) {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $produk_model = new ProdukModel();
        $data = [
            'is_deleted' => 1,
        ];

        
        if($produk_model->update(pos_decrypt($id), $data)) {
            session()->setFlashData('success', 'Data produk berhasil dihapus!');      
        } else {
            session()->setFlashData('danger', 'Internal server error');
        }

        return redirect()->to(base_url('produk/list')); 
    }

    public function listByMinStok()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }


        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        $builder = $db->table('tbl_produk p');
        $builder->select('p.*, s.stok');
        $builder->selectSum('s.stok');
        
        $subQuery = $db->table('tbl_produk_stok ps');
        $builder->select('p.*, k.kategori_id, k.nama_kategori, sup.supplier_id, sup.nama_supplier, s.stok, s.tgl_kadaluarsa');
        $subQuery->selectSum('ps.stok', false);
        $subQuery->where('ps.produk_id = p.produk_id');
        $subQuery->where('ps.is_deleted', '0');
        $subQuery->groupBy('ps.produk_id');

        $builder->where('p.is_deleted', 0);
        $builder->where('s.is_deleted', 0);
        $builder->where('p.stok_min >=', $subQuery);
        $builder->join('tbl_produk_stok s', 's.produk_id = p.produk_id');
        $builder->join('tbl_supplier sup', 'p.supplier_id = sup.supplier_id');
        $builder->join('tbl_kategori k', 'p.kategori_id = k.kategori_id');
        $builder->groupBy('p.produk_id');
       
        $query   = $builder->get();

        return view('produk/list_by_stok', array(
            'produk_data' => $query->getResult(),
            'produk_model' => new ProdukModel(),
            'produk_stok_model' => new ProdukStokModel()
        ));
    }

    public function listByEd()
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $today = date("Y-m-d");
        $date = date('Y-m-d', strtotime('+3 month', strtotime($today)));
        $first_date = date('Y-m-01', strtotime($date));
        $last_date = date('Y-m-t', strtotime($date));

        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        $builder = $db->table('tbl_produk');
        $builder->select('tbl_produk.*, tbl_kategori.kategori_id, tbl_kategori.nama_kategori, tbl_supplier.supplier_id, tbl_supplier.nama_supplier, tbl_produk_stok.stok_id, tbl_produk_stok.stok, tbl_produk_stok.tgl_kadaluarsa');
        $builder->where('tbl_produk.is_deleted', 0);
        $builder->where('tbl_produk_stok.is_deleted', 0);
        // $builder->where('tbl_produk_stok.tgl_kadaluarsa >=', $first_date);
        $builder->where('tbl_produk_stok.tgl_kadaluarsa <=', $last_date);
        $builder->join('tbl_produk_stok', 'tbl_produk.produk_id = tbl_produk_stok.produk_id');
        $builder->join('tbl_supplier', 'tbl_produk.supplier_id = tbl_supplier.supplier_id');
        $builder->join('tbl_kategori', 'tbl_produk.kategori_id = tbl_kategori.kategori_id');
        $query   = $builder->get();

       

        return view('produk/list_by_ed', array(
            'produk_data' => $query->getResult(),
            'produk_model' => new ProdukModel(),
            'produk_stok_model' => new ProdukStokModel()
        ));
    }

    public function diskon($id)
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        // id = produk id
        $id = pos_decrypt($id);
        
        $produk_model = new ProdukModel();
        $produk_data = $produk_model->find($id);

        // get daftar produk
        $daftar_produk = $produk_model->where('is_deleted', 0)
                                    ->findAll();
        
        $produk_diskon_model = new ProdukDiskonModel();
        // get rule validation
        $rules = $produk_diskon_model->getFormRules();

        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                $data = [
                    'produk_id' => $_POST['produk_id'],
                    'tipe_diskon' => $_POST['tipe_diskon'],
                    'nominal' => $_POST['nominal'],
                    'tipe_nominal' => $_POST['tipe_nominal'],
                    'start_diskon' => date('Y-m-d', strtotime($_POST['start_diskon'])),
                    'end_diskon' => date('Y-m-d', strtotime($_POST['end_diskon'])),
                    'tgl_dibuat' => date('Y-m-d H:i:s'),
                    'dibuat_oleh' => session()->user_id,
                    'tgl_diupdate' => null,
                    'diupdate_oleh' => 0,
                    'is_deleted' => 0
                ];

                $hasil = $produk_diskon_model->insert($data);

                if($hasil) {
                    // input produk bundling
                    if(isset($_POST['produk_bundling_ids'])) {
                        $produk_bundling_model = new ProdukBundlingModel();
                        foreach($_POST['produk_bundling_ids'] as $produk_child_id) {
                            $data = [
                                'produk_diskon_id' => $produk_diskon_model->insertID,
                                'produk_id' => $produk_child_id,
                                'is_deleted' => 0   
                            ];

                            $produk_bundling_model->insert($data);
                        }
                    }

                    session()->setFlashData('success', 'Pengaturan diskon berhasil ditambahkan');
                    return redirect()->to(base_url('produk/detail/'.pos_encrypt($id)));
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
                

            }
        }

        return view('produk/form_diskon', array(
            'form_action' => base_url().'produk/diskon/'.pos_encrypt($id),
            'produk_data' => (object) $produk_data,
            'produk_diskon_data' => new ProdukDiskonModel(),
            'daftar_produk'   => $daftar_produk,
        ));
    }


    public function updatediskon($id)
    {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        // id = produk diskon id
        $id = pos_decrypt($id);

        $produk_diskon_model = new ProdukDiskonModel();
        $produk_diskon_data = $produk_diskon_model->find($id);
        

        $produk_id = $produk_diskon_data['produk_id'];
        $produk_model = new ProdukModel();
        $produk_data = $produk_model->find($produk_id);

        // get daftar produk
        $daftar_produk = $produk_model->where('is_deleted', 0)
                                    ->findAll();
        

        // get daftar bundling produk
        $produk_bundling_model = new ProdukBundlingModel();
        $produk_bundling_data = $produk_bundling_model->where('is_deleted', 0)
                                                    ->where('produk_diskon_id', $id)
                                                    ->findAll();

        // ubah data related produk menjadi array
        $produk_bundling_ids = [];
        if($produk_bundling_data) {
            foreach($produk_bundling_data as $d) {
                array_push($produk_bundling_ids, $d['produk_id']);
            }
        }

       
        // get rule validation
        $rules = $produk_diskon_model->getFormRules();

        if ($this->request->is('post')) {
            if ($this->validate($rules)) {
                $data = [
                    'produk_id' => $_POST['produk_id'],
                    'tipe_diskon' => $_POST['tipe_diskon'],
                    'nominal' => $_POST['nominal'],
                    'tipe_nominal' => $_POST['tipe_nominal'],
                    'start_diskon' => date('Y-m-d', strtotime($_POST['start_diskon'])),
                    'end_diskon' => date('Y-m-d', strtotime($_POST['end_diskon'])),
                    'tgl_diupdate' => date('Y-m-d H:i:s'),
                    'diupdate_oleh' => session()->user_id,
                    'is_deleted' => 0
                ];

                $hasil = $produk_diskon_model->update($id, $data);

                if($hasil) {
                    // input produk bundling
                    if(isset($_POST['produk_bundling_ids'])) {
                        $produk_bundling_model = new ProdukBundlingModel();
                        $db      = \Config\Database::connect();
                        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

                        $builder = $db->table('tbl_produk_bundling');
                        $builder->set('is_deleted', 1);
                        $builder->where('produk_diskon_id', $id);
                        $builder->update();

                        foreach($_POST['produk_bundling_ids'] as $produk_child_id) {
                            $data = [
                                'produk_diskon_id' => $id,
                                'produk_id' => $produk_child_id,
                                'is_deleted' => 0   
                            ];

                            $produk_bundling_model->insert($data);
                        }
                    }

                    session()->setFlashData('success', 'Pengaturan diskon berhasil diubah');
                    return redirect()->to(base_url('produk/detail/'.pos_encrypt($produk_id)));
                } else {
                    session()->setFlashData('danger', 'Internal server error');
                }
                

            }
        }


        return view('produk/form_diskon', array(
            'form_action' => base_url().'produk/updatediskon/'.pos_encrypt($id),
            'produk_data' => (object) $produk_data,
            'produk_diskon_data' => (object) $produk_diskon_data,
            'daftar_produk'   => $daftar_produk,
            'produk_bundling_ids' => $produk_bundling_ids
        ));
    }


    public function deleteDiskon($produk_diskon_id) {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $produk_diskon_id = pos_decrypt($produk_diskon_id);
        $produk_diskon_model = new ProdukDiskonModel();
        $produk_diskon_data = $produk_diskon_model->find($produk_diskon_id);

        $data = [
            'is_deleted' => 1,
        ];

        
        if($produk_diskon_model->update($produk_diskon_id, $data)) {
            $db      = \Config\Database::connect();
            $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

            $builder = $db->table('tbl_produk_bundling');
            $builder->set('is_deleted', 1);
            $builder->where('produk_diskon_id', $produk_diskon_id);
            $builder->update();

            session()->setFlashData('success', 'Data produk diskon berhasil dihapus!');      
        } else {
            session()->setFlashData('danger', 'Internal server error');
        }

        return redirect()->to(base_url('produk/listdiskon')); 
    }

    public function listDiskon() {
        // get daftar diskon
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        $builder = $db->table('tbl_produk');
        $builder->select('tbl_produk.produk_id, tbl_produk.nama_produk, tbl_produk_diskon.*');
        $builder->where('tbl_produk_diskon.is_deleted', 0);
        $builder->join('tbl_produk_diskon', 'tbl_produk.produk_id = tbl_produk_diskon.produk_id');
        $produk_diskon_query   = $builder->get();

        return view('produk/list_by_diskon', array(
            'produk_diskon_model' => new ProdukDiskonModel(),
            'produk_diskon' => $produk_diskon_query->getResult(),
        ));
    }

    public function bundling($ids) {
        
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        $ids = pos_decrypt($ids);
        $produk_ids = explode(',', $ids);

        $produk_data = [];
        $db      = \Config\Database::connect();
        $db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        
        foreach($produk_ids as $produk_id) {
            $builder = $db->table('tbl_produk p');
            $builder->select('p.produk_id, p.nama_produk, p.satuan_terkecil, p.satuan_terbesar, h.produk_harga_id, h.satuan, h.netto, h.harga_beli, h.harga_jual');
            $builder->where('p.is_deleted', 0);
            $builder->where('h.is_deleted', 0);
            $builder->where('p.produk_id', $produk_id);
            $builder->join('tbl_produk_harga h', 'h.produk_id = p.produk_id');
            $query   = $builder->get();

            $query_result = $query->getResult();

            if($query_result) {
                foreach($query_result as $q) {
                    if(!isset($produk_data[$q->nama_produk])) {
                        $produk_data[$q->nama_produk] = [];
                     
                    }

                    array_push($produk_data[$q->nama_produk], $q);
                }
               
               
            }
        }

        // get daftar kategori
        $kategori_model = new KategoriModel();
        $kategori_data = $kategori_model->where('is_deleted', 0)
                                        ->findAll();

        // get daftar supplier
        $supplier_model = new SupplierModel();
        $supplier_data = $supplier_model->where('is_deleted', 0)
                                        ->findAll();

       
        return view('produk/form_bundling', array(
            'produk_data' => $produk_data,
            'kategori_data' => $kategori_data,
            'supplier_data' => $supplier_data,
        ));
    }

     public function createBundling() {
        if(!session()->logged_in) {
            return redirect()->to(base_url('user/login')); 
        }

        if ($this->request->is('post')) {
            $data = [
                'supplier_id' => $_POST['supplier_id'],
                'kategori_id' => $_POST['kategori_id'],
                'nama_produk' => $_POST['nama_produk'],
                'satuan_terkecil' => 'pcs',
                'netto' => 1,
                'stok_min' => 1,
                'satuan_terbesar' => 'pcs',
                'tgl_dibuat' => date('Y-m-d H:i:s'),
                'dibuat_oleh' => session()->user_id,
                'tgl_diupdate' => date('Y-m-d H:i:s'),
                'diupdate_oleh' => session()->user_id,
            ];

            $produk_model = new ProdukModel();
            $produk_harga_model = new ProdukHargaModel();
            $produk_stok_model = new ProdukStokModel();

            if($produk_model->insert($data)) {
                if(isset($_POST['produk_harga_id'])) {
                    $harga_beli = 0;
                    $harga_jual = 0;
                    $tgl_kadaluarsa_baru = date('Y-m-d', strtotime('2030-12-30'));

                    for($i=0; $i < count($_POST['produk_harga_id']); $i++) {
                        $produk_harga = $produk_harga_model->find($_POST['produk_harga_id'][$i]);
                        $netto = 0;
                        if($produk_harga) {
                            $harga_beli += $produk_harga['harga_beli'];
                            $harga_jual += $produk_harga['harga_jual'];
                            $netto = $produk_harga['netto'];
                        }

                        $produk_stok = $produk_stok_model->where('is_deleted', 0)
                                                        ->where('produk_id', $_POST['produk_id'][$i])
                                                        ->orderBy('tgl_kadaluarsa')
                                                        ->first();
                        if($produk_stok) {
                            $stok_skrg = $produk_stok['stok'] - ($netto * $_POST['jumlah_produk']);
                            $produk_stok_model->update($produk_stok['stok_id'], ['stok' => $stok_skrg]);

                            if(date('Y-m-d', strtotime($produk_stok['tgl_kadaluarsa'])) < $tgl_kadaluarsa_baru) {
                                $tgl_kadaluarsa_baru = date('Y-m-d', strtotime($produk_stok['tgl_kadaluarsa']));
                            }
                        }
                    }

                    $data_harga = [
                        'produk_id' => $produk_model->insertID,
                        'satuan' => 'pcs',
                        'netto' => 1,
                        'harga_beli' => $harga_beli,
                        'harga_jual' => $harga_jual,
                        'tgl_dibuat' => date('Y-m-d H:i:s'),
                        'dibuat_oleh' => session()->user_id,
                        'tgl_diupdate' => null,
                        'diupdate_oleh' => 0
                    ];

                    $data_stok = [
                        'produk_id' => $produk_model->insertID,
                        'tgl_kadaluarsa' => date('Y-m-d', strtotime($tgl_kadaluarsa_baru)),
                        'stok' => $_POST['jumlah_produk'],
                        'tgl_dibuat' => date('Y-m-d H:i:s'),
                        'dibuat_oleh' => session()->user_id,
                        'tgl_diupdate' => null,
                        'diupdate_oleh' => 0
                    ];

                    if($produk_harga_model->insert($data_harga)) {
                        if($produk_stok_model->insert($data_stok)) {
                            session()->setFlashData('danger', 'Produk bundling berhasil dibuat.');
                            return redirect()->to(base_url('produk/list'));
                        } else {
                            session()->setFlashData('danger', 'Produk bundling gagal input stok.');
                            return redirect()->to(base_url('penjualan/analisa'));    
                        }
                    } else {
                        session()->setFlashData('danger', 'Produk bundling gagal input harga.');
                        return redirect()->to(base_url('penjualan/analisa'));
                    }
                    
                    
                } else {
                    session()->setFlashData('danger', 'Tidak ada produk terpilih.');
                    return redirect()->to(base_url('penjualan/analisa'));
                }
            } else {
                session()->setFlashData('danger', 'Produk bundling gagal dibuat.');
                return redirect()->to(base_url('penjualan/analisa'));
            }
        }
     }

}
