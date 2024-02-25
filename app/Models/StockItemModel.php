<?php

namespace App\Models;

use CodeIgniter\Model;

class StockItemModel extends Model
{
    protected $table = 'stock_item';
    protected $primaryKey = 'id_item';
    protected $allowedFields = ['id_item', 'qty', 'date'];

    public function addOrUpdateStockItem($idItem, $qty, $formattedDate)
    {
        $existingItem = $this->where('id_item', $idItem)->first();

        if ($existingItem) {
            $newQty = $existingItem['qty'] + $qty;
            $this->update($existingItem['id_item'], ['qty' => $newQty, 'date' => $formattedDate]);
        } else {
            $this->save(['id_item' => $idItem, 'qty' => $qty]);
        }

        return true;
    }
    public function getTotalQtystock($id_item)
    {
        $query = $this->selectSum('qty')
            ->where('id_item', $id_item)
            ->get();

        $result = $query->getRowArray();

        return isset($result['qty']) ? (int) $result['qty'] : 0;
    }

    public function updateStockItem($idItem, $qty)
    {
        $existingItem = $this->where('id_item', $idItem)->first();

        if ($existingItem) {
            $newQty = $existingItem['qty'] - $qty;
            $this->update($existingItem['id'], ['qty' => $newQty]);
        } else {
            $this->save(['id_item' => $idItem, 'qty' => $qty]);
        }

        return true;
    }

    public function getQuantities($idItems, $field = 'qty')
    {
        $quantities = [];

        foreach ($idItems as $idItem) {
            $stockItem = $this->where('id_item', $idItem)->first();

            if ($stockItem) {
                $quantities[$idItem] = (int) $stockItem[$field];
            } else {
                $quantities[$idItem] = 0;
            }
        }

        return $quantities;
    }
}