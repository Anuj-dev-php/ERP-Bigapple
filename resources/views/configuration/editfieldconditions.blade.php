@extends('layout.layout')
@section('content')
    <div>
        <span id="showID"></span>
    </div>

  <div class="pagecontent"  >
    <h2  class="menu-title">Edit Field Conditions</h2>
    
    </div>

 
@endsection
@section('js')
    {{-- ROLE --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/snackbar.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script type="text/javascript"></script>
     
 
@endsection
