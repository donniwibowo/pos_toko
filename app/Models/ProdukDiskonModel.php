<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukDiskonModel extends Model
{
    protected $table = 'tbl_produk_diskon';
    protected $primaryKey = 'produk_diskon_id';
    protected $foreignKey = ['produk_id'];
    protected $allowedFields = ['produk_id', 'tipe_diskon', 'nominal', 'tipe_nominal', 'start_diskon', 'end_diskon', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];


    public function getFormRules() {
        $rules = [
            'nominal' => [
                'rules'=> 'required|numeric',
                'errors' => [
                    'required'=> 'Nominal diskon wajib diisi!',
                    'numeric'=> 'Nominal diskon harus angka!'
                ]
            ],
            'start_diskon' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Tanggal mulai diskon wajib diisi!'
                ]
            ],
            'end_diskon' => [
                'rules'=> 'required',
                'errors' => [
                    'required'=> 'Tanggal berakhir diskon wajin diisi!'
                ]
            ],
        ];

        return $rules;
    }

    public function getBundlingProduk($produk_diskon_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_produk_bundling');
        $builder->where('tbl_produk_bundling.produk_diskon_id', $produk_diskon_id);
        $builder->where('tbl_produk_bundling.is_deleted', 0);
        $builder->join('tbl_produk', 'tbl_produk.produk_id = tbl_produk_bundling.produk_id');
        $query   = $builder->get();
        $result = [];
        foreach($query->getResult() as $d) {
            array_push($result, strtoupper($d->nama_produk));
        }

        return implode(', ', $result);
    }
   
}