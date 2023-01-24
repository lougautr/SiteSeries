<?php

require_once("view/View.php");
require_once("model/Series.php");
require_once("model/SeriesBuilder.php");

class Controller {
	private View $view;
	private SeriesStorage $db;
	
	public function __construct(View $view, SeriesStorage $db){
		$this->view = $view;
		$this-> db = $db;
	}
	
	//Fonction qui affiche les informations d'une série
	public function showInformation($id) {
		if(key_exists($id, $this->db->readAll())){
			//On affiche la page de la série
			$this->view->makeSeriesPage($this->db->read($id), $id);
		}
		else{
			//On affiche la page de série inconnue
			$this->view->makeUnknownSeriesPage();
		}
	}
	
	//Fonction qui affiche la liste des séries présentes dans la base de données
	public function showList(){
		$this->view->makeListPage($this->db->readAll());
	}

	//Fonction qui affiche la liste par ordre alphaétique
	public function showListOrderASC(){
		$this->view->makeListPage($this->db->orderByASC());
	}

	//Fonction qui affiche la liste par ordre alphaétique inverse
	public function showListOrderDES(){
		$this->view->makeListPage($this->db->orderByDES());
	}

	//Fonction qui affiche la page "à propos"
	public function showAbout(){
		$this->view->makeAboutPage();
	}

	//Fonction qui sauvegarde la nouvelle série créée dans la base de données
	public function saveNewSeries(array $data){
		$seriesBuilder = new SeriesBuilder($data);

		if ($seriesBuilder->isValid()) {
			//On construit la nouvelle série
			$series = $seriesBuilder->createSeries($data);
			//On l'ajoute en base de données
			$seriesId = $this->db->create($series);
			//On prépare la page de la nouvelle série
			unset($_SESSION['currentNewSeries']);
			$this->view->displaySeriesCreationSuccess($seriesId);
		} else {
			$_SESSION['currentNewSeries'] = $seriesBuilder;
			$this->view->displaySeriesCreationFailure();
		}
	}

	//Fonction qui sauvegarde la modification de la série dans la base de données
	public function saveModification($seriesId, array $data){
		//On lit la série dans la base
		$series = $this->db->read($seriesId);
		if($series === null){
			//On affiche la page de série inconnue
			$this->view->makeUnknownSeriesPage();
		}
		else{
			$seriesBuilder = new SeriesBuilder($data);
			if ($seriesBuilder->isValid()) {
				//Modification de la série
				$seriesBuilder->updateSeries($series);
				$this->db->update($series, $seriesId);
				//On affiche la page de modification
				$this->view->makeSeriesModifiedPage($seriesId);
				unset($_SESSION['currentModifiedSeries']);
				$this->view->displaySeriesModifySuccess($seriesId);
			}
			else{
				$_SESSION['currentModifiedSeries'] = $seriesBuilder;
				$this->view->displaySeriesModifyFailure($seriesId);
			}
		}
	}

	//Fonction qui affiche le formulaire de modification
	public function askSeriesModify($id){
		$series = $this->db->read($id);
		if ($series === null) {
			$this->view->makeUnknownSeriesPage();
		}
		else {
			//Extraction des données modifiables
			$seriesBuilder = SeriesBuilder::buildFromSeries($series);
			//Préparation de la page de formulaire
			$this->view->makeSeriesModifPage($id, $seriesBuilder);
		}
	}

	//Fonction qui affiche le formulaire de suppression
	public function askSeriesDeletion($id){
		if(key_exists($id, $this->db->readAll())){
			//Préparation de la page de formulaire
			$this->view->makeSeriesDeletionPage($id);
		}
		else{
			//On affiche la page d'erreur
			$this->view->makeErrorPage();
		}
	}

	//Fonction qui supprime la série de la base de données
	public function deleteSeries($id){
		//On récupère la série en base de données
		$s = $this->db->read($id);
		if ($s === null) {
			//La série n'existe pas en base de données
			$this->view->displaySeriesDeletionFailure($id);
		}
		else{
			$this->db->delete($id);
			$this->view->displaySeriesDeletionSuccess();	
		}
	}

	//Fonction qui affiche la validation de la modification
	public function modifySeries() {
		$this->view->makeSeriesModifiedPage();
	}

	//Fonction qui créé une SeriesBuilder
	public function newSeries(){
		if(key_exists('currentNewSeries', $_SESSION)){
			$builder = $_SESSION['currentNewSeries'];
			return $builder;
		}
		//On créé la série à partir d'un tableau vide et on le renvoie
		$seriesBuilder = new SeriesBuilder(array());
		return $seriesBuilder;
	}
}