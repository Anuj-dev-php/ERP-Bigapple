<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FieldMaster;

class TableMaster extends Model
{
    use HasFactory;
    protected $table = 'table_master';
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'Id';
    protected $fillable=[ 'Table_Name'
    ,'Field_Name' 
    ,'Tab_Id'
    ,'Parent Table'
    ,'Stock Operation'   
    ,'LinkId'
    ,'Status' 
    ,'Receivable'
    ,'table_label'
    ,'txn_class'
    ,'cr_chk'
    ,'bd_chk'
    ,'t_type'
    ,'ngt_chk'
    ,'qty_zero'
    ,'auto_bill'
    ,'direct_print'
    ,'direct_sms','Created_By'];

    public function rolesMap()
    {
        return $this->hasMany(RolesMap::class, 'Tran_Id');
    }

    
    public function rolesMapWithYes()
    {
        return $this->belongsTo(RolesMap::class, 'id', 'Tran_Id')
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


    public function  fields(){

        return $this->hasMany(FieldsMaster::class,'Table_Name','Table_Name')->where('Tab_Id','<>','None');

    }

    public static function getTableIdByName($tablename){

        return Self::where('Table_Name',$tablename)->value('Id');
    }


    public static function getChildTableName($tablename){

        return Self::where('Parent Table',$tablename)->value('Table_Name');

    }


  

}
