<?php

namespace App\Models;

use CodeIgniter\Model;

class CartonDetailModel extends Model
{
    protected $table = 'carton_detail';
    protected $primaryKey = 'id_carton';
    protected $allowedFields = ['id_item', 'qty', 'status'];

    public function getTotalQtyByIdItem($id_item)
    {
        $query = $this->selectSum('qty')
            ->where('id_item', $id_item)
            ->get();

        $result = $query->getRowArray();

        return isset($result['qty']) ? (int) $result['qty'] : 0;
    }
    public function sumQtyByItemId($id_item)
    {
        return $this->selectSum('qty', 'total_qty')
            ->where('id_item', $id_item)
            ->where('status', 1)
            ->first();
    }

    public function getQtyPacking($idItems)
    {
        $quantities = [];

        foreach ($idItems as $idItem) {
            $totalQty = $this->selectSum('qty', 'total_qty')
                ->where('id_item', $idItem)
                ->where('status', 1)
                ->first();

            $quantities[$idItem] = $totalQty['total_qty'] ?? 0;
        }

        return $quantities;
    }

    public function cekIdItem($id_item)
    {
        return $this->selectSum('qty', 'total_qty')
            ->where('id_item', $id_item)
            ->first();
    }

}