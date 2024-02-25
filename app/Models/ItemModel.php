<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'item_mingala';
    protected $primaryKey = 'id_item';
    protected $allowedFields = ['id_item', 'style', 'color', 'size', 'qty', 'mo', 'date_wh'];

    public function getLastItemId()
    {
        $row = $this->select('id_item')
            ->orderBy('id_item', 'DESC')
            ->first();

        return $row ? $row['id_item'] : null;
    }

    public function cekDuplikatItem($style, $color, $size)
    {
        $item = $this->where('style', $style)
            ->where('color', $color)
            ->where('size', $size)
            ->first();

        return $item;
    }


    public function getItem()
    {
        return $this->findAll();
    }
    // Cek Id Item
    public function isItemExists($id_item)
    {
        $query = $this->getWhere(['id_item' => $id_item]);

        return $query->getRow() !== null;
    }

    public function getTotalQty()
    {
        $rows = $this->findAll();
        $totalQty = 0;

        if ($rows) {
            foreach ($rows as $row) {
                $totalQty += $row['qty'];
            }
        }

        return $totalQty;
    }

    public function isDuplicate($style, $color, $size)
    {
        $item = $this->where('style', $style)
            ->where('color', $color)
            ->where('size', $size)
            ->first();

        return $item !== null;
    }
}