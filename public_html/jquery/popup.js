$('document').ready(function(){
    
    	$('#payBtn').on('click',function(e){
    		e.preventDefault();
    		$('#myModal').modal('toggle');
    
    	});
    
    	$('#continuebtn').on('click',function(){
    
    		$('form').submit();
    	});
    });    