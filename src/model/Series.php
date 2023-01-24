<?php

//Représente une série télévisée
class Series {

	private String $name;
	private String $creator;
	private String $origins;
	private int $diffusion;
	private int $seasons;

	public function __construct(String $name, String $creator, String $origins, int $diffusion, int $seasons) {
		$this->name = $name;
		$this->creator = $creator;
		$this->origins = $origins;
		$this->diffusion = $diffusion;
		$this->seasons = $seasons;
	}
	
	//Renvoie le nom de la série
	public function getName() {
		return $this->name;
	}
	
	//Renvoie le créateur de la série
	public function getCreator() {
		return $this->creator;
	}

	//Renvoie l'origine de la série
	public function getOrigins(){
		return $this->origins;
	}
	
	//Renvoie l'année de la première diffusion de la série
	public function getDiffusion(){
		return $this->diffusion;
	}

	//Renvoie le nombre de saisons de la série
	public function getSeasons() {
		return $this->seasons;
	}

	//Modifie le nom de la série
	public function setName($name) {
		$this->name = $name;
	}

	//Modifie le créateur de la série
	public function setCreator($creator){
		$this->creator = $creator;
	}

	//Modifie l'origine de la série
	public function setOrigins($origins){
		$this->origins = $origins;
	}

	//Modifie l'année de la première diffusion de la série
	public function setDiffusion($diffusion){
		$this->diffusion = $diffusion;
	}
		
	//Modifie le nombre de saisons de la série
	public function setSeasons($seasons){
		$this->seasons = $seasons;
	}
}

?>
