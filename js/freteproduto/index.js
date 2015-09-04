var freteproduto = {

	init: function(){
		jQuery("#postcode").mask("99999-999");
	},

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

	
	 // Ao capturar a tecla enter...
    onEnter: function(evt){
        var key_code = evt.keyCode  ? evt.keyCode  :
                       evt.charCode ? evt.charCode :
                       evt.which    ? evt.which    : void 0;
        if (key_code == 13) {
            return true;
        }
    },

    // Captura tecla enter da tela de login
    enterrateevent: function(evt){
        if(freteproduto.onEnter(evt)){
            jQuery(".rate-button").click();
        }
    },

	collectrates : function() {
		var attrsshipping = jQuery(".attrshipping");
		// se o length dele for maior que 0 é que há atributos configuráveis
		var validate = true;
		// valida
		if (attrsshipping.length > 0) {
			for (var k = 0; k < attrsshipping.length; k++) {
				if (jQuery("#attribute" + attrsshipping.eq(k).html()).val() == '') {
					validate = false;
				}
			}
			if (!validate) {
				alert('Selecionar as opções configuráveis do produto');
			}
		}
		if (validate) {
			
			data_to = "product_id=" + jQuery(".product-id").val();
			data_to += "&postcode="+jQuery("#postcode").val();
			data_to += "&qty="+jQuery("#qty").val();
		
			getattr = freteproduto.getattributesvalue();
			
			if (getattr != ""){
				data_to += "&" + getattr;
			}
			
			jQuery("#fp-loader").css("display", "block");
			jQuery("#shipping-rates").css("display", "none");
			
			
			ajax_request = jQuery.ajax({
				url  : freteproduto.baseurl('freteproduto/index/index'),
				type : "POST",
				data : data_to
			});
			
			ajax_request.done(function(response){
				var obj = eval('(' + response + ')');
				jQuery("#shipping-rates li").remove();
				for(var k = 0; k < obj.length; k++){
					ul = jQuery("#shipping-rates");
					li = jQuery("<li></li>");
					li.html(obj[k]);
					ul.append(li);
					jQuery("#fp-loader").css("display", "none");
					jQuery("#shipping-rates").css("display", "block");
				}
			});
			
		}
	},

	getattributesvalue : function() {
		var attrsshipping = jQuery(".attrshipping");
		var v_string = '';
		for (var k = 0; k < attrsshipping.length; k++) {
			if (attrsshipping.length == (k + 1))
				v_string += "attr" + attrsshipping.eq(k).html() + "=" + jQuery("#attribute" + attrsshipping.eq(k).html()).val();
			else
				v_string += "attr" + attrsshipping.eq(k).html() + "=" + jQuery("#attribute" + attrsshipping.eq(k).html()).val() + "&";
		}
		return v_string;
	}
};

