<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
      <?php echo $dadosboleto["identificacao"]; ?>
    </title>
    <style type="text/css">
    /*<![CDATA[*/
      .cp { 
        font: bold 10px Arial;
        color: #000000
      }
      .ti { 
        font: 9px Arial, Helvetica, sans-serif
      }
      .ld { 
        font: bold 15px Arial;
        color: #000000
      }
      .ct { 
        font: 9px "Arial Narrow";
        color: #000033
      } 
      .cn { 
        font: 9px Arial;
        color: black
      }
      .bc { 
        font: bold 20px Arial;
        color: #000000
      }
      .ld2 { 
        font: bold 12px Arial;
        color: #000000
      }
      * {
        font-size: 10px;
      }
      td.ct {
        font-size: 9px;
      }
      td {
        padding: 0 !important;
        margin: 0 !important;
      }
    /*]]>*/
    </style>
  </head>
  <body text="#000000" bgcolor="#FFFFFF" topmargin="0" rightmargin=
  "0">
    <table width="666" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td valign="top" class="cp">
          <div align="center">
            <b>Instru��es de Impress�o</b>
          </div>
        </td>
      </tr>
      <tr>
        <td valign="top" class="cp">
          <div align="left">
            <ul>
              <li>Imprima em impressora jato de tinta (ink jet) ou
              laser em qualidade normal ou alta (N�o use modo
              econ�mico).<br />
              </li>
              <li>Utilize folha A4 (210 x 297 mm) ou Carta (216 x
              279 mm) e margens m�nimas � esquerda e � direita do
              formul�rio.<br />
              </li>
              <li>Corte na linha indicada. N�o rasure, risque, fure
              ou dobre a regi�o onde se encontra o c�digo de
              barras.<br />
              </li>
              <li>Caso n�o apare�a o c�digo de barras no final,
              clique em F5 para atualizar esta tela.
              </li>
              <li>Caso tenha problemas ao imprimir, copie a
              seq�encia num�rica abaixo e pague no caixa eletr�nico
              ou no internet banking:<br />
                <br />
                <b><span class="ld2" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;Linha
                Digit�vel:
                &nbsp;<?php echo $dadosboleto["linha_digitavel"]?><br />

                &nbsp;&nbsp;&nbsp;&nbsp;Valor: &nbsp;&nbsp;R$
                <?php echo $dadosboleto["valor_boleto"]?><br /></span></b>
              </li>
            </ul>
          </div>
        </td>
      </tr>
    </table>
    <p>
      <br />
    </p>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tbody>
        <tr>
          <td class="ct" width="666">
            <img height="1" src="media/boleto_ultimate/6.png" width="665" border=
            "0" />
          </td>
        </tr>
        <tr>
          <td class="ct" width="666">
            <div align="right">
              <strong class="cp">Recibo do Sacado</strong>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <table width="666" cellspacing="5" cellpadding="0" border="0">
      <tr>
        <td width="41"></td>
      </tr>
    </table>
    <table width="666" cellspacing="5" cellpadding="0" border="0"
    align="default">
      <tr>
        <td width="41">
          <?php $src_logoempresa = 'media/boleto_ultimate/' . $dadosboleto["logoempresa"]; ?>
          <?php if(isset($dadosboleto["logoempresa"]) && file_exists($src_logoempresa)): ?>
            <img src="<?php echo $src_logoempresa ?>"/>
          <?php endif ?>
        </td>
        <td class="ti" width="455" style="font-size:9px;">
          <?php echo $dadosboleto["identificacao"]; ?><?php echo isset($dadosboleto["cpf_cnpj"]) ? "<br>".$dadosboleto["cpf_cnpj"] : '' ?><br />

          <?php echo $dadosboleto["endereco"]; ?><br />
          <?php echo $dadosboleto["cidade_uf"]; ?><br />
        </td>
        <td align="right" width="150" class="ti">
          &nbsp;
        </td>
      </tr>
    </table>
    <p>
      <br />
    </p>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tr>
        <td class="cp" width="150">
          <span class="campo"><img src="media/boleto_ultimate/logohsbc.jpg"
          width="144" height="30" border="0" /></span>
        </td>
        <td width="3" valign="bottom">
          <img height="22" src="media/boleto_ultimate/3.png" width="2" border=
          "0" />
        </td>
        <td class="cpt" width="58" valign="bottom">
          <div align="center">
            <font class=
            "bc" style="font-size:20px; font-weight:bold;"><?php echo $dadosboleto["codigo_banco_com_dv"]?></font>
          </div>
        </td>
        <td width="3" valign="bottom">
          <img height="22" src="media/boleto_ultimate/3.png" width="2" border=
          "0" />
        </td>
        <td class="ld" align="right" width="453" valign="bottom">
          <span class="ld"><span class=
          "campotitulo" style="font-size:15px; font-weight:bold;"><?php echo $dadosboleto["linha_digitavel"]?></span></span>
        </td>
      </tr>
      <tbody>
        <tr>
          <td colspan="5">
            <img height="2" src="media/boleto_ultimate/2.png" width="666" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="298" height="13">
            Cedente
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="126" height="13">
            Ag�ncia/C�digo do Cedente
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="34" height="13">
            Esp�cie
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="53" height="13">
            Quantidade
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="120" height="13">
            Nosso n�mero
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="298" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["cedente"]; ?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="126" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["agencia_codigo"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="34" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["especie"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="53" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["quantidade"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="120"
          height="12">
            <span class=
            "campo"><?php echo $dadosboleto["nosso_numero"]?></span>
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="298" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="298" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="126" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="126" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="34" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="34" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="53" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="53" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="120" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="120" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" colspan="3" height="13">
            N�mero do documento
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="132" height="13">
            CPF/CNPJ
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="134" height="13">
            Vencimento
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="180" height="13">
            Valor documento
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" colspan="3" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["numero_documento"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="132" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["cpf_cnpj"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="134" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["data_vencimento"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="180"
          height="12">
            <span class=
            "campo"><?php echo $dadosboleto["valor_boleto"]?></span>
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="113" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="113" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="72" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="72" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="132" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="132" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="134" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="134" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="180" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="180" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="113" height="13">
            (-) Desconto / Abatimentos
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="112" height="13">
            (-) Outras dedu��es
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="113" height="13">
            (+) Mora / Multa
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="113" height="13">
            (+) Outros acr�scimos
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="180" height="13">
            (=) Valor cobrado
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="113"
          height="12"></td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="112"
          height="12"></td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="113"
          height="12"></td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="113"
          height="12"></td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="180"
          height="12"></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="113" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="113" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="112" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="112" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="113" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="113" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="113" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="113" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="180" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="180" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="659" height="13">
            Sacado
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="659" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["sacado"]?></span>
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="659" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="659" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" width="7" height="12"></td>
          <td class="ct" width="564">
            Demonstrativo
          </td>
          <td class="ct" width="7" height="12"></td>
          <td class="ct" width="88">
            Autentica��o mec�nica
          </td>
        </tr>
        <tr>
          <td width="7"></td>
          <td class="cp" width="564">
            <span class=
            "campo"><?php echo $dadosboleto["demonstrativo1"]?><br />

            <?php echo $dadosboleto["demonstrativo2"]?><br />
            <?php echo $dadosboleto["demonstrativo3"]?><br /></span>
          </td>
          <td width="7"></td>
          <td width="88"></td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tbody>
        <tr>
          <td width="7"></td>
          <td width="500" class="cp">
            <br />
            <br />
            <br />
          </td>
          <td width="159"></td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tr>
        <td class="ct" width="666"></td>
      </tr>
      <tbody>
        <tr>
          <td class="ct" width="666">
            <div align="right">
              Corte na linha pontilhada
            </div>
          </td>
        </tr>
        <tr>
          <td class="ct" width="666">
            <img height="1" src="media/boleto_ultimate/6.png" width="665" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <p>
      <br />
    </p>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tr>
        <td class="cp" width="150">
          <span class="campo"><img src="media/boleto_ultimate/logohsbc.jpg"
          width="144" height="30" border="0" /></span>
        </td>
        <td width="3" valign="bottom">
          <img height="22" src="media/boleto_ultimate/3.png" width="2" border=
          "0" />
        </td>
        <td class="cpt" width="58" valign="bottom">
          <div align="center">
            <font class=
            "bc" style="font-size:20px; font-weight:bold;"><?php echo $dadosboleto["codigo_banco_com_dv"]?></font>
          </div>
        </td>
        <td width="3" valign="bottom">
          <img height="22" src="media/boleto_ultimate/3.png" width="2" border=
          "0" />
        </td>
        <td class="ld" align="right" width="453" valign="bottom">
          <span class="ld"><span class=
          "campotitulo" style="font-size:15px; font-weight:bold;"><?php echo $dadosboleto["linha_digitavel"]?></span></span>
        </td>
      </tr>
      <tbody>
        <tr>
          <td colspan="5">
            <img height="2" src="media/boleto_ultimate/2.png" width="666" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="472" height="13">
            Local de pagamento
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="180" height="13">
            Vencimento
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="472" height="12">
            Pag�vel em qualquer Banco at� o vencimento
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="180"
          height="12">
            <span class=
            "campo"><?php echo $dadosboleto["data_vencimento"]?></span>
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="472" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="472" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="180" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="180" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="472" height="13">
            Cedente
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="180" height="13">
            Ag�ncia/C�digo cedente
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="472" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["cedente"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="180"
          height="12">
            <span class=
            "campo"><?php echo $dadosboleto["agencia_codigo"]?></span>
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="472" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="472" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="180" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="180" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="113" height="13">
            Data do documento
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="133" height="13">
            N<u>o</u> documento
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="62" height="13">
            Esp�cie doc.
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="34" height="13">
            Aceite
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="102" height="13">
            Data processamento
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="180" height="13">
            Nosso n�mero
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="113" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["data_documento"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="133" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["numero_documento"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="62" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["especie_doc"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="34" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["aceite"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="102" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["data_processamento"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="180"
          height="12">
            <span class=
            "campo"><?php echo $dadosboleto["nosso_numero"]?></span>
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="113" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="113" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="133" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="133" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="62" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="62" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="34" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="34" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="102" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="102" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="180" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="180" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="110" colspan="3" height="13">
            Uso do banco
          </td>
          <td class="ct" valign="top" width="7" height="13" width="7">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="83" height="13">
            Carteira
          </td>
          <td class="ct" valign="top" height="13" width="7">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="43" height="13">
            Esp�cie
          </td>
          <td class="ct" valign="top" height="13" width="7">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="103" height="13">
            Quantidade
          </td>
          <td class="ct" valign="top" height="13" width="7">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="102" height="13">
            Valor Documento
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="180" height="13">
            (=) Valor documento
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td valign="top" class="cp" width="110" height="12" colspan="3">
            <div align="left"></div>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="83" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["carteira"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="43" height="12">
              <span class=
              "campo"><?php echo $dadosboleto["especie"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="103" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["quantidade"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="102" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["valor_unitario"]?></span>
          </td>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" align="right" width="180"
          height="12">
            <span class=
            "campo"><?php echo $dadosboleto["valor_boleto"]?></span>
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="75" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="31" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="31" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="83" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="83" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="43" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="43" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="103" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="103" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="102" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="102" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="180" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="180" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tbody>
        <tr>
          <td align="left" width="10">
            <table cellspacing="0" cellpadding="0" border="0"
            align="left">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="1"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td valign="top" width="469" rowspan="5">
            <font class="ct">Instru��es (Texto de responsabilidade
            do cedente)</font><br />
            <br />
            <span class="cp"><font class=
            "campo"><?php echo $dadosboleto["instrucoes1"]; ?><br />

            <?php echo $dadosboleto["instrucoes2"]; ?><br />
            <?php echo $dadosboleto["instrucoes3"]; ?><br />
            <?php echo $dadosboleto["instrucoes4"]; ?></font><br />
            <br /></span>
          </td>
          <td align="left" width="188">
            <table cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="ct" valign="top" width="180" height=
                  "13">
                    (-) Desconto / Abatimentos
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="cp" valign="top" align="left" width=
                  "180" height="12"></td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="7"
                    border="0" />
                  </td>
                  <td valign="top" width="180" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="180"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td align="left" width="10">
            <table cellspacing="0" cellpadding="0" border="0"
            align="left">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="1"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td align="left" width="188">
            <table cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="ct" valign="top" width="180" height=
                  "13">
                    (-) Outras dedu��es
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="cp" valign="top" align="left" width=
                  "180" height="12"></td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="7"
                    border="0" />
                  </td>
                  <td valign="top" width="180" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="180"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td align="left" width="10">
            <table cellspacing="0" cellpadding="0" border="0"
            align="left">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="1"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td align="left" width="188">
            <table cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="ct" valign="top" width="180" height=
                  "13">
                    (+) Mora / Multa
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="cp" valign="top" align="left" width=
                  "180" height="12"></td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="7"
                    border="0" />
                  </td>
                  <td valign="top" width="180" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="180"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td align="left" width="10">
            <table cellspacing="0" cellpadding="0" border="0"
            align="left">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="1"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td align="left" width="188">
            <table cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="ct" valign="top" width="180" height=
                  "13">
                    (+) Outros acr�scimos
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="cp" valign="top" align="left" width=
                  "180" height="12"></td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="7"
                    border="0" />
                  </td>
                  <td valign="top" width="180" height="1">
                    <img height="1" src="media/boleto_ultimate/2.png" width="180"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td align="left" width="10">
            <table cellspacing="0" cellpadding="0" border="0"
            align="left">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td align="left" width="188">
            <table cellspacing="0" cellpadding="0" border="0">
              <tbody>
                <tr>
                  <td class="ct" valign="top" width="7" height=
                  "13">
                    <img height="13" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="ct" valign="top" width="180" height=
                  "13">
                    (=) Valor cobrado
                  </td>
                </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height=
                  "12">
                    <img height="12" src="media/boleto_ultimate/1.png" width="1"
                    border="0" />
                  </td>
                  <td class="cp" valign="top" align="left" width=
                  "180" height="12"></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tbody>
        <tr>
          <td valign="top" width="666" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="666" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="659" height="13">
            Sacado
          </td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="659" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["sacado"]?></span>
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="cp" valign="top" width="7" height="12">
            <img height="12" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="659" height="12">
            <span class=
            "campo"><?php echo $dadosboleto["endereco1"]?></span>
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="cp" valign="top" width="472" height="13">
            <span class=
            "campo"><?php echo $dadosboleto["endereco2"]?></span>
          </td>
          <td class="ct" valign="top" width="7" height="13">
            <img height="13" src="media/boleto_ultimate/1.png" width="1" border=
            "0" />
          </td>
          <td class="ct" valign="top" width="180" height="13">
            C�d. baixa
          </td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="472" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="472" border=
            "0" />
          </td>
          <td valign="top" width="7" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="7" border=
            "0" />
          </td>
          <td valign="top" width="180" height="1">
            <img height="1" src="media/boleto_ultimate/2.png" width="180" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="666">
      <tbody>
        <tr>
          <td class="ct" width="7" height="12"></td>
          <td class="ct" width="409">
            Sacador/Avalista
          </td>
          <td class="ct" width="250">
            <div align="right">
              Autentica��o mec�nica - <strong class="cp">Ficha de
              Compensa��o</strong>
            </div>
          </td>
        </tr>
        <tr>
          <td class="ct" colspan="3"></td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tbody>
        <tr>
          <td valign="bottom" align="left" height="50">
            <?php fbarcode($dadosboleto["codigo_barras"]); ?>
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" width="666" border="0">
      <tr>
        <td class="ct" width="666"></td>
      </tr>
      <tbody>
        <tr>
          <td class="ct" width="666">
            <div align="right">
              Corte na linha pontilhada
            </div>
          </td>
        </tr>
        <tr>
          <td class="ct" width="666">
            <img height="1" src="media/boleto_ultimate/6.png" width="665" border=
            "0" />
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>