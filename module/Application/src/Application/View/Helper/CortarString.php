<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CortarString extends AbstractHelper
{
	public function __invoke($texto, $tamanhoMaximo)
    {
    	if (strlen($texto) <= $tamanhoMaximo) {
    		return $texto;
    	}
    	mb_internal_encoding("UTF-8");
    	return mb_substr($texto, 0, $tamanhoMaximo - 1). '...';
    }
}
