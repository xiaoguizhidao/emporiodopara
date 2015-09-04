// Função que resgata a URL Base, tanto para lojas no servidor ou local
function baseurl(param){
	myurl= (window.location);
	splited_url = (myurl.toString().split("/"));
	final_url = splited_url[0] + "//" + splited_url[2];
	if(islocalhost(document.location.hostname))
		final_url = splited_url[0] + "//" + splited_url[2] + "/" + splited_url[3];
	else
		final_url = splited_url[0] + "//" + splited_url[2];
	return final_url+"/"+param;
};

// Função que verificar se é localhost, usada na função "baseurl"
function islocalhost(hostname){
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
};

// Soma básica das variáveis formatando com vígula e casas decimais
function somar_vars(original, acrescimo, formata){
	total = (parseFloat(original)+parseFloat(acrescimo)).toFixed(2);

	if(formata)
		total = total.replace('.', ',');
		
	return total;
}

// Atualiza os valores totais, tanto o preço "De" sem desconto e o preço com desconto comprando os dois produtos
function updatevalores(tipo,obj){
	var valor1 = jQuery(obj).find('.antigo .update1').text().replace(',', '.');
	var valor2 = jQuery(obj).find('.antigo .update2').text().replace(',', '.');
	var soma   = somar_vars(valor1,valor2,false);

	// Soma dos preços e modificação sem o desconto
	jQuery(obj).find('.antigo del').html('R$ '+soma.replace('.', ','));

	desconto_valor = jQuery('.comprejunto .opcoes #desconto').val();
	desconto_tipo  = jQuery('.comprejunto .opcoes #tipodesconto').val();
	
	if(desconto_tipo == 'fixo'){
		desconto = (soma-desconto_valor).toFixed(2).replace('.', ',');
		jQuery(obj).find('.opcoes .formcomprejunto p.novo b').html('R$ '+desconto);
	} else if(desconto_tipo == 'porcent'){		
		porcent = desconto_valor/100;
		desconto = (parseFloat(soma)-parseFloat(soma*porcent)).toFixed(2).replace('.', ',');		
		jQuery(obj).find('.opcoes .formcomprejunto p.novo b').html('R$ '+desconto);
	}
}

jQuery(document).ready(function(){
	
	jQuery('.btnadd').click(function(e){
		len = jQuery(this).closest('.opcoes').find('.selectAttr').length;
		form = jQuery(this).closest('.formcomprejunto');
		submit = true;
		
		if(len > 0){		
			jQuery(this).closest('.opcoes').find('.selectAttr').each(function(index, element){
				valor = jQuery(this).find('option:checked').val();
				
				if(valor == ''){
					jQuery(this).css('border','1px dashed red');
					submit = false;
				} else {
					jQuery(this).css('border','none'); 
				}
			   
				if (index == len - 1) {
					if(submit){
						jQuery(form).submit();
					}
				}
			   
			}); 
		} else {
			jQuery(form).submit();
		}
	});
	
	jQuery('.selectAttr.firstattr').change(function() {
		attr_code = jQuery(this).attr('id');
		obj_cor = jQuery(this).closest('li');

		jQuery(this).find("option:selected").each(function () {
			attr_id = jQuery(this).val();
		});

		next_attribute = jQuery(this).closest('.product').find('.nextattr').attr('id');
		produto = jQuery(this).closest('.product');
		product_id = jQuery(this).closest('.product').attr('id');
        product_atual = jQuery(this).closest('li.item');
		
		jQuery(produto).find('.loadingattr').show();
		jQuery(produto).css('opacity','0.30');
		
		if(next_attribute === undefined) {
			next_attribute = 'tamanho';
		}

		
		
		jQuery.ajax({
			type: "POST",
			url: baseurl("comprejunto/standard/getnextfirst"),
			data: "attr_code="+attr_code+"&attr_id="+attr_id+"&next_attribute="+next_attribute+"&product_id="+product_id
		}).done(function(response) {
			jQuery(produto).find('.nextattr option').each(function(){
				jQuery(this).hide();
			});
			
			var obj = eval('(' + response + ')');

			if(obj.length > 0){
				for(var k = 0; k < obj.length; k++){
					opt = obj[k].option_id;
					prod_pai = obj[k].prod_pai_id;
					label = obj[k].attribute_text;
					preco_change = obj[k].preco_change;
					
					imagem = obj[k].imagem;
					imagem = imagem.replace("\/","/"); 
					
					preco_original = jQuery('.antigo').find('#'+product_id).attr('data-precooriginal');
					jQuery('.antigo').find('#'+product_id).attr('data-precoattr1',preco_change);
					
					total = somar_vars(preco_original,preco_change,true);			
					jQuery('.antigo').find('#'+product_id).html(total);

					updatevalores('first',obj_cor);
					
					jQuery(produto).find('.nextattr').append(new Option(label, opt, true, true));
					//jQuery('.comprejunto li.item a#'+prod_pai).find('img').attr('src', imagem);
                    //jQuery(this).parent().parent().parent().parent().find('a#'+prod_pai+' img').attr('src', imagem);
                    jQuery(product_atual).find('a#'+prod_pai+' img').attr('src', imagem);
				}
			} else {
				jQuery(produto).find('.nextattr').append(new Option('Selecione...', '', true, true));
			}
			
			jQuery(produto).find('.loadingattr').hide();
			jQuery(produto).css('opacity','1');
			
		});
		
	});
	
	jQuery('.selectAttr.nextattr').change(function() {
		attr_code = jQuery(this).attr('id');

		jQuery(this).find("option:selected").each(function () {
			attr_id = jQuery(this).val();
		});

		produto = jQuery(this).closest('.product');
		product_id = jQuery(this).closest('.product').attr('id');

		obj = jQuery(this).closest('li');
		
		jQuery.ajax({
			type: "POST",
			url: baseurl("comprejunto/standard/updatepreco"),
			data: "attr_id="+attr_id+"&product_id="+product_id
		}).done(function(response) {
			preco_original = jQuery('.antigo').find('#'+product_id).attr('data-precooriginal');
			acrescimo_attr1 = jQuery('.antigo').find('#'+product_id).attr('data-precoattr1');
			
			if(acrescimo_attr1 > 0){
				preco_original = (parseFloat(preco_original)+parseFloat(acrescimo_attr1)).toFixed(2);
			}
			
			total = somar_vars(preco_original,response,true);
			jQuery('.antigo').find('#'+product_id).html(total);
			updatevalores('',obj);
		});
		
	});
	
});