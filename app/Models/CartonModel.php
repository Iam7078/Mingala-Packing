<?php

namespace App\Models;

use CodeIgniter\Model;

class CartonModel extends Model
{
    protected $table = 'carton_mingala';
    protected $primaryKey = 'id_carton';
    protected $allowedFields = ['id_carton', 'nomor_carton', 'qty_per_carton'];

    public function generateNextCartonId()
    {
        $query = $this->selectMax('id_carton')->get();

        $row = $query->getRow();

        if ($row->id_carton) {
            $idNumber = (int) substr($row->id_carton, 2);
            $idNumber++;
        } else {
            $idNumber = 1;
        }

        return 'CR' . str_pad($idNumber, 10, '0', STR_PAD_LEFT);
    }

    public function getNomorCarton()
    {
        $query = $this->select('nomor_carton')->orderBy('id_carton', 'DESC')->limit(1)->get()->getRow();

        if ($query && is_numeric($query->nomor_carton)) {
            return (int) $query->nomor_carton;
        } else {
            return null;
        }
    }

    public function insertCarton($data)
    {
        return $this->insert($data);
    }
}