var BoletoUltimateAdmin = {

	getUrlBase: function(param){
		myurl = (window.location);
	    splited_url = (myurl.toString().split("/"));
	    final_url = splited_url[0] + "//" + splited_url[2];
			if(this.isLocalhost(document.location.hostname))
				final_url = splited_url[0] + "//" + splited_url[2] + "/" + splited_url[3];
			else
				final_url = splited_url[0] + "//" + splited_url[2];
	    return final_url+"/"+param;
	},

	isLocalhost: function(hostname){
		if(hostname == 'localhost'){
			return true
		}else{
			exploded = hostname.split('.');
			for(var k = 0 ; k < exploded.length; k++){
				if(isNaN(exploded[k]))
					return false;
			}
		}
		return true;
	},

	init: function(){
		jQuery('#boleto_ultimate ul li .data-vencimento-input').mask('99/99/9999');
	},

	alterarVencimento: function(event){
		var boleto_ultimate = jQuery('#boleto_ultimate');
		var elem = jQuery('#boleto_ultimate ul li .data-vencimento-input');
		var button = jQuery('#boleto_ultimate ul li .alterar-vencimento');
		var mensagem_alterar = jQuery('#boleto_ultimate ul li .mensagem_alterar');
		var vencimento = jQuery('#boleto_ultimate ul li p .data-vencimento');
		jQuery.ajax({
            url: this.getUrlBase('boleto_ultimate/standard/alterarDataVencimento'),
            data: { order_id: elem.data('orderid'), vencimento: elem.val() },
            type: 'GET',
            beforeSend: function(){
                boleto_ultimate.css('opacity', '0.5')
            	boleto_ultimate.attr('disabled', 'disabled');
                mensagem_alterar.fadeOut('slow');
            }, 
            success: function(response){
            	var response = eval('(' + response + ')');
            	if(response.resultado == 'success'){
            		mensagem_alterar.html(response.mensagem);
	                vencimento.text(response.vencimento);
	                elem.val(response.vencimento);
            	}else{
            		mensagem_alterar.html(response.mensagem);
            		elem.val(vencimento.text());
            	}
            	mensagem_alterar.fadeIn('slow');
            	boleto_ultimate.css('opacity', '1.0')
	            boleto_ultimate.removeAttr('disabled', 'disabled');
            }
        });
	},

	enviarBoleto: function(order_id){
		var boleto_ultimate = jQuery('#boleto_ultimate');
		var mensagem_enviar = jQuery('#boleto_ultimate ul li .mensagem_enviar');
		jQuery.ajax({
            url: this.getUrlBase('boleto_ultimate/standard/enviarBoletoEmail'),
            data: { order_id: order_id },
            type: 'GET',
            beforeSend: function(){
            	boleto_ultimate.css('opacity', '0.5')
            	boleto_ultimate.attr('disabled', 'disabled');
                mensagem_enviar.fadeOut('slow');
            }, 
            success: function(response){
            	var response = eval('(' + response + ')');
                mensagem_enviar.html(response.mensagem);
                mensagem_enviar.fadeIn('slow');
                boleto_ultimate.css('opacity', '1.0')
            	boleto_ultimate.removeAttr('disabled', 'disabled');
            }
        });
	}

}