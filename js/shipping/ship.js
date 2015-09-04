var validate = true; 

var recipe_stack = new Array();

var recipe_validate = false;

var array_validate = new Array();

var ship = {


	/*Retorna Url Base*/
	baseurl : function(param){
		myurl= (window.location);
        splited_url = (myurl.toString().split("/"));
        final_url = splited_url[0] + "//" + splited_url[2];
        return final_url+"/magento/"+param;
	},

	//Adiciona mensagem de erro para o cliente
	adderror : function(message){
		jQuery('#messages_shipping').removeClass('hidden');
		jQuery('#messages_shipping #msg_txt').html(message);
	},

	//reseta mensagem de erro
	removeerror : function(){
		jQuery('#messages_shipping').addClass('hidden');
		jQuery('#messages_shipping #msg_txt').html('');
	},


	// valida se o campo já existe
	validateexistfield : function(my_field){
		allfields = jQuery('.hidden_field');
		for(var k = 0; k < allfields.length; k++){
			field  = allfields.eq(k).val();
			field_exploded = field.split('#');
			if(field_exploded[0] == my_field)
				return false;
		}
		return true;
	},

	// Adiciona campo na tabela de criação da transportadora
	addfield : function(){
		ship.removeerror();
		var name = jQuery('#name').val().toLowerCase();
		var data_type = jQuery('select#data_type').val();
		if(!ship.validateexistfield(name)){
			ship.adderror('Campo já existe'); 
			return;
		}
		if(name == '' || data_type == 0){
			ship.adderror('Preencher os valores do campo corretamente'); 
			return;
		}else{
			type_txt = '';
			if(data_type == 1){
				type_txt = 'Inteiro';
			}else if(data_type == 2){
				type_txt = 'Numérico';
			}else if(data_type == 3){
				type_txt = 'Texto';
			}

			var createtable_form = jQuery('#createtable_form');
			var input_hidden = jQuery('<input type="hidden" />');
			var campo_adicional = jQuery('<input type="hidden" />'); // para controle de edição

			input_hidden.addClass('hidden_field');
			campo_adicional.attr('name', 'adicional[]');
			input_hidden.attr('name', 'hidden[]');
			input_hidden.val(name+'#'+data_type);
			campo_adicional.val(name+'#'+data_type);
			createtable_form.append(input_hidden);
			createtable_form.append(campo_adicional);
			tr = jQuery('<tr></tr>');
			td1 = jQuery('<td></td>');
			td1.html(name);
			td2 = jQuery('<td></td>');
			td2.html(type_txt);
			td3 = jQuery('<td></td>');
			button = jQuery('<button type="button" class="scalable delete" ><span>Deletar</span></button>');
			
			button.click(function(){
				var remove = confirm('Deseja realmente remover esse campo?');
				if(remove){
					input_hidden.remove();
					tr.remove();
				}
			});

			td3.append(button);
			tr.append(td1);
			tr.append(td2);
			tr.append(td3);
			table = jQuery('.table-container table');
			table.append(tr);
			jQuery('#name').val('');
			jQuery('select#data_type').val(0);

		}
	},
	
	// botoes carregados pelo foreach... deleta campos de tabela
	deletefield: function(button){
		var remove = confirm('Deseja realmente remover esse campo?');
		tr = jQuery(button).parent().parent();
		position = (jQuery(button).attr('data-position'));
		if(remove){
			jQuery('.input_hidden').eq(position-1).remove(); // remove o hidden correspondente
			tr.remove();
		}
	},

	addpctnota : function(){
		var the_value = jQuery('#add_pctnota').val();
		var ul = jQuery("#recipe_ul");
		var new_li = jQuery('<li></li>');
		new_li.html(the_value);
		ul.append(new_li);
		recipe_stack.push(the_value);
		ship.addrecipeinput(the_value);
	},

	addfreevalue : function(){
		var the_value = jQuery('#add_freevalue').val();
		var ul = jQuery("#recipe_ul");
		var new_li = jQuery('<li></li>');
		new_li.html(the_value);
		ul.append(new_li);
		recipe_stack.push(the_value);
		ship.addrecipeinput(the_value);
	},

	addrecipeinput: function(the_value){
		input = jQuery('<input type="hidden" class="recipe_hidden" />');
		count = jQuery('.recipe_hidden').length;
		input.attr('name', 'compose_recipe[]');
		input.val(the_value);
		jQuery('#managerecipe_form').append(input);

	},

	addfields : function(){
		var the_value = jQuery('#add_field').val();
		var ul = jQuery("#recipe_ul");
		var new_li = jQuery('<li></li>');
		new_li.html(the_value);
		ul.append(new_li);
		recipe_stack.push(the_value);
		ship.addrecipeinput(the_value);
	},

	addoperand : function(){
		var the_value = jQuery('#add_operand').val();
		var ul = jQuery("#recipe_ul");
		var new_li = jQuery('<li></li>');
		new_li.html(the_value);
		ul.append(new_li);
		recipe_stack.push(the_value);
		ship.addrecipeinput(the_value);
	},

	addparenteses : function(){
		var the_value = jQuery('#add_parenteses').val();
		var ul = jQuery("#recipe_ul");
		var new_li = jQuery('<li></li>');
		new_li.html(the_value);
		ul.append(new_li);
		recipe_stack.push(the_value);
		ship.addrecipeinput(the_value);
	},


	back_recipe : function(){
		var lis = jQuery("#recipe_ul li");
		lis.eq(lis.length-1).remove();
		recipe_stack.pop();
		jQuery('.recipe_hidden').last().remove();
	},


	// Verifica se a tabela já existe
	verify_table : function(){
		jQuery.ajax({
		  type: "POST",
		  url: ship.baseurl('bis2ship/index/validateTable'),
		  data: { table : jQuery("#carrier").val() }
		}).done(function( msg ) {
		  	if(msg == '1'){
		  		ship.adderror('Tabela ' + jQuery('#carrier').val() + ' já existe no banco de dados!');
		  		validate = false;
		  	}else if(msg == '0'){
		  		validate = true;
		  		ship.removeerror();
		  	}
		});
	},

	// valida a fórmula
	validate_recipe: function(){
	 	ship.removeerror();
	 	jQuery('#recipe_ul_wrapper').css('background-color', '#FF3300');
		jQuery('#recipe_ul_wrapper #recipe_ul li').css('color', '#F2F2F2');
		var recipe_string = '';
		for(var k = 0; k < recipe_stack.length; k++){
			code_recipe = recipe_stack[k];
			if(code_recipe != '+' && code_recipe != '-' &&  code_recipe != '(' &&  code_recipe != ')' &&  code_recipe != '/' &&  code_recipe != '*' ){
				code_recipe = '1';
			}
			recipe_string += code_recipe;
		}
		try{
			eval(recipe_string);
			validate_recipe =  true;
			jQuery('#recipe_ul_wrapper').css('background-color', '#6a9d6a');
			jQuery('#recipe_ul_wrapper #recipe_ul li').css('color', '#F2F2F2');
		}catch(err){
			ship.adderror('Fórmula inválida');
			validate_recipe =  false;
		}
	},


	//Salva a formula
	save_recipe:function(){
		if(validate_recipe){
			ship.removeerror();
			if(recipe_stack.length > 0){
				jQuery('#managerecipe_form').submit();
			}else{
				ship.adderror('Montar uma fórmula');
			}
		}else{
			ship.adderror('Fórmula não foi validada ou não está correta');
		}
	},

	//Salva a tabela
	save:function(){
		if(validate){
			ship.removeerror();
			carrier = jQuery('#carrier').val();
			fields = jQuery('.hidden_field').length;
			if(carrier != '' && fields > 0){
				jQuery('#createtable_form').submit();
			}else{
				if(carrier == '')
					ship.adderror('Adicionar um nome para a tabela da transportadora');
				if(fields == 0)
					ship.adderror('Adicionar ao menos um campo para a tabela');
			}
		}else{
			ship.adderror('Tabela ' + jQuery('#carrier').val() + ' já existe no banco de dados!');
		}
	},

	deletetable:function(){
		really = confirm('Deseja realmente apagar a tabela? Todos os dados serão apagados e esse processo é irreversível');
		if(really){
			jQuery('#formtable_delete').submit();
		}
	},


	save_filltable:function(data){
		validate = ship.validateallfields();
		if(validate){
			json_string = (JSON.stringify(data));
			jQuery('#data_filltable').val(json_string);
			jQuery('#form_filltable').submit();
		}else{
			ship.removeerror();
			ship.adderror('Por gentileza preencher todos os campos');
		}
	},


	deactivatetable:function(){
		ship.removeerror();
		jQuery('#deactivetable_form').submit();
	},


	// duplica a linha selecionada
	duplicatelastrows:function(){
		var dd = grid.getData();
		dd.splice(dd.length, 0, Object.create(dd[parseInt(dd.length-1)])) ;
		grid.invalidateRow(dd.length);
		grid.updateRowCount();
		grid.setData(dd,true);
		grid.render();
	},

	// duplica a linha selecionada
	duplicateselectedrows:function(){
		selected = grid.getSelectedRows();
		selected = (selected[0]);
		var dd = grid.getData();
		dd.splice(parseInt(selected+1),0, Object.create(dd[selected])) ;
		grid.invalidateRow(selected+1);
		grid.updateRowCount();
		grid.setData(dd,true);
		grid.render();
	},

	// deleta linhas selecionadas
	deleteselectedrows:function(){
		var delete_row = confirm('Deseja realmente apagar as linhas selecionadas?');
		if(delete_row){
			selected = grid.getSelectedRows();
			selected.sort();
			for(var k = (selected.length-1); k >= 0; k--){
				  var dd = grid.getData();
				  var current_row = selected[k];
				  dd.splice(current_row,1);
				  var r = current_row;
				  while (r<dd.length){
				    grid.invalidateRow(r);
				    r++;
				  }
				  grid.updateRowCount();
				  grid.render();
			}
		}
	},


	activatetable:function(){
		ship.removeerror();
		checkbox_terms = jQuery('#checkbox_accept').is(':checked');
		if(checkbox_terms){
			jQuery('#activetable_form').submit();
		}else{
			ship.adderror('Para ativar você deve aceitar os termos de compromisso');
		}
	},

	// validação
	startvalidate:function(data){
		array_validate = data;
	},

	// funcao que faz a validação geral da tabela
	validateallfields:function(color){
		rows = jQuery('#myGrid .slick-row');
		jQuery('.slick-cell', rows).css('background-color', 'white');
		haserror = new Array();
		for(var k = 0; k < data.length; k++){
			for (var l = 0; l < array_validate.length; l++){
				value_to_validate = (data[k][array_validate[l]]);
				if(value_to_validate == undefined){
					if(l != 5	&& !color ){
						console.log('Valor Vazio na Linha ' + k + ' Coluna ' + l );
						cells = jQuery('.slick-cell', rows.eq(k) );
						cells.eq(l).css('background-color', '#ff6767');
						console.log(cells);
						haserror.push(true);
					}
				}
			}
			
		}
		if(haserror.length>0)
			return false
		else
			return true;
	}


}