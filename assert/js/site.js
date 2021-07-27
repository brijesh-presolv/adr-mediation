
(function( $ ) {

    //Function to animate slider captions 
    function doAnimations( elems ) {
        //Cache the animationend event in a variable
        var animEndEv = 'webkitAnimationEnd animationend';
        
        elems.each(function () {
            var $this = $(this),
                $animationType = $this.data('animation');
            $this.addClass($animationType).one(animEndEv, function () {
                $this.removeClass($animationType);
            });
        });
    }
    
    //Variables on page load 
    var $myCarousel = $('#carousel-example-generic'),
        $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");
        
    //Initialize carousel 
    $myCarousel.carousel();
    
    //Animate captions in first slide on page load 
    doAnimations($firstAnimatingElems);
    
    //Pause carousel  
    //$myCarousel.carousel('pause');

    
    
    //Other slides to be animated on carousel slide event 
    $myCarousel.on('slide.bs.carousel', function (e) {
        var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
        doAnimations($animatingElems);
    });  
    
})(jQuery);

/* Demo Scripts for Bootstrap Carousel and Animate.css article
* on SitePoint by Maria Antonietta Perna
*/
(function( $ ) {

    //Function to animate slider captions 
    function doAnimations( elems ) {
        //Cache the animationend event in a variable
        var animEndEv = 'webkitAnimationEnd animationend';
        
        elems.each(function () {
            var $this = $(this),
                $animationType = $this.data('animation');
            $this.addClass($animationType).one(animEndEv, function () {
                $this.removeClass($animationType);
            });
        });
    }
    
    //Variables on page load 
    var $myCarousel = $('#carousel-example-generic'),
        $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");
        
    //Initialize carousel 
    $myCarousel.carousel();
    
    //Animate captions in first slide on page load 
    doAnimations($firstAnimatingElems);
    
    //Pause carousel  
    //$myCarousel.carousel('pause');

    
    
    //Other slides to be animated on carousel slide event 
    $myCarousel.on('slide.bs.carousel', function (e) {
        var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
        doAnimations($animatingElems);
    });  
    
})(jQuery);
var TxtType = function(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 500;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    };

    TxtType.prototype.tick = function() {
        var i = this.loopNum % this.toRotate.length;
        var fullTxt = this.toRotate[i];

        if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

        var that = this;
        var delta = 200 - Math.random() * 100;

        if (this.isDeleting) { delta /= 2; }

        if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
        }

        setTimeout(function() {
        that.tick();
        }, delta);
    };

    window.onload = function() {
        var elements = document.getElementsByClassName('typewrite');
        for (var i=0; i<elements.length; i++) {
            var toRotate = elements[i].getAttribute('data-type');
            var period = elements[i].getAttribute('data-period');
            if (toRotate) {
              new TxtType(elements[i], JSON.parse(toRotate), period);
            }
        }
        // INJECT CSS
        //var css = document.createElement("style");
        //css.type = "text/css";
        //css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff}";
        //	document.body.appendChild(css);
    };
    
    function fetch_select(val)
    {
        $.ajax({
            type: 'post',
             url: DOMAIN+"functions/presolv360/fetch_data.php", 
            data: {
                get_option:val
            },
            success: function (response) {
                
                $('#sel2').prop('disabled', false);

                document.getElementById("sel2").innerHTML=response;
            }
        });
    }
            $('.abz').on('click', function(event) {
               
event.preventDefault();
         
            var people = $('#sel1').val();
            var cost = $('#sel2').val();
  
            $.ajax({
                url: DOMAIN+"functions/presolv360/fetch_data.php", 
                method:"post",
                dataType:'JSON',
                data:{category:people,sub:cost},
                success:function (data) {
                   
                    if(data.resp==true) {
                        $('#plan1').show();
                        $('#plan').show();
                        $('#planname').html('');
                        $('#price').html('');
                        $('#planname').append(data.planname);
                        $('#price').append(data.price);
                        
                    }
                    else{
                        $('#plan').hide();
                        $('#plan1').show().html('<a type="button" class="btn btn-default" href="mailto:info@presolv360.com" id="btnsub" style="background-color: #FFA600;">Contact Us</a>');

                    }
                }
            });
return false;

        });
        $('.tyu').on('click', function () {
        $('select').val('');

        $('#planname').html('');
        $('#price').html('');
        $('#sel2').prop('disabled', true);

        $('#btnsub').hide();
        $('#plan').hide();


    });
    $('.abzx').on('click', function(event) {
$('#plan1').focus();
      

        var people = $('#sel1').val();
        var cost = $('#sel2').val();

        console.log(people);
        console.log(cost);



            $.ajax({
               url: DOMAIN+"functions/presolv360/fetch_data.php", 
                method:"post",
                dataType:'JSON',
                data:{people:people,cost:cost},
                success:function (data) {

                    console.log(data);

                    if(data.resp==true) {
                    	$('#plan1').hide();
                    	$('#plan').show();
                    	$('#planname').html('');
                    	$('#price').html('');
                        $('#session').html('');
                        $('#planname').append(data.planname);
                        $('#price').append(data.price);
                        if(data.session!=0){
                        	                        $('#session').append(data.session);

                        }
                        else{
                        $('#session').append("Online");
                        
			
			}


                    }
                    else{
                    	                    	$('#plan').hide();
                    	                    	$('#plan1').show().html('<a type="button" class="btn btn-default" href="mailto:info@presolv360.com" id="btnsub" style="background-color: #FFA600;">Contact Us</a>');



                    }
                }
            });


    });
    $('.tyux').on('click', function () {
    $('select').val('');
$('#planname').html('');
                    	$('#price').html('');
                        $('#session').html('');
                    	$('#btnsub').hide();
$('#plan').hide();


});
  var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active1");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
        $(document).ready(function(){

	if ($(window).width() <= 768) {
	
$('#pgr').css('margin-right','-60px'); 
$('#abts').css('margin-right','-60px'); 
$('#ftm').css('margin-right','-60px'); 
$('.nvbr').css('margin-right','-60px');
		}
});

function validateForm_recaptcha() {

	document.getElementById('recaptcha').style.display = 'block'; 
}

    $('#contact_form').submit(function(event) {
        console.log('form submitted.');

        if (!grecaptcha.getResponse()) {
            console.log('captcha not yet completed.');
            //alert('captcha check if');

            event.preventDefault(); //prevent form submit
            grecaptcha.execute();
        } else {
            console.log('form really submitted.');
			// swal("Success!", "Thank you for submitting your information. We will get back to you shortly!", "success");
            //alert('captcha check else');
        }
    });

    onCompleted = function() {
       
        //$('#contact_form').submit();
        //alert('wait to check for "captcha completed" in the console.');
        //swal("Success!", "Thank you for submitting your information. We will get back to you shortly!", "success");
        /* setTimeout(function() {
                  swal({
                      title: "Success!",
                      text: "You have been successfully registered !",
                      type: "success"
                  }, function() {
                      window.location = "presolvdirect.php";
                  });
              }, 200);
              */
    }
    function check_sms_email(resp=''){
        
   $('#box-m').each(function()
{
 var email_display = $('#email_display').prop('disabled');
 var email_otp = $('#email_otp').prop('disabled');
 var mobile_display = $('#mobile_display').prop('disabled');
 var sms_otp = $('#sms_otp').prop('disabled');
    
    if ((email_display == true && email_otp == true) && (mobile_display == true && sms_otp == true))
    {

  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

  gtag('config', 'AW-757076966');
     gtag('event', 'conversion', {'send_to': 'AW-757076966/fXdMCMmE8pYBEOangOkC'});
        
    $(document).ready(function() {


    	console.log(resp);
    	console.log('prashant');


    	if(resp.user=='claimant'){
    		var resptext='Your account is now under Admin Review !';
    	}else if(resp.user=='arbit'){
    		var resptext='Email address and mobile number successfully verified.';
    	} else{
    		var resptext='Email address and mobile number successfully verified.';


    	}
        
    swal({

title: "Success",
            //text: "Email address and mobile number successfully verified.",
            text: resptext,
            type: "success"
   
}). then(function(result){
  window.location = "http://presolv360.com/user/notification_center";
  
 });
});
    }
}); 
  }



    
    
    
    
    
    
    
    

    function ajax_confirm_email(){
  
if(document.getElementById('email_otp').value.length==5)
{ //alert('function click on ajax confirm email');

document.getElementById('cancel').style.display='none';
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



	console.log(ajaxRequest.responseText);


    var respo=JSON.parse(ajaxRequest.responseText); 

                if(respo.resp == "true")
                {

                   
$("#email_display").attr("disabled", "disabled");
$("#email_otp").attr("disabled", "disabled");
$("#email_otp").css("display", "block");
$("#update_email").css("display", "none");
$("#check").css("display", "block");
check_sms_email(respo);


                }else if(respo.resp == "both") {
                   
                    //swal("Success!", "You have been successfully registered and logged in.", "success");                    
                    //window.location.href = '../index.php'; 
                    
                       	document.getElementById('update_email').style.display='none';
                       // document.getElementById('resend_email').style.display='none';
                        //document.getElementById('email_submit').style.display='none';
                        document.getElementById('cancel').style.display='none';
                        //document.getElementById('email_otp').style.display='none';
                        document.getElementById('check').style.display='block';
                        //document.getElementById('buttons').style.display='block';
                        $("#email_display").prop("disabled", true);
                        $("#email_otp").prop("disabled", true);
                        check_sms_email(respo);
                }
                 else if(respo.resp=="checkout")
                {
                   // location.href="../checkout.php";
                    document.getElementById('update_email').style.display='none';
                    document.getElementById('resend_email').style.display='none';
                    document.getElementById('email_submit').style.display='none';
                    document.getElementById('cancel').style.display='none';
                    document.getElementById('email_otp').style.display='none';
                    document.getElementById('check').style.display='block';
                    document.getElementById('buttons2').style.display='block';
check_sms_email(respo);
                }
else if(respo.resp == "right_sms")
                {
                   
                    $("#email_display").attr("disabled", "disabled");
$("#email_otp").attr("disabled", "disabled");
$("#email_otp").css("display", "block");
$("#update_email").css("display", "none");
$("#check").css("display", "block");
check_sms_email(respo);
                    
                }
                else if(respo.resp == "email_done"){
                	document.getElementById('cancel').style.display='none';
                }
                else if(respo.resp == "invalid"){


                            //swal({
                                //title: "Invalid",
                                //text: "Please enter valid OTP",
                                //type: "error"
                            //});
                            document.getElementById('cancel').style.display='block';


                }

                else
                {
                   document.getElementById('cancel').style.display='block';
setTimeout(function() {
                            swal({
                                title: "Error!",
                                text: "Something Wrongs!",
                                type: "error"
                            }, function() {
                                window.location.href = F+"user/presolv-secure";
                            });
                        }, 500);
                }

            }
        }

        var email_otp= document.getElementById('email_otp').value;
        var queryString="?email="+email_otp;
        ajaxRequest.open("GET", "ajax_confirm_email" + queryString, true);
        ajaxRequest.send(null);
}
else
{
  document.getElementById('cancel').style.display='block';
}
    }


    function ajax_confirmed_sms(){

if(document.getElementById('sms_otp').value.length==5)
{

    document.getElementById('cancel1').style.display='none';
          

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
 
var resp=JSON.parse(ajaxRequest.responseText); 


console.log(resp);

if(ajaxRequest.readyState == 4){

                if(resp.respo == "true")
                {

$("#mobile_display").attr("disabled", "disabled");
$("#sms_otp").attr("disabled", "disabled");
$("#sms_otp").css("display", "block");
$("#update_phone").css("display", "none");
$("#correct1").css("display", "block");

    check_sms_email(resp);                   

                }else if(resp.respo == "right_email")
                {
                   
                    $("#mobile_display").prop("disabled", true);
                        $("#sms_otp").prop("disabled", true);
$("#sms_otp").css("display", "block");
$("#update_phone").css("display", "none");
$("#correct1").css("display", "block");
check_sms_email(resp);
                    
                }else if(resp.respo == "both") {
                   
                    //swal("Success!", "You have been successfully registered and logged in.", "success");                    
                    //window.location.href = '../index.php'; 
                   
                       
                        document.getElementById('update_phone').style.display='none';
                        //document.getElementById('resend_phone').style.display='none';
                        //document.getElementById('email_submit').style.display='none';
                        //document.getElementById('cancel').style.display='none';
                        //document.getElementById('email_otp').style.display='none';
                        //document.getElementById('check').style.display='block';
                        //document.getElementById('buttons').style.display='block';
                        document.getElementById('correct1').style.display='block';
                        $("#mobile_display").prop("disabled", true);
                        $("#sms_otp").prop("disabled", true);
check_sms_email(resp);

                }
                 else if(resp.respo == "checkout")
                {
                   // location.href="../checkout.php";
                    document.getElementById('update_phone').style.display='none';
                    document.getElementById('resend_phone').style.display='none';
                    document.getElementById('email_submit').style.display='none';
                    document.getElementById('cancel').style.display='none';
                    document.getElementById('email_otp').style.display='none';
                    document.getElementById('check').style.display='block';
                    document.getElementById('buttons2').style.display='block';
check_sms_email(resp);
                }
                else if(resp.respo == "sms_done"){
                	document.getElementById('cancel1').style.display='none';
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
}else
{
                      document.getElementById('cancel1').style.display='block';

}


    }





$('#box-m').each(function()
{
 var email_display = $('#email_display').prop('disabled');
 var email_otp = $('#email_otp').prop('disabled');
 var mobile_display = $('#mobile_display').prop('disabled');
 var sms_otp = $('#sms_otp').prop('disabled');
   
    if (email_display == true && email_otp == true && mobile_display == true && sms_otp == true )
    {
    $(document).ready(function() {
    swal({
   icon: "success",
  title: "You have been successfully registered and logged in!",
  showConfirmButton: true,
  confirmButtonText: "OK",
  closeOnConfirm: false
}). then(function(result){
  window.location = DOMAIN +"user/notification_center";
 });
});
    }
});


    function change_mobile()
    {
        document.getElementById('mobile_display').removeAttribute('disabled');
        document.getElementById('update_phone').style.display = 'none';
        document.getElementById('resend_phone').style.display = 'block';
    }
    function resend_mobile()
    {
        document.getElementById('loader').style.display = 'block';
        var ajaxRequest;

        try {
            ajaxRequest = new XMLHttpRequest();
        }
        catch (e)
        {

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

                var res=JSON.parse(ajaxRequest.responseText);

                if(res.response=='true')
                {
                    document.getElementById('loader').style.display = 'none';
                    swal("Success!", "An OTP has been resent to your phone!", "success");

                   // alert("otp resend on ur phone");
                }
                else
                {
                document.getElementById('loader').style.display = 'none';
                   // alert("something went wrong do not enter + or space just 10 digit number");
                }

            }
        }

        var mobile_display= document.getElementById('mobile_display').value;
        var username_hidden= document.getElementById('username_hidden').value;
        var queryString="?mobile="+mobile_display+"&username="+username_hidden;
        ajaxRequest.open("GET", "resend_mobile" + queryString, true);
        ajaxRequest.send(null);

    }



    function change_email()
    {
        document.getElementById('email_display').removeAttribute('disabled');
        document.getElementById('update_email').style.display = 'none';
        document.getElementById('resend_email').style.display = 'block';
    }

    function resend_email()
    {
        document.getElementById('loader').style.display = 'block';
        var ajaxRequest;

        try {
            ajaxRequest = new XMLHttpRequest();
        }
        catch (e)
        {

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

                    var res=JSON.parse(ajaxRequest.responseText);

                if(res.response=='true')
                {


                    document.getElementById('loader').style.display = 'none';

                    swal("Success!", "An OTP has been resent to your email!", "success");

                    //alert("otp resend on ur email");
                }
                else
                {
                document.getElementById('loader').style.display = 'none';
                    //alert("something went wrong do not enter + or space just 10 digit number");
                }

            }
        }

        var email_display= document.getElementById('email_display').value;
        var username_hidden= document.getElementById('username_hidden').value;
        var queryString="?email="+email_display+"&username="+username_hidden;
        ajaxRequest.open("GET", "resend_email" + queryString, true);
        ajaxRequest.send(null);

    }
$(document).ready( function() { 

$('.functioncall').click( function() { 

	









swal({
								
				html: '<img src='+F+'public/images/disclaim.jpg>' ,
				input: 'text',
				inputPlaceholder: 'Enter a valid email ID',
				allowOutsideClick:false,
				//closeOnCancel:false,
				customClass:'swal-wide',
				showConfirmButton:true,
  confirmButtonText: 'Agree and Submit!',
				showCancelButton: true,
				inputValidator: function(value) {
					return new Promise(function(resolve, reject) {
						if (value) {
						    var email=/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
							if(email.test(value)){
					    $.ajax({
                        url:F+"functions/updateSummary.php", 
                        method:'post',
                        dataType:'JSON',
                        data:{email:value},
                        success:function (data) {

                            if(data.resp==true) {
                                resolve();

                            }
                            else{
                                location.reload();
                              
                            }
                        }
                    });   

							}
							else{
reject('Insert valid email!');						    }
						} else {
							reject('You need to insert a valid Email!');
						}
					});
				}
			}).then(function(result) {
				swal({
					type: 'success',
					html: 'Thank you! Click on the agreement you would like to download'
				}).then(function(result) {
                                        location.reload();
                                    })
			}).catch(swal.noop);

	});	
 });
$(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 3
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });
});
$(function() {

if($(window).width() == '375' || $(window).width() == '411'){
    $('body').css('overflow','hidden');
    $('.sliderx').css('width','103%');
    $('.fullwidth').css('width','103%');
    $('.navmobile').css('width','103%');
    $('.graymobile').css('width','103%');
    $('.testimonialsmobile').css('width','103%');
     $('.footermobile').css('width','103%');
     
      $('.btnser').css('margin-top','-40px');
    $('.selectContainer').removeClass('col-md-8');
    $('.selectContainer').addClass('col-xs-11');
  $('.selectContainer').css('margin-left','11px');
    $('.subtxtmobile').css('font-size','40px');
    $('.h3mobile').css('line-height','40px');
     $('.servc').css('margin-top','-407px');
      $('.slogan-mobile').css('top','13%');
       //$('.flex-active-slide').css('height','50%');
       $('.leftremovemobile').css('text-align','unset');
        $('.wrapbox').css('margin','15px 0 0 15px');
         $('.hiws').css('padding','0px');
         $('#root').css('margin-right','10px');
    $('.social1').css('margin-left','0px');
     $('#abts').css('margin-right','0px');
      $('.centermobile').css('text-align','center');
       $('.centermobile').css('margin-left','15px');
        $('.uploadmobile').css('float','left');
         $('.loginmobile').css('margin-left','10px');
        $('.btnmobile').css('margin-left','13px');
           $('.formmobile').css('margin-right','0px');
           $('.formmobile').css('margin-left','0px');
           $('.formmobile').css('font-size','0px');
           $('.txtmobile').css('margin-left','15px');
           
          // $('.removerow').removeClass('row');
         
          $('.removerow').css('margin-left','0px');
            $('.businessmobile').css('text-align','center');
           $('.abt1').css('margin-right','12px');
           $('.abt2').css('margin-right','12px');
           $('.abt3').css('margin-right','12px');
       
}
if($(window).height() == '812'){
     $('.slogan-mobile').css('top','20%');
}
if($(window).width() <= '768'){
 /*$('body').css('overflow','hidden');
    $('.sliderx').css('width','103%');
    $('.fullwidth').css('width','103%');
    $('.navmobile').css('width','103%');
    $('.graymobile').css('width','103%');
    $('.testimonialsmobile').css('width','103%');
     $('.footermobile').css('width','103%');*/
      $('.flex-active-slide').css('height','50%');
       $('.leftremovemobile').css('text-align','unset');
        $('.wrapbox').css('margin','15px 0 0 15px');
         $('.selectContainer').removeClass('col-md-8');
    $('.selectContainer').addClass('col-xs-11');
  $('.selectContainer').css('margin-left','11px');
$('.hiws').css('padding','0px');
  $('.social1').css('margin-left','0px');
   $('#root').css('margin-right','10px');
   $('#abts').css('margin-right','0px');
   $('.centermobile').css('text-align','center');
       $('.centermobile').css('margin-left','15px');
       $('.uploadmobile').css('float','left');
        $('.loginmobile').css('margin-left','10px');
        $('.formmobile').css('margin-right','0px');
           $('.formmobile').css('margin-left','0px');
           $('.formmobile').css('font-size','0px');
            $('.txtmobile').css('margin-left','15px');
             $('.btnmobile').css('margin-left','13px');
              $('.frgpmobile').css('margin-right','20px');
              $('.fgmobile').css('margin-top','0px');
               $('.txtmobile').css('margin-left','15px');
$('.abt1').css('margin-right','12px');
           $('.abt2').css('margin-right','12px');
           $('.abt3').css('margin-right','12px');

    

}
        });