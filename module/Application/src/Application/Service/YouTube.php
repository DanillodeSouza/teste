<?php

namespace Application\Service;

use Application\Entity\Video;

class YouTube
{
	const API_KEY = 'AIzaSyCA6Q6e5lTi7Kp4mrDd4TNy9Z6IVzl-dLg';

	/**
    * Busca vídeos utulizando a api do YouTube a partir dos parâmetros passados
    * @param array
    * @return SplObjectStorage 
    */
	public function buscar(array $options)
	{
		$client = new \Google_Client();
		$client->setDeveloperKey(self::API_KEY);

		$youtube = new \Google_Service_YouTube($client);

		try {
		    $response = $youtube->search->listSearch(
		    	'id,
		    	snippet',
		    	array(
		    		'q' => $options['query'],
		    		'maxResults' => 10,
		    	)
		    );
		    return $this->extrairVideosFromResponse($response);
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
}