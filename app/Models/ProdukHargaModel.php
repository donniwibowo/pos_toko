<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukHargaModel extends Model
{
    protected $table = 'tbl_produk_harga';
    protected $primaryKey = 'produk_harga_id';
    protected $foreignKey = ['produk_id'];
    protected $allowedFields = ['produk_id', 'satuan', 'netto', 'harga_beli', 'harga_jual', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
    
    public function getPrice($produk_id, $type) {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_produk_harga');
        $builder->where('tbl_produk_harga.produk_id', $produk_id);
        $builder->where('tbl_produk_harga.is_deleted', 0);
        $builder->orderBy('tbl_produk_harga.harga_beli');
        $query   = $builder->get();

        $html = '';
        
        foreach($query->getResult() as $d) {
            $profit = $d->harga_jual - $d->harga_beli;
            $profit_percentage = number_format(($profit / $d->harga_beli * 100), 2);
            if($type == 'beli') {
                $html .= '<p>'.number_format($d->harga_beli, 0).'</p>';

            } elseif ($type == 'jual') {
                $html .= '<p>'.number_format($d->harga_jual, 0).'</p>';

            } else {
                $html .= '<p>'.$profit_percentage.'%</p>';
            }
           
            
        }

        return $html;
    }
   
}