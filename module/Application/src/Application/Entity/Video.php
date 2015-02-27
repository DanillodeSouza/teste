<?php

namespace Application\Entity;

class Video
{
	const YOUTUBE_SUFIXO_LINK = 'https://www.youtube.com/watch?v=';
	const YOUTUBE_THUMBNAIL_LINK = 'https://i.ytimg.com/vi/%s/mqdefault.jpg';
	const VIMEO_SUFIXO_LINK = 'https://www.vimeo.com/';
	
	private $id;
	private $titulo;
	private $link;
	private $urlThumbNail;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function getTitulo()
	{
		return $this->titulo;
	}

	public function setTitulo($titulo)
	{
		$this->titulo = $titulo;
		return $this;
	}

	public function getLink()
	{
		return $this->link;
	}

	public function setLink($link)
	{
		$this->link = $link;
		return $this;
	}

	public function getUrlThumbNail()
	{
		return $this->urlThumbNail;
	}

	public function setUrlThumbNail($urlThumbNail)
	{
		$this->urlThumbNail = $urlThumbNail;
		return $this;
	}
}