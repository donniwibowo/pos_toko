<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'tbl_produk';
    protected $primaryKey = 'produk_id';
    protected $foreignKey = ['supplier_id', 'kategori_id'];
    protected $allowedFields = ['supplier_id', 'kategori_id', 'nama_produk', 'satuan_terkecil', 'netto', 'stok_min', 'satuan_terbesar', 'remarks', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];
   

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
    
    public function getFormRules() {
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

        return $rules;
    }



    public function getStokInSatuanTerkecil($produk_id) {
        $total_stok = 0;

        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_produk_stok s');
        $builder->selectSum('s.stok', 'total_stok');
        $builder->where('s.is_deleted', 0);
        $builder->where('s.produk_id', $produk_id);
        $builder->groupBy('s.produk_id');
        $query   = $builder->get();

        $query_result = $query->getResult();
        if($query_result) {
            if(isset($query_result[0])) {
                $total_stok = $query_result[0]->total_stok;
            }
        }

        return $total_stok;
        
    }

    public function getStok($produk_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_produk_stok');
        $builder->where('tbl_produk_stok.produk_id', $produk_id);
        $builder->where('tbl_produk_stok.is_deleted', 0);
        $builder->join('tbl_produk', 'tbl_produk.produk_id = tbl_produk_stok.produk_id');
        $query   = $builder->get();

        $total_stok = 0;
        $nett_per_carton = 1;
        $satuan_terkecil = 'pcs';
        $satuan_terbesar = 'dos';

        foreach ($query->getResult() as $row) {
            $total_stok += $row->stok;
            $nett_per_carton = $row->netto;
            $satuan_terkecil = $row->satuan_terkecil;
            $satuan_terbesar = $row->satuan_terbesar;
        }

        $stok_carton = floor($total_stok / $nett_per_carton);
        $stok_ecer = $total_stok - ($nett_per_carton * $stok_carton);

        if($stok_ecer > 0) {
            if($stok_carton > 0) {
                return $stok_carton.' '.$satuan_terbesar.' '.number_format($stok_ecer, 0).' '.$satuan_terkecil;
            } else {
                return number_format($stok_ecer, 0).' '.$satuan_terkecil;
            }
        } else {
            return $stok_carton.' '.$satuan_terbesar;
        }
    }

    public function getRataRataPenjualan($produk_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_penjualan_detail');
        $builder->select('tbl_penjualan_detail.*, tbl_penjualan.tgl_dibuat, tbl_produk_harga.netto as netto_penjualan, tbl_produk.satuan_terkecil, tbl_produk.netto');
        $builder->where('tbl_penjualan_detail.is_deleted', 0);
        $builder->where('tbl_penjualan_detail.produk_id', $produk_id);
        $builder->join('tbl_penjualan', 'tbl_penjualan.penjualan_id = tbl_penjualan_detail.penjualan_id');
        $builder->join('tbl_produk_harga', 'tbl_produk_harga.produk_harga_id = tbl_penjualan_detail.produk_harga_id');
        $builder->join('tbl_produk', 'tbl_produk.produk_id = tbl_penjualan_detail.produk_id');
        $builder->orderBy('tbl_penjualan.tgl_dibuat', 'ASC');

        $query   = $builder->get();
        $result = $query->getResult();

        $total_penjualan = 0;
        $satuan_terkecil = '';
        $netto_produk = 0;
        $start_penjualan = '';
        $end_penjualan = '';
        $lama_waktu = 0;
        $penjualan_per_hari = 0;


        if($result) {
            foreach ($result as $row) {
                $total_penjualan += ($row->qty * $row->netto_penjualan);
                $satuan_terkecil = $row->satuan_terkecil;
                $netto_produk = $row->netto;
            }


            $l = count($result) - 1;
            $start_penjualan = date('d M Y', strtotime($result[0]->tgl_dibuat));
            $end_penjualan = date('d M Y', strtotime($result[$l]->tgl_dibuat));
            $lama_waktu = date_diff(date_create($start_penjualan),date_create($end_penjualan))->days;
            if($lama_waktu < 1) {
                $lama_waktu = 1;
            }
            $penjualan_per_hari = $total_penjualan / $lama_waktu;
            
        }
       

        return array(
            'start_penjualan' => $start_penjualan,
            'end_penjualan' => $end_penjualan,
            'lama_waktu' => $lama_waktu,
            'total_penjualan' => $this->convertStok($total_penjualan, $netto_produk, $satuan_terkecil),
            'penjualan_per_hari' =>  $this->convertStok($penjualan_per_hari, $netto_produk, $satuan_terkecil),
        );
    }

    private function convertStok($total_stok, $netto_produk, $satuan_terkecil) {
        $stok_label = 0;
        if($total_stok > 0) {
            $total_carton = floor($total_stok / $netto_produk);
            $total_ecer = $total_stok - ($netto_produk * $total_carton);
            

            if($total_ecer > 0) {
                if($total_carton > 0) {
                    $stok_label = $total_carton.' dos '.number_format($total_ecer, 0).' '.$satuan_terkecil;
                } else {
                    $stok_label = number_format($total_ecer, 0).' '.$satuan_terkecil;
                }
            } else {
                $stok_label = $total_carton.' dos';
            }
        }

        return $stok_label;
    }
}