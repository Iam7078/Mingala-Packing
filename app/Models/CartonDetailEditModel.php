<?php

namespace App\Models;

use CodeIgniter\Model;

class CartonDetailEditModel extends Model
{
    protected $table = 'carton_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_item', 'qty', 'status'];

}