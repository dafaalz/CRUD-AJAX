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
        $query = $this->request->getVar('query');
        $model = new ProductModel();
        return $this-> response-> setJSON($model->getProducts($query));
    }

    public function update($id)
    {
        $model = new ProductModel();
        $data = [
            'name' => $this-> request-> getPost('name'),
            'price' => $this-> request-> getPost('price'),
        ];
        $model->updateProduct($id, $data);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $model->deleteProduct($id);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function store()
    {
        $model = new ProductModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
        ];

        $model ->saveProduct($data);
        return $this-> response->setJSON(['status' => 'success']);

    }

    public function edit($id) 
    {
        $model =new ProductModel();

        $data = $model->edit($id);
        return $this-> response-> setJSON($data);
    }
}
