<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VchDet extends Model
{
    use HasFactory;
    protected $table='Vchdet';

    protected $primaryKey="Id";

    public $timestamps=false;


    static function getPerticulars($mainId, $accountid)
    {

        $sql = new VchDet();


        $data = $sql->select('VchMain.*', 'Vchdet.Amount as vcAmount', 'Vchdet.FCamt as FcFCamt', 'Vchdet.Costcentre as Costcentre', 'Vchdet.Narration as Narration', 'accounts.ACName as ACName')
            ->Join("VchMain", "VchMain.Id", "=", "Vchdet.MainId")
            ->Join("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->where("Vchdet.AcId", "!=", $accountid)
            ->where("Vchdet.MainId", "=", $mainId)
            ->get();

            // echo '<prE>';print_r($data);exit;

        $perticulars = "";
        $narration = "";
        if (!empty($data)) {
            foreach ($data as $keys => $values) {
                if ($values->vcAmount < 0) {
                    $amount = abs($values->vcAmount) . " CR ";
                } else {
                    $amount = abs($values->vcAmount) . " DR ";
                }
                $narration = isset($values->Naration) ? $values->Naration : "";
                $perticulars .= $values->ACName . " " . $amount . '<br>';
            }
            $perticulars .= $narration;
        }
        

        return $perticulars;
    }

}
