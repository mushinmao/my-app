<?php

namespace App\DB\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ShortLinks extends Model
{
    protected $table = 'shortLinks';

    protected $primaryKey = 'id';

    public $timestamps = false;
}