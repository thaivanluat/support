$('#phone').keyup(function() {
	var isOldCustomer = $('input[name=OldCustomer]:checked', '#ticketForm').val()
	if(isOldCustomer == 1) {
		var email = $('#email').val();
		var phone = $('#phone').val();	
		if(email && phone) {
			$.ajax({
		    type: "POST",
		    url: 'test.php',
		    data: {
		    	email: email,
		    	phone: phone
		    },
		    dataType:"json",
		    success: function(data){
		    	if(data != 'Hop le') {
		        	$('#CustomerError').show();
		        }
		        else {
		    		$('#CustomerError').hide();    	
		        }
		    },
		    error: function(data){ 
		    	alert("Co loi xay ra");	
			    }
			});			
		}	
		else {
			$('#CustomerError').hide();  		
		}		
	}			
});

$('#email').keyup(function() {
	var isOldCustomer = $('input[name=OldCustomer]:checked', '#ticketForm').val()
	if(isOldCustomer == 1) {
		var email = $('#email').val();
		var phone = $('#phone').val();	
		if(email && phone) {
			$.ajax({
		    type: "POST",
		    url: 'test.php',
		    data: {
		    	email: email,
		    	phone: phone
		    },
		    dataType:"json",
		    success: function(data){
		    	if(data != 'Hop le') {
		        	$('#CustomerError').show();
		        }
		        else {
		    		$('#CustomerError').hide();    	
		        }
		    },
		    error: function(data){ 
		    	alert("Co loi xay ra");	
			    }
			});			
		}
		else {
			$('#CustomerError').hide();  
		}			
	}			
});

$("input[name='OldCustomer']").change(function() {
	var isOldCustomer = $('input[name=OldCustomer]:checked', '#ticketForm').val()
	if(isOldCustomer == 1) {
		var email = $('#email').val();
		var phone = $('#phone').val();	
		if(email && phone) {
			$.ajax({
		    type: "POST",
		    url: 'test.php',
		    data: {
		    	email: email,
		    	phone: phone
		    },
		    dataType:"json",
		    success: function(data){
		    	if(data != 'Hop le') {
		        	$('#CustomerError').show();
		        }
		        else {
		    		$('#CustomerError').hide();    	
		        }
		    },
		    error: function(data){ 
		    	alert("Co loi xay ra");	
			    }
			});			
		}
		else {
			$('#CustomerError').hide();  
		}			
	}	
});

$("#HideError").click(function() {
	$('#CustomerError').hide();
});