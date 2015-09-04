<?php 

class BIS2BIS_Filtro_Model_Cores
{


    const SEM_MODULO = 1;
    const ANTIGO_MODULO = 2;
    const V1_MODULO = 3;
    const V2_MODULO = 4;
    const UPLOAD_IMAGEM = 5;
    const DESABILITADO = 0;
    /**
     * Fetch options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'label' => 'Desabilitado',
                'value' => self::DESABILITADO),
            array(
               'label' => 'Não utilizar nenhum módulo',
               'value' => self::SEM_MODULO),
            array(
               'label' => 'Módulo Antigo',
               'value' => self::ANTIGO_MODULO),
            array(
                'label' => 'Módulo novo V1',
                'value' => self::V1_MODULO),
            array(
                'label' => 'Módulo novo V2',
                'value' => self::V2_MODULO),
            array(
                'label' => 'Upload de Imagem',
                'value' => self::UPLOAD_IMAGEM),

        );
    }
}


?>
