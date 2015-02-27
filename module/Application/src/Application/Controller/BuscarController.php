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
            $videosVimeo = $this->getServiceLocator()->get('Application\Service\Vimeo')
                ->buscar($opcoesBusca);
        } catch(\Exception $e) {
            //var_dump($e->getMessage());die;
        }

        try {
            $videosYouTube = $this->getServiceLocator()->get('Application\Service\YouTube')
                ->buscar($opcoesBusca);
        } catch(\Exception $e) {
            //var_dump($e->getMessage());die;
        }

        $view =  new ViewModel();
        $view->setTemplate('resultado');
        $view->setTerminal(true);
        $view->setVariable('videosVimeo' , $videosVimeo);
        $view->setVariable('videosYouTube' , $videosYouTube);

        return $view;
    }
}
