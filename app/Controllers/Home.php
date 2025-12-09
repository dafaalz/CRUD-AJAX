<?php

namespace App\Controllers;

use CodeIgniter\CodeIgniter;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseTrait;



class Home extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        try{
            $db = \Config\Database::connect();
            if($db) {
                return view('welcome_message');
            } else {
                echo "Database Connection Failed";
            }
        } catch(DatabaseException $e) {
            echo "database connection error: " . $e->getMessage();
        }
    }
}
