/*
 * Copyright (C) 2013 Bis2Bis Comércio Eletrônico
 *
 * Author : Guilherme de Almeida Costa
 *  
 * E-mail : guilherme.costa@bis2bis.com.br 
 *
 * This file is part of the Bis2Bis Comércio Eletrônico
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

(function ($) {

	$.fn.novedigitos = function(valor_telefone){
		temp = valor_telefone.replace('(', ''); 
		temp = temp.replace(')', '');
		temp = temp.replace(' ', '');
		temp = temp.substring(0,4);

        //Por gentileza adicionar os DDD's  12, 13, 14, 15, 16, 17, 18,19  21, 22, 24, 27 e 28, para ter 9 digitos

		if(    	
			   temp == '1198'  
			|| temp == '1199'
			|| temp == '1196'  
			|| temp == '1299'  
			|| temp == '1298'
			|| temp == '1296'   
			|| temp == '1399'  
	        || temp == '1398'
	        || temp == '1396'  
	        || temp == '1499'  
	        || temp == '1498' 
	        || temp == '1496' 
	    	|| temp == '1599'  
	    	|| temp == '1598'
	    	|| temp == '1596'  
	    	|| temp == '1699' 
	    	|| temp == '1698'  
	    	|| temp == '1696' 
	    	|| temp == '1799' 
	    	|| temp == '1798' 
	    	|| temp == '1796' 
			|| temp == '1899'  
			|| temp == '1898' 
			|| temp == '1896' 
			|| temp == '1999'  
			|| temp == '1998'
			|| temp == '1996' 
            || temp == '2199'
            || temp == '2198'
            || temp == '2196' 
            || temp == '2299'
            || temp == '2298'
            || temp == '2296' 
            || temp == '2499'
            || temp == '2498'
            || temp == '2496' 
            || temp == '2799'
            || temp == '2798'
            || temp == '2796' 
            || temp == '2899'
            || temp == '2898'
            || temp == '2896' 
		){
			return true; 
		}else{
			return false;
		}
	};

 
    $.fn.telefone = function() {

    	// quando perder o foco
    	this.blur(function(){
    		valor = jQuery(this).val();
    		novedigitos = jQuery.fn.novedigitos(valor);
    		tamanho = valor.length;
    	
    		if(novedigitos){
    			if(tamanho < 15){
    				jQuery(this).val('');
    			}
    		}else{
    			if(tamanho < 14){
    				jQuery(this).val('');
    			}
    		}
    		return true;
    	});

    	// quando pressionar um botao
    	this.keypress(function(e){

    		tamanho = (jQuery(this).val().length); 
    		valor = jQuery(this).val();
			length_9 = false;
			retorno = false;

			if(e.keyCode != 0){
				code = e.keyCode;
			}else{
				code = e.which;
			}


			if(tamanho == 1){
				if(code != 8){
					if(valor.indexOf('(') == -1){
						valor = '(' + valor;
						jQuery(this).val(valor);
					}
				}
			}else if(tamanho == 3){
				if(code != 8){
					if(valor.indexOf(')') == -1){
						valor =  valor + ')';
						jQuery(this).val(valor);
						valor =  valor + ' ';
						jQuery(this).val(valor);
					}	
				}
				
			}else if(tamanho == 7){
				if($.fn.novedigitos(valor)){
					length_9 = true;
				}
			}else if(tamanho > 7){
				if($.fn.novedigitos(valor)){
					length_9 = true;
				}
			}

    		if(e.keyCode == 9 || e.keyCode == 8 || e.keyCode == 17 || e.keyCode == 18){
    			return true;
    		}

			if(code == 48 || code == 49 || code == 50 || code == 51 || code == 52 || code == 53 || code == 54 || code == 55 || code == 56 || code == 57){
				retorno = true;
			}else{
				retorno = false;
			}

			if(length_9){
				if(tamanho == 10){
					valor =  valor + '-' ;
					jQuery(this).val(valor);
				}
				if(tamanho == 15){
					jQuery(this).val(valor);
					return false;
				}
			}else{
				if(tamanho == 9){
					valor =  valor + '-' ;
					jQuery(this).val(valor);
				}
				if(tamanho == 14){
					jQuery(this).val(valor);
					return false;
				}
			}

			return retorno;

    	});
		
		return this;
    };



 
}( jQuery ));