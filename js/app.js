$(document).ready(function() {
	$('.dropdown-menu').on('click', 'li a', function(){
		$('.search-criteria').html($(this).text() +' <i class="caret"></i>');
	});
	
	$('.user').on({
		click: function() {
			window.location = 'users.php?id='+ $(this).attr('data-user-id');
		}
	});
	
	$('.create-user').on({
		click: function() {
			window.location = 'createuser.php';
		}
	});
	
	$('.password-reset-link').on({
		click: function() {
			$('#password-reset-link').submit();
		}
	});
	
	$('.send-random-password').on({
		click: function() {
			$('#send-random-password').submit();
		}
	});
	
	$('.force-logout').on({
		click: function() {
			$('#force-logout').submit();
		}
	});
	
	$('.delete-user').on({
		click: function() {
			$('#delete-user').submit();
		}
	});
	
	$('.edit-user-active-checkbox').bootstrapSwitch({
		'labelWidth': 0,
		'onText': 'Active',
		'offText': 'Inactive',
		'onColor': 'success',
		'offColor': 'danger'
	});
	
	$('.notify-new-user-checkbox').bootstrapSwitch({
		'labelWidth': 0,
		'onText': 'Send email with credentials',
		'offText': 'Do not send credentials',
		'onColor': 'primary',
		'offColor': 'danger'
	});
	
	$('.send-mail-method-checkbox').bootstrapSwitch({
		'labelWidth': 0,
		'onText': 'SMTP',
		'offText': 'PHP',
		'onColor': 'warning',
		'offColor': 'primary'
	});
	
	if ($('.send-mail-method-checkbox').is(':checked')) {
		$('.smtp-section').show();
	}
	else { $('.smtp-section').hide(); }
	
	$('.send-mail-method-checkbox').on({
		'switchChange.bootstrapSwitch': function() {
			if ($(this).is(':checked')) {
				$('.smtp-section').slideDown();
			}
			else { $('.smtp-section').slideUp(); }
		}
	});
	
	$('.send-welcome-email-checkbox').bootstrapSwitch({
		'labelWidth': 0,
		'onText': 'Send welcome email',
		'offText': 'Do not send welcome email',
		'onColor': 'primary',
		'offColor': 'warning'
	});
	
	if ($('.send-welcome-email-checkbox').is(':checked')) {
		$('.welcome-email-section').show();
	}
	else { $('.welcome-email-section').hide(); }
	
	$('.send-welcome-email-checkbox').on({
		'switchChange.bootstrapSwitch': function() {
			if ($(this).is(':checked')) {
				$('.welcome-email-section').slideDown();
			}
			else { $('.welcome-email-section').slideUp(); }
		}
	});
	
	$('.random-password-button').on({
		click: function() {
			$('.random-password-button').tooltip('destroy');
			$('.random-password-input').val('');
			var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890';
			var pass = '';
			var min = 6;
			var max = 8;
			var length = Math.random() * (max - min) + min;
			for (var x = 0; x < length; x++) {
				var i = Math.floor(Math.random() * chars.length);
				pass += chars.charAt(i);
			}
			$('.random-password-input').val(pass);
			$('.random-password-button').tooltip({
				'title': 'New password: '+ pass,
				'trigger': 'manual'
			});
			$('.random-password-button').tooltip('show');
		},
		mouseleave: function() {
			$('.random-password-button').tooltip('destroy');
		}
	});
	
	$('.sortable th').on({
		mouseenter: function() {
			var text = $(this).text().trim().split(' ').join('_');
			var urlarray = window.location.href.split('?');
			var url = urlarray[0];
			if (urlarray.length > 1) {
				var append = [];
				var getarray = urlarray[1].split('&');
				for (i = 0; i < getarray.length; i++) {
					var get = getarray[i].split('=');
					if (get[0] != 'sort' && get[0] != 'page' && get[0] != 'num-per-page') {
						append.push(get[0] +'='+ get[1].trim().split(' ').join('_'));
					}
				}
				if (append.length > 1) {
					append = '?'+ append.join('&') +'&';
				}
				else {
					append = '?'+ append.join('&');
				}
			}
			else { var append = '?'; }
			$(this).html('<a href="'+ url + append +'sort='+ text.toLowerCase() +'"><i class="fa fa-sort"></i> '+ text.split('_').join(' ') +'</a>');
		},
		mouseleave: function() {
			var text = $(this).html();
			$(this).html('<i class="fa fa-sort"></i> '+ $(text).text());
		}
	});
	
	$('select[name="num-per-page"]').on({
		change: function() {
			var urlarray = window.location.href.split('?');
			var url = urlarray[0];
			if (urlarray.length > 1) {
				var append = [];
				var getarray = urlarray[1].split('&');
				for (i = 0; i < getarray.length; i++) {
					var get = getarray[i].split('=');
					if (get[0] != 'sort' && get[0] != 'page' && get[0] != 'num-per-page') {
						append.push(get[0] +'='+ get[1].trim().split(' ').join('_'));
					}
				}
				if (append.length > 1) {
					append = '?'+ append.join('&') +'&';
				}
				else {
					append = '?'+ append.join('&');
				}
			}
			else { var append = '?'; }
			window.location = url + append +'num-per-page='+ $(this).val();
		}
	});
	
	if ($('#lg').is(':visible')) {
		$('#header-tabs').removeClass().addClass('right-nav-tabs-lg');
		$('#header-tabs').insertBefore('h1.page-header');
	}
	if ($('#md').is(':visible')) {
		$('#header-tabs').removeClass().addClass('right-nav-tabs-md');
		$('#header-tabs').insertBefore('h1.page-header');
	}
	if ($('#sm').is(':visible')) {
		$('#header-tabs').removeClass().addClass('right-nav-tabs-sm');
		$('#header-tabs').insertBefore('h1.page-header');
	}
	if ($('#xs').is(':visible')) {
		$('#header-tabs').removeClass().addClass('right-nav-tabs-xs');
		$('#header-tabs').insertAfter('h1.page-header');
	}
	$(window).resize(function() {
		if ($('#lg').is(':visible')) {
			$('#header-tabs').removeClass().addClass('right-nav-tabs-lg');
			$('#header-tabs').insertBefore('h1.page-header');
		}
		if ($('#md').is(':visible')) {
			$('#header-tabs').removeClass().addClass('right-nav-tabs-md');
			$('#header-tabs').insertBefore('h1.page-header');
		}
		if ($('#sm').is(':visible')) {
			$('#header-tabs').removeClass().addClass('right-nav-tabs-sm');
			$('#header-tabs').insertBefore('h1.page-header');
		}
		if ($('#xs').is(':visible')) {
			$('#header-tabs').removeClass().addClass('right-nav-tabs-xs');
			$('#header-tabs').insertAfter('h1.page-header');
		}
	});
	
	$('.deactivate-user').on({
		click: function() {
			//clear notifications in modal
			//disable deactivate button
			$.ajax({
				url: 'ajax.php',
				method: 'post',
				data: { 'action': 'deactivate-user', 'id': $('.deactivate-user').attr('data-user-id') },
				dataType: 'json',
				success: function(response) {
					$('#resultsTable tr:not(:first)').remove();
					$('#resultsTable').append(response);
					if ($('#resultsTable tr td#noResults').length > 0) {
						$('#numResults').html('0');
					}
					else {
						$('#numResults').html($('#resultsTable tr:not(:first)').length);
					}
				}
			});
		}
	});
});
