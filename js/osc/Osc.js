
/*
{
	Software : One Step Checkout
	Versão   : 3.6
	Autor    : Guilherme de Almeida Costa
	E-mail   : guilherme.costa@bis2bis.com.br | guilherme@live.ie
}
*/

// ============ Variáveis globais =========== 
var validacao_form_envio = false;
var validacao_form_cobranca = false;
var metodo_pagamento = ''; // metodo de pagamento
var cep_global_cobranca = ''
var cep_global_envio = '';
// ============ Variáveis globais =========== 

// ============== Operações Gerais ==============
var OscGeral = {

	// Inicializa o checkout
	inicializar:function(){
		this.gerarMascaras(); // carrega as macaras
		this.gerarValidacoes();// carrega validações dos forms
		OscTela.carregarScreenlocker(); // carrega o screenlocker
	},

	gerarValidacoes:function(){

		jQuery('#osc_field_billing_useasshipping').attr('checked', 'checked');
      
        var form_cobranca = document.getElementById('billing_address_form');
        var form_envio = document.getElementById('shippping_address_form');

        if(form_cobranca != null)
        	form_cobranca.reset();
        if(form_envio != null)
        	form_envio.reset();
        	
        jQuery.validator.addMethod('pass1', function(value, element) {
       		if(value.length < 6)
       			return false;
       		else
       			return true;
        }, 'Mínimo 6 dígitos');
        
        
        jQuery.validator.addMethod('pass1', function(value, element) {
       		if(value.length < 6)
       			return false;
       		else
       			return true;
        }, 'Mínimo 6 dígitos');


        jQuery.validator.addMethod('pass2', function(value, element) {
       		if(value != jQuery('.pass1').val() ){
       			return false;
       		}else{
       			return true;
       		}
        }, "Senhas diferentes");

		jQuery.validator.addMethod('brDate', function(value, element) {
		     //contando chars
		    if(value.length!=10) return false;
		    // verificando data
		    var data        = value;
		    var dia         = data.substr(0,2);
		    var barra1      = data.substr(2,1);
		    var mes         = data.substr(3,2);
		    var barra2      = data.substr(5,1);
		    var ano         = data.substr(6,4);
		    if(data.length!=10||barra1!="/"||barra2!="/"||isNaN(dia)||isNaN(mes)||isNaN(ano)||dia>31||mes>12)return false;
		    if((mes==4||mes==6||mes==9||mes==11) && dia==31)return false;
		    if(mes==2 && (dia>29||(dia==29 && ano%4!=0)))return false;
		    if(ano < 1900)return false;
		    var obj_date = new Date();
		    if(ano >= (obj_date.getFullYear() - 10))return false;
		    return true;
		}, "Informe uma data válida");  // Mensagem padrão


        /*Carrega o validador de Billing*/
        jQuery('#billing_address_form').validate({
    		rules: {
				'billing[email]': {
					email: true
			    },
			    'billing[cpf]': {  
	                cpf: 'both' 
	            },
	            'billing[cnpj]' : {
	            	cnpj : 'both'
	            },
	            'billing[dob]' : {
	            	brDate : true
	            }

			},
			submitHandler: function(form) {
				validacao_form_cobranca = true;
			}
    	});
        /*Carrega o validador de Billing */

        /*Carrega o validador de Shipping*/
        jQuery('#shipping_address_form').validate({
        	submitHandler: function(form){
        		validacao_form_envio = true;
        	}
        });	
        
        /*Carrega o validador de Shipping*/


	},

	// Realiza o procedimento de mascaras
	gerarMascaras:function(){
		// ====== Mascaras ======
		jQuery('#osc_field_billing_dob').mask('99/99/9999');
		if(jQuery.browser.msie){
			if(jQuery.browser.version != '7.0'){
        		jQuery('#osc_field_billing_telephone').telefone();
        		jQuery('#osc_field_billing_telephone').blur(function(){
		        	if(!OscCliente.validarTelefone(jQuery(this).val())){
		        		jQuery(this).val('');
		        	}
		        });
    		}
    	}else{
    		jQuery('#osc_field_billing_telephone').telefone();
			jQuery('#osc_field_billing_telephone').blur(function(){
        		if(!OscCliente.validarTelefone(jQuery(this).val())){
	        		jQuery(this).val('');
	        	}
	        });
    	}
		if(jQuery.browser.msie){
			if(jQuery.browser.version != '7.0'){
       			jQuery('#osc_field_shipping_telephone').telefone();
       			jQuery('#osc_field_shipping_telephone').blur(function(){
		        	if(!OscCliente.validarTelefone(jQuery(this).val())){
		        		jQuery(this).val('');
		        	}
		        });
       		}
       	}else{
       		jQuery('#osc_field_shipping_telephone').telefone();
       		jQuery('#osc_field_shipping_telephone').blur(function(){
	        	if(!OscCliente.validarTelefone(jQuery(this).val())){
		        		jQuery(this).val('');
		        }
        	});
       	}
		if(jQuery.browser.msie){
			if(jQuery.browser.version != '7.0'){
				jQuery('#osc_field_billing_cellphone').telefone();
				jQuery('#osc_field_billing_cellphone').blur(function(){
		        	if(!OscCliente.validarTelefone(jQuery(this).val())){
		        		jQuery(this).val('');
		        	}
		        });
			}
		}else{
			jQuery('#osc_field_billing_cellphone').telefone();
			jQuery('#osc_field_billing_cellphone').blur(function(){
	        	if(!OscCliente.validarTelefone(jQuery(this).val())){
		        	jQuery(this).val('');
		        }
	        });
		}

		if(jQuery.browser.msie){
			if(jQuery.browser.version != '7.0'){
				jQuery('#osc_field_shipping_cellphone').telefone();
				jQuery('#osc_field_shipping_cellphone').blur(function(){
		        	if(!OscCliente.validarTelefone(jQuery(this).val())){
		        		jQuery(this).val('');
		        	}
		        });
			}
		}else{
			jQuery('#osc_field_shipping_cellphone').telefone();
			jQuery('#osc_field_shipping_cellphone').blur(function(){
		       if(!OscCliente.validarTelefone(jQuery(this).val())){
		        	jQuery(this).val('');
		       }
		    });
		}

        jQuery('#osc_field_billing_cpf').mask('999.999.999-99');
        jQuery('#osc_field_billing_cnpj').mask('99.999.999/9999-99');
        jQuery('#osc_field_billing_cep').mask('99999-999');
        jQuery('#osc_field_shipping_cep').mask('99999-999');             
        /*Mascaras*/
		// ====== Mascaras ======
	},

	// retorna url base
	getUrlBase:function(param){
		myurl= (window.location);
        splited_url = (myurl.toString().split("/"));
        final_url = splited_url[0] + "//" + splited_url[2];
   		if(this.isLocalhost(document.location.hostname))
   			final_url = splited_url[0] + "//" + splited_url[2] + "/" + splited_url[3];
   		else
   			final_url = splited_url[0] + "//" + splited_url[2];
	    return final_url+"/"+param;
	},

	// valida se o ambiente é localhost
	isLocalhost:function(hostname){
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


	// salvar pedido
	salvarPedido: function(){
		validacao_cobranca =    jQuery('form#billing_address_form').valid();
		validacao_envio    =    jQuery('form#shipping_address_form').valid();
		validacao_metodo_form_pagamento = true;

		if(OscTela.isFormulario(metodo_pagamento)){
			formulario_pagamento = jQuery('.form_payment_'+metodo_pagamento);
			validacao_metodo_form_pagamento = formulario_pagamento.valid();
		}

		valida_selecaopagamento = true;
		if(metodo_pagamento == ''){
			OscPagamento.limparErroPagamento();
			OscPagamento.mostrarErroPagamento('Selecione um método de pagamento');
			valida_selecaopagamento = false;
		}

		if(validacao_envio && validacao_cobranca && validacao_metodo_form_pagamento && valida_selecaopagamento){

			OscTela.mostrarScreenlocker();
			OscAjax.atualizaEndereco().done(function(resposta){
				validacao_metodo_pagamento = OscPagamento.validar();
				validacao_metodo_pagamento.done(function(resposta){
					var resposta = eval('(' + resposta + ')');
					if(resposta.validacao_pagamento){
						OscPagamento.limparErroPagamento();
						validacao_metodo_envio = OscEnvio.validar();
						validacao_metodo_envio.done(function(resposta){
							if(resposta == 1){
								billing = OscCliente.getEnderecoCobranca();
								shipping = OscCliente.getEnderecoEnvio();
								parametros =  billing + '&' + shipping;
								OscAjax.salvarPedido(parametros).done(function(resposta){
									var resposta = eval('(' +  resposta  + ')');
									if(resposta.error){
										OscTela.mostrarErroGlobal(resposta.error);
									}else{
										if(resposta.success){
											if(resposta.redirect == null){
												window.location = OscGeral.getUrlBase('osc/success');
											}else{
												window.location = resposta.redirect;
											}
										}
									}
									OscTela.esconderScreenlocker();
								});
							}else{
								OscEnvio.limparErroEnvio();
								OscEnvio.mostrarErroEnvio('Selecionar um método de envio');
								OscTela.esconderScreenlocker();
							}
							
						});
					}else{
						if(resposta.error){
							OscPagamento.limparErroPagamento();
							OscPagamento.mostrarErroPagamento(resposta.error);
							OscTela.esconderScreenlocker();
						}
					}
				});
			});
			
		}
	}

};
	
// ============== Operações para obter e alterar informação do cliente na tela ==============
var OscCliente = {

	// Ínforma dados de cobrança
	getEnderecoCobranca:function(){
		return jQuery('#billing_address_form').serialize();
	},

	// Informa dados de envio
	getEnderecoEnvio:function(){
		return jQuery('#shipping_address_form').serialize();
	},

	selecionarEndereco:function(elemento, type){
		endereco_selecionado = jQuery(elemento).val();
		if(endereco_selecionado == 0 ){
			if(type == 'billing')
				jQuery('.form_billing_fs').show();
			else if(type == 'shipping')
				jQuery('.form_shipping_fs').show(); 
		}else{
			if(type == 'billing')
				jQuery('.form_billing_fs').hide();
			else if(type == 'shipping')
				jQuery('.form_shipping_fs').hide(); 
			OscTela.mostrarScreenlocker();
			OscAjax.atualizaEndereco().done(function(resposta){
				var resposta = eval('(' + resposta + ')');
				jQuery('.shipping-container').html(resposta.shipping);
	    		jQuery('.payment-container').html(resposta.payment);
	    		jQuery('.review-container').html(resposta.review);
				OscTela.esconderScreenlocker();
			});
		}
	},

	// muda o tipo de pessoa (fisica e juridica)
	mudaTipoPessoa:function(type){
		if(type == 'fisica'){
			jQuery('label.osc_label[for="osc_field_billing_name"]').html("Nome<em class='osc_required'>*</em>");
			jQuery('label.osc_label[for="osc_field_billing_lastname"]').html("Sobrenome<em class='osc_required'>*</em>");
			jQuery('.field_pj').hide();
			jQuery('.field_pf').show();
			jQuery('.osc_fisica').addClass('osc_selected');
			jQuery('.osc_juridica').removeClass('osc_selected');
		}else if(type == 'juridica'){
			jQuery('label.osc_label[for="osc_field_billing_name"]').html("Razão Social");
			jQuery('label.osc_label[for="osc_field_billing_lastname"]').html("Inscrição Estadual");
			jQuery('.field_pj').show();
			jQuery('.field_pf').hide();
			jQuery('.osc_fisica').removeClass('osc_selected');
			jQuery('.osc_juridica').addClass('osc_selected');
		}
	},

	// seta ie isento
	ieIsento:function(elemento){
		checkbox = jQuery(elemento).is(':checked');
		if(checkbox){
			jQuery("#osc_field_billing_ie").removeClass('required');
			jQuery("#osc_field_billing_ie").attr('disabled', 'disabled');
			jQuery("#osc_field_billing_ie").css('background', "#E2E2E2");
			jQuery("#osc_field_billing_ie").css('color', "#E6E6E6");
			jQuery("#osc_field_billing_ie").val('Isento');
		}else{
			jQuery("#osc_field_billing_ie").addClass('required');
			jQuery("#osc_field_billing_ie").removeAttr('disabled');
			jQuery("#osc_field_billing_ie").val('');
			jQuery("#osc_field_billing_ie").css('background', "#FFF");
			jQuery("#osc_field_billing_ie").css('color', "#000");
		}
	},

	// validar telefone
	validarTelefone:function(numero){
		if(numero.length == 14 || numero.length == 15){
			for(var k = 0; k < numero.length; k++){
				if(numero[k] != '(' && numero[k] != ' ' && numero[k] != ')' && numero[k] != '-' ){
					if(isNaN(numero[k])){
						return false;
					}
				}
			}
		}else{
			return false;
		}
		return true;
	}

    
};



// ============== Operações de envio ==============
var OscEnvio = {

	validar:function(){
		return OscAjax.validarEnvio();
	},
	// Mostra erro de envio
	mostrarErroEnvio:function(mensagem){
		html = jQuery('<li class="msg"></li>').html(mensagem)
		jQuery(html).hide().appendTo('#shipping_error').fadeIn();
	},
	// Limpa os erros de envio
	limparErroEnvio:function(){
		jQuery('#shipping_error').html('');
	}
};

// ============== Operações de pagamento ==============
var OscPagamento = {

	mudarMetodoPagamento:function(metodo){
		metodo_pagamento = metodo; 
		jQuery('#co-payment-form dd form ul').hide();
		formulario_pagamento = jQuery('.form_payment_'+metodo);
		if(OscTela.isFormulario(metodo)){
			formulario_pagamento.show();
			OscTela.mostrarScreenlocker();
			OscAjax.atualizarMetodoPagamentoFormulario(metodo).done(function(resposta){
				OscPagamento.limparErroPagamento();
				var resposta = eval('(' + resposta + ')');
				jQuery('.review-container').html(resposta.renderer.review);
				jQuery('.shipping-container').html(resposta.renderer.shipping);
				OscTela.esconderScreenlocker();
			});

		}else{
			if(formulario_pagamento.html()){
				formulario_pagamento.show();
			}
			var parametros = 'payment[method]='+metodo;
			OscTela.mostrarScreenlocker();
			OscAjax.atualizarMetodoPagamento(parametros).done(function(resposta){
				OscPagamento.limparErroPagamento();
				var resposta = eval('(' + resposta + ')');
				if(resposta.error){
					OscPagamento.mostrarErroPagamento(resposta.error);
				}
				jQuery('.review-container').html(resposta.renderer.review);
				jQuery('.shipping-container').html(resposta.renderer.shipping);
				OscTela.esconderScreenlocker();
			});
		}
	},

	// Mostra erro de pagamento
	mostrarErroPagamento:function(mensagem){
		html = jQuery('<li class="msg"></li>').html(mensagem)
		jQuery(html).hide().appendTo('#payment_error').fadeIn();
	},

	// Limpa os erros de pagamento
	limparErroPagamento:function(){
		jQuery('#payment_error').html('');
	},

	// seleciona metodo de pagamento ao carregar a pagina
	selecionarPagamento:function(){
		selecionado = jQuery("#checkout-payment-method-load dt input:checked");  
    	if(selecionado.length > 0){
  			metodo = selecionado.attr('id').replace('p_method_', '');
	  		if(OscTela.isFormulario(metodo)){
	  			formulario_pagamento = jQuery('.form_payment_'+metodo);
				formulario_pagamento.show();
  			}else{
  				
  			}
  		}
	},

	// valida / atualiza metodo de pagamento
	validar:function(){
		return OscAjax.validarPagamento();
	}

};





// ============== Operações do cupom de desconto  ==============

var OscCupom = {

	// Aplica o cupom de desconto
	usar: function(){
		OscTela.mostrarScreenlocker();
		OscCupom.limparErroCupom();
		OscCupom.limparSucessoCupom();
		uso_cupom = OscAjax.usarCupom();
		if(uso_cupom != false){
			uso_cupom.done(function(resposta){
				var resposta = eval('(' + resposta + ')');
				if(resposta.tipo){
					OscCupom.mostrarSuccessoCupom(resposta.msg);
					OscCupom.cupomExiste();
				}else{
					OscCupom.mostrarErroCupom(resposta.msg);
					OscCupom.cupomNExiste();
				}
				jQuery('.span_coupon').html(jQuery('.text_coupon').val());
				jQuery('.review-container').html(resposta.review);
				OscTela.esconderScreenlocker();
			});
		}else{
			OscTela.esconderScreenlocker();
		}
		
	},

	// remove cupom de desconto da sessão
	remover: function(){
		OscTela.mostrarScreenlocker();
		OscAjax.removerCupom().done(function(resposta){
			OscCupom.limparErroCupom();
			OscCupom.limparSucessoCupom();
			
			var resposta = eval('(' + resposta + ')');

			if(resposta.tipo){
				OscCupom.mostrarSuccessoCupom(resposta.msg);
				OscCupom.cupomExiste();
			}else{
				OscCupom.mostrarSuccessoCupom(resposta.msg);
				OscCupom.cupomNExiste();
			}
				
			jQuery('.review-container').html(resposta.review);

			OscTela.esconderScreenlocker();
		});
	},

	cupomExiste:function(){
		jQuery('.text_coupon').hide();
		jQuery('.button_coupon_use').hide();
		jQuery('.span_coupon').show();
		jQuery('.button_coupon_cancel').show();
	},

	cupomNExiste:function(){
		jQuery('.text_coupon').show();
		jQuery('.button_coupon_use').show();
		jQuery('.span_coupon').hide();
		jQuery('.button_coupon_cancel').hide();
	},

	// Mostra erro de cupom
	mostrarErroCupom:function(mensagem){
		html = jQuery('<li class="msg"></li>').html(mensagem)
		jQuery(html).hide().appendTo('#coupon_error').fadeIn();
		setTimeout(
			function(){
				OscCupom.limparErroCupom();
				OscCupom.limparSucessoCupom();
			},
			5000
		);
	},

	// Limpa os erros de cupom
	limparErroCupom:function(){
		jQuery('#coupon_error').html('');
	},

	// Mostra erro de cupom
	mostrarSuccessoCupom:function(mensagem){
		html = jQuery('<li class="msg"></li>').html(mensagem)
		jQuery(html).hide().appendTo('#coupon_success').fadeIn();
		setTimeout(
			function(){
				OscCupom.limparErroCupom();
				OscCupom.limparSucessoCupom();
			},
			5000
		);
	},

	// Limpa os erros de cupom
	limparSucessoCupom:function(){
		jQuery('#coupon_success').html('');
	}

};

// ============== Operações na tela ==============
var OscTela = {

	toggleFormularioEnvio:function(){
		OscTela.mostrarScreenlocker();
		var check = jQuery("#osc_field_billing_useasshipping").is(':checked');
		if(check){
			jQuery("#div_shipping_address_form").hide();
		}else{
			jQuery("#div_shipping_address_form").show();
		}
		OscAjax.atualizaEndereco().done(function(resposta){
			var resposta = eval('(' + resposta + ')');
			jQuery('.shipping-container').html(resposta.shipping);
    		jQuery('.payment-container').html(resposta.payment);
    		jQuery('.review-container').html(resposta.review);
			OscTela.esconderScreenlocker();
		});
	},

	carregarScreenlocker:function(){
		jQuery("a#mostrarscreenlocker").fancybox({ 
			'autoDimensions' : false , 
			'scrolling' : 'no',    
			keys : {
		    	close  : null
		    }, 
		    helpers : { 
		  		overlay : { closeClick: false }
	 	    }  
		});
	},

	mostrarScreenlocker:function(){
		jQuery('a#mostrarscreenlocker').click();
	},


	// Insere um overlay transparente para bloquear o cliente
	bloquearTela:function(){
		jQuery('body').css('overflow', 'hidden');
		jQuery('#loading-overlay').show();
	},

	// Libera a tela para o cliente
	liberarTela:function(){
		jQuery('body').css('overflow', 'auto');
		jQuery('#loading-overlay').hide();
	},

	esconderScreenlocker:function(){
		jQuery.fancybox.close();
	},

	// verifica se o metodo de pagamento necessida de formulário para preenchimento
	isFormulario:function(metodo){
		formulario_pagamento = jQuery('#payment_form_'+metodo);
		if(formulario_pagamento.html() != null && (jQuery('#payment_form_' +metodo+ ' input').length > 0 || jQuery('#payment_form_' +metodo+ ' select').length > 0)   ){
			return true; // é formulário 
		}else{
			return false; // não é formulário
		}
	},

	mostrarMiniaturaProduto: function(elem){
		parent_elem = jQuery(elem).parent();
		jQuery('.hover-img', parent_elem).stop(true, true).fadeIn('fast');
	},
	
	esconderMiniaturaProduto: function(elem){
		parent_elem = jQuery(elem).parent();
		jQuery('.hover-img', parent_elem).stop(true, true).fadeOut('fast');
	},

	mostrarErroGlobal: function(msg){
		jQuery('.alert').html(msg);
		jQuery('.alert').fadeIn();
	},

	esconderErroGlobal: function(){
		jQuery('.alert').fadeOut('slow');
	},

	// Desabilita botão do checkout
	desabilitarBotao: function(){
		jQuery('.btn-checkout').addClass('btn-checkout-disabled');
		jQuery('.btn-checkout').attr('disabled', 'disabled');
	},

	// Habilita botão do checkout
	habilitarBotao: function(){
		jQuery('.btn-checkout').removeAttr('disabled');
		jQuery('.btn-checkout').removeClass('btn-checkout-disabled');
	}


};


// ============== Operações Termo de Compromisso  ==============
var OscTermo = {

	//
	aceitar : function(){
		var accept_term = jQuery('#accept_term');
		if(accept_term.is(':checked')){
			OscTela.habilitarBotao();
		}else{
			OscTela.desabilitarBotao();
		}
	}

};


// ============== Operações Termo de Compromisso ==============


// ============== Operações com Ajax ==============
var OscAjax = {

	// atualiza endereço no sistema (quote)
	atualizaEndereco:function(){
		var endereco_billing  =     OscCliente.getEnderecoCobranca(); // obtem informações dos dados de cobrança
		var endereco_shipping =     OscCliente.getEnderecoEnvio(); // obtem informações dos dados de envio
		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/atualizarEndereco'),
			type : 'POST',
			data : endereco_billing + '&' + endereco_shipping
		});
	},


	///valida se existe ou não metodo de pagamento
	validarPagamento : function(){
		formulario_pagamento = jQuery('.form_payment_'+metodo_pagamento);
		dados_formulario = formulario_pagamento.serialize();
		dados_formulario += '&payment[method]='+metodo_pagamento;
		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/validarPagamento'),
			type : 'POST',
			data : dados_formulario
		});
	},

	///valida se existe ou não metodo de pagamento
	validarEnvio : function(){
		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/validarEnvio'),
			type : 'POST'
		});
	},

	// Usar cupom
	usarCupom: function(){
		var cupom = jQuery('#coupon').val();
		if(cupom != ''){
			return jQuery.ajax({
				url : OscGeral.getUrlBase('osc/onestepcheckout/aplicarCupom'),
				type : 'POST',
				data : { coupon_code : cupom }
			});
		}else{
			OscCupom.mostrarErroCupom('Digitar um cupom válido');
			return false;
		}
	},

	// Remover cupom
	removerCupom: function(){
		var cupom = jQuery('#coupon').val();
		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/aplicarCupom'),
			type : 'POST',
			data : { coupon_code : cupom, remove : 1  }
		});
	},

	//preenche os campos de endereço acordo com informações oriundas do webservice
	preencherEndereco:function(elem){
		cep = jQuery(elem);

        if(cep.val().length == 9){

            elem_id = jQuery(elem).attr('id');

            tipo_endereco = elem_id.replace('osc_field_', ''); //billing ou shipping
            tipo_endereco = tipo_endereco.replace('_cep', ''); //billing ou shipping

            if(tipo_endereco == 'billing')
                cep_global = cep_global_cobranca
            else if(tipo_endereco == 'shipping')
                cep_global = cep_global_envio;

            if(cep.val() != cep_global){

                if(tipo_endereco == 'billing')
                    cep_global_cobranca = cep.val();
                else if(tipo_endereco == 'shipping')
                    cep_global_envio =  cep.val();

                campo_cidade = jQuery('#osc_field_'+tipo_endereco+'_city');
                campo_endereco = jQuery('#osc_field_'+tipo_endereco+'_address');
                campo_bairro = jQuery('#osc_field_'+tipo_endereco+'_neighborhood');
                campo_estado = jQuery('#osc_field_'+tipo_endereco+'_region');

                // espera o done da função update address
                OscTela.mostrarScreenlocker();
                this.atualizaEndereco().done(function(retorno){
                    var retorno = eval('(' + retorno + ')');

                    jQuery.ajax({
                        url : OscGeral.getUrlBase('osc/onestepcheckout/preencherEndereco'),
                        type : 'POST',
                        data : { cep : cep.val() }
                    }).done(function(resposta){
                        if(resposta.replace(resposta[0], '') == ''){
                            campo_cidade.val('');
                            campo_endereco.val('');
                            campo_bairro.val('');
                            campo_estado.val('');
                        }else{
                            var resposta = eval('(' + resposta + ')');

                            bairro = resposta.bairro;
                            cidade = resposta.cidade;
                            logradouro = resposta.logradouro;
                            resultado = resposta.resultado;
                            tipo_logradouro = resposta.tipo_logradouro;
                            uf = resposta.uf;

                            if(resultado == 1){
                                campo_cidade.val(cidade);
                                campo_endereco.val(tipo_logradouro + ' ' + logradouro);
                                campo_bairro.val(bairro);
                                campo_estado.val(jQuery('[name="'+uf+'"]', '#osc_field_'+tipo_endereco+'_region').val());
                            }else{
                                campo_cidade.val('');
                                campo_endereco.val('');
                                campo_bairro.val('');
                                campo_estado.val('');
                            }
                        }

                        jQuery('.shipping-container').html(retorno.shipping);
                        jQuery('.payment-container').html(retorno.payment);
                        jQuery('.review-container').html(retorno.review);

                        // resposta dos métodos de pagamento
                        OscTela.esconderScreenlocker();

                    });

                });

            }

        }else{
            cep.val('');
        }
	
	},

	// atualiza metodo de envio
	atualizarMetodoEnvio:function(metodo){
		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/atualizarMetodoEnvio'),
			type : 'POST',
			data : { method : metodo }
		})
	},

	atualizarMetodoPagamentoFormulario:function(metodo){

		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/atualizarMetodoPagamentoFormulario'),
			type : 'POST',
			data : { 'metodo' : metodo }
		});
	},

	atualizarMetodoPagamento:function(parametros){
		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/atualizarMetodoPagamento'),
			type : 'POST',
			data : parametros
		});
	},

	// salva o pedido
	salvarPedido:function(parametros){
		return jQuery.ajax({
			url : OscGeral.getUrlBase('osc/onestepcheckout/salvarPedido'),
			type : 'POST',
			data : parametros
		})
	}

};