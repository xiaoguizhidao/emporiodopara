var ambiente = '1' //1 para lojista e 2 para cielo

function getBaseURL(){
	var url = window.location;
	var url_parts = url.toString().split("/");
	var host = url_parts[0]+"//"+url_parts[2];	
	return host;
}



function pressCard(field, card, url_skin){
	this.validaNumero(field, card, url_skin);
}




function validaNumero(field, card, url_skin){
	var v = field.value;

	var valor = field.value.match(/\d+/g);
	if (valor==null)
	field.value = '';
	else{
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
	
	changeButtonEffect(type, card, url_skin);
	field.value = valor ;
	}
}

var prev_clicked = new Array();

function changeButtonEffect(cartao, num_cart, url_skin){
    var prev_clicked_initiated_now = true;
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
	    prev_clicked_initiated_now =false;
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
				document.getElementById(imgClicked.id.replace('bandeira','radio')).checked = false;
			}
			imgClicked.src = img.src;
			imgClicked.id = img.id;
			prev_clicked[num_cart] = imgClicked;
		}
		img.src = url_skin+'images/bandeiras/'+cartao+'_clicked.png';
		img.style.margin = '0px 2px 0px 0px';
		document.getElementById('radio_cartao_'+cartao+'_'+num_cart).checked = true;
		document.getElementById('bandeira_cartao_'+num_cart).value = cartao;
		/*if ((cartao == 'discover')||(cartao == 'visa_debito')||(cartao == 'mastercard_debito')){
			document.getElementById('cartoes_parcelas_cartao_'+num_cart).value = 1;
			document.getElementById('coluna_parcelas_'+num_cart).style.display = 'none';
		}
		else
			document.getElementById('coluna_parcelas_'+num_cart).style.display = 'table-row';*/
		if(ambiente == '1')
			resetFields(num_cart);
	}else{
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




function test_found_card(num_card){
	if (document.getElementById('bandeira_cartao_'+num_card).value !='')
		document.getElementById('warning_none_selected_brand').style.display="none";
	else
		document.getElementById('warning_none_selected_brand').style.display="block";
}
 

function changeDisplayWarning(item){
	if (document.getElementeById(item).style.display=="block"){
		document.getElementeById(item).style.display = "none";
	}else if(document.getElementeById(item).style.display=="none"){
		document.getElementeById(item).style.display=="block";
	}
}

function resetFields(index){
	/*document.getElementById('cartoes_nome_cartao_'+index).value = "";*/
	/*document.getElementById('cartoes_numero_cartao_'+index).value = "";
	document.getElementById('cartoes_mes_cartao_'+index).value = "";
	document.getElementById('cartoes_ano_cartao_'+index).value = "";
	document.getElementById('cartoes_codigo_seguranca_cartao_'+index).value = "";*/
}


