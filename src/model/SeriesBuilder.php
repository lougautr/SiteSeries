<?php

require_once("model/Series.php");

//Fonctions de manipulation des séries via des formulaires
class SeriesBuilder {

	private array $data;
	private $error;
	private $NAME_REF;
    private $CREATOR_REF;
	private $ORIGINS_REF;
	private $DIFFUSION_REF;
    private $SEASONS_REF;

    public function __construct(array $data) {
		$this->data = $data;
		$this->error = null;
		$this->NAME_REF = 'name';
		$this->CREATOR_REF = 'creator';
        $this->ORIGINS_REF = 'origins';
		$this->DIFFUSION_REF = 'diffusion';
        $this->SEASONS_REF = 'seasons';
	}

    public function createSeries() {
		return new Series($this->data['name'], $this->data['creator'], $this->data['origins'], $this->data['diffusion'], $this->data['seasons']);
	}

	//Renvoie une nouvelle instance de SeriesBuilder avec les données modifiables de la série passée en argument
	  public static function buildFromSeries(Series $s) {
		return new SeriesBuilder(array(
			"name" => $s->getName(),
			"creator" => $s->getCreator(),
			"origins" => $s->getOrigins(),
            "diffusion" => $s->getDiffusion(),
            "seasons" => $s->getSeasons(),
		));
	}

    //Met à jour une série
	public function updateSeries(Series $s) {
		if (key_exists("name", $this->data)){
			$s->setName($this->data["name"]);
		}
		if (key_exists("creator", $this->data)){
			$s->setCreator($this->data["creator"]);
		}
		if (key_exists("origins", $this->data)){
			$s->setOrigins($this->data["origins"]);
		}
        if (key_exists("diffusion", $this->data)){
            $s->setDiffusion($this->data["diffusion"]);
        }
        if (key_exists("seasons", $this->data)){
            $s->setSeasons($this->data['seasons']);
        }
	}
        
    //Vérifie si une série est valide 
    public function isValid() {
		$this->error = array();
		if (!key_exists('name', $this->data) || $this->data['name'] === "")
			$this->error['name'] = "Vous devez entrer un nom";
		if (!key_exists('creator', $this->data) || $this->data['creator'] === "")
			$this->error['creator'] = "Vous devez entrer un créateur";
        if (!key_exists('origins', $this->data) || $this->data['origins'] === "")
			$this->error['origins'] = "Vous devez entrer une origine";
        if (!key_exists('diffusion', $this->data) || $this->data['diffusion'] <= 0)
			$this->error['diffusion'] = "Vous devez entrer une année de première diffusion";
        if (!key_exists('seasons', $this->data) || $this->data['seasons'] <= 0)
			$this->error['seasons'] = "Vous devez entrer un nombre de saisons";            
		return count($this->error) === 0;
	}

    //Renvoie le nom d'une série si elle existe
	public function getName() {
        if(key_exists('name', $this->data))
		    return $this->data['name'];
        return $this->error;
	}

	//Renvoie le créateur d'une série si elle existe
	public function getCreator() {
        if(key_exists('creator', $this->data))
		    return $this->data['creator'];
        return $this->error;
	}

    //Renvoie l'origine d'une série si elle existe
	public function getOrigins() {
        if(key_exists('origins', $this->data))
		    return $this->data['origins'];
        return $this->error;
	}

    //Renvoie l'année de la première diffusion d'une série si elle existe
	public function getDiffusion() {
        if(key_exists('diffusion', $this->data))
		    return $this->data['diffusion'];
        return $this->error;
	}

    //Renvoie le nombre de la saisons d'une série si elle existe
	public function getSeasons() {
        if(key_exists('seasons', $this->data))
		    return $this->data['seasons'];
        return $this->error;
	}

    //Renvoie le paramètre error
    public function getError($ref){
        if($this->error != null){
			if(key_exists($ref, $this->error)){
				return $this->error[$ref];
            //foreach ($this->error as $e){
            //    return $e;
            }
        }
        return null;
    }

    //Renvoie la «référence» du champ représentant le nom d'une série
	public function getNameRef() {
		return $this->NAME_REF;
	}

    //Renvoie la «référence» du champ représentant le créateur d'une série
	public function getCreatorRef() {
		return $this->CREATOR_REF;
	}

    //Renvoie la «référence» du champ représentant l'origine d'une série
	public function getOriginsRef() {
		return $this->ORIGINS_REF;
	}

    //Renvoie la «référence» du champ représentant l'année de la première diffusion d'une série
	public function getDiffusionRef() {
		return $this->DIFFUSION_REF;
	}

    //Renvoie la «référence» du champ représentant le nombre de saisons d'une série
	public function getSeasonsRef() {
		return $this->SEASONS_REF;
	}

}
?>
