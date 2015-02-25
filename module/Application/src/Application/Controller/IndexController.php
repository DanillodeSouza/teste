<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Header\ContentLength;
use Zend\Http\Header\ContentType;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	try {
    		$urlLinkedIn = 'updates?oauth2_access_token=AQXyCvb4gO0X2qVe84vvEMtnvKiWdfSn2W99N_vLbnNGyElO4BLb-k0Ir2XfYGMlw3hdFAAvnOkRYeiKbyCWKJyZDpUeqLjLbP7qoNRd3j2WSz_HIl9lWf7_XikTLMviTpGlUBBqOcIeyoTRWdWsXsxHPO0poRAbMRMbjvPzMZVn_--KTdc&event-type=status-update';
    		$client = new Client(
    			'https://api.linkedin.com/v1/companies/1337/updates?event-type=status-update',
    			array(
				   'sslcapath' => '/etc/ssl/certs'
				)
    		);
	    	$client->setMethod('GET');
	    	$response = $client->send();
	    	var_dump($response->getBody());die;
    	} catch(\Exception $e) {
    		var_dump($e->getMessage());die;
    	}

    	
       	return new ViewModel();
    }
}