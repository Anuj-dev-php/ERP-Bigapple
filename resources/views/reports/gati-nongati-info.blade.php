 
 
			
					
        <?php

        if($isgati==true){
            ?>

 


            <?php foreach ($gati_info as $res) { ?>

            </br>	
              
            <div class=" mx-auto table-responsive">
            <table  class='table'>
                <tbody>
                    <tr>
                        <th>Docket No.</th>
                        <th>Reference No.</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Pickup Date</th>
                        <th>Status</th>
                    </tr>

                    <?php foreach ($gati_info as $res) { ?>
                    <tr>
                        <td>
                            <?= $res['dktinfo'][0]['DOCKET_NUMBER'] ?>
                        </td>
                        <td>
                            <?= $res['dktinfo'][0]['ORDER_NO'] ?>
                        </td>
                        <td>
                            <?= $res['dktinfo'][0]['BOOKING_STATION'] ?>
                        </td>
                        <td>
                            <?= $res['dktinfo'][0]['DELIVERY_STATION'] ?>
                        </td>
                        <td>
                            <?= $res['dktinfo'][0]['BOOKED_DATETIME'] ?>
                        </td>
                        <td>
                            <?= $res['dktinfo'][0]['DOCKET_STATUS'] ?>
                        </td>
                    </tr>
                    <?php
                }
                    ?>
                </tbody>
            </table>
            </div> 
            </br>
            </br>

            <div>
                <ol id="progress" class="progtrckr" data-progtrckr-steps="4">
                    <li id="shipment" class="progtrckr-done">Booked</li>
                    <li id="transit" class="progtrckr-done">In Transit</li>
                    <li id="reached" class="progtrckr-done">Destination Arrived</li>
                    <li id="delivered" class="progtrckr-done">Delivered</li>
                </ol>

                <?php

                    if ($res['dktinfo'][0]['DOCKET_STATUS'] == "In Transit" 
                    || $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Sent From Enroute Ou"
                    || $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Acknowledgment At Enroute"
                    || $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Sent From Enroute Ou"
                    || $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Acknowledgment At Enroute") 
                    {
                        echo '<script>document.getElementById("shipment").classList.add("progtrckr-done");
                        document.getElementById("transit").classList.add("progtrckr-done");
                        document.getElementById("reached").classList.add("progtrckr-todo");
                        document.getElementById("delivered").classList.add("progtrckr-todo");</script>';
                    } 
                    else if ($res['dktinfo'][0]['DOCKET_STATUS'] == "Destination Arrived") 
                    {
                        echo '<script>document.getElementById("shipment").classList.add("progtrckr-done");
                        document.getElementById("transit").classList.add("progtrckr-done");
                        document.getElementById("reached").classList.add("progtrckr-done");
                        document.getElementById("delivered").classList.add("progtrckr-todo");</script>';
                    } 
                    else if ($res['dktinfo'][0]['DOCKET_STATUS'] == "Delivered"
                    || $res['dktinfo'][0]['DOCKET_STATUS'] == "Delivery Pdc Creation"
                    || $res['dktinfo'][0]['DOCKET_STATUS'] == "Docket Arrived"
                    ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Out From Origin"
                    ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Docket Creation") 
                    {
                        echo '<script>document.getElementById("shipment").classList.add("progtrckr-done");
                        document.getElementById("transit").classList.add("progtrckr-done");
                        document.getElementById("reached").classList.add("progtrckr-done");
                        document.getElementById("delivered").classList.add("progtrckr-done");</script>';
                    } 
                    else if ( $res['dktinfo'][0]['DOCKET_STATUS'] == "Undelivered") {
                        echo '<script>document.getElementById("shipment").classList.add("progtrckr-done");
                        document.getElementById("transit").classList.add("progtrckr-done");
                        document.getElementById("reached").classList.add("progtrckr-done");
                        document.getElementById("delivered").classList.add("progtrckr-todo");</script>';
                    } 
                    else 
                    {
                        echo '<script>document.getElementById("shipment").classList.add("progtrckr-done");
                        document.getElementById("transit").classList.add("progtrckr-todo");
                        document.getElementById("reached").classList.add("progtrckr-todo");
                        document.getElementById("delivered").classList.add("progtrckr-todo");</script>';
                    }

                ?>
            </div>

            <div style="padding-top: 40px;">
                <div style="float: left;">
                    <?php if ( $res['dktinfo'][0]['DOCKET_STATUS'] == "Booked") { ?>
                        <img src="{{ url('assets/images/booked.png') }}" style="height: 100px;">
                    <?php } ?>
                    
                    <?php if (
                         $res['dktinfo'][0]['DOCKET_STATUS'] == "In Transit"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Destination Arrived"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Undelivered"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Delivery Pdc Creation"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Docket Arrived"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Sent From Enroute Ou"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Acknowledgment At Enroute"
                        || $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Sent From Enroute Ou"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Tc Acknowledgment At Enroute"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Out From Origin"
                        ||  $res['dktinfo'][0]['DOCKET_STATUS'] == "Docket Creation"
                    ) { ?>
                        <img src="{{ url('assets/images/deliver.png') }}">
                        
                    <?php } ?>

                    <?php if ( $res['dktinfo'][0]['DOCKET_STATUS'] == "Delivered") { ?>
                        <img src="{{ url('assets/images/delivered.png') }}" style="height: 100px;">
                        
                    <?php } ?>

                </div>

                <div style="display: inline;">
                    <span style="padding-left: 20px"><b>
                            <?=  $res['dktinfo'][0]['DOCKET_STATUS'] ?>
                        </b><br /></span>
                        
                    <span style="padding-left: 20px">on <?= $res['dktinfo'][0]['TRANSIT_DTLS'][0]['INTRANSIT_DATE'] ?>
                            <?=$res['dktinfo'][0]['TRANSIT_DTLS'][0]['INTRANSIT_TIME'] ?><br /></span>

                             
                    <?php if ($res['dktinfo'][0]['DOCKET_STATUS']== "Delivered") { ?>
                        
                        <span style="padding-left: 20px">Receivers Name: <?= $res['dktinfo'][0]['RECEIVER_NAME'] ?><br /></span>
                        <span style="padding-left: 20px">Remarks: ok <br /></span>
                        <span style="padding-left: 20px">GA Remarks: ok </span>
                    <?php } ?>


                </div>
            </div>
            </br></br>
            <div style="padding-top: 30px;">
                <div style="float: left;">
                
                    <span>Booking Date : <?= $res['dktinfo'][0]['BOOKED_DATETIME']?><br /></span>
                   
                    <span>Assured Dly. Dt : <?=  $res['dktinfo'][0]['ASSURED_DELIVERY_DATE'] ?><br /></span>
                </div>

                <div style="display: inline;">

              
                    <span style="padding-left: 500px;">No. of Pkgs : <?=  $res['dktinfo'][0]['NO_OF_PKGS'] ?><br /></span>
                   
                    <span style="padding-left: 500px;">Weight : <?= $res['dktinfo'][0]['ACTUAL_WEIGHT'] ?> (Kgs)
                            <br /></span>
                </div>

                <div></div>
            </div>
            <div></div>
            <div></div>

            <?php } ?>
            <br />
   
            <div class=" mx-auto table-responsive">
            <table  class='table'>
                <tbody>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                  
                    <?php foreach (  $gati_info   as $res) { ?>
                    <?php


                        $count = count($res['dktinfo'][0]['TRANSIT_DTLS']);

                        for ($x = 0; $x < $count; $x++) { ?>
                    <tr>
                        <td>
                     
                            <?=   $res['dktinfo'][0]['TRANSIT_DTLS'][$x]['INTRANSIT_DATE'] ?>
                        </td>
                        <td>
                       
                            <?=  $res['dktinfo'][0]['TRANSIT_DTLS'][$x]['INTRANSIT_TIME'] ?>
                        </td>
                        <td>

                        
                            <?= $res['dktinfo'][0]['TRANSIT_DTLS'][$x]['INTRANSIT_LOCATION'] ?>
                        </td>
                        <td>
                       
                            <?= $res['dktinfo'][0]['TRANSIT_DTLS'][$x]['INTRANSIT_STATUS'] ?>
                        </td>
                    </tr>
                    <?php }
                    }
                    ?>
                </tbody>
            </table>
                </div> 
            <br /> 
            <br />

            <?php
        }
        else{
            ?>
                 
            <div class=" mx-auto table-responsive">

<table  class="table">
    <tbody>
        <tr  >
            <th>Docno</th>
            <th>Docketno</th>
            <th>Docdate</th>
            <th>Transport name</th>
            <th>Transwebsite</th>
        </tr>
        <?php

        if(count($nongati_info)==0){
            ?>

            <tr><td colspan='5'>No Data Found</td></tr>

        <?php

        } 
        ?>
        <?php foreach ($nongati_info as $user) { ?>
        <tr  >
            <td>
                <?= $user['docno']; ?>
            </td>
            <td>
                <?= $user['docketno']; ?>
            </td>
            <td>
                <?= $user['docdate']; ?>
            </td>
            <td>
                <?= $user['transportname']; ?>
            </td>
            <td>
                <?= $user['transwebsite']; ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

        </div> 

        <?php
        } ?>
   