<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'price'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
        'price' => 'required|decimal'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function getProducts($query = null)
    {
        if ($query) {
            $q = strtolower($query);
            return $this->where("LOWER(name) LIKE '%{$q}%'", null, false)->findAll();
        }
        return $this->findAll();
    }

    public function updateProduct($id, $data) {
        if(!isset($id) || !isset($data)) {
            return false;
        }
        return $this -> update($id, $data);
    }

    public function deleteProduct($id) {
        if(!isset($id)) {
            return false;
        }
        return $this -> delete($id);
    }

    public function saveProduct($data)
    {
        if(!isset($data)) {
            return false;
        }
        return $this -> insert($data);
    }

    public function edit($id) {
        if(!isset($id)) {
            return false;
        }
        return $this->find($id);
    }




}
