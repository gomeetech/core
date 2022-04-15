<?php
namespace Gomee\Core;

use Gomee\Files\Filemanager;
use Gomee\Helpers\Arr;

class System{
    /**
     * Undocumented variable
     *
     * @var Filemanager
     */
    protected static $filemanager;
    /**
     * package
     *
     * @var array<string, Arr>
     */
    protected static $packages = [
        
    ];
    
    protected static $routes = [];
    /**
     * get filemanager with path
     *
     * @param string $path
     * @return Filemanager
     */
    public static function fm($path)
    {
        if(!static::$filemanager){
            static::$filemanager = new Filemanager();
        }
        static::$filemanager->setDir($path);
        return static::$filemanager;
    }

    /**
     * them package
     *
     * @param string $name
     * @param string|array $path
     * @param array $data
     * @return bool
     */
    public static function addPackage($name, $path, $data = []): bool
    {
        if(array_key_exists($name, static::$packages)){
            static::$packages[$name] = static::$packages[$name]->merge(is_array($path)?$path:$data);
            return true;
        }
        else{
            if(is_array($path)){
                if(array_key_exists('path', $path) && is_dir($path['path'])){
                    static::$packages[$name] = new Arr(array_merge([
                        'path' => $path['path'],
                        'routes' => [
                            'admin' => [],
                            'client' => []
                        ]
                    ], is_array($path)?$path:[], is_array($data)?$data:[], ['path' => $path['path']]));
                    return true;
                }
                elseif (array_key_exists('dir', $path) && is_dir($path['dir'])) {
                    static::$packages[$name] = new Arr(array_merge([
                        'path' => $path['dir'],
                        'routes' => [
                            'admin' => [],
                            'client' => []
                        ]
                    ], is_array($path)?$path:[], is_array($data)?$data:[], ['path' => $path['dir']]));
                    return true;
                }
            }
            elseif (is_string($path) && is_dir($path)) {
                static::$packages[$name] = new Arr(array_merge([
                    'path' => $path,
                    'routes' => [
                        'admin' => [],
                        'client' => []
                    ]
                ], is_array($data)?$data:[], [
                    'path' => $path
                ]));
                return true;
            }

            
        }
        return false;
        
    }

    public static function getAllRoutes()
    {
        // $routes = [];

        foreach (static::$packages as $slug => $package) {
            if(array_key_exists($slug, static::$routes)) continue;
            $path = $package->path;
            $routePath = $path . '/src/routes/';
            if($package->routes){
                $routes = $package->routes;
                $data = [];
                foreach ($routes as $scope => $route) {
                    if(is_array($route)){
                        foreach ($route as $key => $file) {
                            if(is_array($file)){
                                if(array_key_exists('file', $file) && is_file($routePath . $scope . '/' . $file['file'])){
                                    $r = [
                                        'prefix' => is_numeric($key)?'':$key,
                                        'group' => $routePath . $scope . '/' . $file['file'],
                                        'middleware' => array_key_exists('middleware', $file)?$file['middleware']:'',
                                        'name' => array_key_exists('name', $file)?$file['name']:(array_key_exists('as', $file)?$file['as']:'')
                                    ];
                                    if(!array_key_exists($scope, $data)) $data[$scope] = [];
                                    $data[$scope][] = $r;
                                }
                            }
                            else{
                                $f = $file;
                                if(count($p = explode(':', $f)) == 2){
                                    
                                }
                                if (is_file($routePath . $scope . '/' . $file)) {
                                
                                    $r = [
                                        'prefix' => is_numeric($key)?'':$key,
                                        'group' => $routePath . $scope . '/' . $file,
                                        'middleware' => ''
                                    ];
    
                                    if(!array_key_exists($scope, $data)) $data[$scope] = [];
                                        $data[$scope][] = $r;
                                }
                            }
                            
                        }
                    }
                }
                static::$routes[$slug] = $data;
            }
        }
        return static::$routes;
    }

    /**
     * them package
     *
     * @param string $name
     * @param string|array $path
     * @param array $data
     * @return bool
     */
    public static function register($name, $path, $data = []):bool
    {
        return static::addPackage($name, $path, $data);
    }

    public static function getPackagePath($package)
    {
        return array_key_exists($package, static::$packages)?static::$packages[$package]:null;
    }
    public static function getPackageDir($package)
    {
        return array_key_exists($package, static::$packages)?static::$packages[$package]:null;
    }



    public static function installPackage($package){
        //
    }

    public static function updatePackage($package)
    {
        # code...
    }

    public static function uninstallPackage($package)
    {
        # code...
    }

    


}