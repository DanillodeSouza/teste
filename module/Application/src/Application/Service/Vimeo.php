<?php

namespace Application\Service;

use Application\Entity\Video;
use Application\Entity\Paginacao\Vimeo as Paginacao;

class Vimeo
{
	const CLIENT_ID = "15800d3ce49265a5e599bbb4253453418f726797";
    const CLIENT_SECRET = "0431e781019d4917be0f03fe4e723390d84aa4cc";
    const ACCESSTOKEN = "e49cac72b09f3c684d722233e4bf6676";

    /**
    * Busca v�deos utilizando a api do Vimeo a partir dos par�metros passados
    * @param array
    * @return SplObjectStorage 
    */
    public function buscar(array $options)
    {
    	if (!$this->validarParametros($options)) {
    		throw new \Exception("O par�metro query � obrigat�rio.");
    	}

    	$lib = new \Vimeo\Vimeo(self::CLIENT_ID, self::CLIENT_SECRET);
        $lib->setToken(self::ACCESSTOKEN);
        $scope = array();
        $response = $lib->request(
            '/videos',
            array(
                'query' => $options['query'],
                'per_page' => 10
            ),
            'GET'
        );
        $resultado = new \ArrayObject();
        $videos = $this->extrairVideosFromResponse($response);
        $paginacao = $this->extrairPaginacaoFromResponse($response);
        $resultado->offsetSet('query', $options['query']);
        $resultado->offsetSet('videos', $videos);
        $resultado->offsetSet('paginacao', $paginacao);
        return $resultado;
    }

    /**
    * Busca v�deos utilizando a api do Vimeo a partir dos par�metros passados
    * @param array opcoesBusca
    * @return SplObjectStorage 
    */
    public function paginar($opcoesBusca)
    {
        $lib = new \Vimeo\Vimeo(self::CLIENT_ID, self::CLIENT_SECRET);
        $lib->setToken(self::ACCESSTOKEN);
        $scope = array();
        $response = $lib->request(
            '/videos',
            array(
                'query' => $opcoesBusca['/videos?query'],
                'page' => $opcoesBusca['page'],
                'per_page' => $opcoesBusca['per_page']
            ),
            'GET'
        );
        $resultado = new \ArrayObject();
        $videos = $this->extrairVideosFromResponse($response);
        $paginacao = $this->extrairPaginacaoFromResponse($response);
        $resultado->offsetSet('query', $opcoesBusca['/videos?query']);
        $resultado->offsetSet('videos', $videos);
        $resultado->offsetSet('paginacao', $paginacao);
        return $resultado;
    }

    /**
    *
    * Extrai entidades de v�deos a partir da resposta do Vimeo
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
    * Para buscar v�deo no Vimeo � obrigat�rio uma palavra-chave
    * @param array op��es
    * @return boolean
    **/
    private function validarParametros(array $options)
    {
    	if (isset($options['query'])) {
    		return true;
    	}
    	return false;
    }

    /**
    * Extrai os dados de pagina��o da resposta
    *
    * @param array
    * @return Application\Entity\Paginacao
    **/
    private function extrairPaginacaoFromResponse($response)
    {
        $paginacao = new Paginacao();
        $paginacao->next = $response['body']['paging']['next'];
        $paginacao->previous = $response['body']['paging']['previous'];
        $paginacao->totalResults = $response['body']['total'];

        return $paginacao;
    }
}