var CadastroCliente = {

		/*Retorna Url Base*/
		baseurl : function(param){
			myurl= (window.location);
	        splited_url = (myurl.toString().split("/"));
	        final_url = splited_url[0] + "//" + splited_url[2];
	   		if(CadastroCliente.islocalhost(document.location.hostname))
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


		// carrega mascaras na página de endereço
		init_addresspage: function(){

			if(jQuery.browser.msie){
				if(jQuery.browser.version != '7.0'){
	        		jQuery("#telephone").telefone();
	        		jQuery("#fax").telefone();
	    		}
	    	}else{
	    		jQuery("#telephone").telefone();
	        	jQuery("#fax").telefone();
	    	}

		},

		filladdressbycep: function(elem){
			cep = jQuery(elem).val();

	        loadtext = "Carregando valores..."	;
	    	
	   		jQuery("#street_1").val(loadtext);
			jQuery("#street_4").val(loadtext);
			jQuery("#city").val(loadtext);
	    	
			jQuery.ajax({
				url : this.baseurl("osc/onestepcheckout/preencherEndereco"),
				type : "POST",
				data : { cep : cep }
			}).done(function(response){
		
				if(response != null && response != "" && response != undefined){
					var result = eval('(' + response + ')');
					loadtext = "";
					if(result.resultado == 1){
						jQuery("#street_1").val(loadtext);
						jQuery("#street_4").val(loadtext);
						jQuery("#city").val(loadtext);
						jQuery("#street_1").val(result.tipo_logradouro + " " + result.logradouro );
						jQuery("#street_4").val(result.bairro);
						jQuery("#city").val(result.cidade);
						testando = jQuery('[name="'+result.uf+'"]', "#region_id").val(); 
						jQuery("#region_id").val(jQuery('[name="'+result.uf+'"]', "#region_id").val());
						
						region_name = jQuery("#region_id option:selected").attr("id");
						if(region_name != "" && region_name != null && region_name != undefined){
							jQuery("#region").val(region_name);
						}else{
							jQuery("#region").val("");
						}
					}else{
						loadtext = '';
						jQuery("#street_1").val(loadtext);
						jQuery("#street_4").val(loadtext);
						jQuery("#city").val(loadtext);
					}
				}
				

			});
		},

		init: function(){
			jQuery("#taxvat").mask("999.999.999-99");
		},

		validate_form:function(){
			if(!CadastroCliente.validate_taxvat()){
				tipopessoa = jQuery("#tipopessoa").val();
				if(tipopessoa == 'pf'){
					alert('CPF Inválido');
				}else if(tipopessoa == 'pj'){
					alert('CNPJ Inválido');
				}
				return false;
			}else{
				return true;
			}
			
		},

		validate_taxvat:function(){
			tipopessoa = jQuery("#tipopessoa").val();
			if(tipopessoa == 'pf'){
				valid = CadastroCliente.iscpf(jQuery("#taxvat").val());
			}else if(tipopessoa == 'pj'){
				valid = CadastroCliente.iscnpj(jQuery("#taxvat").val());
			}
			return valid;
		},


		/*Recebe eventos de seleção do tipo de pessoa*/
		tipopessoaLISTENER : function(element){
			tipo_pessoa = jQuery(element).val();
			if(tipo_pessoa == "pf"){
				jQuery('[for="firstname"]').html("Nome<em>*</em>");
				jQuery('[for="lastname"]').html("Sobrenome<em>*</em>");
				jQuery('[for="taxvat"]').html("CPF<em>*</em>");
				jQuery('#lastname').attr('name', 'lastname');
				jQuery("#taxvat").mask("999.999.999-99");
				jQuery('.field_pf').show();
				jQuery('.field_pj').hide();
			}else if(tipo_pessoa = "pj"){
				jQuery('[for="firstname"]').html("Razão Social<em>*</em>");
				jQuery('[for="lastname"]').html("Incrição Estadual<em>*</em>");
				jQuery('[for="taxvat"]').html("CNPJ<em>*</em>");
				jQuery('#lastname').attr('name', 'ie');
				jQuery('#lastname').val('');
				jQuery("#taxvat").mask("99.999.999/9999-99");
				jQuery('.field_pf').hide();
				jQuery('.field_pj').show();
			}
		},


		isentarie : function(elem){
			checkado = jQuery(elem).is(':checked');
			tipo_pessoa = jQuery("#tipopessoa").val();
			if(tipo_pessoa == "pj"){
				if(checkado){
					jQuery('#lastname').css('background', '#E5E5E5');
					jQuery('#lastname').css('color', '#E5E5E5');
					jQuery('#lastname').attr('disabled', true);
					jQuery('#lastname').val('ISENTO');
				}else{
					jQuery('#lastname').css('background', '#FFFFFF');
					jQuery('#lastname').css('color', '#000');
					jQuery('#lastname').attr('disabled', false);
					jQuery('#lastname').val('');
				}
			}
		},

		iscpf: function(cpf){
			exp = /\.|-/g;
		    cpf = cpf.toString().replace(exp, "");
		    if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
				return false;
			add = 0;
			for (i=0; i < 9; i ++)
				add += parseInt(cpf.charAt(i)) * (10 - i);
			rev = 11 - (add % 11);
			if (rev == 10 || rev == 11)
				rev = 0;
			if (rev != parseInt(cpf.charAt(9)))
				return false;
			add = 0;
			for (i = 0; i < 10; i ++)
				add += parseInt(cpf.charAt(i)) * (11 - i);
			rev = 11 - (add % 11);
			if (rev == 10 || rev == 11)
				rev = 0;
			if (rev != parseInt(cpf.charAt(10)))
				return false;
			return true;

		},

		iscnpj: function(cnpj){

			var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;

			if (cnpj.length == 0) {
				return false;
			}
			
			cnpj = cnpj.replace(/\D+/g, '');
			digitos_iguais = 1;

			for (i = 0; i < cnpj.length - 1; i++)
				if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
					digitos_iguais = 0;
					break;
				}
			if (digitos_iguais)
				return false;
			
			tamanho = cnpj.length - 2;
			numeros = cnpj.substring(0,tamanho);
			digitos = cnpj.substring(tamanho);
			soma = 0;
			pos = tamanho - 7;
			for (i = tamanho; i >= 1; i--) {
				soma += numeros.charAt(tamanho - i) * pos--;
				if (pos < 2)
					pos = 9;
			}
			resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
			if (resultado != digitos.charAt(0)){
				return false;
			}
			tamanho = tamanho + 1;
			numeros = cnpj.substring(0,tamanho);
			soma = 0;
			pos = tamanho - 7;
			for (i = tamanho; i >= 1; i--) {
				soma += numeros.charAt(tamanho - i) * pos--;
				if (pos < 2)
					pos = 9;
			}

			resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
			
			return (resultado == digitos.charAt(1));


		}




}