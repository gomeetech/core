<?php

use Gomee\Database\MyAdmin;
use Illuminate\Support\Facades\Config;

if(!function_exists('check_db_migrate')){
    function check_db_migrate(){
        return app(MyAdmin::class, [Config::get("")]);
        $myConfig = Config::get('database.myadmin', []);
        $this->myAdmin = new MyAdmin($myConfig['host'], $myConfig['username'], $myConfig['password']);
        $this->myAdmin->connect();
    }
}