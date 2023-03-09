<?php

namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\Log; 

class GatiService{

    public $api_key;
    public $docket_no;



    public function __construct(){

        $this->api_key="73A163021F60BF94";


    }



    public function getGatiInfoUsingDocketno(){

        $url = 'https://justi.gati.com/webservices/GatiKWEDktJTrack.jsp?p1='.$this->docket_no.'&p2='.$this->api_key; // APIKEY required
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,  $url);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        $decodedData =   json_decode($response, true);
 
        // $decodedData=array (
        //     'Gatiresponse' => 
        //     array (
        //       'requid' => '272619972',
        //       'dktinfo' => 
        //       array (
        //         0 => 
        //         array (
        //           'dktno' => '272619972',
        //           'result' => 'successful',
        //           'PREPICKUP_INFO' => 
        //           array (
        //           ),
        //           'DOCKET_NUMBER' => '272619972',
        //           'DOCKET_STATUS' => 'Tc Sent From Enroute Ou',
        //           'ORDER_NO' => '102',
        //           'REF_NUMBER' => '',
        //           'CONSIGNOR_NAME' => 'BIGAPPLE LIFESTYLE PRIVATE LIMITED',
        //           'CONSIGNEE_NAME' => 'HIMALAYA',
        //           'BOOKING_STATION' => 'Hyderabad East',
        //           'BOOKED_DATETIME' => '26-NOV-2022 17:24',
        //           'ACTUAL_WEIGHT' => '12',
        //           'NO_OF_PKGS' => '2',
        //           'SERVICE_NAME' => 'SUR EXPRESS',
        //           'DELIVERY_STATION' => 'Banagalore',
        //           'ASSURED_DELIVERY_DATE' => '30-NOV-2022',
        //           'REVISED_DELIVERY_DATE' => '',
        //           'REVISED_DELIVERY_REASON' => '',
        //           'TRANSIT_DTLS' => 
        //           array (
        //             0 => 
        //             array (
        //               'INTRANSIT_DATE' => '27-NOV-2022',
        //               'INTRANSIT_TIME' => '05:21',
        //               'INTRANSIT_LOCATION' => 'Hyderabad Outbound',
        //               'INTRANSIT_STATUS' => 'Tc Sent From Enroute Ou',
        //               'INTRANSIT_STATUS_CODE' => 'TCSEO',
        //               'REASON_CODE' => '',
        //               'REASON_DESC' => '',
        //             ),
        //             1 => 
        //             array (
        //               'INTRANSIT_DATE' => '26-NOV-2022',
        //               'INTRANSIT_TIME' => '23:33',
        //               'INTRANSIT_LOCATION' => 'Hyderabad Outbound',
        //               'INTRANSIT_STATUS' => 'Tc Acknowledgment At Enroute',
        //               'INTRANSIT_STATUS_CODE' => 'TCAER',
        //               'REASON_CODE' => '',
        //               'REASON_DESC' => '',
        //             ),
        //             2 => 
        //             array (
        //               'INTRANSIT_DATE' => '26-NOV-2022',
        //               'INTRANSIT_TIME' => '21:36',
        //               'INTRANSIT_LOCATION' => 'Hyderabad East',
        //               'INTRANSIT_STATUS' => 'Out From Origin',
        //               'INTRANSIT_STATUS_CODE' => 'TCSOU',
        //               'REASON_CODE' => '',
        //               'REASON_DESC' => '',
        //             ),
        //             3 => 
        //             array (
        //               'INTRANSIT_DATE' => '26-NOV-2022',
        //               'INTRANSIT_TIME' => '17:24',
        //               'INTRANSIT_LOCATION' => 'Hyderabad East',
        //               'INTRANSIT_STATUS' => 'Docket Creation',
        //               'INTRANSIT_STATUS_CODE' => 'DCRE',
        //               'REASON_CODE' => '',
        //               'REASON_DESC' => '',
        //             ),
        //           ),
        //           'POD' => '',
        //           'errmsg' => '',
        //         ),
        //       ),
        //     ),
        // );

        return   $decodedData;
    }


    public function getNonGatiInfo(){

    }

    

}


?>