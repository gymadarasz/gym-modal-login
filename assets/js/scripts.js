jQuery(document).ready(function($) {
    // Perform AJAX login on form submit
    $('form#login').on('submit', function(e){
			var checkbox_value = "";
			if ($('#rememberme').is(":checked")) {
				checkbox_value = $('form#login #rememberme').val();
			}
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajax_login_object.ajaxurl,
				data: {
					'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
					'username': $('form#login #username').val(), 
					'password': $('form#login #password').val(),
					'rememberme': checkbox_value,
					'security': $('form#login #security').val() },
				beforeSend:function(){
					$('#wp-submit').button('loading');
					$('body').modalmanager('loading');
				},	
				success: function(data){
					$('body').modalmanager('removeLoading');
					$('#wp-submit').button('reset');
					$('form#login div.status').html(data.message).fadeIn();
					if (data.loggedin == true){
						window.location.href = ajax_login_object.loginRedirectURL;
					}
				}
			});
        e.preventDefault();
    });
	// Perform AJAX register on form submit
	$('form#regform').on('submit', function(e) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajax_login_object.ajaxurl,
				data: { 
					'action': 'ajaxregister', //calls wp_ajax_nopriv_ajaxlogin
					'user_login': $('form#regform #user_login').val(), 
					'user_email': $('form#regform #user_email').val(), 
					'user_password': $('form#regform #user_password').val(), 
					'security2': $('form#regform #security2').val() },
				beforeSend:function(){
					$('#pass-submit').button('loading');
					$('body').modalmanager('loading');
				},						
				success: function(data){
					$('body').modalmanager('removeLoading');
					$('#pass-submit').button('reset');
					$('form#regform div.status').html(data.message).fadeIn();
					if (data.registered == true && ajax_login_object.registerRedirectURL!=''){
						window.location.href = ajax_login_object.registerRedirectURL;
					}					
				}
			});
		e.preventDefault();
	});
	// Perform AJAX resetpass on form submit
	$('form#passform').on('submit', function(e) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajax_login_object.ajaxurl,
				data: {
					'action'     : 	'ajaxlostpass', // Calls our wp_ajax_nopriv_ajaxlogin
					'lost_pass'   : 	$('form#passform #lost_pass').val(),
					'security3'   : 	$('form#passform #security3').val()
				},
				beforeSend:function(){
					$('#user-submit').button('loading');
					$('body').modalmanager('loading');
				},								
				success: function(data){
					$('body').modalmanager('removeLoading');
					$('#user-submit').button('reset');
					$('form#passform div.status').html(data.message).fadeIn();
				}
			});
			e.preventDefault();
	});
	// enable tooltip
	$(function () {
		$("[data-toggle='tooltip']").tooltip();
	});
	// remove status box
	$("#register_tab a,#lostpass_tab a,#login_tab a,.close").click(function(){
		$('div.status').slideUp();
	});
});