<?php

namespace Application\Service;

use Application\Entity\Video;
use Application\Entity\Paginacao;

class YouTube
{
	const API_KEY = 'AIzaSyCA6Q6e5lTi7Kp4mrDd4TNy9Z6IVzl-dLg';

    private $serviceLocator;

	/**
    * Busca vídeos utulizando a api do YouTube a partir dos parâmetros passados
    * @param array
    * @return SplObjectStorage
    */
	public function buscar(array $options)
	{
        $youtube = $this->getYouTubeClient();

		try {
		    $response = $youtube->search->listSearch(
		    	'id,
		    	snippet',
		    	array(
		    		'q' => $options['query'],
		    		'maxResults' => 10,
		    	)
		    );
            $resultado = new \ArrayObject();
		    $videos = $this->extrairVideosFromResponse($response);
            $paginacao = $this->extrairPaginacaoFromResponse($response, $videos);
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
        $youtube = $this->getYouTubeClient();

        try {
            $response = $youtube->search->listSearch(
                'id,
                snippet',
                array(
                    'q' => $options['query'],
                    'maxResults' => 10,
                    'pageToken' => $options['pageToken']
                )
            );
            $resultado = new \ArrayObject();
            $videos = $this->extrairVideosFromResponse($response);
            $paginacao = $this->extrairPaginacaoFromResponse($response, $videos);
            $resultado->offsetSet('query', $options['query']);
            $resultado->offsetSet('videos', $videos);
            $resultado->offsetSet('paginacao', $paginacao);
            return $resultado;
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }
    }

    /**
    *
    * @return Google_Service_YouTube
    **/
    private function getYouTubeClient()
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(self::API_KEY);

        return new \Google_Service_YouTube($client);
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
    	foreach ($response->getItems() as $videoData) {
    		$video = new Video();
    		$video->setId($videoData->getId()->getVideoId());
    		$this->extrairLink($video, $videoData);
    		$video->setTitulo($videoData->getSnippet()['title']);
            $video->setUrlThumbNail(
                $videoData->getSnippet()->getThumbnails()->getMedium()->getUrl()
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
        if ($dados->getId()->getKind() == Video::YOUTUBE_CANAL) {
            $video->setLink(Video::YOUTUBE_CANAL_SUFIXO_LINK . $dados->getSnippet()->getChannelTitle());
        } elseif ($dados->getId()->getKind() == Video::YOUTUBE_VIDEO) {
            $video->setLink(Video::YOUTUBE_SUFIXO_LINK . $dados->getId()->getVideoId());
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
        $paginacao->nextToken = $response->getNextPageToken();
        $paginacao->prevToken = $response->getPrevPageToken();
        $paginacao->totalResults = $response->getPageInfo()->getTotalResults();

        return $paginacao;
    }

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}