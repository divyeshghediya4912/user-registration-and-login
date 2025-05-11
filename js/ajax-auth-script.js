jQuery(document).ready(function ($) {
	// Display form from link inside a popup
	$('#pop_login, #pop_signup').live('click', function (e) {
		formToFadeOut = $('form#register');
		formtoFadeIn = $('form#login');
		if ($(this).attr('id') == 'pop_signup') {
			formToFadeOut = $('form#login');
			formtoFadeIn = $('form#register');
		}
		formToFadeOut.fadeOut(500, function () {
			formtoFadeIn.fadeIn();
		})
		return false;
	});
	// Close popup
	$(document).on('click', '.login_overlay, .close', function () {
		$('form#login, form#register').fadeOut(500, function () {
			$('.login_overlay').remove();
		});
		return false;
	});

	// Show the login/signup popup on click
	$('#show_login, #show_signup').on('click', function (e) {
		$('body').prepend('<div class="login_overlay"></div>');
		if ($(this).attr('id') == 'show_login') 
			$('form#login').fadeIn(500);
		else 
			$('form#register').fadeIn(500);
		e.preventDefault();
	});

	// Perform AJAX register on form submit
	$('form#register').on('submit', function (e) {
		if (!$(this).valid()) return false;
		$('p.status', this).show().text(ajax_auth_object.loadingmessage);
		action = 'ajaxregister';
		username = $('#signonname').val();
		firstname = $('#firstname').val();
		lastname = $('#lastname').val();
		password = $('#password').val();
		email = $('#email').val();
		security = $('#signonsecurity').val();
		ctrl = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_auth_object.ajaxurl,
			data: {
				'action': action,
				'username': username,
				'firstname': firstname,
				'lastname': lastname,
				'password': password,
				'email': email,
				'security': security
			},
			success: function (data) {
				$('p.status', ctrl).text(data.message);
				if (data.signedin == true) {
					document.location.href = ajax_auth_object.redirecturls;
				}
			}
		});
		e.preventDefault();
	});

	// Perform AJAX login on form submit
	$('form#login').on('submit', function (e) {
		if (!$(this).valid()) return false;
		$('p.status', this).show().text(ajax_auth_object.loadingmessage);
		action = 'ajaxlogin';
		username = 	$('form#login #username').val();
		password = $('form#login #password').val();
		email = '';
		security = $('form#login #security').val();
		ctrl = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_auth_object.ajaxurl,
			data: {
				'action': action,
				'username': username,
				'password': password,
				'email': email,
				'security': security
			},
			success: function (data) {
				$('p.status', ctrl).text(data.message);
				if (data.loggedin == true) {
					document.location.href = ajax_auth_object.redirecturl;
				}
			}
		});
		e.preventDefault();
	});

	// Perform AJAX profile on form submit
	$('form#profile').on('submit', function (e) {
		if (!$(this).valid()) return false;
		$('p.status', this).show().text(ajax_auth_object.loadingmessage);
		action = 'ajaxprofile';
		firstname = $('#firstname').val();
		lastname = $('#lastname').val();
		password = $('#password').val();
		email = $('#email').val();
		security = $('#signonsecurity').val();
		ctrl = $(this);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_auth_object.ajaxurl,
			data: {
				'action': action,
				'firstname': firstname,
				'lastname': lastname,
				'password': password,
				'email': email,
				'security': security
			},
			success: function (data) {
				$('p.status', ctrl).text(data.message);
				if (data.update == true) {
					//document.location.href = ajax_auth_object.redirecturl;
				}
			}
		});
		e.preventDefault();
	});

	// Client side form validation
	if (jQuery("#register").length) {
		jQuery("#register").validate({ 
			rules:{
				password2:{ equalTo:'#password' }	
			}
		});
	} else if (jQuery("#login").length) {
		jQuery("#login").validate();
	} else if (jQuery("#profile").length) {
		jQuery("#profile").validate({ 
			rules:{
				password2:{ equalTo:'#password' }	
			}
		});
	}
});