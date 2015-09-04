// API Google Merchant

var gmerchant = { 

	/*Retorna Url Base*/
	baseurl : function(param){
		myurl= (window.location);
        splited_url = (myurl.toString().split("/"));
        final_url = splited_url[0] + "//" + splited_url[2];
   		if(this.islocalhost(document.location.hostname))
   			final_url = splited_url[0] + "//" + splited_url[2] + "/" + splited_url[3];
   		else
   			final_url = splited_url[0] + "//" + splited_url[2];
	    return final_url+"/"+param;
	},

	/*Valida se é localhost*/
	islocalhost:function(hostname){
		if(hostname == 'localhost'){
			return true
		}else{
			exploded = hostname.split('.');
			for(var k = 0 ; k < exploded.length; k++){
				if(isNaN(exploded[k])) // se for numero
					return false;
			}
		}
		return true;
	},

	// roda atualização do XML
	run:function(url, turn, elem){
		button = jQuery(elem);
		button.css('display', 'none');
		jQuery('img.run-img', button.parent()).css('display', 'block');

		jQuery.ajax({
		  type: "GET",
		  url: url,
		  data: { turn: turn }
		}).done(function( msg ) {
		  	button.css('display', 'none');
		  	jQuery('img.run-img', button.parent()).css('display', 'none');
		  	jQuery('span.msg-ok-run', button.parent()).css('display', 'block');
			jQuery('.turn_list li .run-button:visible').eq(0).trigger('click');
		});
	},

	rungeneral:function(url){
		jQuery('#gmerchant-content .generating').css('display', 'block');
		jQuery('#message_result').css('display', 'none');
		jQuery('#btn_generate').css('display', 'none');
		jQuery.ajax({
		  type: "GET",
		  url: url
		}).done(function( msg ) {
			
			jQuery('#gmerchant-content .generating').css('display', 'none');
			jQuery('#message_result').css('display', 'block');
		});
	}
}