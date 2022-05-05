<?php

namespace Gomee\Models;


use Gomee\Constants\DbConnectionConstant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;

class SQLModel extends BaseModel
{
    //
    use HasFactory, ModelEventMethods, ModelFileMethods;

    protected $connection = DbConnectionConstant::SQL;

    const MODEL_TYPE = 'sql';

}
