<?php

namespace Gomee\Controllers\Traits;

use Gomee\Core\System;

trait PackageMethods{


    protected $packagePath = null;

    protected $package = null;
    
    public function packageInit()
    {
        if($path = System::getPackagePath($this->package)){
            $this->packagePath = $path;
        }
    }
    
}