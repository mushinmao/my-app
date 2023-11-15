<?php

namespace App\DB\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShortLinks extends Model
{
    protected $table = 'shortLinks';

    protected $primaryKey = 'id';

    public $timestamps = false;
}