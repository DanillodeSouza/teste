<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BuscarController extends AbstractActionController
{
    public function indexAction()
    {
    	$opcoesBusca = $this->getRequest()->getPost()->toArray();

    	try {
            $opcoesBusca = array(
                'query' => 'teste'
            );
            $this->getServiceLocator()->get('Application\Service\Vimeo')
                ->buscar($opcoesBusca);
        } catch(\Exception $e) {
            //var_dump($e->getMessage());die;
        }

        try {
            $opcoesBusca = array(
                'query' => 'teste'
            );
            $this->getServiceLocator()->get('Application\Service\YouTube')
                ->buscar($opcoesBusca);
        } catch(\Exception $e) {
            //var_dump($e->getMessage());die;
        }

        $view =  new ViewModel();
        $view->setTemplate('resultado');
        $view->setTerminal(true);
        $view->setVariable('url' , 'https://vimeo.com/111248229');
        return $view;

        $viewModel = new ViewModel();
        $viewModel->setTemplate('resultado');
        $viewModel->setVariable('teste', 'uhlala');
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}
