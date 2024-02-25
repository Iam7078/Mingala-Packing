<?php

namespace App\Models;

use CodeIgniter\Model;

class PackingModel extends Model
{
    protected $table = 'tb_packing';

    protected $primaryKey = 'id_packing';

    protected $allowedFields = ['id_packing', 'id_carton', 'qty_carton', 'date'];

    protected $useAutoIncrement = true;

    public function getTotalPacking()
    {
        return $this->countAllResults();
    }

    public function getTotalQtyPacking()
    {
        $rows = $this->findAll();
        $totalQty = 0;

        foreach ($rows as $row) {
            $totalQty += $row['qty_carton'];
        }

        return $totalQty;
    }

    public function checkCartonStatus($idCarton)
    {
        return $this->where('id_carton', $idCarton)->countAllResults() > 0;
    }

    public function getDataForDate($date)
    {
        return $this->where('date', $date)->findAll();
    }
}