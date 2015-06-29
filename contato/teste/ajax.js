$(document).ready(function() {	
	$('#send').click(function() {
		$.ajax({
		  type: "POST",
		  url: "mail.php",
		  data: { name: $('#name').val(), from: $('#from').val(), message: $('#message').val(), subject: $('#subject').val()}
		}).done(function( msg ) {
		  alert( "Data Saved: " + msg );
		});
	});
});