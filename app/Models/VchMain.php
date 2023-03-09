<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VchMain extends Model
{
    use HasFactory;
    protected $table = "VchMain";
    public $timestamps=false;

    // Get sub ledger data
    static function getSubLedger($columnName, $columnSortOrder, $draw, $row, $rowperpage, $accountid, $fromdate, $todate, $department, $costCenter, $searchString, $dropval)
    {

        $sql = new VchMain();
        // $sql = new Vchdet();
        if ($department != "") {
            $sql = $sql->where("VchMain.dept", "=", $department);
        }
        if ($costCenter != "") {
            $sql = $sql->where("Vchdet.Costcentre", "=", $costCenter);
        }
        if ($dropval != "" && $searchString != "") {

            $sql = $sql
                ->orWhere(function ($sql) use ($searchString, $dropval) {
                    // if ($searchString->has('Narration') && $searchString->get('Vchdet.Narration') != "0")
                    if ($dropval == 'VchMain.VchDate') {
                        $finalDate = date('Y-m-d', strtotime($searchString));
                        $sql->orWhereDate($dropval, '=', $finalDate);
                    } else {

                        $sql->orWhere($dropval, 'LIKE', "%{$searchString}%");
                    }
                });
        } else if ($searchString != "") {

            $sql = $sql
                ->orWhere(function ($sql) use ($searchString) {
                    $finalDate = date('Y-m-d', strtotime($searchString));
                    // if ($searchString->has('Narration') && $searchString->get('Vchdet.Narration') != "0")
                    $sql->orWhere('Vchdet.Narration', 'LIKE', "%{$searchString}%")
                        ->orWhere('VchMain.VchNo', 'LIKE', "%{$searchString}%")
                        ->orWhere('Vchdet.Amount', 'LIKE', "%{$searchString}%")
                        ->orwhere('Department.DeptName', 'LIKE', "%{$searchString}%")
                        ->orWhere('SalesMen.Name', 'LIKE', "%{$searchString}%")
                        ->orWhere('Costcentre.Name', 'LIKE', "%{$searchString}%")
                        ->orWhereDate('VchMain.VchDate', '=', $finalDate)
                        ->orWhere('VchMain.chq_no', 'LIKE', "%{$searchString}%")
                        ->orWhere('VchMain.ch_status', 'LIKE', "%{$searchString}%")
                        ->orWhere('VchMain.cl_date', 'LIKE', "%{$searchString}%");
                });
        }

        $sql = $sql->select('VchMain.*', 'Vchdet.Amount as vcAmount', 'Vchdet.FCamt as FcFCamt', 'Vchdet.Costcentre as Costcentre', 'Vchdet.Narration as Narration', 'accounts.ACName as ACName', 'Costcentre.Name as costName', 'Department.DeptName as DDeptName', 'SalesMen.Name as exeName', 'Project.ProjectName as ProjectName')
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")

            ->orderBy("VchMain.VchDate", 'ASC')
            ->where("Vchdet.AcId", "=", $accountid)
            ->whereDate("VchMain.VchDate", ">=", $fromdate)
            ->whereDate("VchMain.VchDate", "<=", $todate)
            ->skip($row)
            ->take($rowperpage);

        $data = $sql->get();
        // $data = Str::replaceArray('?', $sql->getBindings(), $sql->toSql());


        // echo '<prE>';print_r($data);exit;

        return $data;
    }

    // Get Opening balance for given date range

    static function getOpening($accountid, $fromdate)

    {

        $sql = new VchMain();
        // $sql = new Vchdet();
        // if ($department != "") {
        //     $sql = $sql->where("VchMain.dept", "=", $department);
        // }
        // if ($costCenter != "") {
        //     $sql = $sql->where("Vchdet.Costcentre", "=", $costCenter);
        // }

        // if ($searchString != "") {
        //     $sql = $sql->orWhere('VchNo', 'like', '%' . $searchString . '%');
        // }


        $data = $sql->select('Vchdet.Amount as vcAmount', 'Vchdet.FCamt as fc_amount')
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")
            ->orderBy("VchMain.VchDate", 'ASC')
            ->where("Vchdet.AcId", "=", $accountid)
            ->whereDate("VchMain.VchDate", "<", $fromdate)
            ->get();

        $result['openingbalance'] = 0;
        $result['OpeningFCbalance'] = 0;

        foreach ($data as $key => $value) {
            $result['openingbalance'] += (float)$value->vcAmount;
            $result['OpeningFCbalance'] += (float)$value->fc_amount;
        }
        return $result;
        /*if (isset($data)) {
            foreach ($data as $key => $value) {
                $creadit = ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
                $debit = ($value->vcAmount > 0) ? $value->vcAmount : "0.00";

                $fccreadit = ($value->FcFCamt < 0) ? $value->FcFCamt : "0.00";
                $fcdebit = ($value->FcFCamt > 0) ? $value->FcFCamt : "0.00";
               

                $openingbalance = (float)$openingbalance + (float)$debit;

                $openingbalance = (float)$openingbalance - abs((float)$creadit);
                if ($openingbalance < 0) {
                    $openingbalance = round(abs($openingbalance), 2) . " CR.";
                } else {
                    $openingbalance = round($openingbalance, 2) . " DR.";
                }

                $OpeningFCbalance = (float)$OpeningFCbalance + (float)$fcdebit;
                $OpeningFCbalance = (float)$OpeningFCbalance - abs((float)$fccreadit);
    
                if($OpeningFCbalance < 0){
                    $OpeningFCbalance = round(abs($OpeningFCbalance),2)." CR.";
                }else{
                    $OpeningFCbalance = round($OpeningFCbalance,2)." DR.";
                }
    
               

            }
        }*/
        // dd($openingbalance);
    }

    // Get closing balance for given date range
    static function getClosing($accountid, $fromdate, $todate)

    {

        $sql = new VchMain();
        // $sql = new Vchdet();
        // if ($department != "") {
        //     $sql = $sql->where("VchMain.dept", "=", $department);
        // }
        // if ($costCenter != "") {
        //     $sql = $sql->where("Vchdet.Costcentre", "=", $costCenter);
        // }

        // if ($searchString != "") {
        //     $sql = $sql->orWhere('VchNo', 'like', '%' . $searchString . '%');
        // }


        $data = $sql->select('Vchdet.Amount as vcAmount', 'Vchdet.FCamt as fc_amount')
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")
            ->orderBy("VchMain.VchDate", 'ASC')
            ->where("Vchdet.AcId", "=", $accountid)
            ->whereDate("VchMain.VchDate", ">=", $fromdate)
            ->whereDate("VchMain.VchDate", "<=", $todate)
            ->get();

        $result['Closingbalance'] = 0;
        $result['ClosingFCbalance'] = 0;
        $result['ClosingCreditbalance'] = 0;
        $result['ClosingDebitbalance'] = 0;

        foreach ($data as $key => $value) {
            $result['Closingbalance'] += (int)$value->vcAmount;
            $result['ClosingFCbalance'] += (int)$value->fc_amount;

            $result['ClosingCreditbalance'] += ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
            $result['ClosingDebitbalance'] += ($value->vcAmount > 0) ? $value->vcAmount : "0.00";
        }

        // echo '<pre>';print_r($result);exit;

        return $result;
        // dd($openingbalance);
    }

    // Get ledger counter for pagination
    public static function getSubLedgerCount($accountid, $fromdate, $todate, $department, $costCenter, $searchString)
    {

        $sql = new VchMain();
        // $sql = new Vchdet();

        if ($department != "") {
            $sql = $sql->where("VchMain.dept", "=", $department);
        }
        if ($costCenter != "") {
            $sql = $sql->where("Vchdet.Costcentre", "=", $costCenter);
        }

        if ($searchString != "") {

            $sql = $sql->where('Vchdet.Narration', 'LIKE', "%{$searchString}%")
                ->orWhere('VchMain.VchNo', 'LIKE', "%{$searchString}%")
                ->orWhere('Vchdet.Amount', 'LIKE', "%{$searchString}%")
                ->orwhere('Department.DeptName', 'LIKE', "%{$searchString}%")
                ->orWhere('SalesMen.Name', 'LIKE', "%{$searchString}%")
                ->orWhere('Costcentre.Name', 'LIKE', "%{$searchString}%")
                // ->orWhere('Project.ProjectName', 'LIKE', "%{$searchString}%")
                ->orWhere('VchMain.VchDate', 'LIKE', "%{$searchString}%")
                ->orWhere('VchMain.chq_no', 'LIKE', "%{$searchString}%")
                ->orWhere('VchMain.ch_status', 'LIKE', "%{$searchString}%")
                ->orWhere('VchMain.cl_date', 'LIKE', "%{$searchString}%");


            // ->orWhereDate("DATE_FORMAT('VchMain.VchDate', '%Y %M %d')", 'LIKE', "%{$searchString}%")



        }


        $data = $sql
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")

            ->where("Vchdet.AcId", "=", $accountid)
            ->whereDate("VchMain.VchDate", ">=", $fromdate)
            ->whereDate("VchMain.VchDate", "<=", $todate)
            ->count();

        return $data;
    }

    static function getGeneralLedger($selectedAccounts, $fromdate, $todate, $costCenter, $department)
    {
        // echo $selectedAccounts;exit;
        $sql = new VchMain();
        $from_date = date('Y-m-d', strtotime($fromdate));
        $to_date = date('Y-m-d', strtotime($todate));
        // $sql = new Vchdet();
        // if ($department != "") {
        //     $sql = $sql->where("VchMain.dept", "=", $department);
        // }
        // if ($costCenter != "") {
        //     $sql = $sql->where("Vchdet.Costcentre", "=", $costCenter);
        // }
        // if ($dropval != "" && $searchString != "") {

        //     $sql = $sql
        //         ->orWhere(function ($sql) use ($searchString, $dropval) {
        //             // if ($searchString->has('Narration') && $searchString->get('Vchdet.Narration') != "0")
        //             if ($dropval == 'VchMain.VchDate') {
        //                 $finalDate = date('Y-m-d', strtotime($searchString));
        //                 $sql->orWhereDate($dropval, '=', $finalDate);
        //             } else {

        //                 $sql->orWhere($dropval, 'LIKE', "%{$searchString}%");
        //             }
        //         });
        // } else if ($searchString != "") {

        //     $sql = $sql
        //         ->orWhere(function ($sql) use ($searchString) {
        //             $finalDate = date('Y-m-d', strtotime($searchString));
        //             // if ($searchString->has('Narration') && $searchString->get('Vchdet.Narration') != "0")
        //             $sql->orWhere('Vchdet.Narration', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('VchMain.VchNo', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('Vchdet.Amount', 'LIKE', "%{$searchString}%")
        //                 ->orwhere('Department.DeptName', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('SalesMen.Name', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('Costcentre.Name', 'LIKE', "%{$searchString}%")
        //                 ->orWhereDate('VchMain.VchDate', '=', $finalDate)
        //                 ->orWhere('VchMain.chq_no', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('VchMain.ch_status', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('VchMain.cl_date', 'LIKE', "%{$searchString}%");
        //         });
        // }

        $sql = $sql->select('VchMain.Id', 'VchMain.VchDate', 'VchMain.VchNo', 'VchMain.Naration', 'VchMain.chq_no', 'VchMain.ch_status', 'VchMain.cl_date', 'VchMain.fcexrate', 'VchMain.dept', 'VchMain.executive', 'VchMain.projid', 'VchMain.fccur', 'VchMain.fcexrate', 'Vchdet.Amount as vcAmount', 'Vchdet.FCamt as FcFCamt', 'Vchdet.Costcentre as Costcentre', 'Vchdet.Narration as Narration', 'accounts.Id as AcId', 'accounts.ACName as ACName', 'Costcentre.Name as costName', 'Department.DeptName as DDeptName', 'SalesMen.Name as exeName', 'Project.ProjectName as ProjectName')
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")

            ->orderBy("VchMain.VchDate", 'ASC')
            ->where('Vchdet.AcId', '=', $selectedAccounts)
            ->whereDate("VchMain.VchDate", ">=", $from_date)
            ->whereDate("VchMain.VchDate", "<=", $to_date);

        $data = $sql->get();
        // $data = Str::replaceArray('?', $sql->getBindings(), $sql->toSql());


        return $data;
    }


    // Get ledger counter for pagination
    public static function getGeneralLedgerCount($selectedAccounts, $fromdate, $todate, $costCenter, $department)
    {

        $sql = new VchMain();
        $from_date = date('Y-m-d', strtotime($fromdate));
        $to_date = date('Y-m-d', strtotime($todate));
        // $sql = new Vchdet();

        // if ($department != "") {
        //     $sql = $sql->where("VchMain.dept", "=", $department);
        // }
        // if ($costCenter != "") {
        //     $sql = $sql->where("Vchdet.Costcentre", "=", $costCenter);
        // }

        // if ($searchString != "") {

        //     $sql = $sql->where('Vchdet.Narration', 'LIKE', "%{$searchString}%")
        //         ->orWhere('VchMain.VchNo', 'LIKE', "%{$searchString}%")
        //         ->orWhere('Vchdet.Amount', 'LIKE', "%{$searchString}%")
        //         ->orwhere('Department.DeptName', 'LIKE', "%{$searchString}%")
        //         ->orWhere('SalesMen.Name', 'LIKE', "%{$searchString}%")
        //         ->orWhere('Costcentre.Name', 'LIKE', "%{$searchString}%")
        //         // ->orWhere('Project.ProjectName', 'LIKE', "%{$searchString}%")
        //         ->orWhere('VchMain.VchDate', 'LIKE', "%{$searchString}%")
        //         ->orWhere('VchMain.chq_no', 'LIKE', "%{$searchString}%")
        //         ->orWhere('VchMain.ch_status', 'LIKE', "%{$searchString}%")
        //         ->orWhere('VchMain.cl_date', 'LIKE', "%{$searchString}%");


        //     // ->orWhereDate("DATE_FORMAT('VchMain.VchDate', '%Y %M %d')", 'LIKE', "%{$searchString}%")



        // }


        $data = $sql
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")

            ->where("Vchdet.AcId", "=", $selectedAccounts)
            ->whereDate("VchMain.VchDate", ">=", $from_date)
            ->whereDate("VchMain.VchDate", "<=", $to_date)
            ->count();

        return $data;
    }

    // Trial balance

    static function getTrialBalance($selectedAccounts, $fromdate, $todate, $costCenter, $department)
    {
        // echo '<pre>'; print_r($selectedAccounts);exit;
        $sql = new Account();
        $from_date = date('Y-m-d', strtotime($fromdate));
        $to_date = date('Y-m-d', strtotime($todate));
        // $sql = new Vchdet();
        // if ($department != "") {
        //     $sql = $sql->where("VchMain.dept", "=", $department);
        // }
        // if ($costCenter != "") {
        //     $sql = $sql->where("Vchdet.Costcentre", "=", $costCenter);
        // }
        // if ($dropval != "" && $searchString != "") {

        //     $sql = $sql
        //         ->orWhere(function ($sql) use ($searchString, $dropval) {
        //             // if ($searchString->has('Narration') && $searchString->get('Vchdet.Narration') != "0")
        //             if ($dropval == 'VchMain.VchDate') {
        //                 $finalDate = date('Y-m-d', strtotime($searchString));
        //                 $sql->orWhereDate($dropval, '=', $finalDate);
        //             } else {

        //                 $sql->orWhere($dropval, 'LIKE', "%{$searchString}%");
        //             }
        //         });
        // } else if ($searchString != "") {

        //     $sql = $sql
        //         ->orWhere(function ($sql) use ($searchString) {
        //             $finalDate = date('Y-m-d', strtotime($searchString));
        //             // if ($searchString->has('Narration') && $searchString->get('Vchdet.Narration') != "0")
        //             $sql->orWhere('Vchdet.Narration', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('VchMain.VchNo', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('Vchdet.Amount', 'LIKE', "%{$searchString}%")
        //                 ->orwhere('Department.DeptName', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('SalesMen.Name', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('Costcentre.Name', 'LIKE', "%{$searchString}%")
        //                 ->orWhereDate('VchMain.VchDate', '=', $finalDate)
        //                 ->orWhere('VchMain.chq_no', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('VchMain.ch_status', 'LIKE', "%{$searchString}%")
        //                 ->orWhere('VchMain.cl_date', 'LIKE', "%{$searchString}%");
        //         });
        // }

        $sql = $sql->select('accounts.Id as AcId', 'accounts.ACName as ACName')
        
            ->leftJoin("Vchdet", "Vchdet.AcId", "=", "accounts.Id")
            ->leftJoin("VchMain", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")

            ->orderBy("accounts.Id", 'ASC')
            ->where('accounts.Id', '=',$selectedAccounts)
            ->whereDate("VchMain.VchDate", ">=", $from_date)
            ->whereDate("VchMain.VchDate", "<=", date('Y-m-d'))
            ->groupBy('accounts.Id', 'accounts.ACName');

        $data = $sql->get();



        // $data = Str::replaceArray('?', $sql->getBindings(), $sql->toSql());


        return $data;
    }


    public function vchdets(){
        return $this->hasMany('App\Models\VchDet','MainId');
    }
}
