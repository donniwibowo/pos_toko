<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukStokModel extends Model
{
    protected $table = 'tbl_produk_stok';
    protected $primaryKey = 'stok_id';
    protected $foreignKey = ['produk_id'];
    protected $allowedFields = ['produk_id', 'tgl_kadaluarsa', 'stok', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];


    public function convertStok($stok, $netto, $satuan_terkecil, $satuan_terbesar = 'dos') {
       
        $stok_carton = floor($stok / $netto);
        $stok_ecer = $stok - ($netto * $stok_carton);

        if($stok_ecer > 0) {
            if($stok_carton > 0) {
                return $stok_carton.' '.$satuan_terbesar.' '.number_format($stok_ecer, 0).' '.$satuan_terkecil;
            } else  {
                return number_format($stok_ecer, 0).' '.$satuan_terkecil;
            }
            
        } else {
            return $stok_carton.' '.$satuan_terbesar;
        }

        
    }
   
}