<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProductController extends BaseController
{
    public function index()
    {
        return view('product_view'); //show all products, maybe
    }

    public function fetch() 
    {
        $model = new ProductModel();
        $data = $model->orderBy('id', 'DESC')->findAll();
        return $this->response->setJSON($data);
    }

    public function store()
    {
        $model = new ProductModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
        ];
        $model -> insert($data);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function edit($id) {
        $model = new ProductModel();
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }

    public function update($id) {
        $model = new ProductModel();
        $data = [
            'name' => $this -> request -> getPost('name'),
            'price' => $this -> request -> getPost('price'),
        ];
        $model -> update($id, $data);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete($id) {
        $model = new ProductModel();
        $model -> delete($id);
        return $this->response->setJSON(['status' => 'success']);
    }
}
