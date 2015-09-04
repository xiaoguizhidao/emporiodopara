function validaNumero(field){
	var v = field.value;
	var valor = field.value.match(/\d+/g);
	if (valor==null)
	field.value = '';
	else{
	field.value = valor ;
	}
}