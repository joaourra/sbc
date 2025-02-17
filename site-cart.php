<?php

use \Sbc\Page;
use \Sbc\Model\Turma;
use \Sbc\Model\Cart;
use \Sbc\Model\Pessoa;
use \Sbc\Model\User;
use \Sbc\Model\Temporada;
use \Sbc\Model\Insc;
use \Sbc\Model\Saude;

$app->get("/cart", function(){

    /*
	if( !isset($_SESSION['User']) || $_SESSION['User']['iduser'] != 1){

		Cart::setMsgError("A página que você tentou acessar está em MANUTENÇÃO! Por favor aguarde!");
		header("Location: /");
		exit();

	}else{	
	*/
   
	$cart = Cart::getFromSession();
	$user = User::getFromSession();
	$page = new Page();

	$turma = $cart->getTurma();
	$idturma  = isset($turma[0]['idturma']) ? $turma[0]['idturma'] : '';
	$desctemporada  = isset($turma[0]['desctemporada']) ? $turma[0]['desctemporada'] : '';

	// Aqui verifica se a temporada é igual ao ano atual
	// Se não for acrescenta (1). Supondo que a inscrição está sendo feita no ano anterior
	if( (int)date('Y')  == (int)$desctemporada ){

		$anoAtual = (int)date('Y');	

	}else{

		$anoAtual = (int)date('Y') + 1;		
	}	


	if(Turma::temToken($idturma)){
		$temtoken = 1;
	}else{
		$temtoken = 0;
	}	

	$page->setTpl("cart", [
		'cart'=>$cart->getValues(),
		'temtoken'=>$temtoken,
		'anoAtual'=>$anoAtual,
		//'anoFinal'=>$anoFinal,
		//'anoInicial'=>$anoInicial,
		'turma'=>$cart->getTurma(),
		'pessoa'=>$user->getPessoa(),
		'error'=>Cart::getMsgError(),
		'msgError'=>Cart::getMsgError(),
		'msgSuccess'=>Cart::getMsgSuccess()
	]);


   //}


});

$app->post("/cart", function() {

	User::verifyLogin(false);

	if(Cart::cartIsEmpty((int)$_SESSION[Cart::SESSION]['idcart']) === false){
		Cart::setMsgError("Não há inscrições a confirmar! selecione uma turma! ");
		header("Location: /cart");
		exit();
	}
	
	if(!isset($_POST['idpess']) || $_POST['idpess'] <= 0){	
		Cart::setMsgError("Você precisa selecionar uma pessoa! ");
		header("Location: /cart");
		exit();

	}else{

		$cart = new Cart();

		$_POST['idcart'] = (int)$_SESSION[Cart::SESSION]["idcart"];
		//$_POST['iduser'] = (int)$_SESSION[User::SESSION]["iduser"];
		$_POST['dessessionid'] = $_SESSION[Cart::SESSION]["dessessionid"];

		$pessoa = new Pessoa();

		$idpess = $_POST['idpess'];

		$pessoa->get((int)$idpess);

		$nomepess = $pessoa->getnomepess();	

		if($pessoa->getpcd() && !Saude::getSaudeExist($idpess)){

			Cart::setMsgError("Para prosseguir com a inscrição, você deve inserir ou atualizar os dados de saúde do(a) ".$nomepess."! ");
			//header("Location: /cart");
			header("Location: /saude-atualiza/".$idpess."/".$nomepess."");
			exit();
		}

		//$numcpf = $pessoa->getcpf();
		$numcpf = $pessoa->getnumcpf();
		//$idade = User::calcularIdade($pessoa->getdtnasc());
		$anoNasc = date('Y',  strtotime($pessoa->getdtnasc()));
		$nomepess = $pessoa->getnomepess();
		$sexodeclarado = $pessoa->getsexo();
	
		$idturma = $_POST['idturma'];
		$idtemporada = $_POST['idtemporada'];		
		$initidade = $_POST['initidade'];
		$fimidade = $_POST['fimidade']; 
		$idlocal = $_POST['idlocal']; 
		$local = $_POST['apelidolocal']; 
		$sexoTurma = $_POST['sexo']; 
		$tipoativ = $_POST['tipoativ']; 
		$idmodal = (int)$_POST['idmodal']; 

		$temporada = new Temporada();

		$temporada->get((int)$idtemporada);

		$desctemporada = $temporada->getdesctemporada();
		//$inicioInscTemporada = formatDate($temporada->getdtinicinscricao());
		$inicioInscTemporada = date('d/m/Y H:i:s', strtotime($temporada->getdtinicinscricao()));
		//$terminoInscTemporada = formatDate($temporada->getdtterminscricao());
		$terminoInscTemporada = date('d/m/Y H:i:s', strtotime($temporada->getdtterminscricao()));
		$inicioMatriculaTemporada = formatDate($temporada->getdtinicmatricula());
		$termimoMatriculaTemporada = formatDate($temporada->getdttermmatricula());
		$dataSorteioTemporada = date('d/m/Y', strtotime($temporada->getdtterminscricao().' + 12 days'));
		//$dataEdital = date('d/m/Y', strtotime($temporada->getdtinicinscricao().' - 1 month'));
		$dataEdital = "28/10/2021";
		$numeroResolucao = "004";

		if(Insc::statusTemporadaIniciada($idtemporada)){

			Cart::setMsgError("As inscrições para os Cursos Esportivos para a temporada ".$desctemporada." não estão disponíveis no momento. Para a temporada ".$desctemporada." o período de inscrições será de ".$inicioInscTemporada." a ".$terminoInscTemporada." conforme resolução SESP Nº ".$numeroResolucao." publicada no jornal Notícias do Município de ".$dataEdital.". O sorteio acontecerá no dia ".$dataSorteioTemporada.". A partir do dia ".$inicioMatriculaTemporada." iniciar-se-á a etapa de matrículas e início das aulas presenciais nos Centros Esportivos, para os inscritos contemplados no sorteio.");
			header("Location: /cart");
			exit();
		}

		if(Insc::statusTemporadaMatriculaIniciada($idtemporada)){

			Cart::setMsgError("As inscrições para os Cursos Esportivos para a temporada ".$desctemporada." não estão disponíveis no momento. Para a temporada ".$desctemporada." o período de inscrições foi de ".$inicioInscTemporada." a ".$terminoInscTemporada." conforme resolução SESP Nº ".$numeroResolucao." publicada no jornal Notícias do Município de ".$dataEdital.". O sorteio aconteceu no dia ".$dataSorteioTemporada.". A partir do dia ".$inicioMatriculaTemporada." iniciar-se-á a etapa de matrículas e início das aulas presenciais nos Centros Esportivos, para os inscritos contemplados no sorteio. A partir do dia ".$termimoMatriculaTemporada." você poderá fazer novas inscrições aqui mesmo no site para uma lista de espera das turmas ou para fazer matrícula caso a turma tenha vagas disponíveis");
			header("Location: /cart");
			exit();
		}

		if(Insc::statusTemporadaInscricoesEncerradas($idtemporada)){

			Cart::setMsgError("As inscrições para os Cursos Esportivos para a temporada ".$desctemporada." não estão disponíveis no momento. Para a temporada ".$desctemporada." o período de inscrições foi de ".$inicioInscTemporada." a ".$terminoInscTemporada." conforme resolução SESP Nº ".$numeroResolucao." publicada no jornal Notícias do Município de ".$dataEdital.". O sorteio aconteceu no dia ".$dataSorteioTemporada.". A partir do dia ".$inicioMatriculaTemporada." iniciar-se-á a etapa de matrículas e início das aulas presenciais nos Centros Esportivos, para os inscritos contemplados no sorteio. A partir do dia ".$termimoMatriculaTemporada." você poderá fazer novas inscrições aqui mesmo no site para uma lista de espera das turmas ou para fazer matrícula caso a turma tenha vagas disponíveis");
			header("Location: /cart");
			exit();
		}

		if(Turma::temToken($idturma)){	
			
			if(!isset($_POST['token']) || $_POST['token'] == ''){

				Cart::setMsgError("Você precisa inserir o número do TOKEN que você recebeu do professor quando ele indicou você para inscrever ".$pessoa->getnomepess()." nesta turma!");
				header("Location: /cart");
				exit();
			}

			if(!Turma::tokemValido($_POST['token'], $_POST['idturma'])){

				Cart::setMsgError("Este token não é valido ou já foi utilizado!");
				header("Location: /cart");
				exit();
			}		

			$_SESSION['token'] = $_POST['token'];
		}		

		if($sexodeclarado != $sexoTurma && $sexoTurma != ''){		

			Cart::setMsgError('Esta turma é exclusiva para pessoas do sexo '.$sexoTurma.'. Verifique, no perfil do(a) '.$nomepess.', em "Minha Família" o sexo declarado dele(a). Se for necessário faça a alteração.');
			header("Location: /cart");
			exit();
		}

		$anoAtual = date('Y');
		$anoFinal = $anoAtual - $fimidade;
		$anoInicial = $anoAtual - $initidade;

		if(($anoNasc > $anoInicial) || ($anoNasc < $anoFinal)){		
		//if(($idade < $initidade) || ($idade > $fimidade)){		

			Cart::setMsgError('Esta turma é exclusiva para pessoas nascidas entre os anos de '.$anoFinal.' e '.$anoInicial.'. Remova a turma atual e escolha outra turma compatível com o ano de nascimento do(a) '.$nomepess.'. Ele(a) nasceu no ano de '.$anoNasc.'.');
			header("Location: /cart");
			exit();
		}	

		//var_dump($numcpf.' - '.$idpess.' - '.$idturma.' - '.$idtemporada.' - '.$idlocal.' - '.$tipoativ');
		//exit();

		if($idmodal === 6 || $idmodal === 14){

			if ($cart->getInscExistAquaticLocal($numcpf, $idpess, $idturma, $idtemporada, $idlocal, $tipoativ)){

				Cart::setMsgError($nomepess.' já está inscrito(a) para uma turma do tipo '.$tipoativ.' no '.$local.'!');
				header("Location: /cart");
				exit();

			}
		}

		if ($cart->getInscExist($numcpf, $idpess, $idturma, $idtemporada)){

			Cart::setMsgError($nomepess.' já está inscrito(a) nesta turma para esta temporada!');
			header("Location: /cart");
			exit();

		}else{				

			$cart->setData($_POST);

			$cart->save([
				'idcart'=>$_POST['idcart'],
				//':iduser'=>$_POST['iduser'],		
				':idpess'=>$_POST['idpess'],
				':dessessionid'=>$_POST['dessessionid']
			]);

			header("Location: /checkout");
			exit();
		}
	}
});

$app->get("/cart/:idturma/:idtemporada/add", function($idturma, $idtemporada){

	$turma = new Turma();

	$turma->get((int)$idturma);

	$cart = Cart::getFromSession();

	$idcart = (int)$_SESSION[Cart::SESSION]["idcart"];

	if( Cart::cartIsEmpty($idcart) > 0){

		Cart::setMsgError("Você já selecionou uma turma! remova a atual, se você quiser selecionar uma outra.");
		header("Location: /cart");
		exit();

	}else{
				
		$cart->addTurma($turma);
	}		
	
	header("Location: /cart");
	exit;
});

$app->get("/cart/:idturma/remove", function($idturma){

	$turma = new Turma();

	$turma->get((int)$idturma);

	$cart = Cart::getFromSession();

	$cart->removeTurma($turma, true);

	Cart::removeFromSession();
    session_regenerate_id();

	header("Location: /");
	exit;
});


?>