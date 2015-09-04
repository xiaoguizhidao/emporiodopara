function ControleJuros(url){
	
	
	this.url = url;
	
	
	
	
	
	this.resetJuros = function (	){
		
	}
	
	
	this.updateJuros = function(select_obj_id){
		
		num_parcelas = jQuery("#"+select_obj_id+" option:selected").attr('value');
		bandeira = jQuery("#bandeira_cartao_1").val();
		new Ajax.Request(this.url+ 'cielo/processadmin/atualizajuroscielojax/' ,{
			
			parameters: {bandeira: bandeira,num_parcelas: num_parcelas   },
			
			onSuccess: function (response){
				//alert (response.responseText);
				order.loadArea('totals',1);
			},
			onFailure: function (response){
							
			}
		});
		
		
		
	}
	
	
	this.resetPaymentMethod = function(select_obj_id){
		
		
		/**
		 * Limpa os campos no formulario de pagamento
		 */
		
		$('order-billing_method_form').select('input', 'select').each(function(elem){
		    if(elem.type == 'radio') elem.checked = false;
		    if(elem.type == 'text') elem.value="";
		})
		
		/**
		 * Tira os juros, caso exista
		 */
		
		
		new Ajax.Request(this.url+ 'cielo/processadmin/resetpaymentmethodajax/' ,{
			
			onSuccess: function (response){
				//alert (response.responseText);
				order.loadArea('totals',1);
			},
			onFailure: function (response){
							
			}
		});
		
		
	}
		
		

}
