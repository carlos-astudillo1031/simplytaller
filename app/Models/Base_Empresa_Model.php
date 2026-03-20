<?php

namespace App\Models;

use CodeIgniter\Model;
require_once APPPATH . 'Libraries/DBManager.php';
use App\Libraries\DBManager;


class Base_Empresa_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = DBManager::getDB();
    }
}
