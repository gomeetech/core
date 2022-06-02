<?php
namespace App\Engines;

Class ModuleManager{
    protected static $containers = [];
    
    public static function addScope($scope, $name = '')
    {
        if(!array_key_exists($scope, static::$containers)){
            static::$containers[$scope] = [
                'slug' => $scope,
                'name' => $name?$name:$scope,
                'modules' => []
            ];
        }elseif($name){
            static::$containers[$scope]['name'] = $name;
        }
    }

    public static function addModule($scope = 'admin', $module = '', $name = '', $subModule = [])
    {
        if(!array_key_exists($scope, static::$containers)){
            static::addScope($scope);
        }
        static::$containers[$scope][$module] = [
            'slug' => $module,
            'name' => $name?$name:$module,
            'sub' => []
        ];
    }

    public static function addSubModule($scope, $module, $subs = [])
    {
        if(!array_key_exists($scope, static::$containers)) static::addScope($scope);
        if(!array_key_exists($module, static::$containers[$scope])) static::addModule($module);
        
        
    }
}