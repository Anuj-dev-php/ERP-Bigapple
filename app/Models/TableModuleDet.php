<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\Helper;

class TableModuleDet extends Model
{
    use HasFactory;
    protected $table = 'tbl_module_det';
    public $timestamps = false;

    protected $fillable=['mid','txn_id'];

    public function rolesMap()
    {
        return $this->belongsTo(RolesMap::class, 'txn_id', 'Tran_Id')
            ->where(function ($query) {
                $query->where('Insert_Roles', 'yes')
                    ->orWhere('Edit_Roles', 'yes')
                    ->orWhere('Delete_Roles', 'yes')
                    ->orWhere('View_Roles', 'yes')
                    ->orWhere('Print_Roles', 'yes')
                    ->orWhere('masters', 'yes')
                    ->orWhere('history', 'yes')
                    ->orWhere('amend', 'yes')
                    ->orWhere('copy', 'yes');
            });
    }

    public function tableMaster()
    {
        return $this->belongsTo(TableMaster::class, 'txn_id', 'Id')->orderBy('table_label', 'ASC');
    }
}
