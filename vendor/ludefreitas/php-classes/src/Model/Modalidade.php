<?php

namespace Sbc\Model;

use \Sbc\DB\Sql;
use \Sbc\Model;
use \Sbc\Mailer;

class Modalidade extends Model {

	const SESSION = "Modalidade";
	const SESSION_ERROR = "ModalidadeError";

	public static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * 
			FROM tb_modalidade			
			ORDER BY descmodal");
	}

	public static function listAllToLocal($idlocal)
	{
		$idStatusTemporadaMatriculasEncerradas = StatusTemporada::MATRICULAS_ENCERRADAS;
		$idStatusTemporadaInscricaoIniciada = StatusTemporada::INSCRICOES_INICIADAS;
		$idStatusTemporadaMatriculaIniciada = StatusTemporada::MATRICULAS_INICIADAS;		
		$idStatusTemporadaTemporadaIniciada = StatusTemporada::TEMPORADA_INICIADA;			
		$idStatusTemporadaInscricoesEncerradas = StatusTemporada::INSCRICOES_ENCERRADAS;

		$sql = new Sql();

		$results = $sql->select("
		SELECT * FROM tb_modalidade a 
        WHERE idmodal IN (
        SELECT c.idmodal FROM tb_turmatemporada b
        INNER JOIN tb_temporada f ON f.idtemporada = b.idtemporada
        INNER JOIN tb_statustemporada g ON g.idstatustemporada = f.idstatustemporada
		INNER JOIN tb_turma c ON c.idturma = b.idturma 
        INNER JOIN tb_espaco d ON d.idespaco = c.idespaco
        INNER JOIN tb_local e ON e.idlocal = d.idlocal
		WHERE e.idlocal = :idlocal 
		AND (g.idstatustemporada = :idStatusTemporadaMatriculasEncerradas
      		 OR g.idstatustemporada = :idStatusTemporadaInscricaoIniciada
      		 OR g.idstatustemporada = :idStatusTemporadaMatriculaIniciada
      		 OR g.idstatustemporada = :idStatusTemporadaTemporadaIniciada
      		 OR g.idstatustemporada = :idStatusTemporadaInscricoesEncerradas
            )
		)",[
			':idlocal'=>$idlocal,
			':idStatusTemporadaMatriculasEncerradas'=>$idStatusTemporadaMatriculasEncerradas,
			':idStatusTemporadaInscricaoIniciada'=>$idStatusTemporadaInscricaoIniciada,
			':idStatusTemporadaMatriculaIniciada'=>$idStatusTemporadaMatriculaIniciada,
			':idStatusTemporadaTemporadaIniciada'=>$idStatusTemporadaTemporadaIniciada,
			':idStatusTemporadaInscricoesEncerradas'=>$idStatusTemporadaInscricoesEncerradas
		]);

		return $results;		
	}	
	

	public static function checkList($list)
	{

		foreach ($list as &$row) {
			
			$p = new Modalidade();
			$p->setData($row);
			$row = $p->getValues();

		}

		return $list;

	}
	// esta função é usada para salvar e editar Atividade
	public function save()
	{
		$sql = new Sql();

		$results = $sql->select("CALL sp_modalidade_save(:idmodal, :descmodal)", array(
			":idmodal"=>$this->getidmodal(),
			":descmodal"=>$this->getdescmodal()
		));

		$this->setData($results[0]);

		Modalidade::updateFile();

	}

	public function get($idmodal)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_modalidade WHERE idmodal = :idmodal", [
			':idmodal'=>$idmodal 
		]);

		if($results){

			$this->setData($results[0]);		

		}else{

			Modalidade::setMsgError("Modalidade selecionado não existe!");
			header("Location: /admin/modalidades");
			exit();			
		}				
	}

	public function delete()
	{
		$sql = new Sql();

		$results = $sql->select("DELETE FROM tb_modalidade WHERE idmodal = :idmodal", [
			':idmodal'=>$this->getidmodal()
		]);		

		Modalidade::updateFile();
	}

	// atualiza lista de Atividade no site (no rodapé) Modalidade-menu.html
	public static function updateFile()	
	{
		$modalidade = Modalidade::listAll();

		$html = [];

		foreach ($modalidade as $row) {
			array_push($html, '<li><a href="/modalidade/'.$row['idmodal'].'">'.$row['descmodal'].'</a></li>');
		}
		file_put_contents($_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."modalidade-menu.html", implode('', $html));
	}

	public function getFromId($idmodal)
	{

		$sql = new Sql();

		$rows = $sql->select("SELECT * FROM tb_modalidade WHERE idmodal = :idmodal LIMIT 1", [
			':idmodal'=>$idmodal
		]);

		if (count($results) > 0) {

			$this->setData($results[0]);

		}		

	}

	public static function getPage($page = 1, $itemsPerPage = 6)
	{

		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_modalidade a 			
			ORDER BY a.descmodal
			LIMIT $start, $itemsPerPage;
		");

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];

	}

	public static function getPageSearch($search, $page = 1, $itemsPerPage = 6)
	{

		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("
			SELECT * 
			FROM tb_modalidade a 
			ORDER BY a.descmodal
			LIMIT $start, $itemsPerPage;
		", [
			':search'=>'%'.$search.'%'
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];

	}	
	
	public function getTurmaModalidadePage($page = 1, $itemsPerPage = 100)
	{

		$start = ($page - 1) * $itemsPerPage;

		$sql = new Sql();

		$results = $sql->select("

			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_turmatemporada n 
            INNER JOIN tb_temporada o ON o.idtemporada = n.idtemporada
			INNER JOIN tb_turma a ON a.idturma = n.idturma
			INNER JOIN tb_modalidade b ON b.idmodal = a.idmodal
            INNER JOIN tb_espaco c ON c.idespaco = a.idespaco
            INNER JOIN tb_horario d ON d.idhorario = a.idhorario
            INNER JOIN tb_atividade e ON a.idativ = e.idativ
            INNER JOIN tb_fxetaria f ON e.idfxetaria = f.idfxetaria
			INNER JOIN tb_users g ON a.iduser = g.iduser
			INNER JOIN tb_persons h ON g.idperson = h.idperson
            INNER JOIN tb_espaco i ON a.idespaco = i.idespaco
			INNER JOIN tb_local j ON j.idlocal = c.idlocal
			INNER JOIN tb_turmastatus m ON m.idturmastatus = a.idturmastatus
			WHERE b.idmodal = :idmodal
			ORDER BY a.descturma
			LIMIT $start, $itemsPerPage;
			
		", [
			':idmodal'=>$this->getidmodal()
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>Turma::checkList($results),
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}

	public static function setMsgError($msg)
	{
		$_SESSION[Modalidade::SESSION_ERROR] = $msg;
	}

	public static function getMsgError(){

		$msg = (isset($_SESSION[Modalidade::SESSION_ERROR])) ? $_SESSION[Modalidade::SESSION_ERROR] : "";

		Modalidade::clearMsgError();

		return $msg;
	}

	public static function clearMsgError()
	{
		$_SESSION[Modalidade::SESSION_ERROR] = NULL;
	}

	public static function setMsgSuccess($msg)
	{
		$_SESSION[Modalidade::SUCCESS] = $msg;
	}

	public static function getMsgSuccess()
	{
		$msg = (isset($_SESSION[Modalidade::SUCCESS]) && $_SESSION[Modalidade::SUCCESS]) ? $_SESSION[Modalidade::SUCCESS] : '';

		Modalidade::clearMsgSuccess();

		return $msg;
	}

	public static function clearMsgSuccess()
	{

		$_SESSION[Modalidade::SUCCESS] = NULL;

	}
	

}


?>