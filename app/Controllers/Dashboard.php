<?php

namespace App\Controllers;

class Dashboard extends BaseController
{

    public function __construct()
    {
        helper('session');
        $dbName = session()->get('db_nombre');
        if ($dbName) {
            \App\Libraries\DBManager::init($dbName);
        }
    }

    public function Index(): string {
         return view('main/dashboard'); 
    }
}    