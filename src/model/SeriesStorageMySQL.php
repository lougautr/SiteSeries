<?php

require_once("model/SeriesStorage.php");

class SeriesStorageMySQL implements SeriesStorage{

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //Fonction qui renvoie l'instance de Series correspondant à l'identifiant donné, ou null s'il n'y en a pas
	public function read($id){
        $stmt = $this->pdo->query('SELECT * FROM series WHERE id=' . $id);
        $tableau = $stmt->fetch(PDO::FETCH_ASSOC);
        $s = new Series($tableau['name'], $tableau['creator'], $tableau['origins'], $tableau['diffusion'], $tableau['seasons']);
        return $s;
    }

    //Fonction qui renvoie un tableau associatif id=>series avec tous les series de la base
	public function readAll(){
        $stmt = $this->pdo->query('SELECT * FROM series');
        $tableau = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = array();
        foreach ($tableau as $ligne) {
            $db[$ligne['id']] = new Series($ligne['name'], $ligne['creator'], $ligne['origins'], $ligne['diffusion'], $ligne['seasons']);
        }
        return $db;
    }

    //Fonction qui ajoute la série donnée en argument à la base, et retourne l'identifiant de la série ainsi créée
	public function create(Series $s){
        $dernier_id = $this->pdo->query('SELECT MAX(id) FROM series')->fetchColumn();
        $stmt = $this->pdo->prepare('INSERT INTO series VALUES(:id, :name, :creator, :origins, :diffusion, :seasons)');
        $data = array('id' => $dernier_id+1, 'name' => $s->getName(), 'creator' => $s->getCreator(), 'origins' => $s->getOrigins(), 'diffusion' => $s->getDiffusion(), 'seasons' => $s->getSeasons());
        $stmt->execute($data);
        return $dernier_id+1;
    }

    //Fonction qui supprime la série donné en argument de la base
	public function delete($id){
        $stmt = $this->pdo->prepare('DELETE FROM series WHERE id=' . $id);
        $data = array('id' => $id);
        $stmt->execute($data);
    }

    //Fonction qui met à jour la série donné en argument dans la base
    public function update(Series $s, $id){
        $stmt = $this->pdo->prepare('UPDATE series SET name=:name, creator=:creator, origins=:origins, diffusion=:diffusion, seasons=:seasons WHERE id=:id');
        $data = array('id' => $id, 'name' => $s->getName(), 'creator' => $s->getCreator(), 'origins' => $s->getOrigins(), 'diffusion' => $s->getDiffusion(), 'seasons' => $s->getSeasons());
        $stmt->execute($data);
    }

    //Fonction qui tri les noms de séries par ordre croissant
    public function orderByASC(){
        $stmt = $this->pdo->query('SELECT * FROM series ORDER BY name ASC');
        $tableau = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = array();
        foreach ($tableau as $ligne) {
            $db[$ligne['id']] = new Series($ligne['name'], $ligne['creator'], $ligne['origins'], $ligne['diffusion'], $ligne['seasons']);
        }
        return $db;
    }

    //Fonction qui tri les noms de séries par ordre décroissant
    public function orderByDES(){
        $stmt = $this->pdo->query('SELECT * FROM series ORDER BY name DESC');
        $tableau = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = array();
        foreach ($tableau as $ligne) {
            $db[$ligne['id']] = new Series($ligne['name'], $ligne['creator'], $ligne['origins'], $ligne['diffusion'], $ligne['seasons']);
        }
        return $db;
    }
}