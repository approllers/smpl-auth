$(document).ready(function() {
	$('.dropdown-menu').on('click', 'li a', function(){
		$('.search-criteria').html($(this).text() +' <i class="caret"></i>');
	});
	
	$('.user').on({
		click: function() {
			window.location = 'user.php?id='+ $(this).attr('data-user-id');
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
	
	$('select[name="num-per-page"]').on({
		change: function() {
			$('#num-per-page').submit();
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
	
	$('.random-password-button').on({
		click: function() {
			$('.random-password-input').val('');
			var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890';
			var pass = '';
			var length = 8;
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
			setTimeout(function() {
				$('.random-password-button').tooltip('hide');
				$('.random-password-button').tooltip('destroy');
			}, 500);
		}
	});
	
	$('.sortable th').on({
		mouseenter: function() {
			var text = $(this).html();
			var urlarray = window.location.href.split('?');
			var url = urlarray[0];
			if (urlarray.length > 1) {
				var append = [];
				var getarray = urlarray[1].split('&');
				for (i = 0; i < getarray.length; i++) {
					var get = getarray[i].split('=');
					if (get[0] != 'sort' && get[0] != 'page') {
						append.push(get[0] +'='+ get[1]);
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
			$(this).html('<a href="'+ url + append +'sort='+ text.toLowerCase() +'">'+ text +' <i class="fa fa-sort"></i></a>');
		},
		mouseleave: function() {
			var text = $(this).html();
			$(this).html($(text).text());
		}
	});
	
});
