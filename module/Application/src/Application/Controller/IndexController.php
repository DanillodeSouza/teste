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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	try {
            $clientId = "15800d3ce49265a5e599bbb4253453418f726797";
            $clientSecret = "0431e781019d4917be0f03fe4e723390d84aa4cc";
            $accessToken = "e49cac72b09f3c684d722233e4bf6676";
            $lib = new \Vimeo\Vimeo($clientId, $clientSecret);
            $lib->setToken($accessToken);
            $scope = array();
    		$urlVimeo = 'https://api.vimeo.com/videos';
            $response = $lib->request(
                '/videos',
                array(
                    'search' => 'teste',
                    'per_page' => 2
                ),
                'GET'
                );
            var_dump($response);die;
    		$client = new Client(
    			$urlVimeo,
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