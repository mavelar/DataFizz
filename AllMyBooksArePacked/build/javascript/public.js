console.info("public loaded");

require.config({
  baseUrl:								'/assets/js/',
  paths: {
  	'async': 							'lib/require/async',
    'jquery':							'lib/jquery.min',
		'jquery-migrate':			'lib/jquery-migrate.min',
		'bootstrap':					'lib/bootstrap.min',
		'jqValidate':					'jquery-plugins/jquery-validate.min',
		'jqEasing':						'jquery-plugins/jquery.easing.min',
		'jqCookie':						'jquery-plugins/jquery.cookie.min',
		'modGmaps': 					'modules/gmaps.min'
  },
	shim: {
		'jquery': {
			exports: '$',
		},
		'jquery-migrate':			['jquery'],
		'bootstrap':					['jquery'],
		'jqValidate': 				['jquery'],
		'jqEasing': 					['jquery'],
		'jqCookie': 					['jquery']
	}
});

require(['jquery','modGmaps','jquery-migrate','bootstrap','jqValidate','jqEasing','jqCookie'], function($,gmaps) {
	"use strict"; // Start of use strict
	
	$(document).ready(function() {
		$.validator.setDefaults({
	    highlight: function(element) {
	      $(element).closest('.form-group,.input-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	      $(element).closest('.form-group,.input-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	    }
		});

		$(window).scroll(function() {
		    if ($(".navbar").offset().top > 50) {
		        $(".navbar-fixed-top").addClass("top-nav-collapse");
		    } else {
		        $(".navbar-fixed-top").removeClass("top-nav-collapse");
		    }
		});

		$(function() {
		    $('a.page-scroll').bind('click', function(event) {
		        var $anchor = $(this);
		        $('html, body').stop().animate({
		            scrollTop: $($anchor.attr('href')).offset().top
		        }, 1500, 'easeInOutExpo');
		        event.preventDefault();
		    });
		});

		if($('section.google-map').length>0)
			gmaps.init();

		$('.open-docs').on('click', function() {
			var $this = $(this);
			var filename = $this.data('filename');

			if(!$.cookie('registered')) {
				$('#registrationModal').modal();

				$('#registrationModal').on('hidden.bs.modal', function() {
					// openFile(filename);
				})
			} else {
				openFile(filename);
			}
		})

		$('#registration-form').validate();

		$('.send-registration').on('click', function() {
			if($('#registration-form').valid()) {
				$('#registration-form').submit();

				$.cookie('registered',true,{ expires: 7});

				$('#registrationModal').modal('hide');
			}
		});

		$('#contact-form').validate();
		$('#mc-embedded-subscribe-form').validate();
	});
});

openFile = function(filename) {
	window.open('/assets/files/'+filename,'_blank');
}