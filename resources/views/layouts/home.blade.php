<!DOCTYPE html>
<head>
<title> {{ config('app.name', 'Medtiator') }} | Dispute resolution made easy | ODR | ODR India</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="msapplication-TileImage" content="https://www.presolv360.com/public/images/DisputeManagement.jpg" />

<meta name="description" content="Online Dispute Resolution platform to conduct arbitration, mediation, conciliation digitally." />

<meta name="keywords" content="ODR, ADR, Online Dispute Resolution, Alternative Dispute Resolution, Out of Court Settlement, Arbitration, Mediation, Conciliation, Online Arbitration, Online Negotiation, Money Recovery, Legaltech, ODR India" />

<meta property="og:keywords" content="ODR, ADR, Online Dispute Resolution, Alternative Dispute Resolution, Out of Court Settlement, Arbitration, Mediation, Conciliation, Online Arbitration, Online Negotiation, Money Recovery, Legaltech, ODR India" />
<meta property="og:description" content="Online Dispute Resolution platform to conduct arbitration, mediation, conciliation digitally." />
<meta property="og:image" content="https://www.presolv360.com/public/images/DisputeManagement.jpg" />
<meta property="og:image:secure_url" content="https://www.presolv360.com/public/images/DisputeManagement.jpg" />
<meta property="og:url" content="https://www.presolv360.com">
<meta property="og:title" content=" {{ config('app.name', 'Medtiator') }} | Dispute resolution made easy" />
<meta property="og:type" content="article" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- Standard Favicon -->
<link rel="icon" type="image/x-icon" href="https://www.presolv360.com/presolv360/images/icon.png" />

<link rel="stylesheet" type="text/css" href="https://presolv360.com/asset/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="{{url('/assert/')}}/css/home.css">
  <link rel="stylesheet" type="text/css" href="https://presolv360.com/asset/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="https://www.presolv360.com/presolv360/js/sweetalert2.css">
  <link rel="stylesheet" href="https://www.presolv360.com/presolv360/css/style_slide.css">




  <style type="text/css">

    .modal-content {
    position: relative;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #999;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 6px;
    outline: 0;
    -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
    box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
}

.modal-header {
    min-height: 16.43px;
    padding: 15px;
    border-bottom: 1px solid #e5e5e5;
}

.form-horizontal .form-group {
    margin-right: -15px;
    margin-left: -15px;
    font-size: 16px;
}

@media (min-width: 768px) {
  .modal-dialog {
    width: 600px;
    margin: 30px auto;
    }

  }
  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">


  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://www.presolv360.com/presolv360/js/sweetalert2.css">

<!--[if lt IE 9]>
        <script src="https://www.presolv360.com/presolv360/js/html5/respond.min.js"></script>
<![endif]-->
<!-- <link href="https://www.presolv360.com/presolv360/presolvuser/plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"> -->


<!-- Global site tag (gtag.js) - Google Analytics -->
<script src="https://presolv360.com/presolv360/js/jquery.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118321131-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118321131-1');
</script>

<script async src="https://www.googletagmanager.com/gtag/js?id=AW-757076966"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

  gtag('config', 'AW-757076966');



  $( document ).ready(function() {
    $('#set_cookies').hide();
    if(getCookie("presolv_cookie")){
    var user=getCookie("presolv_cookie");
  }

    if (user != "") {
        //alert("Welcome again " + user);
        $('#set_cookies').hide();
    } else {
 $('#set_cookies').show();
    }
});
</script>
	<style>
 ul li :hover {
    background: #5D9CEC;
}

@media only screen and (max-width: 768px) {
ul li :hover {
margin-left: -5px;
}
}
</style>
<style>
@media (min-width: 768px) and (max-width: 1024px){
	.widget.widget-about{
	    padding-right: 10px;
	}}
</style>
</head>
  <!--links for carousel end-->
<body data-offset="" data-spy="" data-target=".ow-navigation" style="width: 100%;">
<!-- Main Container -->

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top bxshadow ">

	<div class="container">
		  <a class="navbar-brand" href="{{url('/')}}">

		  	<img src="{{url('/assert/')}}/img/logo_mediacje.png" class="img-fluid home-logo">
		  </a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		    <ul class="navbar-nav ml-auto">
		      <li class="nav-item active">
		        <a class="nav-link abtbtn" href="about_us">O mediacji<span class="sr-only">(current)</span></a>
		      </li>

          <li>

          <?php if(Auth::user() and Auth::user()->role==0){ ?>
              <a class="nav-link btn btn-circle-y loginbtn loginbtn2" href="user/dashboard">Dashboard</a>

          <?php } else if (Auth::user() and Auth::user()->role==1){ ?>

              <a class="nav-link btn btn-circle-y loginbtn loginbtn2" href="mediator/dashboard">Dashboard</a>


            <?php } else if (Auth::user() and Auth::user()->role==2){ ?>

              <a class="nav-link btn btn-circle-y loginbtn loginbtn2" href="admin/dashboard">Dashboard</a>


          <?php } else { ?>
		      			        <a class="nav-link btn btn-circle-y loginbtn loginbtn2" href="login">Zaloguj się</a>
                      <?php } ?>

            </li>
            <?php if(Auth::user()){ ?>
            <li>
              <a href="logout" class="nav-link btn  loginbtn btn-danger" style="border-radius: 18px;">Logout</a>
            </li>
          <?php } ?>

          </ul>
		  </div>
	</div>
</nav>
<div class="loader">
</div><!-- Header -->





@yield('content')

 <script>

var cat = document.forms['vform']['cat'];
var cat_error = document.getElementById('cat_error');
cat.addEventListener('blur', catVerify, true);


var npd = document.forms['vform']['npd'];
var npd_error = document.getElementById('npd_error');
npd.addEventListener('blur', npdVerify, true);


var damount = document.forms['vform']['damount'];
var damount_error = document.getElementById('damount_error');
damount.addEventListener('blur', damountVerify, true);



function validateFormx() {

   if (cat.value == "") {
    cat.style.border = "1px solid red";
    document.getElementById('cat_div').style.color = "red";
    cat_error.textContent = "Dispute category is required";
    cat.focus();
    return false;
  }

   if (npd.value == "") {
    npd.style.border = "1px solid red";
    document.getElementById('npd_div').style.color = "red";
    npd_error.textContent = "Number of parties involved in the dispute is required";
    npd.focus();
    return false;
  }
   if (damount.value == "") {
    damount.style.border = "1px solid red";
    document.getElementById('damount_div').style.color = "red";
    damount_error.textContent = "Disputed amount is required";
    damount.focus();
    return false;
  }

}

function catVerify() {
  if (cat.value != "") {
   cat.style.border = "1px solid #5e6e66";
   document.getElementById('cat_div').style.color = "#5e6e66";
   cat_error.innerHTML = "";
   return true;
  }
}

function npdVerify() {
  if (npd.value != "") {
   npd.style.border = "1px solid #5e6e66";
   document.getElementById('npd_div').style.color = "#5e6e66";
   npd_error.innerHTML = "";
   return true;
  }
}
function damountVerify() {
  if (damount.value != "") {
   damount.style.border = "1px solid #5e6e66";
   document.getElementById('damount_div').style.color = "#5e6e66";
   damount_error.innerHTML = "";
   return true;
  }
}

function isNumber(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57)){
            return false;
        }else{
        return true;
        }
    }


function earbsubmit(){

    $('.resarbform').attr('action','savedirectarbitration');

}


function resolvsubmit(){

    $('.resarbform').attr('action','mediation');

}


    </script>
  <link rel="stylesheet" href="https://presolv360.com/asset/css/plyr.css" />

  <div class="contact-section">
    <div class="container">

  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="section-title text-center pb-25">
<h2 class="title">Get In Touch</h2><p></p>
<p>Nunc id dui at sapien faucibus fermentum ut vel diam. Nullam tempus, nunc id efficitur sagittis, urna est ultricies eros, ac porta sem turpis quis leo.</p>
</div>
    </div>
<div class="col-lg-4 col-md-6 col-sm-7">
<div class="card text-center mt-30">
  <div class="card-body">
      <div class="contact-icon">
      <i class="fa fa-hand-pointer"></i>
      </div>
      <div class="contact-content">
      <h4>Locate us</h4>
      <p>Mumbai, India</p>
      </div>
    </div>
</div>
</div>
<div class="col-lg-4 col-md-6 col-sm-7">
<div class="card text-center mt-30">
  <div class="card-body">

<div class="contact-icon">
<i class="fa fa-hand-pointer"></i>
</div>
<div class="contact-content">
<h4>Zadzwoń do nas</h4>
<p><a href="tel:022-20821102" target="_top">Tel 721 782 222</a></p>
</div>
</div>
</div>
</div>
<div class="col-lg-4 col-md-6 col-sm-7">
<div class="card text-center mt-30">
  <div class="card-body">

    <div class="contact-icon">
    <i class="fa fa-hand-pointer"></i>
    </div>
    <div class="contact-content">
    <h4> Napisz do nas</h4>
    <p><a href="mailto:info@presolv360.com" target="_top">info@pdmo24.pl</a></p>
    </div>
  </div>
</div>
</div>
</div>
</div>

  </div>


<div class="container-fluid footersection">

  <div class="container">
      <div class="row pb-5">
      <div class="col-md-7">
         <h5 class="mb-3">PORTAL DLA MEDIACJI ONLINE</h5>
         <p>jest nowatorskim rozwiązaniem
technologicznym, które jest dedykowane dla
stron konfliktu i profesjonalnych mediatorów,
żeby ułatwić ugodowe rozwiązywanie sporów
online w łatwy, poufny i bezpieczny sposób z
dostępem przez całą dobę.
Portal Dla Mediacji Online powstał dzięki
współpracy z firmą Edgecraft Solutions Private
Limited z Indii prowadzonej przez Urszulę
Ciołeszyńską - Prezes fundacji Polska Sieć
Ambasadorów Przedsiębiorczości Kobiet oraz
Henryka Stężałę - Prezesa Zarządu TENSOFT w
konsultacji z ekspertem mediacji –
doświadczoną Adwokat i Mediator Anetą
Gibek-Wiśniewską.</p>
      </div>
      <div class="col-md-2">
         <h5 class="mb-3">Ważne linki</h5>
         <a href="https://presolv360.com/faqs" target="_blank"><p>Pytania i odpowiedzi</p></a>
         <a href="https://presolv360.com/terms_conditions" target="_blank"><p>Regulamin i warunki</p></a>
         <a href="https://presolv360.com/privacy_policy" target="_blank"><p>Polityka prywatności</p></a>
         <a href="https://presolv360.com/odr_standard" target="_blank"><p>Nasze standardy</p></a>

      </div>
      <div class="col-md-3">
         <h5 class="mb-3">Bądźmy w kontakcie</h5>
         <div class="social">
          <a href="https://www.linkedin.com/company/presolv360/" target="_blank"><i class="fab fa-linkedin"></i></a>
                    <a href="https://twitter.com/presolv360" target="_blank"><i class="fab fa-twitter" ></i></a>
         </div>

      </div>
    </div>

  </div>


</div>

<div class="container-fluid footer">

  <div class="container">

  <div class="row">
      <div class="col-12">
                  <div class="pb-3 pt-3">
                    <p class="mb-0">
                      Presolv360 © 2017-2020 Edgecraft Solutions Private Limited. All rights reserved. Powered By <a href="http://bombayblokes.com/" target="_blank">Bombay Blokes Digital Solutions</a>
                    </p>
                  </div>
      </div>
    </div>
  </div>

</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="
    padding: 20px;
    color: white;
    background: #ffa600;font-weight: 500;
">
        <p>Everything else can come to a standstill, justice shouldn’t.</p>
<p>The entire team of Presolv360 is working 24*7 to ensure that your disputes are resolved quickly, efficiently and economically, even amidst the Coronavirus outbreak. From the comfort of your homes.</p>
<p>Welcome to the new way of resolving disputes, the ‘Presolv’ way.</p>
<p>Presolv360 thanks doctors, scientists, paramedics, pharmacies, policemen, fire fighters, farmers, logistics personnel and all those coming together to fight the virus called ‘COVID-19’ and all our professionals for bringing dispute resolution to the fingertips!</p>
<button type="button"  data-dismiss="modal" aria-label="Close" style="
    max-width: 246px;
    margin: 0 auto;
    background: #0e5587;
" class="btn btn-primary">
          <span aria-hidden="true">I pledge to fight COVID-19</span>
        </button>
    </div>
  </div>
</div>

<div id="set_cookies" style="">
<style type="text/css">
.cookbtn{
  margin-top: -10px;
}
div#hs-eu-cookie-confirmation{background:#fff;height:auto;left:0;position:absolute;width:100%;padding: 10px 0px;
/* z-index:100000000 !important; */
border-bottom:1px solid #cbd6e2;border-top:1px solid #cbd6e2;box-shadow:0 1px 5px #eaf0f6;color:#33475b;font-family:inherit;font-size:inherit;font-weight:normal !important;line-height:inherit;text-align:left;text-shadow:none !important;font-size:12px;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;line-height:18px}div#hs-eu-cookie-confirmation *{box-sizing:border-box !important}font-weight: 500;

div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner{background:#fff;max-width:1000px;padding:15px}

div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a{background:none !important;border:none !important;box-shadow:none !important;color:#0091ae;font-family:inherit;font-size:inherit;font-weight:normal !important;line-height:inherit;text-align:left;text-shadow:none !important;text-decoration:none !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a:hover{background:none !important;border:none !important;box-shadow:none !important;color:#0091ae;font-family:inherit;font-size:inherit;font-weight:normal !important;line-height:inherit;text-align:left;text-shadow:none !important;text-decoration:underline !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner div#hs-en-cookie-confirmation-buttons-area{margin:10px 0 0 !important;text-align:right !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a#hs-eu-confirmation-button,div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a#hs-eu-decline-button{border-radius:3px;display:inline-block;padding:10px 16px !important;text-decoration:none !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a#hs-eu-confirmation-button{background-color:#425b76 !important;border:1px solid #425b76 !important;margin-right:12px !important;color:#fff;font-family:inherit;font-size:inherit;font-weight:normal !important;line-height:inherit;text-align:left;text-shadow:none !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a#hs-eu-decline-button{border:1px solid #425b76 !important;color:#425b76;font-family:inherit;font-size:inherit;font-weight:normal !important;line-height:inherit;text-align:left;text-shadow:none !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner p{margin:0 0 12px;color:#33475b;font-family:inherit;font-size:inherit;font-weight:normal !important;line-height:inherit;text-align:left;text-shadow:none !important}@media print{div#hs-eu-cookie-confirmation{display:none !important}}@media screen and (max-width: 480px){div#hs-eu-cookie-confirmation{font-size:12px !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner{padding:8px 14px 14px !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a{font-size:12px !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner a#hs-eu-confirmation-button{font-size:12px !important}div#hs-eu-cookie-confirmation div#hs-eu-cookie-confirmation-inner p{font-size:12px !important;margin-bottom:12px !important;line-height:15px !important}}@media only screen and (min-width: 960px){div#hs-eu-cookie-confirmation{position:fixed}div#hs-eu-cookie-confirmation.hs-cookie-notification-position-bottom{bottom:0;top:auto;box-shadow:0 -1px 3px #eaf0f6}div#hs-eu-cookie-confirmation.hs-cookie-notification-position-bottom-left{width:500px;bottom:0;top:auto;left:0;right:auto;box-shadow:0 -1px 3px #eaf0f6}}
@media (min-width: 320px) and (max-width: 560px) {

  div#hs-eu-cookie-confirmation {
    background: #fff;

    left: 0;
    position: fixed;
    /*bottom:0%;*/
    width: 100%;
    z-index: 9999999999;
    border-bottom: 1px solid #cbd6e2;
    border-top: 1px solid #cbd6e2;
    box-shadow: 0 1px 5px #eaf0f6;
    color: #33475b;
    font-family: inherit;
    font-size: inherit;
    font-weight: normal !important;
    line-height: inherit;
    text-align: left;
    text-shadow: none !important;
    font-size: 12px;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    line-height: 18px;

    /* margin-bottom: 99px; */
}
  .cls{
    z-index: 99999999999;
  }

  .cookbtn{
    margin-top: 0;
    text-align: center;
    padding-bottom: 20px;
  }
}
.owl-theme .owl-dots {
  display: none;
}
</style>
<div class="container">
  <div id="hs-eu-cookie-confirmation" style="z-index: 999; margin: 0px;" class="hs-cookie-notification-position-bottom can-use-gradients"><div id="hs-eu-cookie-confirmation-inner" class="container">
    <div class="row">
  <div class="col-md-10" style="margin-bottom: 10px;">
This
 website stores cookies on your computer. By using our website you
 consent to our use of cookies as described in our <a href="privacy_policy" id="cool">Privacy Policy</a>.These
cookies  are used to improve our website and provide more personalised
and relevant services to you, both on this website and through other
media.</div>
<div class="col-md-2">
  <div class="cookbtn"  style='margin-top: 0px;'>
<a href="#" class="cls btn btn-primary" style="margin-left: 25px;" id="hs-eu-confirmation-button">Accept</a></div>

</div>
</div>
</div></div>

</div>


<!-- <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script> -->
 <script>
 var DOMAIN = "{{url('/')}}";
  </script>
  <script src="https://presolv360.com/presolv360/js/jquery.min.js"></script>
<script type="text/javascript" src="https://presolv360.com/asset/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.js"></script>

<script src="https://presolv360.com/asset/js/brand-slider.js"></script>

<script src="https://presolv360.com/presolv360/js/main.js"></script>


<script src="https://presolv360.com/public/js/site.js"></script>

<script src="{{url('/assert/')}}/js/slick.js"></script>


   <!-- <script src="{{url('/assert/')}}js/slick.js"></script>  -->

        <script src="https://presolv360.com/presolv360/js/sweetalert2.js"></script>
         <script src="//code.tidio.co/whiaumb73nws3xz5su24odtrgpni9etf.js" async></script>
        @yield('extra-js')


        <script type="text/javascript">

            $(window).load(function() {


              $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});






    $(".loader").fadeOut("slow");

    var hash = window.location.hash;

    console.log(hash);

     if(hash=='#profile'){

      $('.nav-1 .nav-link').addClass('active');
      $('.nav-2 .nav-link').removeClass('active');
      $('#resolve').removeClass('in active show');
      $('#secure').addClass('in active show');

     } else if(hash=='#resolve'){



          $('.nav-2 .nav-link').addClass('active');
          $('.nav-1 .nav-link').removeClass('active');
           $('#resolve').addClass('in active show');
           $('#secure').removeClass('in active show');

     }
})
        </script>





<script type="text/javascript">

  var banrvid=document.getElementById('bannervdo');

if(banrvid){

  document.getElementById('bannervdo').play();
}


    $('.securevideomodal').click(function(){

        //$('#video1').play();

        document.getElementById('video1').play();

  });


    $('#mobile').on('keypress', function (event) {

    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});






 $('#myModal').on('hidden.bs.modal', function () {
   document.getElementById('video1').pause();
});

  $('.resolvideomodal').click(function(){

       //$('#video2').play();
       document.getElementById('video2').play();
  });

  $('#myModal2').on('hidden.bs.modal', function () {
   document.getElementById('video2').pause();
});

$( document ).ready(function() {
  //$('#set_cookies').show();
        $('.cntctus').hide();
});


$('#npd').change(function(){
        if($('#npd').val() != '2'){
            $('#arb_submit1').prop('disabled', true);
            $('#arb_submit1').addClass('d-none');

        } else{
            $('#arb_submit1').prop('disabled', false);
            $('#arb_submit1').removeClass('d-none');
        }

    });


 $('#npd,#Damount').change(function(){
        if($('#npd').val() == 'more_than_3' || $('#Damount').val() == "Above Rs. 5 crore"){

               $('.cntctus').show();
               $('.sbmtt').hide();

               $('#arb_submit1').hide();
               document.getElementById("cnn").onclick = function () {
        location.href = DOMAIN+"contact_us";
    };
        }else{
             $('.cntctus').hide();
             $('.sbmtt').show();
               $('#arb_submit1').show();

        }
  });

 $('.vowl').owlCarousel({
        items:3,
        merge:true,
        loop:true,
        margin:10,
        video:true,
        lazyLoad:true,
        center:true,
        nav    : true,
        navText:['<i class="fas fa-angle-left"></i>','<i class="fas fa-angle-right"></i>'],
        responsive:{
          320:{
                items:1
            },
            480:{
                items:1
            },
            600:{
                items:1
            },
            900:{
                items:3
            }
        }
    });




  $('#apromocode').click(function(){

      var pcode=$('#promocode').val();

      if(pcode==''){
        $('#promomsg').text('Enter promocode').addClass('red').removeClass('green');
      }

      $.ajax({

        'url':DOMAIN+'applypromocode',
        'data':{pcode:pcode},
        'type':'POST',
        'success':function(res){

          var res=JSON.parse(res);

          console.log(res);

           if(res.response=='true'){

            $('.pcost').html(res.cost);
            $('#apromocode').hide();
            $('#rpromocode').show();
            $('#promomsg').text('Promocode applied').addClass('green').removeClass('red');
            $('#promocode').hide();
            $('#pcodehead').html(res.code).show();
           }

           if(res.response=="invalid"){
              $('#promomsg').text('Invalid promocode').addClass('red').removeClass('green');
           }

           if(res.response=="Used"){
              $('#promomsg').text('Promocode expired').addClass('red').removeClass('green');
           }
        },
        'error':function(res){
          console.log(res);
        }

      })


    });


  $('#qty').keyup(function(){

    var qty=$(this).val();

      if(qty=='0'){
       $(this).val('1');

       }

       if(qty==''){
        qty=1;
       }



      $.ajax({

        'url':DOMAIN+'/secure/plan/qty',
        'data':{qty:qty},
        'type':'POST',
        'success':function(res){

          var res=JSON.parse(res);

          console.log(res);

           if(res.response=='true'){

            $('.pcost').html(res.cost);
           }

        },
        'error':function(res){
          console.log(res);
        }

      })



  });

  $('#rpromocode').click(function(){

      var rcode='1';

      $.ajax({

        'url':DOMAIN+'removepromocode',
        'data':{rcode:rcode},
        'type':'POST',
        'success':function(res){

          console.log(res);

          var res=JSON.parse(res);

           if(res.response=='true'){

            $('.pcost').html(res.cost);
            $('#rpromocode').hide();
            $('#promomsg').text('');
            $('#pcodehead').hide();
            $('#promocode').show();
            $('#apromocode').show();
           }
        },
        'error':function(res){
          console.log(res);
        }

      })


    });


</script>
      <script>

function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

$( document ).ready(function() {

if( $(window).width() > 767 ){

  $('#hs-eu-cookie-confirmation').css('margin','0 0px');
//$('#hs-eu-cookie-confirmation-inner').css('margin','0 30px');
}else{
  $('#hs-eu-cookie-confirmation').css('margin','0 auto');
$('#hs-eu-cookie-confirmation-inner').css('margin','0 auto');
}



    var user=getCookie("presolv_cookie");
    if (user != "") {
       // alert("Welcome again " + user);
        $('#set_cookies').hide();
    } else {
    //alert(2);
     $('#set_cookies').show();


    }
});
 //$('#set_cookies').show();

          $('.cls').click(function(){


             setCookie("presolv_cookie", 'dataip', 1000);
             $('#set_cookies').hide();
          });

           $('#btn_sub1').click(function (event) {
             swal(
                            'Please Login/signup!',
                            'Login if you have a account or else create a new account.',
                            'info'
                        ).then(function(result) {
                            window.location.replace('login');
                        }).catch(swal.noop);

   });



     $('#sub').click(function (event) {

            var plan=$('#planname').text();
            var price=$('#price').text();

            $.ajax({
                method:'POST',

                 url: DOMAIN+'functions/presolv360/middleware.php',
                dataType:'JSON',
                data:{plan:plan,price:price},
                success: function (data) {
                    if(data.resp==true) {
                        window.location.replace('presolvcheckout');
                    }
                    else{
                        window.location.replace('presolvreview.php');

                    }

                }
            });
        });
    </script>

<!-- <script src="//code.tidio.co/us2r7rp6mpmnzat0gxobxzr6ilx4fjqn.js"></script>  -->


  <script>
$(document).ready(function(){

  $('[data-toggle="tooltip"]').tooltip();




});
</script>
<script>

        function ajax_confirm_sms(){

if(document.getElementById('sms_otp').value.length==5)
{
        var ajaxRequest;

        try {
            ajaxRequest = new XMLHttpRequest();
        }catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            }catch (e) {
                try{
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                }catch (e){
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
        ajaxRequest.onreadystatechange = function(){
            if(ajaxRequest.readyState == 4){

                if(ajaxRequest.responseText=='true')
                { //alert(ajaxRequest.responseText);
                   // document.getElementById('sms_submit').style.display='none'; /*commented by kewal*/
                    document.getElementById('update_phone').style.display='none';
                    document.getElementById('resend_phone').style.display='none';
                    document.getElementById('correct1').style.display='block';
                    document.getElementById('cancel1').style.display='none';
                    document.getElementById('sms_otp').style.display='none';

                }
               else if(ajaxRequest.responseText=='both')
                { //alert(ajaxRequest.responseText);

                      //alert('You have been successfully registered and logged in');
                    //swal("Success!", "You have been successfully registered and logged in.", "success");
                    //window.location.href = 'https://presolv360.com/';
                    gtag('event', 'conversion', {'send_to': 'AW-757076966/fXdMCMmE8pYBEOangOkC'});
                     swal(
                            'Success!',
                            'You have been successfully registered and logged in.',
                            'success'
                        ).then(function() {
                            window.location.href = "https://presolv360.com/user/presolv-secure ";
                         }).catch(swal.noop);



                  document.getElementById('sms_submit').style.display='none';
                    document.getElementById('update_phone').style.display='none';
                    document.getElementById('resend_phone').style.display='none';
                    document.getElementById('correct1').style.display='block';
                    document.getElementById('cancel1').style.display='none';
                    document.getElementById('sms_otp').style.display='none';
                      document.getElementById('buttons').style.display='block';
                }
                else if(ajaxRequest.responseText=='checkout')
                { //alert(ajaxRequest.responseText);
gtag('event', 'conversion', {'send_to': 'AW-757076966/fXdMCMmE8pYBEOangOkC'});
          setTimeout(function() {
                  swal({
                      title: "Success!",
                      text: "You have been successfully registered and logged in!",
                      type: "success"
                  }, function() {
                      window.location = "../checkout.php";
                  });
              }, 200);

                      document.getElementById('sms_submit').style.display='none';
                    document.getElementById('update_phone').style.display='none';
                    document.getElementById('resend_phone').style.display='none';
                    document.getElementById('correct1').style.display='block';
                    document.getElementById('cancel1').style.display='none';

                    document.getElementById('sms_otp').style.display='none';
                      document.getElementById('buttons2').style.display='block';

                }
                else
                {
                    document.getElementById('cancel1').style.display='block';
                }
            }
        }

        var sms_otp= document.getElementById('sms_otp').value;
        var queryString="?sms="+sms_otp;
        ajaxRequest.open("GET", "ajax_confirm_sms" + queryString, true);
        ajaxRequest.send(null);
}
else
{
                      document.getElementById('cancel1').style.display='block';

}
    }

</script>
<script>
    $('#load2').on('click', function() {

    var str=document.getElementById('username_reset').value;
        if(str!=='') {
            var $this = $(this);
            $this.button('loading');

            $.ajax({

              url:DOMAIN+'/forgotpassword',
              type: "POST",
              data:{'username':str},
              success:function(d){

                console.log(d);

                d=JSON.parse(d);


                console.log(d);

                 if(d.response=='success'){
                swal("Success!", "Your Password Has Been Updated. Please Check Your Registered Email Id!", "success");
              }else{

                swal("Failed!", "Wrong username entered!", "warning");
              }
              },
              error:function(e){

                  console.log(e);
                  swal("Failed!", "Wrong username entered!", "warning");
              }


            });

        }
        else
        {
            $this.button('reset');
            $('#myModal').modal('hide');

            swal("Failed!", "Please enter an Username!", "warning");


        }



    });

</script>
<script>
    $('#load3').on('click', function() {
        var str=document.getElementById('forgot_username_reset').value;

    if(str!=='') {
            var $this = $(this);
            $this.button('loading');

            $.ajax({

              url:DOMAIN+'/forgotusername',
              type: "POST",
              data:{'email':str},
              success:function(d){
                if(d.response=='success'){
                  swal("Success!", "Your Username Has Been Sent To Your Registered Email Id. Please Check Your Registered Email Id!", "success");
                } else{
                  swal("Failed!", "Wrong email entered!", "warning");
                }

              },
              error:function(e){

                  console.log(e);
                  swal("Failed!", "Wrong email entered!", "warning");
              }


            });

        }
        else
        {
            $this.button('reset');
            $('#myModal2').modal('hide');

            swal("Failed!", "Please enter an Email Id!", "warning");


        }

    });

    function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('fa-plus fa-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);



</script>

<script src="https://presolv360.com/asset/js/plyr.js"></script>
<script>
    const player = new Plyr('#video2');
    const player2 = new Plyr('#video1');

</script>
 </body>
</html>
<script>

        function ajax_confirm_sms(){

if(document.getElementById('sms_otp').value.length==5)
{
        var ajaxRequest;

        try {
            ajaxRequest = new XMLHttpRequest();
        }catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            }catch (e) {
                try{
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                }catch (e){
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
        ajaxRequest.onreadystatechange = function(){
            if(ajaxRequest.readyState == 4){

                if(ajaxRequest.responseText=='true')
                { //alert(ajaxRequest.responseText);
                   // document.getElementById('sms_submit').style.display='none'; /*commented by kewal*/
                    document.getElementById('update_phone').style.display='none';
                    document.getElementById('resend_phone').style.display='none';
                    document.getElementById('correct1').style.display='block';
                    document.getElementById('cancel1').style.display='none';
                    document.getElementById('sms_otp').style.display='none';

                }
               else if(ajaxRequest.responseText=='both')
                { //alert(ajaxRequest.responseText);

                      //alert('You have been successfully registered and logged in');
                    //swal("Success!", "You have been successfully registered and logged in.", "success");
                    //window.location.href = 'https://presolv360.com/';
                    gtag('event', 'conversion', {'send_to': 'AW-757076966/fXdMCMmE8pYBEOangOkC'});
                     swal(
                            'Success!',
                            'You have been successfully registered and logged in.',
                            'success'
                        ).then(function() {
                            window.location.href = "https://presolv360.com/user/presolv-secure ";
                         }).catch(swal.noop);



                  document.getElementById('sms_submit').style.display='none';
                    document.getElementById('update_phone').style.display='none';
                    document.getElementById('resend_phone').style.display='none';
                    document.getElementById('correct1').style.display='block';
                    document.getElementById('cancel1').style.display='none';
                    document.getElementById('sms_otp').style.display='none';
                      document.getElementById('buttons').style.display='block';
                }
                else if(ajaxRequest.responseText=='checkout')
                { //alert(ajaxRequest.responseText);
gtag('event', 'conversion', {'send_to': 'AW-757076966/fXdMCMmE8pYBEOangOkC'});
          setTimeout(function() {
                  swal({
                      title: "Success!",
                      text: "You have been successfully registered and logged in!",
                      type: "success"
                  }, function() {
                      window.location = "../checkout.php";
                  });
              }, 200);

                      document.getElementById('sms_submit').style.display='none';
                    document.getElementById('update_phone').style.display='none';
                    document.getElementById('resend_phone').style.display='none';
                    document.getElementById('correct1').style.display='block';
                    document.getElementById('cancel1').style.display='none';

                    document.getElementById('sms_otp').style.display='none';
                      document.getElementById('buttons2').style.display='block';

                }
                else
                {
                    document.getElementById('cancel1').style.display='block';
                }
            }
        }

        var sms_otp= document.getElementById('sms_otp').value;
        var queryString="?sms="+sms_otp;
        ajaxRequest.open("GET", "ajax_confirm_sms" + queryString, true);
        ajaxRequest.send(null);
}
else
{
                      document.getElementById('cancel1').style.display='block';

}
    }

</script>





</body>
</html>
