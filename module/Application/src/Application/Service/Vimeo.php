<?php

namespace Application\Service;

use Application\Entity\Video;

class Vimeo
{
	const CLIENT_ID = "15800d3ce49265a5e599bbb4253453418f726797";
    const CLIENT_SECRET = "0431e781019d4917be0f03fe4e723390d84aa4cc";
    const ACCESSTOKEN = "e49cac72b09f3c684d722233e4bf6676";

    /**
    * Busca vídeos utulizando a api do Vimeo a partir dos parâmetros passados
    * @param array
    * @return SplObjectStorage 
    */
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
                'per_page' => 10
            ),
            'GET'
        );

        return $this->extrairVideosFromResponse($response);
    }

    /**
    *
    * Extrai entidades de vídeos a partir da resposta do Vimeo
    * @param array
    * @return SplObjectStorage
    **/
    private function extrairVideosFromResponse($response)
    {
    	$videos = new \SplObjectStorage();
    	foreach ($response['body']['data'] as $videoData) {
    		$video = new Video();
            $videoId = str_replace('/videos/', '', $videoData['uri']);
    		$video->setId($videoId);
    		$video->setLink(Video::VIMEO_SUFIXO_LINK . $videoId);
    		$video->setTitulo($videoData['name']);
    		$video->setUrlThumbNail($videoData['pictures']['sizes'][1]['link']);
    		$videos->attach($video);
    	}
    	return $videos;
    }

    /**
    * Para buscar vídeo no Vimeo é obrigatório uma palavra-chave
    * @param array opções
    * @return boolean
    **/
    private function validarParametros(array $options)
    {
    	if (isset($options['query'])) {
    		return true;
    	}
    	return false;
    }
}