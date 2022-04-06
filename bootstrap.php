<?php
use Core\Package;
if(class_exists('Core\Package')){
    Package::register('core', __DIR__);
}