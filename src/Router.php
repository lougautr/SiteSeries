<?php

require_once("model/SeriesStorageMySQL.php");
require_once("view/View.php");
require_once("control/Controller.php");

class Router {

	private SeriesStorage $db;

	public function __construct(SeriesStorage $db) {
		$this->db = $db;
	}

	public function main() {
		//On démarre notre session
		session_start();

		//On initialise notre feedback
		$feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
		$_SESSION['feedback'] = '';
			
		//On initialise notre vue, notre controller et notre id
		$this->view = new View($this, $feedback);
		$this->ctl = new Controller($this->view, $this->db);
		$this->id = null;

		//On récupère l'id du $_GET
		if(key_exists('id', $_GET)){
			$this->id = $_GET['id'];
		}

		//Si l'id est le seul élément du $_GET, alors on affiche la page de la série correspondante
		if ($this->id and sizeOf($_GET) == 1) {
			$this->ctl->showInformation($this->id);
		}

		//On affiche la liste des séries existants
		else if (key_exists('liste', $_GET)) {
			if ($_GET["liste"] == '') {
				$this->ctl->showList();
			}
			if ($_GET["liste"] == 'sortASC') {
				$this->ctl->showListOrderASC();
			}
			if ($_GET["liste"] == 'sortDES') {
				$this->ctl->showListOrderDES();
			}
		}

        //On affiche les informations de la remise du DM
        else if (key_exists('about', $_GET)){
            $this->ctl->showAbout();
        }

		else if (key_exists('action', $_GET)){
			//Pour créer une nouvelle série
			if ($_GET["action"] == 'nouveau') {
				$this->view->makeSeriesCreationPage($this->ctl->newSeries());
			}

			//Pour sauvegarder la nouvelle série créée
			if ($_GET["action"] == 'sauverNouveau'){
				$this->ctl->saveNewSeries($_POST);
			}

			//Pour supprimer une série
			if($_GET["action"] == 'supprimer'){
				if ($this->id === null) {
					$this->view->makeUnknownActionPage();
				} else {
					$this->ctl->askSeriesDeletion($this->id);
				}
			}

			//Pour sauvegarder la suppression de la série
			if($_GET["action"] == 'sauverSupprimer'){
				$this->ctl->deleteSeries($this->id);
			}

			//Pour modifier une série
			if($_GET["action"] == 'modifier'){
				if ($this->id === null) {
					$this->view->makeUnknownActionPage();
				} else {
					$this->ctl->askSeriesModify($this->id);
				}
			}

			//Pour sauvegarder la modification de la série
			if($_GET["action"] == 'sauverModifier'){
				$this->ctl->saveModification($this->id, $_POST);
			}
		}
		
		else{
			//On affiche la page d'accueil
			$this->view->makeHomePage();
		}
		$this->view->render();
	}

	//URL de la page d'accueil
	public function getHomeURL(){
		return ".";
	}

	//URL de la page de liste des séries
	public function getListURL(){
		return ".?liste";
	}

	//URL de la page de liste des séries triées par ordre alphabétique
	public function getListURLSortASC(){
		return ".?liste=sortASC";
	}

	//URL de la page de liste des séries triées par ordre alphabétique inverse
	public function getListURLSortDES(){
		return ".?liste=sortDES";
	}

	//URL de la page de la série d'identifiant $id
	public function getSeriesURL($id) {
		return ".?id=$id";
	}

	//URL de la page de création de la série
	public function getSeriesCreationURL(){
		return ".?action=nouveau";
	}

	//URL de la page de sauvegarde de la série
	public function getSeriesSaveURL(){
		return ".?action=sauverNouveau";
	}

	//URL de la page de demande de suppression de la série
	public function getSeriesAskDeletionURL($id){
		return ".?id=$id&action=supprimer";
	}

	//URL de la page de demande de modification de la série
	public function getSeriesAskModifyURL($id){
		return ".?id=$id&action=modifier";
	}

	//URL de la page de suppression de la série
	public function getSeriesDeletionURL($id){
		return ".?id=$id&action=sauverSupprimer";
	}

	//URL de la page de modification de la série
	public function getSeriesModifyURL($id){
		return ".?id=$id&action=sauverModifier";
	}
        
    //URL de la page d'informations sur le rendu du DM
    public function getAboutURL(){
        return ".?about";
    }

	public function POSTredirect($url, $feedback){
		$_SESSION['feedback'] = $feedback;
		header("Location: ". $url, true, 303);
		die;
	}

}