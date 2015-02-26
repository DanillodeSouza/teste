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
            $opcoesBusca = array(
                'query' => 'teste'
            );
            $this->getServiceLocator()->get('Application\Service\Vimeo')
                ->buscar($opcoesBusca);
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }
        
		/*$client = new Client(
			$urlVimeo,
			array(
			   'sslcapath' => '/etc/ssl/certs'
			)
		);
    	$client->setMethod('GET');
    	$response = $client->send();
    	var_dump($response->getBody());die;*/
    	
       	$view =  new ViewModel();
        $view->setTemplate('index');
        $view->setTerminal(true);
        $view->setVariable('url' , 'https://vimeo.com/111248229');
        return $view;
    }
}