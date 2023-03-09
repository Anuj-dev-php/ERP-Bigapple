@php @endphp @extends('layout.layout') @section('content')
    <div> <span id="showID"></span> </div>

    <h2 class="menu-title">General Ledger - Report</h2>
    <div class="pagecontent text-center" id="divpagecontent">
        <div class="row">
            <div class="col-12 mx-auto">

                <div class="clearfix">
                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="Print" id="btn_print"
                        onclick="window.print();" />

                    <input type="button" class="btn btn-primary btn-md  btn_float_left" style="float: left;" value="Back"
                        id="back-btn" onclick="history.back();" />


                    <!-- <input type="button" class="btn btn-primary btn-md  btn_float_right" value="doc" id="doc-btn"
                                                onclick="dwonloaddoc()" />

                                            <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xls" id="xls-btn"
                                                onclick="dwonloadxls()" />

                                            <input type="button" class="btn btn-primary btn-md  btn_float_right" value="image" id="img-btn"
                                                onclick="dwonloadimg()" /> -->
                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="image" id="img-btn"
                        onclick="dwonloadimg()" />

                    {{-- <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xlsx" id="xlsx-btn"
                    onclick="dwonloadxlsx()" /> --}}
                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xls" id="xls-btn"
                        onclick="dwonloadxls()" />
 
                </div>
            </div>
            <div class="row">
                <div class="col-12 mx-auto" style="margin-top:20px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="mx-auto table-responsive" id="table_row">

                                {{-- {{dd($arraydata);}} --}}

                                @foreach ($arraydata as $responseData)
                                    @if (isset($responseData['accountName']))
                                        <table class="" style="border: 1px solid" width="100%">
                                            <tbody>
                                                <tr style="border: 1px solid">
                                                    <td>
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td colspan="8" style="text-align: center; padding:20px;">
                                                                    <h4> Account - General Ledger</h4>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr style="border: 1px solid">
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td style="text-align: left; padding:20px;width:30%">A/C
                                                                    No.: </td>
                                                                <td><strong>{{ $responseData['account_id'] }}</strong></td>
                                                                <td style="text-align: left; padding:20px;width:10%">Start
                                                                    Date.:
                                                                </td>
                                                                <td><strong>{{ $responseData['startDate'] }}</strong></td>
                                                                <td style="text-align: left; padding:20px;width:10%">End
                                                                    Date: </td>
                                                                <td><strong>{{ $responseData['toDate'] }}</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr style="border: 1px solid">
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td style="text-align: left; padding:20px;width:20%">Account Name:
                                                                </td>
                                                                <td><strong>{{ $responseData['accountName'] }}</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="table" id="tableData">
                                            <thead style="border: 1px solid">
                                                <tr style="border: 1px solid">
                                                    <th style="width:8%;">VchDate </th>
                                                    <th style="width:10%;">Vch No </th>
                                                    <th style="width:20%;">Account Name</th>
                                                    <th style="width:20%;">Particulars</th>
                                                    <th style="width:10%;">Naration</th>
                                                    <th style="width:10%;">Debit</th>
                                                    <th style="width:10%;">Credit</th>
                                                    <th style="width:10%;">Balance</th>

                                                    @if (isset($ChequeNo))
                                                        <th style="width:10%;">Cheque No</th>
                                                    @endif
                                                    @if (isset($ChequeStatus))
                                                        <th style="width:10%;">Cheque Status</th>
                                                    @endif
                                                    @if (isset($ClearingDate))
                                                        <th style="width:10%;">Clearing Date</th>
                                                    @endif
                                                    @if (isset($CostCentre))
                                                        <th style="width:10%;">Cost Centre</th>
                                                    @endif
                                                    @if (isset($Department))
                                                        <th style="width:10%;">Department</th>
                                                    @endif
                                                    @if (isset($Executive))
                                                        <th style="width:10%;">Executive</th>
                                                    @endif
                                                    @if (isset($Project))
                                                        <th style="width:10%;">Project</th>
                                                    @endif
                                                    @if (isset($ForeignCurrency))
                                                        <th style="width:10%;">FC Debit</th>
                                                        <th style="width:10%;">FC Credit</th>
                                                        <th style="width:10%;">FC Balance</th>
                                                        <th style="width:10%;">FC Exchange Rate</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="border: 1px solid">
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td colspan="3"><strong> Op. Bal.:
                                                            {{ $responseData['Openingbalance'] }} </strong></td>
                                                    @if (isset($ChequeNo))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ChequeStatus))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ClearingDate))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($CostCentre))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Department))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Executive))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Project))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ForeignCurrency))
                                                        <td colspan="4"><strong>FC Op. Bal.:
                                                                {{ $responseData['OpeningFCbalance'] }} </strong></td>
                                                    @endif
                                                </tr>
                                                @foreach ($responseData as $keys => $data)
                                                    @if (is_numeric($keys))
                                                        <tr style="border: 1px solid">
                                                            <td style="width:8%;">{{ $data['data']->VchDate }}</td>
                                                            <td style="width:10%;">{{ $data['data']->VchNo }}</td>
                                                            <td style="width:20%;">{{ $data['data']->ACName }}</td>
                                                            <td style="width:20%;">{!! $data['data']->perticulars !!}</td>
                                                            <td style="width:10%;">{{ $data['data']->Narration }}</td>
                                                            <td style="width:10%;">{{ $data['debit'] }}</td>
                                                            <td style="width:10%;">{{ $data['credit'] }}</td>
                                                            <td style="width:10%;">{{ $data['balance'] }}</td>

                                                            @if (isset($ChequeNo))
                                                                <td style="width:10%;">{{ $data['data']->chq_no }}</td>
                                                            @endif
                                                            @if (isset($ChequeStatus))
                                                                <td style="width:10%;">{{ $data['data']->ch_status }}</td>
                                                            @endif
                                                            @if (isset($ClearingDate))
                                                                <td style="width:10%;">{{ $data['data']->cl_date }}</td>
                                                            @endif
                                                            @if (isset($CostCentre))
                                                                <td style="width:10%;">{{ $data['data']->Costcentre }}</td>
                                                            @endif
                                                            @if (isset($Department))
                                                                <td style="width:10%;">{{ $data['data']->DDeptName }}</td>
                                                            @endif
                                                            @if (isset($Executive))
                                                                <td style="width:10%;">{{ $data['data']->executive }}</td>
                                                            @endif
                                                            @if (isset($Project))
                                                                <td style="width:10%;">{{ $data['data']->ProjectName }}
                                                                </td>
                                                            @endif
                                                            @if (isset($ForeignCurrency))
                                                                <td style="width:10%;">{{ $data['fcdebit'] }}</td>
                                                                <td style="width:10%;">{{ $data['fccreadit'] }}</td>
                                                                <td style="width:10%;">{{ $data['FCbalance'] }}</td>
                                                                <td style="width:10%;">{{ $data['data']->fcexrate }}</td>
                                                            @endif
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr style="border: 1px solid">
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>{{ $responseData['totalDebit'] }}</td>
                                                    <td>{{ $responseData['totalCredit'] }}</td>
                                                    <td>&nbsp;</td>
                                                    @if (isset($ChequeNo))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ChequeStatus))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ClearingDate))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($CostCentre))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Department))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Executive))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Project))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ForeignCurrency))
                                                        <td><strong>{{ $responseData['totalFCCredit'] }} </strong></td>
                                                        <td>{{ $responseData['totalFCDebit'] }}</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    @endif
                                                </tr>
                                                <tr style="border: 1px solid">
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td colspan="3"><strong>Cl. Bal.:
                                                            {{ $responseData['Closingbalance'] }}</strong></td>
                                                    <td>&nbsp;</td>
                                                    <!-- <td>&nbsp;</td>
                                                                        <td>&nbsp;</td> -->
                                                    @if (isset($ChequeNo))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ChequeStatus))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ClearingDate))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($CostCentre))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Department))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Executive))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Project))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($ForeignCurrency))
                                                        <td colspan="3"><strong>FC Cl. Bal.:
                                                                {{ $responseData['ClosingFCbalance'] }}</strong></td>
                                                        <td>&nbsp;</td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                @endforeach
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
            // $("[id$=xls-btn]").click(function(e) {
            //     // alert(1111);

            //     e.preventDefault();
            //     window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('div[id$=table_row]').html()));
            // });

            var tabla = $('div[id$=table_row]').html();

            var myBlob = new Blob([tabla], {
                type: 'text/html'
            });
            var url = window.URL.createObjectURL(myBlob);
            var a = document.createElement("a");

            document.body.appendChild(a);
            a.href = url;
            a.download = "export.xls";
            a.click();

            setTimeout(function() {
                window.URL.revokeObjectURL(url);
            }, 0);
            // });
        }


        // xlsx table--------------------------------------------
        // function dwonloadxlsx(e) {

        //     $('#table_row').table2excel({
        //         exclude: ".nosExl",
        //         name: "Excel Document Name",
        //         filename: "myFileName.xlsx",
        //         fileext: ".xlsx",
        //         exclude_img: true,
        //         exclude_links: true,
        //         exclude_inputs: true,
        //     });
        // }
        // for XLS dwonload=============================== END


        // for image dwonload========================== START
        function dwonloadimg() {
            html2canvas(document.getElementById("divpagecontent"))
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

            html2canvas(document.getElementById("tableData"), {
                allowTaint: true,
                useCORS: true
            }).then(function(canvas) {
                var anchorTag = document.createElement("a");
                document.body.appendChild(anchorTag);
                document.getElementById("previewImg").appendChild(canvas);
                anchorTag.download = "filename.jpg";
                anchorTag.href = canvas.toDataURL();
                anchorTag.target = '_blank';
                setTimeout(() => {
                    anchorTag.click();
                }, 5000);

            });
        }
        // function  dwonloadtext(){

        // for image dwonload========================== END
        // $(document).ready(function() {
        //     $("#divpagecontent").printPage()
        // });
    </script>
@endsection
