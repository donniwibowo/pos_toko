<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukStokModel extends Model
{
    protected $table = 'tbl_produk_stok';
    protected $primaryKey = 'stok_id';
    protected $foreignKey = ['produk_id'];
    protected $allowedFields = ['produk_id', 'tgl_kadaluarsa', 'stok', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];


    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
    
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