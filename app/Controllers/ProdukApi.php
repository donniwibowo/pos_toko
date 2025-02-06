<?php 

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProdukModel;
use App\Models\ProdukHargaModel;
use App\Models\ProdukDiskonModel;
use App\Models\UserApiLoginModel;


class ProdukApi extends ResourceController
{
	use ResponseTrait;
   
   	public function getAllProduk($user_token, $keyword) {
        $response = array(
            'status' => 404,
            'data' => []
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            $db      = \Config\Database::connect();
            $builder = $db->table('tbl_produk p');
            $builder->select('p.produk_id, UPPER(p.nama_produk) as nama_produk, p.satuan_terkecil, p.netto, p.stok_min, p.satuan_terbesar');
            // $builder->selectSum('s.stok', 'total_stok');
            $builder->where('p.is_deleted', 0);
            // $builder->where('s.is_deleted', 0);
            // $builder->join('tbl_produk_stok s', 's.produk_id = p.produk_id');

            if($keyword != '') {
                $builder->like('p.nama_produk', $keyword, 'both');
            }
            
            $builder->orderBy('p.nama_produk');
            // $builder->groupBy('p.produk_id');
            $query   = $builder->get();
            $query_result = $query->getResult();

            $produk_model = new ProdukModel();

            $data = [];
            foreach($query_result as $q) {
                $data[] = array(
                    'produk_id' => $q->produk_id,
                    'nama_produk' => $q->nama_produk,
                    'satuan_terkecil' => $q->satuan_terkecil,
                    'netto' => $q->netto,
                    'stok_min' => $q->stok_min,
                    'satuan_terbesar' => $q->satuan_terbesar,
                    // 'printed_stok' => $produk_model->getStok($q->produk_id),
                    // 'total_stok' => $produk_model->getStokInSatuanTerkecil($q->produk_id)
                );
            }

            if($query) {
                $response = array(
                    'status' => 200,
                    'data' => $data
                    // 'data' => $query->getResult(),
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

    public function getNewestDiskon($produk_id, $user_token) {
        $response = array(
            'status' => 404,
            'data' => [],
            'data_diskon' => [],
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            $produk_model = new ProdukModel();
            $produk = $produk_model->find($produk_id);

            $produk_diskon_model = new ProdukDiskonModel();
            $produk_diskon = $produk_diskon_model->where('is_deleted', 0)
                                                ->where('produk_id', $produk_id)
                                                ->orderBy('start_diskon', 'desc')->first();

            $data_diskon = [];
            if($produk_diskon) {
                $status_diskon = 1;
                $tgl_skrg = date('Y-m-d H:i:s');
                $start_diskon = date('Y-m-d H:i:s', strtotime($produk_diskon['start_diskon']));
                $end_diskon = date('Y-m-d H:i:s', strtotime($produk_diskon['end_diskon']));

                if($tgl_skrg > $end_diskon) {
                    $status_diskon = 0;
                }

                if($tgl_skrg < $start_diskon) {
                    $status_diskon = 0;
                }

                if($status_diskon) {
                    $produk_diskon_model = new ProdukDiskonModel();
                    $ket = '';
                    $total_diskon = 0;

                    // get jumlah diskon
                    if($produk_diskon['tipe_nominal'] == 'persen') {
                        $total_diskon = $produk_diskon['nominal'].'%';
                    }

                    if($produk_diskon['tipe_nominal'] == 'nominal') {
                        $total_diskon = number_format($produk_diskon['nominal']);
                    }

                    // get tipe diskon
                    if($produk_diskon['tipe_diskon'] == 'bundling') {
                        $produkBundled = $produk_diskon_model->getBundlingProduk($produk_diskon['produk_diskon_id']);
                        $ket = 'Bundling disc '.$total_diskon.' dengan penambahan '.$produkBundled;
                    }

                    if($produk_diskon['tipe_diskon'] == 'tebus murah') {
                        $produkBundled = $produk_diskon_model->getBundlingProduk($produk_diskon['produk_diskon_id']);
                        $ket = 'Tebus murah '.$produk['nama_produk']. ' (Disc '.$total_diskon.') dengan penambahan '.$produkBundled;
                    }

                    if($produk_diskon['tipe_diskon'] == 'diskon langsung') {
                        $ket = 'Disc '.$total_diskon;
                    }

                    $data_diskon[] = array(
                        'list_diskon' => $ket
                    );

                    $response = array(
                        'status' => 200,
                        'data' => [],
                        'data_diskon' => $data_diskon,
                    );
                }
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

    public function getProdukHarga($produk_id, $user_token) {
        $response = array(
            'status' => 404,
            'data' => [],
            'data_diskon' => [],
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            $db      = \Config\Database::connect();

            $query = $db->query('SELECT produk_harga_id, produk_id, satuan, netto, harga_beli, harga_jual FROM tbl_produk_harga USE INDEX (idx_produk_id, idx_is_deleted) WHERE is_deleted=0 AND produk_id='.$produk_id.' ORDER BY harga_jual');

             if($query) {
                $response = array(
                    'status' => 200,
                    'data' => $query->getResult(),
                    // 'data_diskon' => [],
                );
            }


            // $builder = $db->table('tbl_produk_harga');
            // $builder->select('tbl_produk_harga.produk_harga_id, tbl_produk_harga.produk_id, tbl_produk_harga.satuan, tbl_produk_harga.netto, tbl_produk_harga.harga_jual');
            // $builder->where('tbl_produk_harga.is_deleted', 0);
            // $builder->where('tbl_produk_harga.produk_id', $produk_id);
            // $builder->orderBy('tbl_produk_harga.netto');
            // $query   = $builder->get();

            // if($query) {
            //     $response = array(
            //         'status' => 200,
            //         'data' => $query->getResult(),
            //         // 'data_diskon' => [],
            //     );
            // }
        } else {
            $response = array(
                'status' => 403,
                'msg' => 'Token tidak valid',
                'data' => []
            );
        }

        return $this->respond($response);
    }

    public function getProdukDiskon($user_token) {
        $response = array(
            'status' => 404,
            'data' => []
        );

        $api_model = new UserApiLoginModel();
        if($api_model->isTokenValid($user_token)) {
            $db      = \Config\Database::connect();
            $builder = $db->table('tbl_produk');
            $builder->select('tbl_produk.produk_id, tbl_produk.nama_produk, tbl_produk_diskon.*');
            $builder->where('tbl_produk_diskon.is_deleted', 0);
            $builder->join('tbl_produk_diskon', 'tbl_produk.produk_id = tbl_produk_diskon.produk_id');
            $query   = $builder->get();

            if($query) {
                $tmp_data = [];
                foreach($query->getResult() as $d) {
                    $status_diskon = 1;
                    $tgl_skrg = date('Y-m-d H:i:s');
                    $start_diskon = date('Y-m-d H:i:s', strtotime($d->start_diskon));
                    $end_diskon = date('Y-m-d H:i:s', strtotime($d->end_diskon));

                    if($tgl_skrg > $end_diskon) {
                        $status_diskon = 0;
                    }

                    if($tgl_skrg < $start_diskon) {
                        $status_diskon = 0;
                    }

                    if($status_diskon) {
                        $total_diskon = 0;
                        if($d->tipe_nominal == 'persen') {
                            $total_diskon = $d->nominal.'%';
                        }

                        if($d->tipe_nominal == 'nominal') {
                            $total_diskon = number_format($d->nominal);
                        }

                        $produkBundled = '-';
                        if($d->tipe_diskon == 'bundling' || $d->tipe_diskon == 'tebus murah') {
                            $produk_diskon_model = new ProdukDiskonModel();
                            $produkBundled = $produk_diskon_model->getBundlingProduk($d->produk_diskon_id);
                        }

                        $tmp_data[] = array (
                            "produk_id" => $d->produk_id,
                            "nama_produk" => $d->nama_produk,
                            "produk_diskon_id" => $d->produk_diskon_id,
                            "tipe_diskon" => ucwords(strtolower($d->tipe_diskon)),
                            "nominal" => $total_diskon,
                            "tipe_nominal" => $d->tipe_nominal,
                            "start_diskon" => date('d M Y', strtotime($d->start_diskon)),
                            "end_diskon" => date('d M Y', strtotime($d->end_diskon)),
                            'produk_bundled' => $produkBundled,
                            "tgl_dibuat" => date('d M Y H:i:s', strtotime($d->tgl_dibuat)),
                            "dibuat_oleh" => $d->dibuat_oleh,
                            "tgl_diupdate" => $d->tgl_diupdate,
                            "diupdate_oleh" => $d->diupdate_oleh,
                            "is_deleted" => $d->is_deleted
                        );

                    }
                }

                $response = array(
                    'status' => 200,
                    'data' => $tmp_data,
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