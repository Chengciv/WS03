<?php

namespace App\Controllers;

use Framework\Database;

use PDO;

class HomeController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);  // ← $this->db not $db
    }

    public function index()
    {
        $listings = $this->db->query(  // ← $this->db not $this->$db
            'SELECT * FROM listings LIMIT 6'
        )->fetchAll(PDO::FETCH_OBJ);

        loadView('home', ['listings' => $listings]);
    }
}