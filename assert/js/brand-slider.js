$(document).ready(function(){

if($('.brands_slider').length)
{
var brandsSlider = $('.brands_slider');

brandsSlider.owlCarousel(
{
loop:true,
autoplay:true,
autoplayTimeout:5000,
nav:false,
dots:false,
autoWidth:true,

//items:2,
margin:42,

items: 5,
singleItem: true,

 responsiveClass:true,
    responsive:{
        0:{
            items:2,
            //nav:true
        },
        600:{
            items:2,
            //nav:false
        },
        1000:{
            items:5,
            //nav:true,
            //loop:false
        }
    },
});

if($('.brands_prev').length)
{
var prev = $('.brands_prev');
prev.on('click', function()
{
brandsSlider.trigger('prev.owl.carousel');
});
}

if($('.brands_next').length)
{
var next = $('.brands_next');
next.on('click', function()
{
brandsSlider.trigger('next.owl.carousel');
});
}
}


});