 //---------------------------------------------------------------------------------
 // js name: ecal.login.js
 // porpuse: had jquery and ajax technic for handling login page
 //---------------------------------------------------------------------------------
 
  function do_login()
  {
	  var user = $('#user').val();
      var pass = $('#pass').val();
		  $('#messages').text('Authenticating...');
		  $.ajax ({
			  type: 'POST',
			  url: 'do_login.php',
			  data: {
				  username:user,
				  password:pass
			  },
			  success: function(feedback){
				  $('#messages').html(feedback);
			  }
		  });
  }

$(document).ready(function() {
	//--- this part is used for Clear button
	$('#clear').click(function() {
      $('#user').val('');
      $('#pass').val('');
      $('#messages').text('');
      $('#login-wrapper input').css('background-color','white');
      $('#user').focus();
    });
	//this part is used for login button
	$('#login').click(function() {
		var good = 0;
		$('#login-wrapper input[type]').each(function() {
			if(!$.trim($(this).val()).length) {
				$(this).css({'background-color':'rgb(250,192,196)'});
				$('#messages').text('مطمئین شوید که باکس های هایلات شده خالی نمیباشد.');
				return;
			}
			else{
				do_login();
			}
		});
	});
	//this part is used key events
	$(document.body).on('keypress', '#login-wrapper input', function(event) {
		if(event.which === 13) {
			var good = 0;
			$('#login-wrapper input').each(function() {
				if(!$.trim($(this).val()).length) {
					$(this).css({'background-color':'rgb(250,192,196)'});
					$('#messages').text('Ensure highlighted fields are not blank.');
					return;
				}
				else{
					do_login();
				}
			});
		}
		else {
			$(this).css({'background-color':'white'});
			$('#messages').text('');
		}
	});
});	