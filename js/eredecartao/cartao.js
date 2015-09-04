var eredecartao = {


	// aplica valor do cartão com ou sem juros
	aplicarvalor: function(element){
		//jQuery('#valor_cartao').val(valor);
		var option = jQuery(element).find(':selected');
		console.log(option.html());
		
		valor = option.attr('data-valor');
		juros = option.attr('data-juros');
		formatado = option.attr('data-formatado');

		jQuery('.flag_juros').val(juros);
		jQuery('#valor_cartao').val(valor);

		if(juros == 1){
			jQuery('.data-table tfoot tr td .price').last().html(formatado + ' <br/> <small style="font-size : 10px; font-weight :normal !important; color : #ff0000; ">Juros cartão aplicado</small>');
		}else{
			jQuery('.data-table tfoot tr td .price').last().html(formatado);
		}

	},


	// mostra o que é isso ? do código de segurança
	mostraroqi:function(){
		jQuery('#oqueeissodiv').slideDown();
	},

	// esconde o que é isso ? do código de segurança
	fecharoqi:function(){
		jQuery('#oqueeissodiv').slideUp();
	},
	
	selecionarbandeira:function(bandeira, imagem){

		jQuery('.flag img').each(function(){
			var attr_image = jQuery(this).attr('src');
			if(attr_image.indexOf('_pb') == -1)
				jQuery(this).attr('src', attr_image.replace('.png', '_pb.png'));
		});
		
		jQuery('.parcelas_li').hide();
		
		jQuery('.selecionar_bandeira').hide();

		if(bandeira == 'visa'){
			jQuery('#visa_flag img').attr('src', imagem);
			jQuery('#parcelas_visa').show();
		}else if(bandeira == 'mastercard'){
			jQuery('#mastercard_flag img').attr('src', imagem);
			jQuery('#parcelas_mastercard').show();
		}else if(bandeira == 'diners'){
			jQuery('#diners_flag img').attr('src', imagem);
			jQuery('#parcelas_diners').show();
		}else if(bandeira == 'hipercard'){
			jQuery('#hipercard_flag img').attr('src', imagem);
			jQuery('#parcelas_hipercard').show();
		}else if(bandeira == 'visa_debito'){
			jQuery('#visa_debito_flag img').attr('src', imagem);
		}else if(bandeira == 'mastercard_debito'){
			jQuery('#mastercard_debito_flag img').attr('src', imagem);
		}

		jQuery('.flag_geral').val(bandeira);
	},
	
	
	
	// Seleciona o tipo do cartao	
	selecionartipo:function(elemento){
		if(jQuery(elemento).val() == 1){ //crédito
			jQuery('.bandeiras_credito').show();
			jQuery('.bandeiras_debito').hide();
			jQuery('.codigo_seguranca').show();
		}else if(jQuery(elemento).val() == 2){// debito
			jQuery('.parcelas_li').hide();
			jQuery('.bandeiras_credito').hide();
			jQuery('.bandeiras_debito').show();
			jQuery('.codigo_seguranca').hide();
		}
	},
	
	
	// Seleciona o tipo do cartao	
	selecionartipoporvalor:function(valor){
		if(valor == 1){ //crédito
			jQuery('.bandeiras_credito').show();
			jQuery('.bandeiras_debito').hide();
			jQuery('.codigo_seguranca').show();
		}else if(valor == 2){// debito
			jQuery('.parcelas_li').hide();
			jQuery('.bandeiras_credito').hide();
			jQuery('.bandeiras_debito').show();
			jQuery('.codigo_seguranca').hide();
		}
	},


	detectarcartao:function(){
        var v = jQuery('#eredecartao_numerocartao').val();

        tipo = jQuery('#eredecartao_tipo').val();

		if(v == ''){
			jQuery('.selecionar_bandeira').show();
			
		}
        if(!isNaN(v)){
            var type;

            if(v.substr(0,1) == 4){
                // VISA
                if(tipo == 1){ // credito
					jQuery('#visa_flag').click();
                }else if(tipo == 2){
                	jQuery('#visa_debito_flag').click();
                }
                
            } else if(v.substr(0,1) == 5){
                // MASTERCARD
                if(tipo == 1){ // credito
					jQuery('#mastercard_flag').click();
                }else if(tipo == 2){
                	jQuery('#mastercard_debito_flag').click();
                }
            } else if((v.substr(0,3) == 300 || v.substr(0,3) == 305 || v.substr(0,2) == 36)){
                // DINERS
                jQuery('#diners_flag').click();
            } else if((v.substr(0,2) == 60 || v.substr(0,2) == 38)){
                // HIPERCARD
                jQuery('#hipercard_flag').click();
            } else if((v.substr(0,2) == 34 || v.substr(0,2) == 37)){
                // AMEX
                type = 'amex';
            }else{
            	jQuery('.flag img').each(function(){
					var attr_image = jQuery(this).attr('src');
					if(attr_image.indexOf('_pb') == -1)
						jQuery(this).attr('src', attr_image.replace('.png', '_pb.png'));
				});
            	jQuery('.flag_geral').val('');
            }

         
        }
    }
	
	
	

}