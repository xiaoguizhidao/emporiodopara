
function ControleJuros(total, source){
	

	
	
	this.Calculavalorparcelas = function (){
		
			
    	
	}
	
	
	this.Requisitavalorparcelas=function (total_quote, num_parcelas, error_box){
		
		return jQuery.ajax({
			url : OscGeral.getUrlBase('cielo/processfront/calculaparcelasajax'),
			type : 'POST',
			statusCode: {
				503:function (){
					//error_box = eval( '('+ error_box + ')' );
					alert('.'+error_box);
					jQuery('.'+error_box).html('Erro ao recuperar o numero de parcelas para esta bandeira.');
					jQuery('.'+error_box).css('display', 'block');
				}
			} ,
			data : { 
				
				num_parcelas : num_parcelas, 
				total: total_quote
			}
		});

	}
	
	this.updateJuros = function(select_obj_id, valor_minimo_com_juros){
		OscTela.mostrarScreenlocker();
		//alert(jQuery("#"+select_obj_id+" option:selected").attr('value'));
		num_parcelas = jQuery("#"+select_obj_id+" option:selected").attr('value');
		
		bandeira = jQuery("#bandeira_cartao_1").val();
		jQuery.ajax({	
			url : OscGeral.getUrlBase('cielo/pagamentoajax/atualizajurosacielojax'),
			type : 'POST',
			
			statusCode: {
				503:function (){
					//error_box = eval( '('+ error_box + ')' );
					alert('.'+error_box);
					jQuery('.'+error_box).html('Erro ao recuperar o numero de parcelas para esta bandeira.');
					jQuery('.'+error_box).css('display', 'block');
				}
			} ,
			data : { 
				num_parcelas : num_parcelas,
				total:total,
				bandeira:bandeira
			},
							
		}).done(
				
			function (resposta){
				var resposta = eval('(' + resposta + ')');
				
				jQuery('.review-container').html(resposta.review);
			}
		)
		
		OscTela.esconderScreenlocker();
		
	}
	
	
}