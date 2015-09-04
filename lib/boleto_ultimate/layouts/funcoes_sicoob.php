<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto BANCOOB/SICOOB: Marcelo de Souza              |
// | Ajuste de algumas rotinas: Anderson Nuernberg                        |
// +----------------------------------------------------------------------+
$codigobanco = "756";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);
//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//agencia é sempre 4 digitos
$agencia = formata_numero($dadosboleto["agencia"],4,0);
//conta é sempre 8 digitos
$conta = formata_numero($dadosboleto["conta"],8,0);

//Zeros: usado quando convenio de 7 digitos
$livre_zeros='000000';
$carteira = preg_replace("/[^0-9]/","",$dadosboleto["carteira"]);
$modalidadecobranca = formata_numero(substr($carteira,-2,2),2,0);
$carteira = substr($carteira,-3, 1);
$numeroparcela      = formata_numdoc($dadosboleto["numero_parcela"],3);
$convenio = formata_numdoc($dadosboleto["convenio"], 7);

//agencia e conta
$agencia_codigo = $agencia ." / ". $convenio;

//é utilizado apenas os últimos 7 dígitos do id do pedido pois 7 digitos é o padrão do sicoob
$nosso_numero = formata_numdoc(substr($dadosboleto["nosso_numero"],-7), 7);

//Lógica para nosso número
$coop = formata_numdoc($dadosboleto["agencia"], 4);
$conveniovalidacao = formata_numdoc($dadosboleto["convenio"], 10);
$numero_validacao = "$coop$conveniovalidacao$nosso_numero";
$cont=0;
$calculoDv = '';
for($num=0;$num<=strlen($numero_validacao);$num++)
{
	$cont++;
	if($cont == 1)
	{
		// constante fixa Sicoob » 3197
		$constante = 3;
	}
	if($cont == 2)
	{
		$constante = 1;
	}
	if($cont == 3)
	{
		$constante = 9;
	}
	if($cont == 4)
	{
		$constante = 7;
		$cont = 0;
	}
	$calculoDv = $calculoDv+(substr($numero_validacao,$num,1) * $constante);
}
$resto = $calculoDv % 11;

if($resto == 0 || $resto == 1){
	$dv = 0;
}else{
	$dv = 11 - $resto;
}
$nossonumero = $nosso_numero.$dv;

//geração do boleto
$campolivre  = "$modalidadecobranca$convenio$nossonumero$numeroparcela";
$dv=modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$carteira$agencia$campolivre");
$linha="$codigobanco$nummoeda$dv$fator_vencimento$valor$carteira$agencia$campolivre";

$dadosboleto["codigo_barras"] = $linha;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
$dadosboleto["codigo_banco"] = $codigobanco;

// FUNÇÕES
// Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco

function formata_numdoc($num,$tamanho)
{
	while(strlen($num)<$tamanho)
	{
		$num="0".$num;
	}
return $num;
}
function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		retira as virgulas
		formata o numero
		preenche com zeros
		*/
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}
function fbarcode($valor){
$fino = 1 ;
$largo = 3 ;
$altura = 50 ;
  $barcodes[0] = "00110" ;
  $barcodes[1] = "10001" ;
  $barcodes[2] = "01001" ;
  $barcodes[3] = "11000" ;
  $barcodes[4] = "00101" ;
  $barcodes[5] = "10100" ;
  $barcodes[6] = "01100" ;
  $barcodes[7] = "00011" ;
  $barcodes[8] = "10010" ;
  $barcodes[9] = "01010" ;
  for($f1=9;$f1>=0;$f1--){
    for($f2=9;$f2>=0;$f2--){
      $f = ($f1 * 10) + $f2 ;
      $texto = "" ;
      for($i=1;$i<6;$i++){
        $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      }
      $barcodes[$f] = $texto;
    }
  }
//Desenho da barra
//Guarda inicial
?><img src=media/boleto_ultimate/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=media/boleto_ultimate/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=media/boleto_ultimate/p.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=media/boleto_ultimate/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
<?php
$texto = $valor ;
if((strlen($texto) % 2) <> 0){
	$texto = "0" . $texto;
}

// Draw dos dados
while (strlen($texto) > 0) {
  $i = round(esquerda($texto,2));
  $texto = direita($texto,strlen($texto)-2);
  $f = $barcodes[$i];
  for($i=1;$i<11;$i+=2){
    if (substr($f,($i-1),1) == "0") {
      $f1 = $fino ;
    }else{
      $f1 = $largo ;
    }
?>
    src=media/boleto_ultimate/p.png width=<?php echo $f1?> height=<?php echo $altura?> border=0><img
<?php
    if (substr($f,$i,1) == "0") {
      $f2 = $fino ;
    }else{
      $f2 = $largo ;
    }
?>
    src=media/boleto_ultimate/b.png width=<?php echo $f2?> height=<?php echo $altura?> border=0><img
<?php
  }
}

// Draw guarda final
?>
src=media/boleto_ultimate/p.png width=<?php echo $largo?> height=<?php echo $altura?> border=0><img
src=media/boleto_ultimate/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img
src=media/boleto_ultimate/p.png width=<?php echo 1?> height=<?php echo $altura?> border=0>
  <?php
} //Fim da função
function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}
function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}
function fator_vencimento($data) {
	$data = explode("/",$data);
	$ano = $data[2];
	$mes = $data[1];
	$dia = $data[0];
    return(abs((_dateToDays("1997","10","07")) - (_dateToDays($ano, $mes, $dia))));
}
function _dateToDays($year,$month,$day) {
    $century = substr($year, 0, 2);
    $year = substr($year, 2, 2);
    if ($month > 2) {
        $month -= 3;
    } else {
        $month += 9;
        if ($year) {
            $year--;
        } else {
            $year = 99;
            $century --;
        }
    }
    return ( floor((  146097 * $century)    /  4 ) +
            floor(( 1461 * $year)        /  4 ) +
            floor(( 153 * $month +  2) /  5 ) +
                $day +  1721119);
}
/*
#################################################
FUNÇÃO DO MÓDULO 10 RETIRADA DO PHPBOLETO
ESTA FUNÇÃO PEGA O DÍGITO VERIFICADOR DO PRIMEIRO, SEGUNDO
E TERCEIRO CAMPOS DA LINHA DIGITÁVEL
#################################################
*/
function modulo_10($num) {
	$numtotal10 = 0;
	$fator = 2;

	for ($i = strlen($num); $i > 0; $i--) {
		$numeros[$i] = substr($num,$i-1,1);
		$parcial10[$i] = $numeros[$i] * $fator;
		$numtotal10 .= $parcial10[$i];
		if ($fator == 2) {
			$fator = 1;
		}
		else {
			$fator = 2;
		}
	}

	$soma = 0;
	for ($i = strlen($numtotal10); $i > 0; $i--) {
		$numeros[$i] = substr($numtotal10,$i-1,1);
		$soma += $numeros[$i];
	}
	$resto = $soma % 10;
	$digito = 10 - $resto;
	if ($resto == 0) {
		$digito = 0;
	}
	return $digito;
}
/*
#################################################
FUNÇÃO DO MÓDULO 11 RETIRADA DO PHPBOLETO
MODIFIQUEI ALGUMAS COISAS...
ESTA FUNÇÃO PEGA O DÍGITO VERIFICADOR:
NOSSONUMERO
AGENCIA
CONTA
CAMPO 4 DA LINHA DIGITÁVEL
#################################################
*/
function modulo_11($num, $base=9, $r=0) {
	$soma = 0;
	$fator = 2;
	for ($i = strlen($num); $i > 0; $i--) {
		$numeros[$i] = substr($num,$i-1,1);
		$parcial[$i] = $numeros[$i] * $fator;
		$soma += $parcial[$i];
		if ($fator == $base) {
			$fator = 1;
		}
		$fator++;
	}
	if ($r == 0) {
		$soma *= 10;
		$digito = $soma % 11;

		//corrigido
		if ($digito == 10) {
			$digito = 1;
		}
		return $digito;
	}
	elseif ($r == 1){
		$resto = $soma % 11;
		return $resto;
	}
}
/*
Montagem da linha digitável - Função tirada do PHPBoleto
Não mudei nada
*/
function monta_linha_digitavel($linha) {
    // Posição 	Conteúdo
    // 1 a 3    Número do banco
    // 4        Código da Moeda - 9 para Real
    // 5        Digito verificador do Código de Barras
    // 6 a 19   Valor (12 inteiros e 2 decimais)
    // 20 a 44  Campo Livre definido por cada banco
    // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
    // do campo livre e DV (modulo10) deste campo
    $p1 = substr($linha, 0, 4);
    $p2 = substr($linha, 19, 5); //apenas o numero significativo do lado esquerdo da carteira e agencia
    $p3 = modulo_10("$p1$p2");
    $p4 = "$p1$p2$p3";
    $p5 = substr($p4, 0, 5);
    $p6 = substr($p4, 5);
    $campo1 = "$p5.$p6";
    // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
    // e livre e DV (modulo10) deste campo
    //$p10 = substr($linha, 20, 2); //modalidade
    $p1 = substr($linha, 24, 10); //cod beneficiario e 1 digito do nosso numero
    $p2 = modulo_10("$p1");
    $p3 = "$p1$p2";
    $p4 = substr($p3, 0, 5);
    $p5 = substr($p3, 5);
    $campo2 = "$p4.$p5";
    // 3. Campo composto pelas posicoes 16 a 25 do campo livre
    // e livre e DV (modulo10) deste campo
    $p1 = substr($linha, 34, 10); //7 digitos restantes do nosso número e numero parcela
    $p2 = modulo_10($p1);
    $p3 = "$p1$p2";
    $p4 = substr($p3, 0, 5);
    $p5 = substr($p3, 5);
    $campo3 = "$p4.$p5";
    // 4. Campo - digito verificador do codigo de barras
    $campo4 = substr($linha, 4, 1);
    // 5. Campo composto pelo valor nominal pelo valor nominal do documento, sem
    // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
    // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
    $campo5 = substr($linha, 5, 14);
    return "$campo1 $campo2 $campo3 $campo4 $campo5";
}
function geraCodigoBanco($numero) {
    $parte1 = substr($numero, 0, 3);
    $parte2 = modulo_11($parte1);
    return $parte1 . "-" . $parte2;
}
?>