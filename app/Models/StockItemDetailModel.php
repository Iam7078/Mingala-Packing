<?php

namespace App\Models;

use CodeIgniter\Model;

class StockItemDetailModel extends Model
{
    protected $table = 'stock_item_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_item', 'qty', 'date'];

    public function addStockToday($idItem, $qty, $formattedDate)
    {
        $existingItem = $this->where('id_item', $idItem)
            ->where('date', $formattedDate)
            ->first();

        if ($existingItem) {
            $newQty = $existingItem['qty'] + $qty;
            return $this->update($existingItem['id'], ['qty' => $newQty]);
        } else {
            return $this->save(['id_item' => $idItem, 'qty' => $qty, 'date' => $formattedDate]);
        }

    }
}