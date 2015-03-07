<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class BuscarController extends AbstractActionController
{
    public function indexAction()
    {
    	$opcoesBusca = $this->getRequest()->getPost()->toArray();

    	try {
            $resultadoVimeo = $this->getServiceLocator()->get('Application\Service\Vimeo')
                ->buscar($opcoesBusca);
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }

        try {
            $youtubeService = $this->getServiceLocator()->get('Application\Service\YouTube');
            $youtubeService->setServiceLocator($this->getServiceLocator());
            $resultadoYouTube = $youtubeService->buscar($opcoesBusca);
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }

        $view =  new ViewModel();
        $view->setTemplate('resultado');
        $view->setTerminal(true);
        $view->setVariable('resultadoVimeo' , $resultadoVimeo);
        $view->setVariable('resultadoYouTube' , $resultadoYouTube);

        return $view;
    }

    public function paginarYoutubeAction()
    {
        $opcoesBusca = $this->getRequest()->getPost()->toArray();

        try {
            $youtubeService = $this->getServiceLocator()->get('Application\Service\YouTube');
            $resultadoYouTube = $youtubeService->paginar($opcoesBusca);
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }

        $view = new ViewModel();
        $view->setTemplate('include/videosYoutube');
        $view->setVariable('resultadoYouTube' , $resultadoYouTube);
        $view->setTerminal(true);
        return $view;
    }

    public function paginarVimeoAction()
    {
        $opcoesBusca = $this->getRequest()->getPost()->toArray();

        try {
            $vimeoService = $this->getServiceLocator()->get('Application\Service\Vimeo');
            $resultadoVimeo = $vimeoService->paginar($opcoesBusca);
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }

        $view = new ViewModel();
        $view->setTemplate('include/videosVimeo');
        $view->setVariable('resultadoVimeo' , $resultadoVimeo);
        $view->setTerminal(true);
        return $view;
    }
}
