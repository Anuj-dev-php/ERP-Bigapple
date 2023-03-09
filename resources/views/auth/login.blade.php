<!-- Navigation -->
<style>
    .carousel-item {
  height: 100vh;
  min-height: 350px;
  background: no-repeat center center scroll;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.cascading-right {
    margin-right: -50px;
   
}
.text-center {
    text-align: center!important;
}
.p-5 {
    padding: 3rem!important;
}
.shadow-5 {
    box-shadow: 0 2px 25px -5px rgba(0,0,0,.07),0 25px 21px -5px rgba(0,0,0,.04)!important;
}
#logoFix
{
height:50px;width:70px;
}
.card-body {
    flex: 1 1 auto;
    padding: 1.5rem;
}
*, :after, :before {
    box-sizing: border-box;
}

div {
    display: block;
}
.text-right
{
    color: black!important;;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="http://127.0.0.1:8000/assets/css/loginpage.css" type="text/css" media="all">
<!-- //Custom-Stylesheet-Links -->
<!--fonts -->
<!-- //fonts -->
<link rel="stylesheet" href="http://127.0.0.1:8000/assets/css/font-awesome.min.css" type="text/css" media="all">
<!-- //Font-Awesome-File-Links -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  .form-outline .form-control {
    min-height: auto;
    padding: 0.33em 0.75em;
    border: 0;
    background: transparent;
    transition: all .2s linear;
}

    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active{
  background-color: white !important;
  -webkit-box-shadow: 0 0 0 30px white inset !important;

 
}
input[type='text']
{
   
   
    font-size: 14px;font-weight: 400;
}
input[type='password']
{
   
   
    font-size: 14px;font-weight: 400;
}
     
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="{{ url('assets/css/loginpage.css') }}" type="text/css" media="all">
<!-- //Custom-Stylesheet-Links -->
<!--fonts -->
<!-- //fonts -->
<link rel="stylesheet" href="{{ url('assets/css/font-awesome.min.css') }}" type="text/css" media="all">
<!-- //Font-Awesome-File-Links -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <header>
  <style>
/* 
  @media screen and (max-width:890px)
      {
       #loginForm
       {
        width:500px; 
        margin-left:-20px!important;
       
       }
      }    */
     
    @media  screen and (max-width:1000px)
      {
        .carousel-item {
  height: 100vh;
  min-height: 350px;
  background: no-repeat center center scroll; 
   -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.cascading-right {
    margin-right: -50px;
    backdrop-filter: blur(30px); 
     background: hsla(0, 0%, 100%, 0.55);
}  
#logoFix
{
height:100px;width:120px;
}  
.cascading-right {
    margin-right: -50px;
    opacity:1;
}
.text-center {
    text-align: center!important;
}
.p-5 {
    padding: 3rem!important;
}

.card-body {
    flex: 1 1 auto;
    padding: 1.5rem;
    width:800px;
    
}
.shadow-5 {
    box-shadow: 0 2px 25px -5px rgba(0,0,0,.07),0 25px 21px -5px rgba(0,0,0,.04)!important; 
}
 :after, :before {
    box-sizing: border-box;
}

div {
    display: block;
}
.text-right
{
    color: black!important;;
}
  .form-outline .form-control {
    min-height: auto;
    padding: 0.33em 0.75em;
    border: 0;
  
}
input[type='text']
{
    height: 100px;
    width: 200px;
   
    font-size: 34px;font-weight: 400;
}
input[type='password']
{
    height: 100px;
    width: 200px;
  
    font-size: 34px;font-weight: 400;
}
input[type='submit']
{
    height: 140px!important;
    width: 200px;
    font-size: 30px!important;  
}
#upassword
{
    font-size: 30px;
    margin-top: 10px;
}
#uname
{
    font-size: 30px;
    margin-top: 10px;
}
#forget
{
    font-size: 30px!important;
}


       }
      
    </style>

    <body style="background-color:#F1F2F3">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel"  style="position:relative ;" id="imageFile">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner" >
      
      <div class="carousel-item active"
          style="background-image: url('assets/images/Trolley101.jpg');height:650px;margin-top:100px">
        
    </div>
        <!-- style="background-image: url('{{ url('assets/images/Trolley.jpg') }}');background-size:cover;background-position: center center;" -->
        <div class="carousel-item"
          style="background-image: url('assets/images/Floodlight101.jpg');height:650px;margin-top:100px">
        
    </div>
        <!-- <div class="carousel-item">
        <img src="{{url('assets/images/Trolley101.jpg')}}" class="img-fluid" style="height:100%;"/>
        </div> -->
        
        <div class="carousel-item"
          style="background-image: url('assets/images/Highbay101.jpg');height:650px;margin-top:100px">
          
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </header>
  <div class="container-fluid" id="logForm" style="opacity: 1;">
  <div class="card cascading-right" id="logForm" style="margin-left:120px;position: absolute;margin-top:-400px; backdrop-filter: blur(30px);
    background: hsla(0, 0%, 100%, 0.55);

 align-items: center;
justify-content: center;
  ">
 
  <div class="card-body p-5 shadow-5 text-center" id="logForm">
      
      
    @if (session('status'))
           
          <div class="alert alert-danger">{{ session('status') }}</div>
        @endif

        
        <x-jet-validation-errors class="mb-4" />

    <div class="text-center icon">
        <img src="{{url('assets/images/Logo-removebg-preview.png')}}"id="logoFix">
    </div>
    <div class="content-bottom"id="logForm" >
        <form  method="POST" action="{{ route('login') }}" >
        @csrf

            <div class="field-group form-control form-outline t1">
                <span class="fa fa-user" aria-hidden="true" style="color: black;" id="uname"></span>&nbsp
                <div class="wthree-field form-outline">
                    <input   name="user_id"  id="user_id"  id="text1"  type="text"    placeholder="Username" required style="    color: #545a6d !important;margin-top: 5px!important;    background: transparent;" autofocus >
                    
                </div>
            </div>
            <div class="field-group form-control wthree-field">
                <span class="fa fa-lock" aria-hidden="true " style="color: black;" id="upassword"></span>&nbsp
                <div class="wthree-field">
                    <input   id="password"    type="password" name="password" required   placeholder="Password" style="    color: #545a6d !important;margin-top: 5px!important;" >
                    
                </div>
            </div>
            <div class="wthree-field">
                <button type="submit" class="btn btn-primary btn-block mb-4" style="background-color: #fc636b
                ;" >Get Started</button>
            </div>
            <div class="wthree-field">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-right"style="    color: #545a6d !important;font-size: 14px;font-weight: 400;"id="forget" >Forgot Password</a>
                @endif
            </div>
            <ul class="list-login" >
               
                <li>
                   
                </li>
                <li class="clearfix"></li>
            </ul>
          
        </form>
    </div>
</div>
    </div>
  
  
</div>  
  
