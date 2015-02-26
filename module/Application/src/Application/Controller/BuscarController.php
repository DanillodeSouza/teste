<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BuscarController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('resultado');
        $viewModel->setVariable('teste', 'uhlala');
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}
