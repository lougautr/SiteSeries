<?php

require_once("model/Series.php");
require_once("model/SeriesBuilder.php");
require_once("Router.php");

class View {

	private Router $router;
	private $title;
	private $content;

	public function __construct(Router $router, $feedback){
		$this->router = $router;
		$this->title = null;
		$this->content = null;
		$this->feedback = $feedback;
	}

	//Page de test
	public function makeTestPage(){
		$this->title = "TITRE";
		$this->content = "blabla";
	}

	/*Page d'une série avec son nom, son pays d'origine, son créateur, sa première année de
	diffusion, son nombre de saisons et les liens de suppression et de modification*/
	public function makeSeriesPage(Series $s, $id){
		$this->title = "Page sur " . $s->getName();
		$this->content = $s->getName() . " est une série " . $s->getOrigins() . " créée par " . $s->getCreator() . ". Elle a été diffusée pour la première fois en " . $s->getDiffusion() . " et compte aujourd'hui " . $s->getSeasons() . " saisons.<br>";
		$this->content .= "<br><span class='options'><a href='" . $this->router->getSeriesAskModifyURL($id) . "'>Modifier</a>";
        $this->content .= "<a href='" . $this->router->getSeriesAskDeletionURL($id) . "'>Supprimer</a></span>";
	}

	//Page de série inconnue
	public function makeUnknownSeriesPage(){
		$this->title = "Série Inconnu";
		$this->content = "Essayez une autre série";
	}

	//Page d'accueil
	public function makeHomePage() {
		$this->title = "Bienvenue !";
		$this->content = "Un site sur des séries télévisées";
	}

	//Page contenant la liste de toutes les séries de la base de données
	public function makeListPage(array $tab){
		$this->title = "Toutes les séries";
		$this->content = "<span class='order'><a href='" . $this->router->getListURLSortASC() . "'>Trier de A-Z</a>";
		$this->content .= "<a href='" . $this->router->getListURLSortDES() ."'>Trier de Z-A</a></span>";
		$this->content .= "<p>Cliquer sur une série pour voir des détails.</p>\n";
		$this->content .= "<ul class=\"gallery\">\n";
		foreach($tab as $series => $serie){
			$this->content .= "<li><a href='" . $this->router->getSeriesURL($series) . "'>" . $serie->getName() . "</a></li>";
		}
        $this->content .= "</ul>\n";
	}

	//Page de debug
	public function makeDebugPage($variable) {
		$this->title = 'Debug';
		$this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
	}

	//Page d'informations sur les éléments techniques du site
    public function makeAboutPage(){
        $this->title = "A propos";
        $this->content = "Numéro étudiant: 22001251<br>";
        $this->content .= "Ce site comporte plusieurs fonctionnalités telles que:";
        $this->content .= "<ul class='about'><li>Ajout d'une série à la liste de celles déjà existantes dans la base de données</li>";
        $this->content .= "<li>Modification possible des toutes les caractéristiques d'une série</li>";
        $this->content .= "<li>Suppression d'une série existante dans la base de données</li>";
        $this->content .= "<li>Possibilité d'afficher la liste des séries de la base de données par ordre alphabétique ou ordre alphabétique inverse</li></ul>";
    }

	//Formulaire de création d'une série
	public function makeSeriesCreationPage(SeriesBuilder $seriesBuilder){
		$this->title = "Ajouter une série";
		$this->content = "<form action='" . $this->router->getSeriesSaveURL() . "' method='POST'>";
		
        $this->content .= "<label>Nom de la série:  <input type='text' name='" . $seriesBuilder->getNameRef() . "' value='" . $seriesBuilder->getName() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getNameRef()) ."</span>";
		
        $this->content .= "<br><br><label>Créateur(s) de la série:  <input type='text' name='" . $seriesBuilder->getCreatorRef() . "' value='" . $seriesBuilder->getCreator() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getCreatorRef()) ."</span>";
		
        $this->content .= "<br><br><label>Origine(s) de la série:  <input type='text' name='" . $seriesBuilder->getOriginsRef() . "' value='" . $seriesBuilder->getOrigins() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getOriginsRef()) ."</span>";

        $this->content .= "<br><br><label>Année de la première diffusion de la série:  <input type='text' name='" . $seriesBuilder->getDiffusionRef() . "' value='" . $seriesBuilder->getDiffusion() . "'></label>";
		$this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getDiffusionRef()) ."</span>";

        $this->content .= "<br><br><label>Nombre de saisons de la série:  <input type='text' name='" . $seriesBuilder->getSeasonsRef() . "' value='" . $seriesBuilder->getSeasons() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getSeasonsRef()) ."</span>";
        
        $this->content .= "<br><br><button type='submit'>Envoyer</button>";
		$this->content .= "</form>";
	}

	//Formulaire de modification d'une série
	public function makeSeriesModifPage($id, SeriesBuilder $seriesBuilder) {
		$this->title = "Modifier la série";
		$this->content = '<form action="'.$this->router->getSeriesModifyURL($id).'" method="POST">'."\n";
		
        $this->content .= "<label>Nom de la série:  <input type='text' name='" . $seriesBuilder->getNameRef() . "' value='" . $seriesBuilder->getName() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getNameRef()) ."</span>";
		
        $this->content .= "<br><br><label>Créateur(s) de la série:  <input type='text' name='" . $seriesBuilder->getCreatorRef() . "' value='" . $seriesBuilder->getCreator() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getCreatorRef()) ."</span>";
		
        $this->content .= "<br><br><label>Origine(s) de la série:  <input type='text' name='" . $seriesBuilder->getOriginsRef() . "' value='" . $seriesBuilder->getOrigins() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getOriginsRef()) ."</span>";

        $this->content .= "<br><br><label>Année de la première diffusion de la série:  <input type='text' name='" . $seriesBuilder->getDiffusionRef() . "' value='" . $seriesBuilder->getDiffusion() . "'></label>";
		$this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getDiffusionRef()) ."</span>";

        $this->content .= "<br><br><label>Nombre de saisons de la série:  <input type='text' name='" . $seriesBuilder->getSeasonsRef() . "' value='" . $seriesBuilder->getSeasons() . "'></label>";
        $this->content .= "<span class='error'>". $seriesBuilder->getError($seriesBuilder->getSeasonsRef()) ."</span>";
        
        $this->content .= "<br><br><button type='submit'>Envoyer</button>";
		$this->content .= "</form>";
	}

	//Page d'erreur
	public function makeErrorPage(){
		$this->title = "ERROR";
		$this->content = "Veuillez rééssayer";

	}

	//Page d'action inconnue
	public function makeUnknownActionPage() {
		$this->title = "Erreur";
		$this->content = "La page demandée n'existe pas.";
	}

	//Formulaire de suppression d'une série
	public function makeSeriesDeletionPage($id){
		$this->title = "Suppression de la série";
		$this->content = "Voulez-vous vraiment supprimer la série ?";
		$this->content .= '<form action="'.$this->router->getSeriesDeletionURL($id).'" method="POST">'."\n";
		$this->content .= "<br><button>Confirmer</button>\n</form>\n";
	}

	//Page de validation de suppression d'une série
	public function makeSeriesDeletePage(){
		$this->title = "Série supprimée";
		$this->content = "La série a bien été supprimé de la base de données";
	}

	//Page de validation de modification d'une série
	public function makeSeriesModifiedPage(){
		$this->title = "Série modifiée";
		$this->content = "La série a bien été modifié";
	}

	//Page de non-validation de modification d'une série
	public function makeSeriesNotModifiedPage(){
		$this->title = "Série non-modifié";
		$this->content = "Votre série n'a pas pu être modifié";
	}

	/*Menu contenant des liens redirigeant vers l'accueil, la liste des séries, la création 
	d'une série, et la page d'informations des éléments techniques du site*/
	public function getMenu() {
		return array(
			"Accueil" => $this->router->getHomeURL(),
			"Séries" => $this->router->getListURL(),
			"Nouvelle série" => $this->router->getSeriesCreationURL(),
            "A propos" => $this->router->getAboutURL(),
		);
	}

	//Feedback de succès de création d'une série
	public function displaySeriesCreationSuccess($id){
		$this->router->POSTredirect($this->router->getSeriesURL($id), "Série créée avec succès");
	}

	//Feedback d'échec de création d'une série
	public function displaySeriesCreationFailure(){
		$this->router->POSTredirect($this->router->getSeriesCreationURL(), "Erreur lors de la création d'une série");
	}

	//Feedback de succès de modification d'une série
	public function displaySeriesModifySuccess($id){
		$this->router->POSTredirect($this->router->getSeriesURL($id), "Série modifiée avec succès");
	}

	//Feedback d'échec de modification d'une série
	public function displaySeriesModifyFailure($id){
		$this->router->POSTredirect($this->router->getSeriesAskModifyURL($id), "Erreur lors de la modification d'une série");
	}

	//Feedback de succès de suppression d'une série
    public function displaySeriesDeletionSuccess(){
		$this->router->POSTredirect($this->router->getListURL(), "Série supprimée avec succès");
	}

	//Feedback d'échec de suppression d'une série
    public function displaySeriesDeletionFailure($id){
		$this->router->POSTredirect($this->router->getSeriesAskDeletionURL($id), "Erreur lors de la suppression d'une série");
	}

	//Fonction contenant tout le code HTML
	public function render() {
		if ($this->title === null || $this->content === null) {
			$this->makeErrorPage();
		}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $this->title; ?></title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="skin/style.css" />
</head>
<body>
	<nav class="menu">
		<ul>
<?php
/* Construit le menu à partir d'un tableau associatif texte=>lien. */
foreach ($this->getMenu() as $text => $link) {
	echo "<li><a href=\"$link\">$text</a></li>";
}
?>
		</ul>
	</nav>
<?php if ($this->feedback !== '') { ?>
	<div class="feedback"><?php echo $this->feedback; ?></div>
<?php } ?>
	<main>
		<h1><?php echo $this->title; ?></h1>
<?php
echo $this->content;
?>
	</main>
</body>
</html>
<?php //Fin de l'affichage de la page et fin de la méthode render()

	}
}