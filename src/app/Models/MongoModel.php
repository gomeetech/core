<?php

namespace Gomee\Models;

use Gomee\Constants\DbConnectionConstant;
use Jenssegers\Mongodb\Eloquent\Model as BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class MongoModel extends BaseModel
{
    use ModelEventMethods, ModelFileMethods;

    const MODEL_TYPE = 'mongo'; 

    protected $connection = DbConnectionConstant::NOSQL;

    protected $dates = ['deleted_at'];

    protected $fillable = ['*'];

    protected $appends = ['id'];

    protected $guarded = [];

}
