<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboxTab extends Model
{
    use HasFactory;
    protected $table = 'inbox_tabs';
    protected $fillable=['tab_name'];
}
