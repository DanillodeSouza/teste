<?php

namespace Application\Service;

use Application\Entity\Video;
use Application\Entity\Paginacao\Vimeo as Paginacao;
use Vimeo\Vimeo as VimeoClass;
use Zend\Http\Request;
use Zend\Http\Client;

class Vimeo
{
	const CLIENT_ID = "15800d3ce49265a5e599bbb4253453418f726797";
    const CLIENT_SECRET = "0431e781019d4917be0f03fe4e723390d84aa4cc";
    const ACCESSTOKEN = "e49cac72b09f3c684d722233e4bf6676";

    /**
    * Busca vídeos utilizando a api do Vimeo a partir dos parâmetros passados
    * @param array
    * @return SplObjectStorage 
    */
    public function buscar(array $options)
    {
    	if (!$this->validarParametros($options)) {
    		throw new \Exception("O parâmetro query é obrigatório.");
    	}

        $client = new Client(
            VimeoClass::ROOT_ENDPOINT . "/videos" . "?query= " . $options["query"] .
                "&per_page=10",
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
        $headers = array(

        );
        $headers[] = 'Accept: ' . VimeoClass::VERSION_STRING;
        $headers[] = 'User-Agent: ' . VimeoClass::USER_AGENT;
        $headers[] = 'Authorization: Bearer ' . self::ACCESSTOKEN;

        $client->setHeaders($headers);
        $response = $client->send();
        $response = json_decode($response->getBody());
        $resultado = new \ArrayObject();
        $videos = $this->extrairVideosFromResponse($response);
        $paginacao = $this->extrairPaginacaoFromResponse($response);
        $resultado->offsetSet('query', $options['query']);
        $resultado->offsetSet('videos', $videos);
        $resultado->offsetSet('paginacao', $paginacao);
        return $resultado;
    }

    /**
    *
    * Extrai entidades de vídeos a partir da resposta do Vimeo
    * @param StdClass
    * @return SplObjectStorage
    **/
    private function extrairVideosFromResponse($response)
    {
        $videos = new \SplObjectStorage();
        foreach ($response->data as $videoData) {
            $video = new Video();
            $videoId = str_replace('/videos/', '', $videoData->uri);
            $video->setId($videoId);
            $video->setLink(Video::VIMEO_SUFIXO_LINK . $videoId);
            $video->setTitulo($videoData->name);
            if (isset($videoData->pictures->sizes[1]->link)) {
                $video->setUrlThumbNail($videoData->pictures->sizes[1]->link);
            }
            $videos->attach($video);
        }
        return $videos;
    }

    /**
    * Busca vídeos utilizando a api do Vimeo a partir dos parâmetros passados
    * @param array opcoesBusca
    * @return SplObjectStorage 
    */
    public function paginar($opcoesBusca)
    {
        $client = new Client(
            VimeoClass::ROOT_ENDPOINT . "/videos" . "?query= " . $opcoesBusca["/videos?query"] .
            "&page= " . $opcoesBusca["page"] . "&per_page=10",
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
        $headers = array(

        );
        $headers[] = 'Accept: ' . VimeoClass::VERSION_STRING;
        $headers[] = 'User-Agent: ' . VimeoClass::USER_AGENT;
        $headers[] = 'Authorization: Bearer ' . self::ACCESSTOKEN;

        $client->setHeaders($headers);
        $response = $client->send();
        $response = json_decode($response->getBody());

        $resultado = new \ArrayObject();
        $videos = $this->extrairVideosFromResponse($response);
        $paginacao = $this->extrairPaginacaoFromResponse($response);
        $resultado->offsetSet('query', $opcoesBusca['/videos?query']);
        $resultado->offsetSet('videos', $videos);
        $resultado->offsetSet('paginacao', $paginacao);
        return $resultado;
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

    /**
    * Extrai os dados de paginação da resposta
    *
    * @param array
    * @return Application\Entity\Paginacao
    **/
    private function extrairPaginacaoFromResponse($response)
    {
        $paginacao = new Paginacao();
        $paginacao->next = $response->paging->next;
        $paginacao->previous = $response->paging->previous;
        $paginacao->totalResults = $response->total;

        return $paginacao;
    }
}
