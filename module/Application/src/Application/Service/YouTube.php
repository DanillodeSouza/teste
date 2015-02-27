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
    *
    * Extrai entidades de vídeos a partir da resposta do YouTube
    * @param array
    * @return SplObjectStorage
    **/
    private function extrairVideosFromResponse($response)
    {
    	$videos = new \SplObjectStorage();
    	foreach ($response->getItems() as $videoData) {
    		$video = new Video();
    		$video->setId($videoData->getId()->getVideoId());
    		$video->setLink(Video::YOUTUBE_SUFIXO_LINK . $videoData->getId()->getVideoId());
    		$video->setTitulo($videoData->getSnippet()['title']);
    		$video->setUrlThumbNail(
    			sprintf(
    				Video::THUMBNAIL_LINK,
    				$videoData->getId()->getVideoId()
    			)
    		);
    		$videos->attach($video);
    	}
    	return $videos;
    }
}