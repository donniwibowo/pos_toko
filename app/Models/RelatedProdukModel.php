<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatedProdukModel extends Model
{
    protected $table = 'tbl_related_produk';
    protected $primaryKey = 'related_produk_id';
    protected $allowedFields = ['produk_parent_id', 'produk_child_id', 'tgl_dibuat', 'dibuat_oleh', 'tgl_diupdate', 'diupdate_oleh', 'is_deleted'];
   
}