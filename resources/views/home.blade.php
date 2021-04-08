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
		  <a class="navbar-brand" href="https://presolv360.com/">
		  	
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
		      			        <a class="nav-link btn btn-circle-y loginbtn loginbtn2" href="login">Zaloguj się</a>
		       		    </ul>
		  </div>
	</div>
</nav>
<div class="loader">
</div><!-- Header -->

<!DOCTYPE html>
<html>
<body>
  <div class="loader">
</div>



<div class="container-fluid bannersection" style="padding: 0px;">

    <div class="videosection">
      <video width="100%"   loop="loop" id="bannervdo" autoplay="autoplay" muted="muted" playsinline>
          <source src="https://presolv360.com/asset/video/2.mp4"  />
      </video>
    </div>

    

  <div class="container bannersectiontext">

    <div class="col-12">

      <div class="row">
        <div class="col-12">

          <h1>Nasz Mediator pomoże Ci zakończyć spory ugodą,
która może zastąpić orzeczenie sądu</h1>
                  <h2>Szybko. Efektywnie. Skutecznie.</h2>

               </div>
        
      </div>

      
            <div class="row ">
        <div class="col-12 bannerbtn">
                <a href="#howitwork" class="btn btn-circle-y">Rozpocznij </a>
                <!-- <a href="" class="btn btn-circle-y ml-3" data-toggle="modal" data-target="#myModal">Watch Video </a> -->
              </div>
             </div><div class="row">
        <div class="col-12 toc mt-5">
                <a href="https://presolv360.com/secure_terms_conditions"><p>*<span style="color: #ffa600"><b><u>Zaakceptuj nasz regulamin i warunki</u></b></span></p></a>
              </div>
      </div>

    </div>

    <div class="col-5"></div>
    <span id="howitwork"></span>
    
  </div>

  
</div>
<div class="container-fluid recongnitionsec">

  <div class="container">


    <div class="row">

      <div class="col-12 tsection">
        <!-- <img src="https://presolv360.com/asset/img/logo-21.png"> -->
        <p>Nasz portal jest uznany przez Sądy Powszechne</p>
      </div>
    </div>
  </div>
</div>


<div class="container-fluid">

  <div class="container">


    <div class="row">

      

      <div class="col-12 tabcentertext" id="howitworks">
         <center><h2>OFERUJEMY MEDIACJE ONLINE</h2></center>
         <center><p>Korzystając z nowatorskich rozwiązań technologicznych umożliwiamy stronom bezpieczną, poufną
i efektywną komunikację wraz z uzgodnieniem i podpisaniem warunków ugody online.</p></center>
      </div>

    </div>


    <div class="row buttonsection">
      
      <div class="col-12 resmargpad0">

        <!-- Bootstrap CSS -->
<!-- jQuery first, then Bootstrap JS. -->
<!-- Nav tabs -->

<!--       <ul class="nav nav-tabs justify-content-center" role="tablist">
        <li class="nav-item nav-2">
          <a class="nav-link active" href="#resolve" role="tab" data-toggle="tab">Resolve Disputes</a>
        </li>
      </ul> -->

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane fade in active show" id="resolve">
    
      <div class="row">
        <div class="col-md-4 imgtxtbox">
          <div class="card">
            <div class="card-body">
          <img src="https://presolv360.com/asset/img/test2111.png" style="max-width: 150px;">
        <p>1. Zgłoś do nas sprawę do
rozwiązania, a my udzielimy Ci
wsparcia na każdym etapie aż
do zakończenia ugodą.</p>

        </div>
        </div>
        </div>
       
        <div class="col-md-4 imgtxtbox">
          <div class="card">
            <div class="card-body">
          <img src="https://presolv360.com/asset/img/test12.png">
                <p>2. Wspólnie opracujemy
i wyślemy zaproszenie do
mediacji drugiej stronie
konfliktu.
</p>
<br>

</div>
</div>
        </div>
      
        <div class="col-md-4 imgtxtbox">
          <div class="card">
            <div class="card-body">
          <img src="https://presolv360.com/asset/img/test111.png">
                <p>3. Wspólnie z naszym
mediatorem pomożemy
zakończyć spór ugodą.</p>
<br>
        </div>
       </div>
     </div>
   </div>
       <div class="row ">
        <div class="col-12 btnsection">
                <a href="" class="btn btn-circle-y" data-toggle="modal" id="" data-target="#myModalResolve">Rozpocznij </span></a>
                <a href="" class="btn btn-circle-y ml-2 resolvideomodal" data-toggle="modal" id="" data-target="#myModal2">Zobacz wideo </span></a>
                <button class="btn btn-circle-y ml-2 clse" data-toggle="modal" id="" data-target="#resolvefees">Opłaty</button>
                <a href="https://presolv360.com/arbitrator" class="btn btn-circle-y ml-2 clse" target="_blank">Nasi eksperci </span></a>
              </div>
         </div>

  </div>
</div>
        
      </div>
    </div>
    

    
  </div>

  
</div>

<div class="container-fluid numbersection" style="background: #f9f9f9;" >

  <div class="container">
    <div class="row">
      <div class="col-12">
         <center><h2 class="mb-5" style="color: #0e5587;">Wypowiedzi ekspertów </h2></center>
      </div>
    </div>
    
    <div class="numbercount">

          <div class="row">
            <style type="text/css">
                            .ifr{
                              height: 250px!important;
                              width: 350px!important;
                              margin: 0 auto;
                            }

                            @media (max-width: 400px) {
                              .ifr{
                   height: 200px!important;
                              /*width: 100%!important;*/
                              margin: 0 auto;
                            }
                }
                          </style>
        <div class="col-sm-12">

           <div class="owl-carousel vowl owl-theme">
                        <div class="owl-item">

                          

                          <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/wtGSvrd_rhk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="owl-item">
                          <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/7ucnjGw1AvY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>

                        <div class="owl-item">
                          <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/UozMurcWIcU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="owl-item">
                        <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/YnwnYv6KsVw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="owl-item">
                        <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/TjOyFnNfz4U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="owl-item">
                        <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/kfCa8JoW-ns" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="owl-item">
                    <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/l6TRFntt77s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="owl-item">
                    <iframe class="ifr" width="350" height="220" src="https://www.youtube.com/embed/ni-k3Oj2G_M" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
          </div>
          
        </div>
        
      </div>

    </div>

    
  </div>

  
</div>

<div class="container-fluid awardsection">

  <div class="container">
    

    <h2>Partnerzy i Sponsorzy</h2>

<div class="brands">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="brands_slider_container">
                    <div class="owl-carousel owl-theme brands_slider">
                        <div class="owl-item">

                          <a href="http://doj.gov.in/page/online-dispute-resolution-through-mediation-arbitration-conciliation-etc" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/awrdz3.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://www.thelegalforecast.com/blog/interview-bhaven-shah-presolv360" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/legaal.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://superlawyer.in/bhaven-shah-co-founder-presolv360-entrepreneurship-technology-future-of-dispute-resolution/" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/superlaw.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://www.thehindu.com/news/cities/mumbai/a-startup-that-helps-you-avoid-courts/article24618326.ece" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/The-Hindu-Logo-1.png" alt=""></div>
                          </a>
                        </div>
                        <!-- <div class="owl-item">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="img/weefoundation.jpg" alt=""></div>
                        </div> -->
                        <div class="owl-item">
                          <a href="https://indianconventions.com/in/presolv360-receives-start-up-award-2018/" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/awardz6.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://www.mediate.com/articles/rodriguesj1.cfm" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/media.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/Sine-IIT.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://www.businesstoday.in/magazine/the-buzz/presolv360-dispute-management-on-the-cloud/story/280068.html" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/Business-Today.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/ICAI.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/MSINS.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/Startup-India.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/WEE.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/DST.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://soundcloud.com/hrishikay/hrishi-k-with-namita-shah-aman-sanghvi-presolv360-mediation-out-of-court-settlements" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/Radio-one.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://yourstory.com/2020/03/womens-day-women-entrepreneurs-legal-tech-startups" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/Yourstory.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/Legal-Business-World.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://www.artificiallawyer.com/2020/03/16/in-india-civil-cases-take-13-years-but-presolv360-has-a-better-solution/" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img src="https://presolv360.com/asset/img/Artificial-Lawyer.png" alt=""></div>
                          </a>
                        </div>
                    </div> <!-- Brands Slider Navigation -->
                    <!-- <div class="brands_nav brands_prev"><i class="fas fa-chevron-left"></i></div>
                    <div class="brands_nav brands_next"><i class="fas fa-chevron-right"></i></div> -->
                </div>
            </div>
        </div>
    </div>
</div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade videomodal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <video id="video1" controls="true" style="width: 100%; height: auto; margin:0 auto; frameborder:0;">
          <source src="https://presolv360.com/asset/video/Secure_explainer_video.mp4" type="video/mp4">
          Your browser doesn't support HTML5 video tag.
        </video>
        <!-- <iframe width="auto" height="315" src="https://www.youtube.com/embed/BgEy12SijzY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="resolvefees" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    	<div class="modal-header" style="background-color: #0A5185; color: #fff;">

                

                <center>

                <h6 class="modal-title" id="myModalLabel">

                </h6>
                </center>
                <button type="button" class="close" data-dismiss="modal" style="opacity: 2.2; color:#fff;">

                       <span aria-hidden="true">×</span>

                       <span class="sr-only">Close</span>

                </button>

            </div>

      <div class="modal-body">
        The fees will be determined on the basis of the nature and quantum of the dispute. To know the fee applicable to you, contact us at  <a href="mailto:info@presolv360.com" target="_top">info@presolv360.com</a> or call us on .<a href="tel:+91-7710048834" target="_top">+91-7710048834</a>
      </div>
    </div>
  </div>
</div>


<div class="modal fade videomodal" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <video controls id="video2" style="width: 100%; height: auto; margin:0 auto; frameborder:0;">
          <source src="https://presolv360.com/asset/video/Resolve_explainer_video.mp4" type="video/mp4">
          Your browser doesn't support HTML5 video tag.
        </video>
        <!-- <iframe width="auto" height="315" src="https://www.youtube.com/embed/_110tyMBaLo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="myModalHorizontal" tabindex="-1" role="dialog"

     aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">


        <div class="modal-content">

            <!-- Modal Header -->

            <div class="modal-header" style="background-color: #0A5185; color: #fff;">

                


                <center>

                <h5 class="modal-title" id="myModalLabel">

                  Please enter the following details:

                </h5>
                </center>
                <button type="button" class="close" data-dismiss="modal" style="opacity: 2.2; color:#fff;">

                       <span aria-hidden="true">&times;</span>

                       <span class="sr-only">Close</span>

                </button>

            </div>



            <!-- Modal Body -->

            <div class="modal-body">



             <form class="form-horizontal" role="form" id='form'>

                <div class="form-group row">

                  <label  class="col-md-6 col-12 control-label1" style="padding-top: 23px;font-size: 14px; text-align: left;font-weight:600;">Number of Contracting Parties<span style="color:red;">*</span>:<br
 class="hidden-xs"><span style="font-size:12px;">(Include all parties signing the agreement)</span> </label>

                    <div class="col-md-6 col-12">

                        <label for="sel1"></label>
                        	<input type="" class="form-control" id="sel1" value="2" readonly>
                            <!-- <select class="form-control" id="sel1"> -->

                               <!--  <option disabled selected value> select an option </option> -->

                                
                             <!-- </select> -->

                     </div>

                  </div>

                  <div class="form-group row">

                  <label  class="col-md-6 col-12 control-label1" style="padding-top: 23px;font-size: 14px; text-align: left;font-weight:600;">Agreement Value<span style="color:red;">*</span><br class="hidden-xs"><!-- <span style="font-size:12px;"><a href="https://presolv360.com/documents/faqs/Defining Contract Value.pdf" download>(How to determine agreement value?) </a></span> --></label>

                    <div class="col-md-6 col-12">

                        <label for="sel1"></label>



                            <select class="form-control" id="sel2">

                                <option disabled selected value>select an option </option>

                                <option value='100'>&#8377; 0 - &#8377; 100.00</option><option value='100000'>&#8377; 100.00 - &#8377; 100000.00</option><option value='1000000'>&#8377; 100000.00 - &#8377; 1000000.00</option><option value='2500000'>&#8377; 1000000.00 - &#8377; 2500000.00</option><option value='5000000'>&#8377; 2500000.00 - &#8377; 5000000.00</option>                                <option value="5000001">Above ₹ 50,00,000.00</option>


                             </select>

                           

                     </div>

           <label  class="col-sm-1 control-label"></label>

                  </div>



                  <div class="form-group row">

                    <div class="col-sm-12 secutesubres" >

                          <button type="button" class="btn btn-warning abzx" style="background-color: #FFA600; color:#fff;width:120px;height:40px;">Submit</button>

          <button type="button" class="btn  tyux" style="background-color: #0B5386;color:#fff;width:120px;height:40px;">Reset</button>

                    </div>

                    

                  </div>

                  <div class="row">

                    <div class="col-12">
                    <!-- <center class="mb-2">
                      <span style="font-size:12px;"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> For agreements not categorized above, or for bulk plans, kindly<a href="tel:022-20821102" target="_top"> Contact Us</a> </span><br>
                    </center> -->
                    
                    </div>
                  </div>


                   
                   <div class=" row keynbenfsec" style="display: none;">

                   <div class="col-12">

                        <center><p style="font-size:12px;font-weight:600;text-decoration:underline;" class="mb-3">    Key Features and Benefits</p></center>
                    </div>


                   <div class="col-md-6 col-sm-6">

                   <p style="font-size:12px;text-align:justify;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> Agreement storage & management</p>

                   <p style="font-size:12px;text-align:justify;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> Free document repository</p>

                   <p style="font-size:12px;text-align:justify;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> 24x7 access to online platform</p>

                   <p style="font-size:12px;text-align:justify;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> Dedicated domain experts</p>

                   </div>

                   <div class="col-md-6 col-sm-6" >

                   <p style="font-size:12px;text-align:left;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> Complete agreement security</p>

                   <p style="font-size:12px;text-align:left;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> PresolvReview at discounted rates</p>

                   <p style="font-size:12px;text-align:left;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> End-to-end resolution support</p>

                   <p style="font-size:12px;text-align:left;letter-spacing:0px;"><i class="fa fa-check" aria-hidden="true" style="color:#FFA600;"></i> Unlimited resolution at zero cost</p>

                   </div>

                   </div>

                   

                            <div id="plan1" style="display: none;">

</div>



          <div class="form-group row mt-2" id="plan" style="display: none;">

                   <div class="col-sm-12">

                                      <!---    <form action="checkout.php" method="post">     --->        

                      <center>
             <h5 class="modal-title mt-3" style="background-color: #0A5185; color: #fff;padding: 4px; border-radius: 14px;">

                     Secure Contract:</h5>

                     </center>

                    <table class="table">

           <tbody>

                       <tr>

                          <td style="text-align:left;font-size: 14px;font-weight: 600;">Plan Name:</td>

                           <td style="text-align:right; font-size: 14px;font-weight: 600;"><div id="planname" name="planname" readonly required></div></td>

                        </tr>

                         <tr>

                        <td style="text-align:left;font-size: 14px;font-weight: 600;">Plan Cost (Rs.):<br>(Inclusive of all taxes)</td>

                        <td style="text-align:right; font-size: 14px;font-weight: 600;">&#8377; <span id="price" name="price" readonly required></span></td>

                        </tr>

                       <!-- <td style="text-align:left;font-size: 14px;font-weight: 600;">Sessions 
                       
                      
&nbsp;<a href="#" data-toggle="tooltip" title="Advance situations of a dispute may require assignment of a certified dispute resolution expert for arriving at a resolution. Video-conferencing will be conducted to facilitate the dispute resolution process with the expert. The number of sessions (1 session = 3 hours) included in you plan has been mentioned here. These sessions will be conducted at no additional cost. 'Online' sessions indicates that the dispute will be resolved using online negotiation only."><i class="fa fa-info-circle" aria-hidden="true"></i></a> 



</td>

                       <td style="text-align:right; font-size: 14px;font-weight: 600;"><span id="session" name="session" readonly required></span></td> -->

                       </tr>

                     </tbody>

            </table>

          <div class="form-group row">

                    <div class="col-sm-12">

                    	<span style="font-size: 12px;"><b><a href="https://presolv360.com/secure_terms_conditions" style="line-height: 68px;" target="_blank">*Terms & Conditions apply</a></b>  </span>

 <button type="button" class="btn btn-warning"  id="btn_sub1" style="background-color: #FFA600;height:40px;float: right;">Purchase</button>
                    </div>

                  </div>

<!--                   </form>

 -->                    </div>


                  </div>



                </form>

          </div>



           </div>

        </div>

      </div>



    </div>





      </div>

    </div>









  </div><!-- end model /- -->


  <style type="text/css">
  label{
    font-size: 15px;
  }

  optgroup{
    
  }
</style>
<div class="modal fade" id="myModalResolve" tabindex="-1" role="dialog"

     aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <!-- Modal Header -->

            <div class="modal-header" style="background-color: #0A5185; color: #fff;">

                

                <center>

                <h6 class="modal-title" id="myModalLabel">

                  Please enter the following details:

                </h6>
                </center>
                <button type="button" class="close" data-dismiss="modal" style="opacity: 2.2; color:#fff;">

                       <span aria-hidden="true">&times;</span>

                       <span class="sr-only">Close</span>

                </button>

            </div>



            <!-- Modal Body -->

            <div class="modal-body">





        <form class="well form-horizontal resarbform" action="savedirect" method="post" role="form" id="contact_form" enctype="multipart/form-data" name="vform" onSubmit="return validateFormx()">

                    <fieldset>
                        
                        <!-- Select Basic-->
                        <div class="form-group row">
                            <!--<label class="col-md-4 control-label" style="text-align:left;">Dispute category*</label>-->
                            <label class="col-md-12 control-label" style="text-align:left;">What is your dispute regarding?*</label>
                            <div class="col-md-12 selectContainer" id="cat_div">
                                <div class="input-group">
                                    <!-- <span class="input-group-addon"><i class="fa fa-filter" aria-hidden="true"></i></span> -->
                                    <select name="cat" id="Cat" class="form-control selectpicker" >
                                      <optgroup>
                                    <option value="">Please select option</option>
                                    <option>Banking</option>
                                    <option>Bankruptcy and Insolvency</option>
                                    <option>Business and Commercial</option>
                                    <option>Consumer Complaint</option>
                                    <option>Employment and Labour</option>
                                    <option>Family Dispute</option>
                                    <option>Government and Community</option>
                                    <option>Intellectual Property Rights</option>
                                    <option>Insurance Claim</option>
                                    <option>Property</option>
                                    <option>Tenancy and Rental</option>
                                    <option>Will and Inheritance</option>
                                    <option>Others</option>
                                    </optgroup>
                                    </select>
                                </div><div id="cat_error"></div><!-- input-group -->
                            </div>
                        </div>
                        <!-- Select Basic -->
                        <div class="form-group row" style="display: none;">
                            <label class="col-md-12 control-label" style="text-align:left;">Number of parties involved in the dispute (including you)*</label>
                            <div class="col-md-12 selectContainer" id="npd_div">
                                <div class="input-group">
                                    <!-- <span class="input-group-addon"><i class="fa fa-filter" aria-hidden="true"></i></span> -->
                                    
                                     <select name="npd"  id="npd" class="form-control selectpicker" />
                                     <optgroup>
                                        <!-- <option value="">Please select option</option> -->
                                        <option value="2" selected>2</option>
                                        <!-- <option value="3">3</option>
                                        <option value="more_than_3">More than 3</option> -->
                                      </optgroup>
                                        
                                    </select>
                                </div><div id="npd_error"></div><!-- input-group -->
                            </div>
                        </div>
                        <!-- Select Basic-->
                        <div class="form-group row">
                            <label class="col-md-12 control-label" style="text-align:left;">Disputed amount*</label>
                            <div class="col-md-12 selectContainer" id="damount_div">
                                <div class="input-group">
                                    <!-- <span class="input-group-addon"><i class="fa fa-inr" aria-hidden="true"></i></span> -->
                                    <!--<input type="text" name="damount" id="Damount" placeholder="Disputed amount" onkeypress="javascript:return isNumber(event)"   class="form-control number" type="text"> -->
                               
                                  <!--<select name="damount" id="Damount" class="form-control" />
                                       <option value="">Please select option</option>
                                        <option value="Upto Rs. 5 lakhs">Upto Rs. 5 lakhs</option>
                                        <option value="Rs. 5 lakhs to Rs. 10 lakhs">Rs. 5 lakhs to Rs. 10 lakhs</option>
                                        <option value="Rs. 10 lakhs to Rs. 30 lakhs">Rs. 10 lakhs to Rs. 30 lakhs</option>
                                        <option value="Above Rs. 30 lakhs">Above Rs. 30 lakhs</option>
                                        <option value="Not Applicable">Not Applicable</option>
                                </select>   -->
                                <select name="damount" id="Damount" class="form-control"  required>
                                  <optgroup>
                                        <option value="">Please select option</option>
                                        <option value="Upto Rs. 10 lakhs">Upto Rs. 10 lakhs</option>
                                        <option value="Rs. 10 lakhs to Rs. 50 lakhs">Rs. 10 lakhs to Rs. 50 lakhs</option>
                                        <option value="Rs. 50 lakhs to Rs. 1 crore">Rs. 50 lakhs to Rs. 1 crore</option>
                                        <option value="Rs. 1 crore to Rs. 5 crore">Rs. 1 crore to Rs. 5 crore</option>
                                        <option value="Above Rs. 5 crore">Above Rs. 5 crore</option>
                                  </optgroup>
                                </select>
                                
                                </div><div id="damount_error"></div><!-- input-group -->
                            </div>
                        </div>
                      


<div class="col-md-4">

</div>

<div class="col-md-12">
  <center>
            
            <small>By choosing <b><i>Arbitration360</i></b> the parties are bound by an award made by the arbitrator. You should opt for arbitration in the event you desire a decision by an independent adjudicator.</small><p></p>
            <small>By choosing <b><i>Mediation360</i></b> the parties mutually opt to settle the dispute with the help of an independent expert. You should opt for mediation in the event you desire a negotiated settlement.</small>
  </center>



</div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-12">

                                <center>
                            
                                 <label class="cntctus"><h6>Email us at <span style="color: #ffa600;">info@presolv360.com</span></h6></label>
                               <!--  <button type="button" class="btn btn-warning cntctus" id="cnn" >Contact Us</button> -->

                                <button type="submit" id="send_submit" name="send_submit" class="btn btn-warning sbmtt" onclick="resolvsubmit()" >Mediation360</button>

							
                            </center>


                            </div>
                        </div>
                        <div class="alert alert-danger" id="error" style="display:none;">
                    </fieldset>
 
                </form>

   </div>

</div>

</div>

</div>

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

    $('.resarbform').attr('action','savedirect');

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


  <!-- <div class="container-fluid contactsection">

	<div class="container">
		

		<div class="row">


			<div class="col-md-4">
				<div class="consection">
					 <span class="float-left mr-2"><h2><i class="fas fa-map-marker-alt"></i></h2></span>
					 <span class="float-left " style="margin-left:8px">
						<h2>Locate us</h2>
					    <p>Mumbai, India</p>
					 </span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="consection">
					<span class="float-left mr-2"><h2><i class="fas fa-phone-alt"></i></h2></span>
					<span class="float-left ">
						<h2>Contact us</h2>
					    <p><a href="tel:022-20821102" target="_top">Tel: 022-20821102</a></p>
					</span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="consection">
					<span class="float-left mr-2"><h2><i class="fas fa-edit"></i></h2></span>
					<span class="float-left ">
						<h2> Write to us</h2>
					    <p><a href="mailto:info@presolv360.com" target="_top">info@presolv360.com</a></p>
					</span>
				</div>
			</div>
		</div>
	</div>

	
</div> -->
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
 var DOMAIN = "https://presolv360.com/";
  </script>
  <script src="https://presolv360.com/presolv360/js/jquery.min.js"></script>
<script type="text/javascript" src="https://presolv360.com/asset/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.js"></script>

<script src="https://presolv360.com/asset/js/brand-slider.js"></script>

<script src="https://presolv360.com/presolv360/js/main.js"></script>


<script src="https://presolv360.com/public/js/site.js"></script>

   <script src="https://presolv360.com/public/js/lib/slick-carousel/slick.js"></script> 

        <script src="https://presolv360.com/presolv360/js/sweetalert2.js"></script>
        <script src="//code.tidio.co/us2r7rp6mpmnzat0gxobxzr6ilx4fjqn.js"></script>
        

        <script type="text/javascript">
            
            $(window).load(function() {






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
           // console.log("here");
            //console.log(document.getElementById('username_reset').value);

            /*$.ajax({

              'type':'GET',
              'url':DOMAIN+"functions/password_recover.php?username=" + str +'&flag=1',
              'success':function(res){
                console.log(res);
              },

              'error':function(err){
                console.log(err);
              }
            });*/

              $.get(DOMAIN+"functions/password_recover.php?username=" + str +'&flag=1', function(data, status){

                var d=$.trim(data.resp);

                   if(d=="success"){

                    //alert('this');

                    $('#myModal').modal('hide');

                        swal("Success!", "Your Password Has Been Updated. Please Check Your Registered Email Id!", "success");

                   } else if(d=="fail"){


                    $this.button('reset');
                        $('#myModal').modal('hide');

                        swal("Failed!", "Wrong username entered!", "warning");


                   }
              });


            var xmlhttp = new XMLHttpRequest();
            /*xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("txtHint").innerHTML = this.responseText;
                    console.log(this.reponseText);
                    if(this.responseText.localeCompare('success')===0)
                    {
                        $this.button('reset');
                        $('#myModal').modal('hide');

                        swal("Success!", "Your Password Has Been Updated. Please Check Your Registered Email Id!", "success");


                    }
                    else
                    {
                        $this.button('reset');
                        $('#myModal').modal('hide');

                        swal("Failed!", "Wrong username entered!", "warning");

                    }

                }
            };
            xmlhttp.open("GET", DOMAIN+"functions/password_recover.php?username=" + str +'&flag=1', true);
            xmlhttp.send();*/

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
            /*console.log("here");
            console.log(document.getElementById('forgot_username_reset').value);
			var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("txtHint").innerHTML = this.responseText;
                    console.log(this.reponseText);
					
					console.log('nil'+this.responseText.localeCompare('success'));
                    
					if(this.responseText.localeCompare('success')===0)
                    {
                        $this.button('reset');
                        $('#myModal2').modal('hide');
                        swal("Success!", "Your Username Has Been Sent To Your Registered Email Id. Please Check Your Registered Email Id!", "success");
                    }
                    else
                    {
                        $this.button('reset');
                        $('#myModal2').modal('hide');
                        swal("Failed!", "Wrong email entered!", "warning");
                    }

                }
            };
            xmlhttp.open("GET", DOMAIN+"functions/password_recover.php?password=" + str + '&flag=2', true);
            xmlhttp.send();*/

            $.get(DOMAIN+"functions/password_recover.php?password=" + str +'&flag=2', function(data, status){

                var d=$.trim(data.resp);


                   if(d=="success"){

                    $this.button('reset');
                        $('#myModal2').modal('hide');
                        swal("Success!", "Your Username Has Been Sent To Your Registered Email Id. Please Check Your Registered Email Id!", "success");

                   } if(d=="fail"){


                    $this.button('reset');
                        $('#myModal2').modal('hide');
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
