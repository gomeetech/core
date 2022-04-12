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
     * @param string $path
     * @return void
     */
    public static function addPackage($name, $path)
    {
        if(is_string($path) && is_dir($path)){
            static::$packages[$name] = [
                'path' => $path
            ];
        }
    }

    public static function getPackagePath($package)
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