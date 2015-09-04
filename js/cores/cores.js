
var CORES_URLBASE;// criado para url base
var CORES_IDPRODUTO; // produto configurado 


var Cores = {

	urlBase: function(parametros){
		return CORES_URLBASE + parametros ;
	},

    fireEvent :function(obj,evt){ 
        var fireOnThis = obj; 
         if( document.createEvent ) { 
           var evObj = document.createEvent('MouseEvents'); 
           evObj.initEvent( evt, true, false ); 
           fireOnThis.dispatchEvent( evObj ); 
         } else if( document.createEventObject ) { 
          var evObj = document.createEventObject(); 
            fireOnThis.fireEvent( 'on' + evt, evObj ); 
         } 
    }, 


    mudarImagem:function(id_cor, elemento){
    	elemento = jQuery(elemento);
    	if(elemento.attr('data-status') != 'desabilitado'){
    		novo_html_imagens   = jQuery('#cores'+id_cor).html();
			more_views          = jQuery('.more-views');
			more_views.html(novo_html_imagens);
			jQuery('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
			jQuery('.product-img-box .cloud-zoom-gallery').eq(0).click();
		}

    },

	selecionar : function(elemento){
		elemento = jQuery(elemento);
		
		if(elemento.attr('data-status') != 'desabilitado'){
			ul = jQuery(elemento).parent().parent();
			a_geral = jQuery('a', ul).removeClass('selecionado');
			
			// =+== ADICIONAR CLASSE
			elemento.addClass('selecionado');
			
			// ===== CAMPOS
			index = elemento.attr('data-index');
			atributo     = elemento.attr('data-atributo');
			valor        = elemento.attr('data-valor');
			idatributo   = elemento.attr('data-idatributo');

			// ==== CAMPO HIDDEN
			jQuery('#campo_'+atributo).val(valor);

			jQuery('#erro_'+atributo).hide();

			var atributos = '';

			var valores = '';

			for(var k = index; k >= 0; k--){
				campo_anterior = jQuery("input.campos_atributo[data-campoindex='"+k+"']");
				campo_valor = campo_anterior.val();
				campo_codigo =  campo_anterior.attr('data-campocode');
				atributos  += campo_codigo+'#';
				valores    += campo_valor+'#';
			}

			dropdown = document.getElementById('attribute'+idatributo);
			dropdown.value = valor;
			Cores.fireEvent(dropdown, 'change'); // troca o select
			// ===== CAMPOS
			// envia requisição por Ajax para validar o proximo atributo
			jQuery.ajax({
				url: this.urlBase('cores/front/selecionar'),
				dataType : 'json',
				data : { 
					  produto_configuravel : CORES_IDPRODUTO,
					  codigo_atributo : atributos,
					  valor_atributo : valores,
					  index : index
				}
			}).done(function(resposta) {
				  var proximo_atributo = resposta.proximo_atributo;
				  var proximos_itens = resposta.proximos_itens;
				  item_geral = jQuery('#atributos-principal ul');
				  for(var k = parseInt(index)+1;  k < item_geral.length; k++){
				  	  item_desabilitar = jQuery("a[data-index='"+k+"']");
				  	  item_desabilitar.addClass('desabilitado');
				      item_desabilitar.attr('data-status','desabilitado');
				      item_desabilitar.removeClass('selecionado');
				  }
				  for(var k = 0; k < proximos_itens.length; k++){
				  	 item_liberado =  jQuery( "#dados_"+proximo_atributo + " a[data-valor='"+proximos_itens[k]+"']" )
				  	 item_liberado.removeClass('desabilitado');
				  	 item_liberado.removeAttr('data-status');
				  }
			});

		}
	}

};


jQuery(document).ready(function($) {
	jQuery('.products-grid .color-list .change-image').on('click', function(){

		url_image = jQuery(this).attr('configurable-image');
		product_image = jQuery(this).attr('product-image');
				

		jQuery('#'+product_image).attr('src', url_image);
	});
});