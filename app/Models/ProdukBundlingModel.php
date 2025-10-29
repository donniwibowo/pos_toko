<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukBundlingModel extends Model
{
    protected $table = 'tbl_produk_bundling';
    protected $primaryKey = 'produk_bundling_id';
    protected $foreignKey = ['produk_diskon_id','produk_id'];
    protected $allowedFields = ['produk_diskon_id', 'produk_id', 'is_deleted'];

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if needed

        $this->db->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }
   
}