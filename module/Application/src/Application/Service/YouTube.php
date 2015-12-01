<?php

namespace Application\Service;

use Application\Entity\Video;
use Application\Entity\Paginacao\YouTube as Paginacao;
use Zend\Http\Request;
use Zend\Http\Client;

class YouTube
{
    const URL = 'https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=10&key=AIzaSyCA6Q6e5lTi7Kp4mrDd4TNy9Z6IVzl-dLg';

	/**
    * Busca vídeos utilizando a api do YouTube a partir dos parâmetros passados
    * @param array
    * @return SplObjectStorage
    */
	public function buscar(array $options)
	{
        $client = new Client(
            self::URL . "&q=" . $options['query'],
            array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl',
                'proxyhost' => 'proxy.devel',
                'proxyport' => '8180',
                'proxyuser' => 'danillobarreto',
                'proxypass' => 'db25896',
                'timeout'   => 100,
            )
        );
        $client->setMethod(Request::METHOD_GET);

		try {
            $response = $client->send();
            $response = json_decode($response->getBody());

            $resultado = new \ArrayObject();
		    $videos = $this->extrairVideosFromResponse($response);
            $paginacao = $this->extrairPaginacaoFromResponse($response);
            $resultado->offsetSet('query', $options['query']);
            $resultado->offsetSet('videos', $videos);
            $resultado->offsetSet('paginacao', $paginacao);
            return $resultado;
		} catch(\Exception $e) {
			var_dump($e->getMessage());die;
		}
	}

    /**
    * Busca vídeos utulizando a api do YouTube a partir dos parâmetros passados
    * @param array
    * @return SplObjectStorage 
    */
    public function paginar(array $options)
    {
        $client = new Client(
            self::URL . "&q=" . $options['query'] . "&pageToken= " . $options['pageToken'],
            array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl',
                'proxyhost' => 'proxy.devel',
                'proxyport' => '8180',
                'proxyuser' => 'danillobarreto',
                'proxypass' => 'db25896',
                'timeout'   => 100,
            )
        );
        $client->setMethod(Request::METHOD_GET);

        try {
            $response = $client->send();
            $response = json_decode($response->getBody());
            $resultado = new \ArrayObject();
            $videos = $this->extrairVideosFromResponse($response);
            $paginacao = $this->extrairPaginacaoFromResponse($response);
            $resultado->offsetSet('query', $options['query']);
            $resultado->offsetSet('videos', $videos);
            $resultado->offsetSet('paginacao', $paginacao);
            return $resultado;
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }
    }

    /**
    * Extrai entidades de vídeos a partir da resposta do YouTube
    * 
    * @param array
    * @return SplObjectStorage
    **/
    private function extrairVideosFromResponse($response)
    {
    	$videos = new \SplObjectStorage();
    	foreach ($response->items as $videoData) {
    		$video = new Video();
    		$this->extrairLink($video, $videoData);
    		$video->setTitulo($videoData->snippet->title);
            $video->setUrlThumbNail(
                $videoData->snippet->thumbnails->medium->url
            );
    		$videos->attach($video);
    	}
    	return $videos;
    }

    /**
    * Extrai o link do vídeo
    * 
    * @param Application\Entity\Video
    * @param Google_Service_YouTube_SearchResult
    * @return void
    */
    private function extrairLink($video, $dados)
    {
        if ($dados->id->kind == Video::YOUTUBE_CANAL) {
            $video->setLink(Video::YOUTUBE_CANAL_SUFIXO_LINK . $dados->snippet->channelId);
        } elseif ($dados->id->kind == Video::YOUTUBE_VIDEO) {
            $video->setLink(Video::YOUTUBE_SUFIXO_LINK . $dados->id->videoId);
        }
    }

    /**
    * Extrai os dados de paginação da resposta
    *
    * @param array
    * @return Application\Entity\Paginacao
    **/
    private function extrairPaginacaoFromResponse($response)
    {
        $paginacao = new Paginacao();
        $paginacao->nextToken = $response->nextPageToken;
        $paginacao->prevToken = isset($response->prevPageToken) ? $response->prevPageToken : "";
        $paginacao->totalResults = $response->pageInfo->totalResults;

        return $paginacao;
    }

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
