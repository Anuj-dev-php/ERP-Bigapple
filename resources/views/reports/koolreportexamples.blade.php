@extends('layout.layout')

@section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading"> KOOL REPORT</h4>




<div class="pagecontent"> 
	<div class="container-fluid">
    <?php $report->render(); ?>
    </div>

</div>
