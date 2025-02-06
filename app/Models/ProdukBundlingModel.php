<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukBundlingModel extends Model
{
    protected $table = 'tbl_produk_bundling';
    protected $primaryKey = 'produk_bundling_id';
    protected $foreignKey = ['produk_diskon_id','produk_id'];
    protected $allowedFields = ['produk_diskon_id', 'produk_id', 'is_deleted'];
   
}