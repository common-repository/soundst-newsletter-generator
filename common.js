jQuery(document).ready(function($){
  
 
  	//hide unnecessary fields (button or input)
	function  ActiveColorRadioButton(idColorInput, idImageInput, idUpdateButton ){
		$('#'+idUpdateButton ).attr('disabled', 'disabled');
		$('#'+idImageInput).attr('disabled', 'disabled');
		$('#'+idImageInput).css("background-color","#F7F7F7");
		$('#'+idColorInput).removeAttr('disabled');
		$('#'+idColorInput).css("background-color","#fff");
		
	}
	
	function ActiveImageRadioButton(idColorInput, idImageInput, idUpdateButton ){
		$('#'+idUpdateButton).removeAttr('disabled');
		$('#'+idImageInput).removeAttr('disabled');
		$('#'+idImageInput).css("background-color","#fff");
		$('#'+idColorInput).attr('disabled', 'disabled');
		$('#'+idColorInput).css("background-color","#F7F7F7");
	}
	
	$(document).ready(function () {
		var value = $(":radio[name=\'keys[mbg]\']").filter(":checked").val();
		 if (value == '0') {
			 ActiveColorRadioButton('bgc_field', 'bgimg', 'bgimg_button' );
		 } else {
			 ActiveImageRadioButton('bgc_field', 'bgimg', 'bgimg_button' );
		 }
		 var value = $(":radio[name=\'keys[header_type]\']").filter(":checked").val();
		 if (value == '1') {
			 ActiveColorRadioButton('header_html_field', 'header_img', 'header_img_button' ); 
		 } else {
			 ActiveImageRadioButton('header_html_field', 'header_img', 'header_img_button' );
		 }
		 var value = $(":radio[name=\'keys[footer_type]\']").filter(":checked").val();
		 if (value == '1') {
			 ActiveColorRadioButton('footer_html_field', 'footer_img', 'footer_img_button' );
		 } else {
			 ActiveImageRadioButton('footer_html_field', 'footer_img', 'footer_img_button' );
		 }
	});
	
	  $("input[name=\'keys[mbg]\']").change(function(){          
		  if ($(this).val() == 0) {
			  ActiveColorRadioButton('bgc_field', 'bgimg', 'bgimg_button' ); 
	      }
	      else {
	    	  ActiveImageRadioButton('bgc_field', 'bgimg', 'bgimg_button' );
	      }                                                            
	  });
	  $("input[name=\'keys[header_type]\']").change(function(){          
		  if ($(this).val() == 1) {
			  ActiveColorRadioButton('header_html_field', 'header_img', 'header_img_button' );
	      }
	      else {
	    	  ActiveImageRadioButton('header_html_field', 'header_img', 'header_img_button' );
	      }                                                            
	  });
	  $("input[name=\'keys[footer_type]\']").change(function(){          
		  if ($(this).val() == 1) {
			  ActiveColorRadioButton('footer_html_field', 'footer_img', 'footer_img_button' );
	      }
	      else {
	    	  ActiveImageRadioButton('footer_html_field', 'footer_img', 'footer_img_button' );
	      }                                                            
	  });
 
  // date format
  $('#ng_start_date_field').datepicker({
	  dateFormat: 'mm/dd/yy',
  });
  $('#ng_end_date_field').datepicker({
	  dateFormat: 'mm/dd/yy',
  });
  
  
  // popup preview 
  function deselect() {
	    $(".pop").slideFadeToggle(function() { 
	       // $("#preview").removeClass("selected");
	    });    
	}

	$(function() {
	    $("#popupdelay").live('click', function() {
	            deselect();               
	        return false;
	    });

	    $(".close").live('click', function() {
	        deselect();
	        return false;
	    });
	});

	$.fn.slideFadeToggle = function(easing, callback) {
	    return this.animate({ opacity: 'toggle', height: 'toggle' }, "fast", easing, callback);
	} 
});

