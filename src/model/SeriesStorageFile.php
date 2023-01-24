<?php

require_once("model/Series.php");
require_once("model/SeriesStorage.php");
require_once("lib/ObjectFileDB.php");

class SeriesStorageFile implements SeriesStorage{
    private $db;

    public function __construct($file){
        $this->db = new ObjectFileDB($file);
    }

	//Fonction qui renvoie la série d'identifiant $id, ou null si l'identifiant ne correspond à aucune série
	public function read($id) {
		if ($this->db->exists($id)) {
			return $this->db->fetch($id);
        } else {
			return null;
        }
	}

	//Fonction qui insère une nouvelle série dans la base. Renvoie l'identifiant de la nouvelle série
	public function create(Series $s) {
		return $this->db->insert($s);
	}

	//Fonction qui renvoie un tableau associatif id => Series contenant toutes les séries de la base
	public function readAll() {
		return $this->db->fetchAll();
	}

	//Fonction qui met à jour une série dans la base. Renvoie true si la modification a été effectuée, false si l'identifiant ne correspond à aucune série
	public function update(Series $s, $id) {
		if ($this->db->exists($id)) {
            $this->db->update($id, $s);
			return true;
		}
		return false;
	}

	//Fonction qui supprime une série. Renvoie true si la suppression a été effectuée, false si l'identifiant ne correspond à aucune série
	public function delete($id) {
		$this->db->delete($id);
	}

	//Fonction qui vide la base
	public function deleteAll() {
        $this->db->deleteAll();
	}

    //Fonction qui remet la base dans l'état initial
    public function reinit(){
		$this->db->deleteAll();
		$this->db->insert(new Series('The Simpsons', 'Matt Groening', 'américaine', 1989, 34));
		$this->db->insert(new Series('La Casa de Papel', 'Alex Pina', 'espagnole', 2017, 2));
		$this->db->insert(new Series('Sherlock', 'Mark Gatiss, Steven Moffat', 'britannique', 2010, 4));
		$this->db->insert(new Series('Lupin', 'George Kay, François Uzan', 'française', 2021, 2));
		$this->db->insert(new Series('The Rain', 'Jannik Tai Mosholt', 'danoise', 2018, 3));
    }
}