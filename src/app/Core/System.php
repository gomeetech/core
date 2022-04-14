<?php
namespace Gomee\Core;

use Gomee\Files\Filemanager;

class System{
    /**
     * Undocumented variable
     *
     * @var Filemanager
     */
    protected static $filemanager;
    protected static $packages = [
        
    ];
    

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
            static::$packages[$name] = array_merge(static::$packages[$name], is_array($path)?$path:$data);
            return true;
        }
        else{
            if(is_array($path)){
                if(array_key_exists('path', $path) && is_dir($path['path'])){
                    static::$packages[$name] = array_merge([
                        'path' => $path['path'],
                        'routes' => [
                            'admin' => [],
                            'client' => []
                        ]
                    ], is_array($path)?$path:[], is_array($data)?$data:[], ['path' => $path['path']]);
                    return true;
                }
                elseif (array_key_exists('dir', $path) && is_dir($path['dir'])) {
                    static::$packages[$name] = array_merge([
                        'path' => $path['dir'],
                        'routes' => [
                            'admin' => [],
                            'client' => []
                        ]
                    ], is_array($path)?$path:[], is_array($data)?$data:[], ['path' => $path['dir']]);
                    return true;
                }
            }
            elseif (is_string($path) && is_dir($path)) {
                static::$packages[$name] = array_merge([
                    'path' => $path,
                    'routes' => [
                        'admin' => [],
                        'client' => []
                    ]
                ], is_array($data)?$data:[], [
                    'path' => $path
                ]);
                return true;
            }

            
        }
        return false;
        
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