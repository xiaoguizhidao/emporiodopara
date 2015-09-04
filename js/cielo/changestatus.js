function Changestatus(url, order ){
	
	this.order =order;
	this.url  = url;
	/**
	 * Cancelar um 
	 */
	
	
	this.doaction = function (modo,tid){
		
		new Ajax.Request(this.url+'/cielo/processadmin/do/tid/'+tid+'/modo/'+modo + '/order_id/' + order ,{
			onSuccess: function (response){
				change_status_result_transaction = "change_status_result_transaction";				

				
				if ((response.responseText=="capturado") || (response.responseText=="cancelado")){
					document.getElementById(change_status_result_transaction).style.width="200px";
					document.getElementById(change_status_result_transaction).style.border="2px solid #c8e188";
					document.getElementById(change_status_result_transaction).style.padding="10px";
					document.getElementById(change_status_result_transaction).style.backgroundColor="#deeeb5";
					
					if (response.responseText=="capturado"){
						document.getElementById(change_status_result_transaction).innerHTML = "Pedido Capturado com sucesso, atualize a pagina para ver o novo status. ATENCAO: Para modalidade de dois cartoes, o pedido so mudara de status para 'Solicitado Fornecedor' quando ambas as transacoes forem capturados.";
					}
					if (response.responseText=="cancelado"){
						document.getElementById(change_status_result_transaction).innerHTML = "Pedido Cancelado com sucesso, atualize a pagina para ver o novo status";
					}

					
				}
				
				if (response.responseText=="erro"){
					document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
					document.getElementById(change_status_result_transaction).style.width="200px";
					document.getElementById(change_status_result_transaction).style.border="2px solid red";
					document.getElementById(change_status_result_transaction).style.padding="10px";
					document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
					document.getElementById(change_status_result_transaction).innerHTML = "Erro ao requisitar captura, favor tentar novamente ";
				}				
				
			},
			onFailure: function (response){
				change_status_result_transaction = "change_status_result_transaction";
				document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
				document.getElementById(change_status_result_transaction).style.width="200px";
				document.getElementById(change_status_result_transaction).style.border="2px solid red";
				document.getElementById(change_status_result_transaction).style.padding="10px";
				document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
				document.getElementById(change_status_result_transaction).innerHTML = "Erro ao realizar o pedido, se o problema persistir contacte o suporte.";				
				
			}
		})
	};
	
	
	this.doactidon = function (modo,tid){
		
		new Ajax.Request(this.url+'/cielo/admin/do/tid/'+tid+'/modo/'+modo + '/order_id/' + order ,{
			onSuccess: function(response){
				//alert(response.responseText);
				
				change_status_result_transaction = "change_status_result_transaction";				

				
				if ((response.responseText=="capturado") || (response.responseText=="cancelado")){
					document.getElementById(change_status_result_transaction).style.width="200px";
					document.getElementById(change_status_result_transaction).style.border="2px solid #c8e188";
					document.getElementById(change_status_result_transaction).style.padding="10px";
					document.getElementById(change_status_result_transaction).style.backgroundColor="#deeeb5";
					
					if (response.responseText=="capturado"){
						document.getElementById(change_status_result_transaction).innerHTML = "Pedido Capturado com sucesso, atualize a pagina para ver o novo status. ATENCAO: Para modalidade de dois cartoes, o pedido so mudara de status para 'Solicitado Fornecedor' quando ambas as transacoes forem capturados.";
					}
					if (response.responseText=="cancelado"){
						document.getElementById(change_status_result_transaction).innerHTML = "Pedido Cancelado com sucesso, atualize a pagina para ver o novo status";
					}

					
				}
				
				if (response.responseText=="erro"){
					document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
					document.getElementById(change_status_result_transaction).style.width="200px";
					document.getElementById(change_status_result_transaction).style.border="2px solid red";
					document.getElementById(change_status_result_transaction).style.padding="10px";
					document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
					document.getElementById(change_status_result_transaction).innerHTML = "Erro ao requisitar captura, favor tentar novamente ";
				}				
				
				
				
			},
			onFailure: function (response){
				change_status_result_transaction = "change_status_result_transaction_"+id;
				document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
				document.getElementById(change_status_result_transaction).style.width="200px";
				document.getElementById(change_status_result_transaction).style.border="2px solid red";
				document.getElementById(change_status_result_transaction).style.padding="10px";
				document.getElementById(change_status_result_transaction).style.backgroundColor="#eec7c7";
				document.getElementById(change_status_result_transaction).innerHTML = "Erro ao realizar o pedido, se o problema persistir contacte o suporte.";
				
			}
			
		});
		
	
	}	
}

