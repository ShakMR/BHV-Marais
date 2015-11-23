// FUNCTION TO CHECK MAIL

function checkMail(mail) { 
    var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
    return re.test(mail);
}


// FUNCTION TO SEND POST

function sendData(code, name, lastname, mail, info){
	$.ajax({
		url: 'process-data.php',
		method: "POST",
		data: { 
				CODE: code,
				NAME: name,
				LAST: lastname,
				MAIL: mail,
				INFO: info
			  }
	})
	.done( function( html ) {
		$( "form" ).empty().append( html );
	});	
}


// WHEN DOCUMENT LOADS

jQuery(document).ready(function($) {

	$(document).on('click', '.form-submit', function(event) {
		event.preventDefault();			

		var $code 		= $( ".form-field[name='code']" );
		var $name 		= $( ".form-field[name='name']" );
		var $lastname 	= $( ".form-field[name='lastname']" );
		var $mail 		= $( ".form-field[name='email']" );
		var $info		= $( ".form-field[name='info']" );

		if(!$info.checked){
			$info = "Wants more information";
		}else{
			$info = "Not checked"
		}

		if(!$code.val() || !$name.val() || !$lastname.val() || !$mail.val()){
			// alert("Field/s empty");
			var inputs = $(document).find('input:not(.form-submit)');
			$.each(inputs, function(i, value) {
				 if($(inputs[i]).val() == ""){
				 	$(this).addClass('error-field');
				 	$('.form-alert-general').show();
				 }
			});
		}else{
			if($code.val().length <= 6){
				alert("The code is not valid");
			}else{
				if(checkMail($mail.val())){
					sendData($code.val(), $name.val(), $lastname.val(), $mail.val(), $info);
				}else{
					$('.form-alert-password').show();
					$mail.addClass('error-field');
					$name.addClass('not-margin-bottom');
				}
			}
		}
	});
});