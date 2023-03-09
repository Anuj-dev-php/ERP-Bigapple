<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableModule extends Model
{
    use HasFactory;
    protected $table = 'tbl_module';
    public $timestamps = false;
    protected $primaryKey='id';
    protected $fillable=['mname'];

    public function tblRoleModule()
    {
        return $this->belongsTo(TblRoleModule::class, 'id', 'module_id');
    }

    public function tableModuleDets()
    {
        return $this->hasMany(TableModuleDet::class, 'mid');
    }

}
