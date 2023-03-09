<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstApiCredentials extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $connection = 'default';

    protected $table = "gst_api_credentials";
}
