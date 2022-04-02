<?php

namespace Gomee\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    //
    use ModelEventMethods, ModelFileMethods;

}
