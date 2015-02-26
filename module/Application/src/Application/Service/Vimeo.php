<?php

namespace Application\Service;

class Vimeo
{
	const CLIENT_ID = "15800d3ce49265a5e599bbb4253453418f726797";
    const CLIENT_SECRET = "0431e781019d4917be0f03fe4e723390d84aa4cc";
    const ACCESSTOKEN = "e49cac72b09f3c684d722233e4bf6676";

    public function buscar(array $options)
    {
    	if (!$this->validarParametros($options)) {
    		throw new \Exception("O parâmetro query é obrigatório.");
    	}

    	$lib = new \Vimeo\Vimeo(self::CLIENT_ID, self::CLIENT_SECRET);
        $lib->setToken(self::ACCESSTOKEN);
        $scope = array();
        $response = $lib->request(
            '/videos',
            array(
                'query' => $options['query'],
                'page' => 1,
                'per_page' => 2
            ),
            'GET'
        );
        $urlPrimeiroVideo = $response['body']['data'][0]['uri'];
    }

    private function validarParametros(array $options)
    {
    	if (isset($options['query'])) {
    		return true;
    	}
    	return false;
    }
}