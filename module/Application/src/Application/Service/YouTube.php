<?php

namespace Application\Service;

class YouTube
{
	const API_KEY = 'AIzaSyCA6Q6e5lTi7Kp4mrDd4TNy9Z6IVzl-dLg';

	public function buscar(array $options)
	{
		$client = new \Google_Client();
		$client->setDeveloperKey(self::API_KEY);

		$youtube = new \Google_Service_YouTube($client);

		try {
		    $searchResponse = $youtube->search->listSearch(
		    	'id,
		    	snippet',
		    	array(
		    		'q' => $options['query'],
		    		'maxResults' => 2,
		    	)
		    );
		} catch(\Exception $e) {
			var_dump($e->getMessage());die;
		}
	}
}