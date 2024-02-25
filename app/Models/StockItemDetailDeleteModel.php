<?php

namespace App\Models;

use CodeIgniter\Model;

class StockItemDetailDeleteModel extends Model
{
    protected $table = 'stock_item_detail';
    protected $primaryKey = 'id_item';
    protected $allowedFields = ['id_item', 'qty', 'date'];
}