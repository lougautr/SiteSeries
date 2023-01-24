<?php

//Interface representant un système de stockage des séries
interface SeriesStorage {

	//Fonction qui renvoie l'instance de Series correspondant a l'identifiant donné, ou null s'il n'y en a pas
	public function read($id);

	//Fonction qui renvoie un tableau associatif id=>series avec toutes les séries de la base
	public function readAll();

	//Fonction qui ajoute la série donné en argument à la base, et retourne l'identifiant de la série ainsi créée
	public function create(Series $s);

	//Fonction qui supprime la série donné en argument de la base
	public function delete($id);

	//Fonction qui modifie la série donné en argument de la base
	public function update(Series $s, $id);

	//Fonction qui sélectionne les séries par ordre croissant
	public function orderByASC();

	//Fonction qui sélectionne les séries par ordre décroissant
	public function orderByDES();
}