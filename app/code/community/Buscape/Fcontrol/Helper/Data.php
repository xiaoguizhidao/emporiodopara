<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to suporte.developer@buscape-inc.com so we can send you a copy immediately.
 *
 * @category   Buscape
 * @package    Buscape_Fcontrol
 * @copyright  Copyright (c) 2010 Buscapé Company (http://www.buscapecompany.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Buscape_Fcontrol_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CORE = 'Buscape_Core';
    
    /**
    * log facility for fcontrol
    *
    * @param string $message
    * @param integer $level
    * @param string $file
    */
	
    /* type file generate in log */
    const LOG_TYPE_DEFAULT         = '';
    const LOG_TYPE_EXCEPTION       = 'exception_';
    const LOG_TYPE_MESSAGE_SEND    = 'send_';
    const LOG_TYPE_MESSAGE_RETURN  = 'return_';

    const LOG_TYPE_CHECK_RETURN  = 'return_check_';
    const LOG_TYPE_CHECK_EXCEPTION  = 'exception_check_';

    const LOG_TYPE_QUEUE_RETURN  = 'return_queue_';
    const LOG_TYPE_QUEUE_EXCEPTION  = 'exception_queue_';
	
    /**
     * 
     * @param string $message
     * @param integer $level
     * @param const $file
     * @param Hipxik_Bpag_Helper_Data $type
     * @return void|void
     */
    public static function log($message, $level=null, $file = '', $type = self::LOG_TYPE_DEFAULT)
    {
       static $loggers = array();
 
       $level  = is_null($level) ? Zend_Log::DEBUG : $level;
       
       if (empty($file)) {
           $file = Mage::getStoreConfig('dev/log/file');
           
           $file   = empty($file) ? $type . 'system.log' : $type . $file;
       }
 
       try {
           if (!isset($loggers[$file])) {
               $logFile = Mage::getBaseDir('var').DS.'log'.DS.'fcontrol'.DS.$file;
               $logDir = Mage::getBaseDir('var').DS.'log'.DS.'fcontrol';
 
               if (!is_dir(Mage::getBaseDir('var').DS.'log'.DS.'fcontrol')) {
                   mkdir(Mage::getBaseDir('var').DS.'log'.DS.'fcontrol', 0777);
               }
 
               if (!file_exists($logFile)) {
                   file_put_contents($logFile,'');
                   chmod($logFile, 0777);
               }
               $format = "================================================= \n";
               // Define title for this option
               switch($type)
               {
                   default:
                   // general
               	   case self::LOG_TYPE_DEFAULT:
               	       $format .= "F-Control: \n";
                       $format .= "DATE: %timestamp% %priorityName% (%priority%): " . PHP_EOL . "\n";
                       $format .= "MSG: \n %message% \n";
               	   break;
               	   case self::LOG_TYPE_MESSAGE_SEND:
               	       $format .= "Novo envio ao F-Control: \n";
                       $format .= "DATE: %timestamp% %priorityName% (%priority%): " . PHP_EOL . "\n";
                       $format .= "MSG Enviada: \n %message% \n";
               	   break;
               	   case self::LOG_TYPE_MESSAGE_RETURN:
               	       $format .= "Retorno do F-Control: \n";
                       $format .= "DATE: %timestamp% %priorityName% (%priority%): " . PHP_EOL . "\n";
                       $format .= "MSG Retorno: \n %message% \n";
               	   break;
               	   // check
               	   case self::LOG_TYPE_CHECK_RETURN:
               	       $format .= "Return Check do F-Control: \n";
                       $format .= "DATE: %timestamp% %priorityName% (%priority%): " . PHP_EOL . "\n";
                       $format .= "MSG Retorno: \n %message% \n";
               	   break;
               	   case self::LOG_TYPE_CHECK_EXCEPTION:
               	       $format .= "Exception Check do F-Control: \n";
                       $format .= "DATE: %timestamp% %priorityName% (%priority%): " . PHP_EOL . "\n";
                       $format .= "MSG Retorno: \n %message% \n";
               	   break;
               	   // queue
               	   case self::LOG_TYPE_QUEUE_RETURN:
               	       $format .= "Return Queue do F-Control: \n";
                       $format .= "DATE: %timestamp% %priorityName% (%priority%): " . PHP_EOL . "\n";
                       $format .= "MSG Retorno: \n %message% \n";
               	   break;
               	   case self::LOG_TYPE_QUEUE_EXCEPTION:
               	       $format .= "Exception Queue do F-Control: \n";
                       $format .= "DATE: %timestamp% %priorityName% (%priority%): " . PHP_EOL . "\n";
                       $format .= "MSG Retorno: \n %message% \n";
               	   break;
               }
               $format .= "================================================= \n \n";
               
               $formatter = new Zend_Log_Formatter_Simple($format);
               $writer = new Zend_Log_Writer_Stream($logFile);
               $writer->setFormatter($formatter);
               $loggers[$file] = new Zend_Log($writer);
           }
 
           if (is_array($message) || is_object($message)) {
               $message = print_r($message, true);
           }
 
           $loggers[$file]->log($message, $level);
       }
       catch (Exception $e){
			
       }
    }
    
    /**
     * Return the config value for the passed key
     */
    public function getConfig($key)
    {
        
        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        
        sort($modules);
        
        $path = 'sales/fcontrol/' . $key;
        
        if(in_array(self::CORE, $modules)) {
            
            $modules = Mage::getConfig()->getNode('modules')->children();

            /* Precisa verificar se o modulo esta habilitado para fazer migração dos nodes (melhoria prevista) */
            if($modules->Buscape_Core->active->xmlentities() == 'true') {
                // $path = 'buscape/fcontrol/' . $key;
            }
        }
        
        return Mage::getStoreConfig($path, Mage::app()->getStore());
    }
    
    /**
     * Check if the extension has been disabled in the system configuration
     *
     * @return boolean
     */
    public function moduleActive()
    {
        return (!(bool) $this->getConfig('active')); // || (!(bool) parent::getConfig('disable_ext'));
    }
    
    /**
     * Return true if the reqest is made via the api
     *
     * @return boolean
     */
    public function isRequest()
    {
        return Mage::app()->getRequest()->getModuleName() === 'api';
    }
}