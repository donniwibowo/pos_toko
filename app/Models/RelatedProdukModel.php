<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatedProdukModel extends Model
{
    protected $table = 'tbl_related_produk';
    protected $primaryKey = 'related_produk_id';
    protected $allowedFields = ['produk_parent_id', 'produk_child_id', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
   
}