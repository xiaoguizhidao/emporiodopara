<?php

    class BIS2BIS_GoogleTagManager_Model_Standard extends Mage_Core_Model_Abstract {

    	public function getGoogleAnalytics(){
    		if($this->getConfig('active_google_analytics')){
	    		if($this->getType('type_google_analytics') == "new_version"){
	    			return $this->getGoogleAnalyticsNewScript();
	    		}else if($this->getType('type_google_analytics') == "old_version"){
	    			return $this->getGoogleAnalyticsOldScript();
	    		}
	    	}
	    	return "";
    	}

    	private function getGoogleAnalyticsNewScript(){
    		$script = "<script>";
    		$script .= "
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			";
			$script .= "ga('create', '" . $this->getConfig('id_google_analytics') . "', '" . $this->getConfig('domain_google_analytics') . "');";
			$script .= "ga('send', 'pageview');";
	  		if($this->getConfig('active_google_analytics_commerce') && $this->isSuccessOrder()){
	  			$script .= $this->getGoogleAnalyticsCommerceNewScript($this->getOrder());
	  		}
	  		$script .= "</script>";
    		return $script;
    	}

    	private function getGoogleAnalyticsOldScript(){
    		$script = "<script>";
    		$script .= "
				(function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
			    })();
			";
			$script .= "var _gaq = _gaq || [];";
			$script .= "_gaq.push(['_setAccount', '" . $this->getConfig('id_google_analytics') . "']);";
			$script .= "_gaq.push(['_trackPageview']);";
	  		if($this->getConfig('active_google_analytics_commerce') && $this->isSuccessOrder()){
	  			$script .= $this->getGoogleAnalyticsCommerceOldScript($this->getOrder());
	  		}
	  		$script .= "</script>";
    		return $script;
    	}

    	private function getGoogleAnalyticsCommerceNewScript($_order){
			$script = "ga('require', 'ecommerce', 'ecommerce.js');";
			$script .= "
				ga('ecommerce:addTransaction', {
			  		id: '" . $_order->getIncrementId() . "',
			  		affiliation: '" . Mage::app()->getStore()->getName() . "',
			  		revenue: '" . $_order->getGrandTotal() . "',
			  		shipping: '" . $_order->getShippingInclTax() . "',
			  		tax: '" . $_order->getDiscountAmount() . "'
				});
			";
			foreach($_order->getItemsCollection() as $item){
			    $script .= "
			    	ga('ecommerce:addItem', {
			      		id: '" . $_order->getIncrementId() . "',
			     		name: '" . $item->getName() . "',
			     		sku: '" . $item->getSku() . "',
			     		category: '',
			     		price: '" . $item->getPriceInclTax() ."',
			     		quantity: '" . intval($item->getQtyOrdered()) . "'
			   		});
				";
			}
			$script .= "ga('ecommerce:send');";
			return $script;
    	}

    	private function getGoogleAnalyticsCommerceOldScript($_order){
			$script = "
				_gaq.push(['_addTrans',
					'" . $_order->getIncrementId() . "',
					'" . Mage::app()->getStore()->getName() . "',
					'" . $_order->getGrandTotal() . "',
					'" . $_order->getDiscountAmount() . "',
					'" . $_order->getShippingInclTax() . "',
					'" . $_order->getBillingAddress()->getCity() . "',
					'" . $_order->getBillingAddress()->getStreet(1) . "',
					'BR'
				]);
			";
			foreach($_order->getItemsCollection() as $item){
				$script .= "
					_gaq.push(['_addItem',
						'" . $_order->getIncrementId() . "',
						'" . $item->getSku() . "',
						'" . $item->getName() . "',
						'',
						'" . $item->getPriceInclTax() . "',
						'" . intval($item->getQtyOrdered()) . "'
					]);
				";
			}
			$script .= "_gaq.push(['_trackTrans']);";
			$script .= "
				(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			";
			return $script;
    	}

    	private function getOrder(){
    		return Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
    	}

    	private function isSuccessOrder(){
    		$currentUrl = rtrim(Mage::helper('core/url')->getCurrentUrl(), "/");
			$currentUrl = str_replace(":443", "", $currentUrl);
			$currentUrl = str_replace("https", "http", $currentUrl);
			if(
				$currentUrl == Mage::getBaseUrl() . 'checkout/onepage/success'
				|| $currentUrl == Mage::getBaseUrl() . 'osc/success'
				|| $currentUrl == Mage::getBaseUrl() . 'cielo/processfront/success'
				|| $currentUrl == Mage::getBaseUrl() . 'cielo/index/success'
			){
				return true;
			}
			return false;
    	}

    	public function getConfig($info){
    		return Mage::getStoreConfig('googletagmanager/config/' . $info);
    	}

    	public function getType($info){
    		return Mage::getStoreConfig('googletagmanager/type/' . $info);
    	}

    }

?>