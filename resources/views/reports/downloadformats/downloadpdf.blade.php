<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
         
        </style>
    
</head>
<body>

    <div class="container-fluid">
    

    <h1>Convert HTML to PDF with image generate Snappy - Webappfix</h1>

    <div class="list-group">
    <a href="#" class="list-group-item ">
      <h4 class="list-group-item-heading">First List Group Item Heading</h4>
      <p class="list-group-item-text">List Group Item Text</p>
    </a>
    <a href="#" class="list-group-item">
      <h4 class="list-group-item-heading">Second List Group Item Heading</h4>
      <p class="list-group-item-text">List Group Item Text</p>
    </a>
    <a href="#" class="list-group-item">
      <h4 class="list-group-item-heading">Third List Group Item Heading</h4>
      <p class="list-group-item-text">List Group Item Text</p>
    </a>
  </div>


 <table class="table table-striped table-bordered" style='width:50%;'>
     <thead><th>First Name</th><th>Last Name</th></thead>
     <tbody>
     @foreach (     $names as     $data)
     <tr><td>{{$data['fname']}} </td><td>{{$data['lname']}} </td></tr>

     @endforeach
     </tbody>
</table> 
 
 <table  class="table table-striped  table-bordered"  style='width:70%;'>
     <thead><th>First Name</th><th>Last Name</th></thead>
     <tbody>
     @foreach (     $names as     $data)
     <tr><td>{{$data['fname']}} </td><td>{{$data['lname']}} </td></tr>

     @endforeach
     </tbody>
</table> 

        </div>

</body>
</html>