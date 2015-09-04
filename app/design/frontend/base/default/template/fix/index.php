<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require('../app/Mage.php');
Mage::app();

$customer_stock_alerts = Mage::getModel('productalert/stock')
         ->getCollection()
         ->addFieldToFilter('status', 0);

foreach ( $customer_stock_alerts as $alert ){
	
$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($alert['product_id']);
//echo "ID do Produto:" .$alert['product_id'] ." Id Usuario:". $alert['customer_id']."Disp:". $stock->getData('is_in_stock') ."</br>";
//$_product = Mage::getModel('catalog/product')->load($alert['product_id']);


$model = Mage::getModel('catalog/product'); //inicia o modelo do produto
$_product = $model->load($alert['product_id']); //abre o produto baseado no id
 
$customer = Mage::getModel('customer/customer')->load($alert['customer_id']);
 
/* print_r($customer);
 	die;*/

	$para = $customer['email'];
	$assunto = 'Disponibilidade do Produto';

		$html = '
<tbody><tr>
            <td valign="top" align="center">
                
                <table width="650" cellspacing="0" cellpadding="0" border="0">
                    <tbody><tr>
                        <td valign="top">
                            <a target="_blank" href="http://www.casanostracosmeticos.com.br/"><img border="0" style="margin-bottom:10px" alt="Casa Nostra Cosméticos" src="http://www.casanostracosmeticos.com.br/skin/frontend/casanostra/2012/images/logo.png"></a></td>
                    </tr>
                </tbody></table>

                
                <table  cellspacing="0" cellpadding="0" border="0">
                    <tbody><tr>
                        <td valign="top">
                            <p><strong>Prezado(a) '.$customer['firstname'].'</strong>,<br>
                            Você está recebendo está notificação por que você se inscreveu para receber um alerta quando o seguinte produto volta-se ao estoque:<br>

                            </p>

				<table style="border:1px solid #bebcb7;padding:13px 18px;background:#f8f7f5; width:100%">
				  <tbody>
			  
					<tr>
					  <td width="80"><a target="_blank" href="'.$_product->getProductUrl().'"><img width="75" height="75" border="0" align="left" alt="" src="'.$_product->getImageUrl().'"></a></td>
					  <td><div><a target="_blank" href="'.$_product->getProductUrl().'"><b>'.$_product->getName().'</b></a></div>
						<p><small>'.$_product->getName().'</small></p>
						<p> <span>R$ '.number_format($_product->getPrice(), 2, ',', ' ').'</span></p>
					  </td>
					</tr>
				  </tbody>
				</table>
                                
                          <p></p>
                          <p>Se você possuir quaisquer dúvidas acerca de sua conta ou qualquer outro assunto, por favor, sinta-se à vontade para nos contactar pelo e-mail <a target="_blank" style="color:#1e7ec8" href="mailto:sac@casanostracosmeticos.com.br">sac@casanostracosmeticos.com.<wbr></wbr>br</a> ou pelo telefone (21) 2411-8411</p>
                            <p>Obrigado!</p>
                        <p></p><p></p><p></p></td>
                    </tr>
                </tbody></table>
            </td>
        </tr>
    </tbody>
	';
 

	$headers = "Content-type: text/html; charset=iso-8859-1\r\n";
	$remetente = 'sac@casanostracosmeticos.com.br';
	$headers.=  "From: CasaNostra Cosméticos <$remetente>";

	//echo $alert['product_id'] . "</br>";


		//die;
		

	//if($customer['email'] == 'suporte@bis2bis.com.br'){
		if (mail($para, $assunto, $html, $headers)) {

		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$write->query("UPDATE product_alert_stock SET status=1 WHERE customer_id = $alert[customer_id] and product_id = $alert[product_id] ");
		
				//echo 'SUCESSO';
				
			} else {
				
				echo 'Não Foi Possivel Disparar os E-mail';
				
		}
			
	//echo $html;
	//}
		//echo $html;

			//die;

}

