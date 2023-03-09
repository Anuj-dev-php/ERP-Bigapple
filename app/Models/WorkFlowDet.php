<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkFlowDet extends Model
{
    use HasFactory;
    protected $table="Workflow_Det";
    public $timestamps=false;
    protected $primaryKey="id";

    protected $fillable=[  
        'fk_id' 
        ,'Tranname' 
        ,'Fieldid' 
        ,'statusid' 
        ,'Condition' 
        ,'Value' 
        ,'Statustype' 
        ,'conjestion' 
        ,'fld_val' 
    ];
    
}
