<?php

namespace Application\Controller\Image;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Image\Resize;

class ImageController extends AbstractActionController
{
    public function redimensionaAction()
    {
    	$url = $this->getRequest()->getQuery('url');

        $resize = new Resize($url);
        $resize->resizeImage(210, 135, 'crop');
        $resize->renderizaImagem();
    }
}
