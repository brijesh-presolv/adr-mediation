$(document).ready(function(){
	
	$('.row-left ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('.row-left ul.tabs li').removeClass('current');
		$('.row-left .tab-content').removeClass('current');

		$(this).addClass('current');
		$(".row-left #"+tab_id).addClass('current');
	})
	
	$('.row-right ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('.row-right ul.tabs li').removeClass('current');
		$('.row-right .tab-content').removeClass('current');

		$(this).addClass('current');
		$(".row-right #"+tab_id).addClass('current');
	})

		
 document.addEventListener("DOMContentLoaded", function(event) { 


var acc = document.getElementsByClassName("accordion");
var panel = document.getElementsByClassName('panel');

for (var i = 0; i < acc.length; i++) {
    acc[i].onclick = function() {
        var setClasses = !this.classList.contains('active');
        setClass(acc, 'active', 'remove');
        setClass(panel, 'show', 'remove');

        if (setClasses) {
            this.classList.toggle("active");
            this.nextElementSibling.classList.toggle("show");
        }
    }
}

function setClass(els, className, fnName) {
    for (var i = 0; i < els.length; i++) {
        els[i].classList[fnName](className);
    }
}

});

})