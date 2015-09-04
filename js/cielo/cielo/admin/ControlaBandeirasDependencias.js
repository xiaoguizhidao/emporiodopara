function ControlaBandeirasDependencias(relacao_bandeiras_parcelas, url, total_quote ){
	
	total_quote = eval( '('+ total_quote + ')' );
	
	relacao_bandeiras_parcelas  = eval( '('+relacao_bandeiras_parcelas + ')');
	
	
	
	

	
	this.pressCard = function (field,card, url_skin){
		this.validaNumero(field, card, url_skin);
	}
	
	this.validaNumero  = function(field, card, url_skin){
		var v = field.value;
		var valor = field.value.match(/\d+/g);
		
		if (valor==null)
		field.value = '';
		else
		var type;
		if(v.substr(0,1) == 4){
		// VISA
		type = 'visa';

		} else if(v.substr(0,3) == 507){
		// AURA
		type = 'aura';
		
		} else if(v.substr(0,1) == 5){
		// MASTERCARD
		type = 'mastercard';
		
		} else if((v.substr(0,3) == 300 || v.substr(0,3) == 305 || v.substr(0,2) == 36)){
		// DINERS
		type = 'diners';
		
		}else if((v.substr(0,2) == 34 || v.substr(0,2) == 37)){
		// AMEX
			type = 'amex';
			
		}else{
			//document.getElementById('warning_none_selected_brand').style.display= "block";
			type=null;
			
		}
		
		
		
		this.changeButtonEffect(type, card, url_skin);
		field.value = valor ;
		
		
	}
	
	
	prev_clicked = new Array();
	
	this.changeButtonEffect = function (cartao, num_cart, url_skin) {
		/**
		* Guarda o objeto de imagem que acabou de ser clicado
		*/
			var img = document.getElementById('bandeira_cartao_'+cartao+'_'+num_cart);
		/**
		* Se este cartao foi o primeiro a ser clicado, seta como o imagem  anterior um objeto img sem origem de imagem
		* e sem id
		* Caso Contrario , pegue o que estava na fila
		*/
			if(prev_clicked[num_cart] == null){
				var imgClicked = document.createElement('img');
				imgClicked.setAttribute('src', "#");
				imgClicked.setAttribute('id', "");

				prev_clicked[num_cart] = imgClicked;
			}else{
			   
				var imgClicked = prev_clicked[num_cart];
			}
			
			/**
			*Se o cliente escolheu um cartao valido, faz o efeito de pressionar no cartao
			*atual e tira do anterior. Alem disso, seta o campo hidden responsavel por guardar a bandeira.
			*Caso contrario ele apenas ira tirar o efeito do cartao anterior.
			****/
			if(img){
				if(img.src != imgClicked.src){
					if(imgClicked.src != window.location+'#'){
						var old_clicked = document.getElementById(imgClicked.id);
						if(old_clicked == null)
							old_clicked = document.createElement('img');
						old_clicked.src = imgClicked.src;
						old_clicked.style.margin = '6px';
						
						if (imgClicked.id.replace('bandeira','radio')!=''){
							document.getElementById(imgClicked.id.replace('bandeira','radio')).checked = false;
						}
					}
					imgClicked.src = img.src;
					imgClicked.id = img.id;
					prev_clicked[num_cart] = imgClicked;
				}
				img.src = url_skin+'images/bandeiras/'+cartao+'_clicked.png';
				img.style.margin = '0px 2px 0px 0px';
				document.getElementById('radio_cartao_'+cartao+'_'+num_cart).checked = true;
				document.getElementById('bandeira_cartao_'+num_cart).value = cartao;
				
				/*
				 * Atualiza o numero de parcelas para este cartao
				 * */
				this.updateParcelas(cartao);			
				
				//if(ambiente == '1')
					//this.resetFields(num_cart);
			}else{
				/****
				 * Esconde o campo de parcelas
				 */
				
				this.hideParcelas();
				/****
				 * Se o id  da imagem clicada anterior for diferente de vazio, ou seja, tem um id (um cartao correspondente), 
				 * tira o efeitoi de clicado neste cartao  
				 */
				if(imgClicked.id!=""){
					imgClicked = prev_clicked[num_cart];
					document.getElementById(imgClicked.id).src=imgClicked.src;
					document.getElementById(imgClicked.id).style.margin='6px';
					document.getElementById('bandeira_cartao_'+num_cart).value = '';
					document.getElementById(imgClicked.id.replace('bandeira','radio')).checked = false;

				}
			}
			return false;			
	}
	
	

	/**
	 * Faz a atualizacao das parcelas , e dos seus juros caso exista
	 */
	
	this.updateParcelas =  function (bandeira){
		
		this.showParcelas();
		
		for ( var key in relacao_bandeiras_parcelas ){
			if (key==bandeira)  parcelas = relacao_bandeiras_parcelas[key];
		}
		
		
		
		
		jQuery('option', "#cartoes_parcelas_cartao_1").remove();
		select = jQuery("#cartoes_parcelas_cartao_1");
		var options = select.prop('options');

		
		this.Requisitavalorparcelas(bandeira, parcelas).done(
				function (resposta){
					jQuery('option',select).remove();
					resposta = eval( '('+ resposta + ')' );
					
					options[options.length] = new Option('Escolha sua opcao', '');
					for (i=0; i<resposta.parcelas.length; ++i){
					  label = resposta.parcelas[i].label + "x de " +
					  	resposta.parcelas[i].valor.toString() +" "+ resposta.parcelas[i].modalidade;
					  options[options.length] = new Option(label, resposta.parcelas[i].label);
					}
					
				}
		);
		
	}	
	
	/**
	 * Pede o valor das parcelas para determinada bandeira
	 */

	
	
	this.Requisitavalorparcelas=function (bandeira, parcelas){
		
		return jQuery.ajax({
			url : url+ '/cielo/pagamentoajax/calculaparcelasajax',
			type : 'POST',
			statusCode: {
				503:function (){
				
					alert('errado');
					
					
				}
			} ,
			data : { 
				num_parcelas : parcelas, 
				total: total_quote,
				bandeira: bandeira
			}
		});

	}		
		
	
	
	this.showParcelas =  function (){
		this.hideWarningParcelas(); //esconde a mensagem de escolher a bandeira
		jQuery("#cartoes_parcelas_cartao_1").css('display', 'block');
	}

	
	this.hideParcelas =  function (){
		this.showWarningParcelas();//mostra a mensagem de escolher a bandeira
		jQuery("#cartoes_parcelas_cartao_1").css('display', 'none');
	}	
	

	this.hideWarningParcelas=  function (){
		
		jQuery(".warning_select_brand_parcelas").css('display', 'none');
	}
	
	this.showWarningParcelas =  function (){
		
		jQuery(".warning_select_brand_parcelas").css('display', 'block');
	}		
}