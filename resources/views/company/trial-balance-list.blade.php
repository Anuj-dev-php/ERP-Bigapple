@php @endphp @extends('layout.layout') @section('content')
<div> <span id="showID"></span> </div>
<div> <span id="tree-container-2"></span> </div>
<style type="text/css">
    table.trial-balance-table>ul,
    #myUL {
        list-style-type: none;
    }

    #myUL {
        margin: 0;
        padding: 0;
    }

    .box {
        cursor: pointer;
        -webkit-user-select: none;
        /* Safari 3.1+ */
        -moz-user-select: none;
        /* Firefox 2+ */
        -ms-user-select: none;
        /* IE 10+ */
        user-select: none;
    }

    .box::before {
        content: "+";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    .check-box::before {
        content: "-";
    }

    .nested {
        display: none;
    }

    .trial-balance-table .active {
        display: block;
    }
</style>
</head>

<body>
    <!-- <h2>Tree View</h2>
    <p>A tree view represents a hierarchical view of information, where each item can have a number of subitems.</p>
    <p>Click on the +/- symbol to open or close the tree branches.</p>
    <ul id="myUL">
        <li><span class="box">Beverages</span>
            <ul class="nested">
                <li><span>Cold beverages</span></li>
                <li><span>Hot Beverages</span></li>
            </ul>
        </li>
        <li><span class="box">Tea</span>
            <ul class="nested">
                <li><span>Black Tea</span></li>
                <li><span>White Tea</span></li>
            </ul>
        </li>
        <li><span class="box">Green Tea</span>
            <ul class="nested">
                <li><span>Sencha</span></li>
                <li><span>Gyokuro</span></li>
            </ul>
        </li>
    </ul> -->
    

    @php

    $branch = array();
    
    function buildTree(array $elements, array $branch, $parentId=0) {
    // group elements by parents if it does not comes on the parameters
    if (empty($branch)) {
        $branch = array();

        foreach ($elements as $element) {
            $branch[(int)$element["Parent2"]][$element["account_id"]] = $element;
        }
    }


    

    // echo the childs referenced by the parentId parameter
    $totalOpeningBal = 0;

        if (isset($branch[$parentId])) {
            foreach ($branch[$parentId] as $keyBranch => $itemBranch) {
                
                $totalOpeningBal += (int)$itemBranch["Openingbalance"];
                
                echo '<li style="padding:10px">';
                echo '<p class="box" style="width:max-content;">'.$itemBranch['accountName'].' <span style="width:20%;position: absolute; left: 37%" class="opening_bal_class"><strong>'.$totalOpeningBal.'</strong></span> <span style="width:10%;position: absolute; left: 50%;" class="total_class"><strong>'.$itemBranch["totalDebit"].'</strong></span> <span style="width:20%;position: absolute; left: 65%;" class="credit_class"><strong>'.$itemBranch["totalCredit"].'</strong></span><span style="width:20%;position: absolute; left: 80%;" class="credit_class"><strong>'.$itemBranch["ClosingDebitbalance"].'</strong></span><span style="width:20%;position: absolute; left: 90%;" class="credit_class"><strong>'.$itemBranch["ClosingCreditbalance"].'</strong></span></p> ';
                
                    if(!empty($itemBranch)){
                    echo '<ul class="nested" style="padding-left: 0px; margin-left: 10%; padding: 5px;">';
                        buildTree($elements, $branch, $itemBranch["account_id"]); // iterate with the actual Id to check if this record have childs
                    echo '</ul>';
                    }
                echo '</li>';

                


            }
        }
    }

    @endphp

    <h2 class="menu-title">Trial Balance</h2>
    <div class="pagecontent text-center" id="divpagecontent">
        <div class="row">
            <!-- <div class="col-12 mx-auto">

                <div class="clearfix">
                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="Print" id="btn_print" onclick="window.print();" />

                    <input type="button" class="btn btn-primary btn-md  btn_float_left" style="float: left;" value="Back" id="back-btn" onclick="history.back();" />


                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="doc" id="doc-btn" onclick="dwonloaddoc()" />

                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xls" id="xls-btn" onclick="dwonloadxls()" />

                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="image" id="img-btn" onclick="dwonloadimg()" />

                    {{-- <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xlsx" id="xlsx-btn"
                    onclick="dwonloadxlsx()" /> --}}



                </div>
            </div> -->
            <div class="row">
                <div class="col-12 mx-auto" style="margin-top:20px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="mx-auto table-responsive">

                                

                                <table class="table table-striped trial-balance-table" style="position: relative;">
                                    <thead>
                                        <tr>
                                            <th style="width:8%;">Account Name </th>
                                            <th style="width:10%;">Op.Bal</th>
                                            <th style="width:10%;">Debit</th>
                                            <th style="width:10%;">Credit</th>
                                            <th style="width:10%;">Cls. Debit Balance</th>
                                            <th style="width:10%;">Cls. Credit Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="width:20%;">
                                                <ul id="myUL" style="text-align: left; padding: 10px;">
                                                    @php buildTree($arraydata, array()); @endphp
                                                </ul>
                                            </td>

                                           
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="previewImg" hidden>
            </div>
        </div>
    </div>
    @endsection @section('js')
    {{-- ROLE --}}
    <script type="text/javascript">
        var toggler = document.getElementsByClassName("box");
        for (var i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                if (this.parentElement.querySelector(".nested") != null) {
                    this.parentElement.querySelector(".nested").classList.toggle("active");
                    this.classList.toggle("check-box");
                }
            });
        }
    </script>
    <script type="text/javascript">
        // csv button
        // function dwonloadcsv() {
        //     $(".datatableCls").table2csv({
        //         appendTo: '.datatableCls',
        //         separator: ',',
        //         newline: '\n',
        //         quoteFields: true,
        //         excludeColumns: '',
        //         excludeRows: '',
        //         trimContent: true,
        //     });

        // }

        // function dwonloadcsv() {
        //     $("[id$=csv-btn]").click(function(e) {
        //         e.preventDefault();
        //         window.open('data:application/.csv,' + encodeURIComponent($('div[id$=table_row]').html()));
        //     });
        // }

        // for doc dwonload==========================START
        function dwonloaddoc() {

            $("#table_row").wordExport();
        }
        // for pdf dwonload========================== END


        // for XLS dwonload========================== START
        function dwonloadxls() {
            $("[id$=xls-btn]").click(function(e) {
                e.preventDefault();
                window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('div[id$=table_row]').html()));
            });
        }


        // xlsx table--------------------------------------------
        // function dwonloadxlsx(e) {

        //     $('#table_row').table2excel({
        //         //exclude: ".nosExl",
        //         name: "Excel Document Name",
        //         filename: "myFileName.xlsx",
        //         // fileext: ".xlsx",
        //         // exclude_img: true,
        //         // exclude_links: true,
        //         // exclude_inputs: true,
        //     });
        // }
        // for XLS dwonload=============================== END


        // for image dwonload========================== START
        function dwonloadimg() {
            html2canvas(document.getElementById("table_row"))
                .then(function(canvas) {
                    console.log(canvas);
                    var anchorTag = document.createElement("a");
                    document.body.appendChild(anchorTag);
                    document.getElementById("previewImg")
                        .appendChild(canvas);
                    anchorTag.download = "filename.jpg";
                    anchorTag.href = canvas.toDataURL();
                    anchorTag.target = '_blank';
                    anchorTag.click();
                });
        }
        // function  dwonloadtext(){

        // for image dwonload========================== END


        // $(document).ready(function() {
        //     $("#divpagecontent").printPage()
        // });
    </script>
    @endsection