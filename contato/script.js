// 
//	jQuery Validate example script
//
//	Prepared by David Cochran
//	
//	Free for your use -- No warranties, no guarantees!
//

$(document).ready(function() {

	// Validate
	// http://bassistance.de/jquery-plugins/jquery-plugin-validation/
	// http://docs.jquery.com/Plugins/Validation/
	// http://docs.jquery.com/Plugins/Validation/validate#toptions
	
		
		$('#npedido').keypress(function(e) {
		  // allow digits only
		  if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
		  {
		    return false;
		  }
		});
	
		$('#subject').change(function() {
			if($('#subject option:selected').val() == 'pedido' || $('#subject option:selected').val() == 'reclelog') {
					$('#pedido-group').css('display', 'block');
			} else {
				$('#pedido-group').css('display', 'none');
			}
		});
	
		function resetForm($form) {
			$form.find('input:text, input:password, input:file, select, textarea').val('');
			$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
		}
	
		$('#contact-form').validate({
	    rules: {
	      name: {
	        minlength: 2,
	        required: true
	      },
	      email: {
	        required: true,
	        email: true
	      },
	      subject: {
	        required: true
	      },
	      message: {
	        minlength: 5,
	        required: true
	      }		  
	    },
		messages: {
			name: {
				required: 'O campo Nome é obrigatório.',
				minlength: 'O campo Nome deve ter no mínimo 2 caracteres.'
			},
			email: {
				required: 'O campo email é obrigatório.',
				email: 'Digite um email válido.'
			},
			message: {
				required: 'O campo mensagem é obrigatório.',
				minlength: 'O campo Mensagem deve ter no mínimo 5 caracteres.'
			}
		},
		/*errorPlacement: function(error, element) {
			error.appendTo(element);
		},*/
	    highlight: function(label) {
	    	$(label).closest('.control-group').addClass('error');
	    },
	    success: function(label) {
	    	label
				.addClass('valid')
	    		//.text('OK!').addClass('valid')
				//.addClass('valid')
				//.attr('class', 'valid')//.text('OK!')
	    		.closest('.control-group').addClass('success');
	    },
		submitHandler: function( form ){
				$("#send").attr('disabled', 'disabled'); 
				$.ajax({
				  type: "POST",
				  url: "mail.php",
				  data: { name: $('#name').val(), email: $('#email').val(), message: $('#message').val(), subject: $('#subject').val(), npedido: $('#npedido').val()},
				  success: function(data) {
					resetForm($('#contact-form'));
					$("#msg").ajaxSuccess(function(evt, request, settings){
						$('#msg').fadeIn('slow');
						setTimeout(function() {
							$('#msg').fadeOut('slow');
							$("#send").removeAttr('disabled');
						}, 5000);
					});
				  }
				}).done(function( msg ) {
					$('#pedido-group').css('display', 'none');
				  //alert( "Data Saved: " + msg );
				});
	    }
	  });
	  
	  
	  
}); // end document.ready