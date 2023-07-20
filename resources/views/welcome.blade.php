@extends('layouts.home')
@section('title', 'Users')

@section('content')


<div class="container-fluid bannersection" style="padding: 0px;">

    <div class="videosection">
      <video width="100%"   loop="loop" id="bannervdo" autoplay="autoplay" muted="muted" playsinline>
          <source src="{{url('/assert/')}}/video/2.mp4"  />
      </video>
    </div>

    

  <div class="container bannersectiontext">

    <div class="col-12">

      <div class="row">
        <div class="col-12">

          <h1>Online dispute resolution<br>platform to resolve legal<br>disputes and achieve<br>settlements in record time</h1>
                  <h2>Easy. Efficient. Enforceable.</h2>

               </div>
        
      </div>

      
            <div class="row ">
        <div class="col-12 bannerbtn">
                <a href="#howitwork" class="btn btn-circle-y">Get Started </a>
                <!-- <a href="" class="btn btn-circle-y ml-3" data-toggle="modal" data-target="#myModal">Watch Video </a> -->
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
              <!-- <img loading="lazy" src="/assets/images/logo-21.png">
              <p>Recognised by the Ministry of Law and Justice, Government of India</p> -->
          </div>
      </div>
  </div>
</div>


<div class="container-fluid">

  <div class="container">


    <div class="row">

      

      <div class="col-12 tabcentertext" id="howitworks">
         <center><h2>Our Services</h2></center>
      </div>

    </div>


    <div class="row buttonsection">
      
      <div class="col-12 resmargpad0">

        <!-- Bootstrap CSS -->
<!-- jQuery first, then Bootstrap JS. -->
<!-- Nav tabs -->

      <ul class="nav nav-tabs justify-content-center" role="tablist">
        <li class="nav-item nav-2">
          <a class="nav-link active" href="#resolve" role="tab" data-toggle="tab">Resolve Disputes</a>
        </li>
      </ul> 

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane fade in active show" id="resolve">
    
      <div class="row">
        <div class="col-md-4 imgtxtbox">
          <div class="card">
            <div class="card-body">
          <img src="/assert/img/test2111.png" loading="lazy" style="max-width: 150px;">
        <p>Register an existing dispute and opt for e-arbitration or e-mediation</p>

        </div>
        </div>
        </div>
       
        <div class="col-md-4 imgtxtbox">
          <div class="card">
            <div class="card-body">
          <img src="/assert/img/test12.png" loading="lazy">
                <p>The other party is invited to register on the platform
</p>
<br>

</div>
</div>
        </div>
      
        <div class="col-md-4 imgtxtbox">
          <div class="card">
            <div class="card-body">
          <img src="/assert/img/test111.png" loading="lazy">
                <p>Achieve a timely resolution with the help of Presolv360's experts</p>
<br>
        </div>
       </div>
     </div>
   </div>
       <div class="row ">
        <div class="col-12 btnsection">
          <a href="" class="btn btn-circle-y" data-toggle="modal" id="" data-target="#myModalResolve">Get Started </span></a>
          <a href="" class="btn btn-circle-y ml-2 resolvideomodal" data-toggle="modal" id="" data-target="#myModal2">Watch Video </span></a>
          <!-- <button class="btn btn-circle-y ml-2 clse" data-toggle="modal" id="" data-target="#resolvefees">Fees</button> -->
          <a href="https://presolv360.com/dispute_resolution_clause" class="btn btn-circle-y ml-2 clse" target="_blank">Clause</span></a>
          <!--<a href="https://presolv360.com/arbitrator" class="btn btn-circle-y ml-2 clse" target="_blank">Panel of Experts </span></a>-->
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
         <center><h2 class="mb-5" style="color: #0e5587;">Experts’ Views </h2></center>
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
                        <div class="owl-item ">
                          <a target="_blank" href="https://www.youtube.com/embed/wtGSvrd_rhk"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/wtGSvrd_rhk/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        <div class="owl-item">
                          <a target="_blank" href="https://www.youtube.com/embed/7ucnjGw1AvY"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/7ucnjGw1AvY/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        <div class="owl-item">
                          <a target="_blank" href="https://www.youtube.com/embed/UozMurcWIcU"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/UozMurcWIcU/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        <div class="owl-item">
                          <a target="_blank" href="https://www.youtube.com/embed/YnwnYv6KsVw"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/YnwnYv6KsVw/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        <div class="owl-item">
                          <a target="_blank" href="https://www.youtube.com/embed/TjOyFnNfz4U"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/TjOyFnNfz4U/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        <div class="owl-item">
                          <a target="_blank" href="https://www.youtube.com/embed/kfCa8JoW-ns"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/kfCa8JoW-ns/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        <div class="owl-item">
                          <a target="_blank" href="https://www.youtube.com/embed/l6TRFntt77s"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/l6TRFntt77s/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        <div class="owl-item">
                          <a target="_blank" href="https://www.youtube.com/embed/ni-k3Oj2G_M"><img class="ifr" width="350" height="220" src="http://img.youtube.com/vi/ni-k3Oj2G_M/hqdefault.jpg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </a></div>
                        {{-- <div class="owl-item">
                          <img class="ifr" width="350" height="220" src="https://www.youtube.com/embed/UozMurcWIcU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </div>
                        <div class="owl-item">
                          <img class="ifr" width="350" height="220" src="https://www.youtube.com/embed/YnwnYv6KsVw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </div>
                        <div class="owl-item">
                          <img class="ifr" width="350" height="220" src="https://www.youtube.com/embed/TjOyFnNfz4U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </div>
                        <div class="owl-item">
                          <img class="ifr" width="350" height="220" src="https://www.youtube.com/embed/kfCa8JoW-ns" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </div>
                        <div class="owl-item">
                          <img class="ifr" width="350" height="220" src="https://www.youtube.com/embed/l6TRFntt77s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </div>
                        <div class="owl-item">
                            <img class="ifr" width="350" height="220" src="https://www.youtube.com/embed/ni-k3Oj2G_M" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </div> --}}
          </div>
          
        </div>
        
      </div>

    </div>

    
  </div>

  
</div>

<div class="container-fluid awardsection">

  <div class="container">


      <h2>Alliances and Incubation</h2>

      <div class="brands">
          <div class="container">
              <div class="row">
                  <div class="col">
                      <div class="brands_slider_container">
                          <div class="owl-carousel owl-theme brands_slider">
                              <div class="owl-item">
                                  <a href="https://shaktipreneurs.org/portfolio/" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/shaktiai.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item nextflaw">
                                  <a href="https://legaltech.asia/announcement-nextlaw-referral-network-joins-hands-with-presolv360-to-promote-online-dispute-resolution/" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/refnetai.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a href="https://alita.legal/join-us" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/asiaai.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">

                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/weeai.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/shineai.png" alt=""></div>
                                  </a>
                              </div>
                              
                              <!-- <div class="owl-item">
                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="img/weefoundation.jpg" alt=""></div>
                  </div> -->
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/nsrai.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/blairai.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a  href=" https://www.cyrilshroff.com/prarambh/" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/camii.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/ICAI.png" alt=""></div>
                                  </a>
                              </div>

                              
                              
                          </div>
                          <!-- Brands Slider Navigation -->
                          <!-- <div class="brands_nav brands_prev"><i class="fas fa-chevron-left"></i></div>
              <div class="brands_nav brands_next"><i class="fas fa-chevron-right"></i></div> -->
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="container-fluid awardsection">

  <div class="container">


      <h2>Awards and Recognition</h2>

      <div class="brands">
          <div class="container">
              <div class="row">
                  <div class="col">
                      <div class="brands_slider_container">
                          <div class="owl-carousel owl-theme brands_slider">
                              <!-- <div class="owl-item">

                                  <a href="http://doj.gov.in/page/online-dispute-resolution-through-mediation-arbitration-conciliation-etc" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/awrdz3.png" alt=""></div>
                                  </a>
                              </div> -->
                              <div class="owl-item">
                                  <a href="https://www.thelegalforecast.com/blog/interview-bhaven-shah-presolv360" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/legaal.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a href="https://superlawyer.in/bhaven-shah-co-founder-presolv360-entrepreneurship-technology-future-of-dispute-resolution/" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/superlaw.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a href="https://www.thehindu.com/news/cities/mumbai/a-startup-that-helps-you-avoid-courts/article24618326.ece" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/The-Hindu-Logo-1.png" alt=""></div>
                                  </a>
                              </div>
                              <!-- <div class="owl-item">
                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="img/weefoundation.jpg" alt=""></div>
                  </div> -->
                              <div class="owl-item">
                                  <a href="https://indianconventions.com/in/presolv360-receives-start-up-award-2018/" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/awardz6.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a href="https://www.mediate.com/articles/rodriguesj1.cfm" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/media.png" alt=""></div>
                                  </a>
                              </div>
                              <!-- <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Sine-IIT.png" alt=""></div>
                                  </a>
                              </div> -->
                              <div class="owl-item">
                                  <a href="https://www.businesstoday.in/magazine/the-buzz/presolv360-dispute-management-on-the-cloud/story/280068.html" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/Business-Today.png" alt=""></div>
                                  </a>
                              </div>
                              
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/MSINS.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/Startup-India.png" alt=""></div>
                                  </a>
                              </div>
                              <!-- <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/WEE.png" alt=""></div>
                                  </a>
                              </div> -->
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/DST.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a href="https://soundcloud.com/hrishikay/hrishi-k-with-namita-shah-aman-sanghvi-presolv360-mediation-out-of-court-settlements" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/Radio-one.png" alt=""></div>
                                  </a>
                              </div>
                               {{-- <div class="owl-item">
                                  <a href="https://asialawportal.com/2020/09/12/indias-online-dispute-resolution-platform-an-interview-with-namita-shah-co-founder-presolv360/" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/AsiaLawPortal.png" style="margin-top: 12px;height: 180px;
width: 200px;" alt=""></div>
                                  </a>
                              </div> --}}
                              {{-- <div class="owl-item">
                                  <a href="https://www.livemint.com/opinion/online-views/opinion-collections-and-dispute-resolution-hope-faith-and-more-for-lenders-11599974221412.html" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Livemint.png" style="
height: 165px;
width: 200px;
margin-top: 12px;" alt=""></div>
                                  </a>
                              </div> --}}
                               {{-- <div class="owl-item">
                                  <a href="https://timesofindia.indiatimes.com/india/as-lockdown-slows-down-courts-e-mediation-across-cities-picks-up/articleshow/78204960.cms" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/TimesofIndia.png" style="height:150px;width:200px;" alt=""></div>
                                  </a>
                              </div> --}}
                              
                              {{-- <div class="owl-item">
                                  <a href="https://www.youtube.com/watch?v=jfezqCotG9U" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Businessworld_(logo).png" style="margin-top: 76px;
height: 56px;
width: 250px;" alt=""></div>
                                  </a>
                              </div> --}}
                              <div class="owl-item">
                                  <a href="https://yourstory.com/2020/03/womens-day-women-entrepreneurs-legal-tech-startups" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/Yourstory.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a  target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/Legal-Business-World.png" alt=""></div>
                                  </a>
                              </div>
                              <div class="owl-item">
                                  <a href="https://www.artificiallawyer.com/2020/03/16/in-india-civil-cases-take-13-years-but-presolv360-has-a-better-solution/" target="_blank">
                                      <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="/assert/img/Artificial-Lawyer.png" alt=""></div>
                                  </a>
                              </div>
                          </div>
                          <!-- Brands Slider Navigation -->
                          <!-- <div class="brands_nav brands_prev"><i class="fas fa-chevron-left"></i></div>
              <div class="brands_nav brands_next"><i class="fas fa-chevron-right"></i></div> -->
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

{{-- <div class="container-fluid awardsection">

  <div class="container">
    

    <h2>Partners and Sponsors</h2>

<div class="brands">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="brands_slider_container">
                    <div class="owl-carousel owl-theme brands_slider">
                        <div class="owl-item">

                          <a href="http://doj.gov.in/page/online-dispute-resolution-through-mediation-arbitration-conciliation-etc" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/awrdz3.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://www.thelegalforecast.com/blog/interview-bhaven-shah-presolv360" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/legaal.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://superlawyer.in/bhaven-shah-co-founder-presolv360-entrepreneurship-technology-future-of-dispute-resolution/" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/superlaw.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://www.thehindu.com/news/cities/mumbai/a-startup-that-helps-you-avoid-courts/article24618326.ece" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/The-Hindu-Logo-1.png" alt=""></div>
                          </a>
                        </div>
                        <!-- <div class="owl-item">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="img/weefoundation.jpg" alt=""></div>
                        </div> -->
                        <div class="owl-item">
                          <a href="https://indianconventions.com/in/presolv360-receives-start-up-award-2018/" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/awardz6.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="https://www.mediate.com/articles/rodriguesj1.cfm" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/media.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Sine-IIT.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://www.businesstoday.in/magazine/the-buzz/presolv360-dispute-management-on-the-cloud/story/280068.html" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Business-Today.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/ICAI.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/MSINS.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Startup-India.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/WEE.png" alt=""></div>
                          </a>
                        </div>
                        <div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/DST.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://soundcloud.com/hrishikay/hrishi-k-with-namita-shah-aman-sanghvi-presolv360-mediation-out-of-court-settlements" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Radio-one.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://yourstory.com/2020/03/womens-day-women-entrepreneurs-legal-tech-startups" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Yourstory.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Legal-Business-World.png" alt=""></div>
                          </a>
                        </div><div class="owl-item">
                          <a href="https://www.artificiallawyer.com/2020/03/16/in-india-civil-cases-take-13-years-but-presolv360-has-a-better-solution/" target="_blank">
                            <div class="brands_item d-flex flex-column justify-content-center"><img loading="lazy" src="https://presolv360.com/asset/img/Artificial-Lawyer.png" alt=""></div>
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
</div> --}}

<!-- Modal -->
<div class="modal fade videomodal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <video id="video1" controls="true" style="width: 100%; height: auto; margin:0 auto; frameborder:0;">
          <source src="/assert/video/Secure_explainer_video.mp4" type="video/mp4">
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
        The fees will be determined on the basis of the nature and quantum of the dispute. To know the fee applicable to you, contact us at <a href="mailto:info@presolv360.com" target="_top">info@presolv360.com</a> or call us on .<a href="tel:+91-7710048834"
                        target="_top">+91-8447728708</a>
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
          <source src="{{url('storage/app/public/video/How_it_works_video_final.mp4')}}" type="video/mp4">
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
                        <div class="form-group row">
                            <label class="col-md-12 control-label" style="text-align:left;">Number of parties involved in the dispute (including you)*</label>
                            <div class="col-md-12 selectContainer" id="npd_div">
                                <div class="input-group">
                                    <!-- <span class="input-group-addon"><i class="fa fa-filter" aria-hidden="true"></i></span> -->
                                    
                                     <select name="npd"  id="npd" class="form-control selectpicker" />
                                     <optgroup>
                                        <!-- <option value="">Please select option</option> -->
                                        <option value="2" selected>2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="more_than_10">More than 10</option>
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
                                        <option value="Rs. 1 crore to Rs. 50 crore">Rs. 1 crore to Rs. 50 crores</option>
                                        <option value="Above Rs. 50 crore">Above Rs. 50 crores</option>
                                  </optgroup>
                                </select>
                                
                                </div><div id="damount_error"></div><!-- input-group -->
                            </div>
                        </div>
                      


<div class="col-md-4">

</div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-12">

                                <center>
                            
                                 <label class="cntctus"><h6>Email us at <span style="color: #ffa600;">admin@presolv360.com</span></h6></label>
                               <!--  <button type="button" class="btn btn-warning cntctus" id="cnn" >Contact Us</button> -->

                                <button type="submit" id="send_submit" name="send_submit" class="btn btn-warning sbmtt" onclick="resolvsubmit()" >Mediation</button>

              
                            </center>


                            </div>
                        </div>
                        <div class="alert alert-danger" id="error" style="display:none;">
                    </fieldset>
                  @csrf
                </form>

   </div>

</div>

</div>

</div>

@endsection

<script>
  <script>
                $('img').click(function(){
            var video = '<div class="video-container"><iframe src="'+ $(this).attr('data-video') +'"></iframe></div>';
            $(this).replaceWith(video);
        });
        </script>
</script>