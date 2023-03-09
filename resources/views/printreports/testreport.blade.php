@extends('layout.layout')
@section('content')

<h2  class="menu-title   mb-5 font-size-18">Report Print </h2>

<div class="pagecontent"   id="divpagecontent" > 


<iframe id="framepdf"  style="width:80%;margin:auto;height:500px;"   src="https://reportapi.bigapple.in/reportapi/generatereport?id=1&reportfilename=invoice_pdf.rpt">

</iframe>

 

</div>
 

@endsection
@section('js')
<script type='text/javascript'>

    function printreport(){
        document.getElementById('framepdf').contentWindow.print();

    }

    </script>

 

@endsection