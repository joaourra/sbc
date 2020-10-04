<?php

namespace Sbc\Model;

use \Sbc\DB\Sql;
use \Sbc\Model;
use \Sbc\Mailer;

class Turma extends Model {

	public static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * 
			FROM tb_turma a 
			INNER JOIN tb_users b
			using(iduser)
			INNER JOIN tb_modalidade c
			using(idmodal)
			INNER JOIN tb_espaco d
			using(idespaco)
			INNER JOIN tb_turmastatus e
			using(idturmastatus)
			ORDER BY a.descturma");
	}	
	// esta função é usada para salvar e editar turma
	public function save()
	{
		$sql = new Sql();
		
		$results = $sql->select("CALL sp_turma_save(:idturma, :idmodal, :idespaco, :iduser, :idturmastatus, :descturma, :vagas, :temporada, :numinicialinscritos, :dtinicinscricao, :dtterminscricao, :dtinicmatricula, :dttermmatricula)", array(
			":idturma"=>$this->getidturma(),
			":idmodal"=>$this->getidmodal(),
			":idespaco"=>$this->getidespaco(),
			":iduser"=>$this->getiduser(),
			":idturmastatus"=>$this->getidturmastatus(),
			":descturma"=>$this->getdescturma(),
			":vagas"=>$this->getvagas(),
			":temporada"=>$this->gettemporada(),
			":numinicialinscritos"=>$this->getnuminicialinscritos(),
			":dtinicinscricao"=>$this->getdtinicinscricao(),
			":dtterminscricao"=>$this->getdtterminscricao(),
			":dtinicmatricula"=>$this->getdtinicmatricula(),
			":dttermmatricula"=>$this->getdttermmatricula()			
		));	

		$this->setData($results[0]);

		Turma::updateFile();
	}

	public function get($idturma)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * 
			FROM tb_turma a 
			INNER JOIN tb_users b
			using(iduser)
			INNER JOIN tb_modalidade c
			using(idmodal)
			INNER JOIN tb_espaco d
			using(idespaco)
			INNER JOIN tb_turmastatus e
			using(idturmastatus)
			WHERE idturma = :idturma", [
			':idturma'=>$idturma 
		]);

		$this->setData($results[0]);		
	}

	public function delete()
	{
		$sql = new Sql();

		$results = $sql->select("DELETE FROM tb_turma WHERE idturma = :idturma", [
			':idturma'=>$this->getidturma()
		]);		

		Turma::updateFile();
	}

	// atualiza lista de turma no site (no rodapé) turma-menu.html
	public static function updateFile()	
	{
		$turma = Turma::listAll();

		$html = [];

		foreach ($turma as $row) {
			array_push($html, '<li><a href="/turma/'.$row['idturma'].'">'.$row['descturma'].'</a></li>');
		}
		file_put_contents($_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."turma-menu.html", implode('', $html));
	}
}

?>