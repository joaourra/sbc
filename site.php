<?php

use \Sbc\Page;
use \Sbc\Model\Turma;
use \Sbc\Model\Temporada;
use \Sbc\Model\Cart;
use \Sbc\Model\Pessoa;
use \Sbc\Model\User;
use \Sbc\Model\Insc;
use \Sbc\Model\InscStatus;
use \Sbc\Model\CartsTurmas;
use \Sbc\Model\Endereco;
use \Sbc\Model\Local;

$app->get('/', function() {

	if(isset($_COOKIE['sisgen_user']) && isset($_COOKIE['sisgen_pass'])){

		$login = base64_decode($_COOKIE['sisgen_user']);
		$password = base64_decode($_COOKIE['sisgen_pass']);

		try {

			User::login($login, $password);

		} catch(Exception $e) {

			User::setError($e->getMessage());
			header("Location: /login");
			exit;
		}	

	}

	//$search = (isset($_GET['search'])) ? $_GET['search'] : "";

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	//if ($search != '') {

		//$pagination = Turma::getPageSearchTurmaTemporada($search, $page);
		
	//} else {

		$pagination = Turma::getPageTurmaTemporada();
	//}

	$temporada = new Temporada();

	if(!isset($pagination['data']) || $pagination['data'] == NULL){

		if(!isset($search) || $search == NULL){

			Cart::setMsgError("Não existem inscrições diponíveis para esta temporada!.");
			//Cart::setMsgError("Não existem inscrições diponíveis para esta temporada!. Para a temporada 2021 o período de inscrições foi de xx/xx/xxxx a xx/xx/xxxx conforme resolução (xxxxx) publicada no jornal Notícias do Município de xx/xx/xxxx. O sorteio acontecerá dia xx/xx/xxxx. A partir do dia xx/xx/xxxx iniciar-se-a a etapa de matrículas, para os contemplados, no Centro Esportivo no dia e horário de sua aula. Acompanhe o status da sua inscrição, clicando aqui.");

		}else{

			Cart::setMsgError("Não encontramos nenhuma turma com a palavra '".$search."' nesta temporada! A temporada pode não estar iniciada, estar em processo de sorteio ou foi encerrada. Aguarde, ou entre em contato com o Centro Esportivo mais próximo à sua casa.");
		}		

	}else{

		if(isset($search) && $search != NULL){
			Cart::setMsgError("Encontramos ".$pagination['total']." turmas com a palavra '".$search."' para esta temporada! ");
		}

		$idtemporada = $pagination['data'][0]['idtemporada']; 

		$temporada->get((int)$idtemporada);

		$dtInicinscricao = $temporada->getdtinicinscricao(); 
		$dtTerminscricao = $temporada->getdtterminscricao();
		$dtInicmatricula = $temporada->getdtinicmatricula();
		$dtTermmatricula = $temporada->getdttermmatricula();

		if($temporada->getidstatustemporada() == 2){ // statustemporada = Temporada iniciada

			//Altera status para idstatustemporada = 4
			Temporada::alterarStatusTemporadaParaIncricoesIniciadas($dtInicinscricao, $idtemporada);
		}	
		
		if($temporada->getidstatustemporada() == 4){ // statustemporada = Inscrições iniciadas

			//Altera status para idstatustemporada = 3
			Temporada::alterarStatusTemporadaParaInscricoesEncerradas($dtTerminscricao, $idtemporada);
		}		

		/*
		if($temporada->getidstatustemporada() == 3){ // statustemporada = Inscrições encerradas

			//Altera status para idstatustemporada = 6 --> Será feito ao fazer o sorteio
			Temporada::alterarStatusTemporadaParaMatriculasIniciadas($dtInicmatricula, $idtemporada);
		}
		*/	

		if($temporada->getidstatustemporada() == 6){ // statustemporada = Matrículas iniciadas

			//Altera status para idstatustemporada = 5
			Temporada::alterarStatusTemporadaParaMatriculasEncerradas($dtTermmatricula, $idtemporada);
		}		
	}	

	// Aqui verifica se a temporada é igual ao ano atual
	// Se não for acrescenta (1). Supondo que a inscrição está sendo feita no ano anterior
	if( (int)date('Y')  == (int)$temporada->getdesctemporada() ){

		$anoAtual = (int)date('Y');	

	}else{

		$anoAtual = (int)date('Y') + 1;		
	}

	$locais = Local::listAllCrecAtivo();

	if(!isset($locais) || $locais == NULL){

		Cart::setMsgError("Não existe Crecs Cadastrados para esta temporada. A temporada pode não estar iniciada, estar em processo de sorteio ou foi encerrada. Aguarde, ou entre em contato com o Centro Esportivo mais próximo a sua casa. ");
	}		
		
	$page = new Page(); 

	$page->setTpl("index", array(
		'turma'=>Turma::checkList($pagination['data']),
		'idtemporada'=>$temporada->getidtemporada(),
		'anoAtual'=>$anoAtual,
		'profileMsg'=>User::getSuccess(),
		'error'=>Cart::getMsgError(),
		'locais'=>$locais,
	));
});

$app->get('/busca', function() {

	$search = (isset($_GET['search'])) ? $_GET['search'] : "";

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

		$pagination = Turma::getPageSearchTurmaTemporada($search, $page);

	$temporada = new Temporada();

	if(!isset($search) || $search == ''){

		Cart::setMsgError("Não foram encontradas turmas com a palavra digitada!");	
			header("Location: /");
			exit();
	}

	if(isset($search) && $search != NULL){

		Cart::setMsgError("Encontramos ".$pagination['total']." turmas com a palavra '".$search."' para esta temporada! ");			

		if( (int)date('Y')  == (int)$temporada->getdesctemporada() ){
			$anoAtual = (int)date('Y');	
		}else{
			$anoAtual = (int)date('Y') + 1;		
		}

		$page = new Page(); 

		$page->setTpl("busca", array(
			'turma'=>Turma::checkList($pagination['data']),
			"search"=>$search,
			'anoAtual'=>$anoAtual,
			'profileMsg'=>User::getSuccess(),
			'error'=>Cart::getMsgError(),
		));	
	}		
});




/*
$app->get('/', function() {

	$turma = Turma::listAllTurmaTemporada();
	$temporada = new Temporada();

	if(!isset($turma) || $turma == NULL){

		Cart::setMsgError("Não existe turmas para esta temporada. Aguarde! ");

	}else{

		$idtemporada = $turma[0]['idtemporada']; 

		$temporada->get((int)$idtemporada);

		$dtInicinscricao = $temporada->getdtinicinscricao();
		$dtTerminscricao = $temporada->getdtterminscricao();
		$dtTermmatricula = $temporada->getdttermmatricula();

		if($temporada->getidstatustemporada() == 2){

			Temporada::alterarStatusTemporadaParaIncricoesIniciadas($dtInicinscricao, $idtemporada);

		}	

		if($temporada->getidstatustemporada() == 4){

			Temporada::alterarStatusTemporadaParaMatriculasIniciadas($dtTerminscricao, $idtemporada);

		}	

		if($temporada->getidstatustemporada() == 6){

			Temporada::alterarStatusTemporadaParaMatriculasEncerradas($dtTermmatricula, $idtemporada);

		}				

	}	
		
	$page = new Page();  	

	$page->setTpl("index", [
		'turma'=>Turma::checkList($turma),
		'error'=>Cart::getMsgError()
	]);
});
*/

$app->get("/checkout", function(){

	User::verifyLogin(false);

	$cart = Cart::getFromSession();
	$user = User::getFromSession();	

	$idperson = (int)$_SESSION[User::SESSION]['idperson'];
	Endereco::seEnderecoExiste($idperson);

	$_SESSION['token'] = isset($_SESSION['token']) ? $_SESSION['token'] : '';

	$token = $_SESSION['token'];
	
	//$insc = new Insc;

	if(Cart::cartIsEmpty((int)$_SESSION[Cart::SESSION]['idcart']) === false){
		Cart::setMsgError("Selecione uma turma e a pessoa que irá fazer a aula! ");
		header("Location: /cart");
		exit();
	}	

	$page = new Page();

	$page->setTpl("checkout", [
		'token'=>$token,
		'cart'=>$cart->getValues(),
		'pessoa'=>$cart->getPessoa(),
		'turma'=>$cart->getTurma(),
		'error'=>Pessoa::getError()
	]);
});

$app->post("/checkout", function(){

	User::verifyLogin(false);

	$user = User::getFromSession();
	$cart = Cart::getFromSession();

	$idcart = (int)$cart->getidcart();

	$idtemporada = $_POST['idtemporada'];
	$idturma = $_POST['idturma'];

	$temporada = new Temporada();

	$temporada->get((int)$idtemporada);
	
	if(!isset($_POST['laudo']) || $_POST['laudo'] == NULL){

		Pessoa::setError("Informe se você irá confirmar uma inscrição para pessoa com inscicação médica! ");
		header("Location: /checkout");
		exit();
	}
	

	if(!isset($_POST['inscpcd']) || $_POST['inscpcd'] == NULL){

			Pessoa::setError("Informe se você irá confirmar uma inscrição para pessoa com deficiência! ");
			header("Location: /checkout");
			exit();
	}

	if(!isset($_POST['edital']) || $_POST['edital'] == NULL){

		Pessoa::setError("Assinale que você leu os termos para as inscrições! ");
		header("Location: /checkout");
		exit();
	}

	if(!isset($_POST['ciente']) || $_POST['ciente'] == NULL){

		Pessoa::setError("Marque, logo abaixo, que você está ciente das regras para finalizar a inscrição! ");
		header("Location: /checkout");
		exit();
	}

	$laudo = isset($_POST['laudo']) ? (int)$_POST['laudo'] : 0;
	$inscpcd = isset($_POST['inscpcd']) ? (int)$_POST['inscpcd'] : 0;

	$cartsturmas = CartsTurmas::getCartsTurmasFromId($idcart);

	$turma = new Turma();

	$pessoa = new Pessoa();
	
	$insc = new Insc();	

	$desctemporada = $temporada->getdesctemporada();

	$idpess= $cart->getidpess();

	$pessoa->get((int)$idpess);
	
	$anoNasc = $pessoa->getdtnasc();
	
	$anoNasc = new DateTime($anoNasc);
	
	$anoNasc = (int)$anoNasc->format('Y');
	
	if( (int)date('Y')  == (int)$desctemporada ){

		$anoAtual = (int)date('Y');	

	}else{

		$anoAtual = (int)date('Y') + 1;		
	}
	
	$idlocal = $_POST['idlocal'];
	
	
	$initidade = $_POST['initidade'];
	
	$idmodal = $_POST['idmodal'];
	
	
	// idade 40 para idade inicial das hidros da pauliceia
	// idlocal 21 para comparar com local pauliceia
	// idmodal para para comparar com modalidade hidroginástica
	
	
    if($laudo == 0){

		if($idlocal == 21 && $idmodal == 6){

			if(($anoAtual - $anoNasc) < 40){

				Pessoa::setError("Você deve marcar a opçãp 'Sim' em: Esta é uma  inscrição para pessoa com laudo médico (Solicitação Médica) ");
				header("Location: /checkout");
				exit();
		   }

	    }	
	}
	

	$nomepess = $pessoa->getnomepess();

	$email = $user->getdesemail();	

	$desperson = $user->getdesperson();		

	//if(Insc::statusTemporadaMatriculaIniciada($idtemporada)){
		//$InscStatus = InscStatus::AGUARDANDO_MATRICULA;

	//}

	if(Insc::statusTemporadaMatriculasEncerradas($idtemporada)){

		$InscStatus = InscStatus::FILA_DE_ESPERA;

		$numOrdemMax = Insc::numMaxNumOrdem($idtemporada, $idturma);
		$mumMatriculados = Insc::numMatriculados($idtemporada, $idturma);

		$numordem = $numOrdemMax[0]['maxNumOrdem'] + 1;
		$matriculados = $mumMatriculados[0]['nummatriculados'];

		$turma->get((int)$idturma);

		
		$vagas = $turma->getvagas();

		$token = $_POST['token'];

		$posicao = $numordem - $vagas;

		$insc->setData([
			'idcart'=>$idcart,
			'idinscstatus'=>$InscStatus,
			'numordem'=>$numordem,
			'laudo'=>$laudo,
			'inscpcd'=>$inscpcd,
			'idturma'=>$idturma,
			'idtemporada'=>$idtemporada	
		]);

		$insc->save();

		Turma::setUsedToken($idturma, $token);

		$idinsc = $insc->getidinsc();	

		$numsorte = $insc->getnumsorte();	

		$_SESSION['token'] = NULL;	

		$cart->removeTurma($turma, true);
		Cart::removeFromSession();
	    session_regenerate_id();

	    $insc->inscricaoEmailPosSorteio($idinsc, $idpess, $nomepess, $email, $desperson, $desctemporada, $turma, $posicao, $matriculados, $vagas);

		header("Location: /profile/insc/".$insc->getidinsc()."/".$idpess."");
        exit;	

	}else{

		$InscStatus = InscStatus::AGUARDANDO_SORTEIO;

		$token = $_POST['token'];
		
		$numordem = 0;	

		$insc->setData([
			'idcart'=>$idcart,
			'idinscstatus'=>$InscStatus,
			'numordem'=>$numordem,
			'laudo'=>$laudo,
			'inscpcd'=>$inscpcd,
			'idturma'=>$idturma,
			'idtemporada'=>$idtemporada	
		]);

		$insc->save();

		Turma::setUsedToken($idturma, $token);		

		$idinsc = $insc->getidinsc();	

		$numsorte = $insc->getnumsorte();

		$turma->get((int)$idturma);

		$_SESSION['token'] = NULL;

		$cart->removeTurma($turma, true);
		Cart::removeFromSession();
	    session_regenerate_id();

	    $insc->inscricaoEmail($idinsc, $numsorte, $idpess, $nomepess, $email, $desperson, $desctemporada, $turma);

		header("Location: /profile/insc/".$insc->getidinsc()."/".$idpess."");
		exit;
	}	
});

$app->get("/turma/:idturma/:idtemporada", function($idturma, $idtemporada){

	$turma = new Turma();

	$turma->getFromIdTurmaTemporada($idturma, $idtemporada);

	if(
		$turma->getidstatustemporada() != 4 
	   	AND $turma->getidstatustemporada() != 5 
	    AND $turma->getidstatustemporada() != 6
		)
	{

		Turma::setMsgError("Não existem inscrições diponíveis para esta temporada. Para a temporada 2021 o período de inscrições foi de xx/xx/xxxx a xx/xx/xxxx conforme resolução (xxxxx) publicada no jornal Notícias do Município de xx/xx/xxxx. O sorteio acontecerá dia xx/xx/xxxx. A partir do dia xx/xx/xxxx iniciar-se-a a etapa de matrículas, para os contemplados, no Centro Esportivo no dia e horário de sua aula. Acompanhe o status da sua inscrição, clicando aqui.");

	}

	$page = new Page(); 

	$page->setTpl("turma-detail", [
		'turma'=>$turma->getValues(),
		'error'=>Turma::getMsgError(),
	]);
});

$app->get("/login", function(){

	$page = new Page();

	/*
	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);
	*/

	$page->setTpl("login", [
		'error'=>User::getError(),
		'profileMsg'=>User::getSuccess(),
		'errorRegister'=>User::getErrorRegister()
		//'registerValues'=>(isset($_SESSION['registerValues'])) ? $_SESSION['registerValues'] : ['name'=>'', 'email'=>'', 'phone'=>'']
	]);
});

$app->post("/login", function(){

	try {

		User::login($_POST['login'], $_POST['password']);

	} catch(Exception $e) {

		User::setError($e->getMessage());
		header("Location: /login");
		exit;
	}

	header("Location: /");
	exit;
});

$app->get("/logout", function(){

	User::logout();

	User::forgotUserPass();

	Cart::removeFromSession();
	
	session_regenerate_id();

	header("Location: /login");
	exit;
});




$app->get("/forgot", function() {


	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");	
});


$app->post("/forgot", function($email){

	$user = User::getForgot($_POST["email"], false);

	header("Location: /forgot/sent");
	exit;
});

$app->get("/forgot/sent", function(){

	$page = new Page();

	$page->setTpl("forgot-sent");	
});


$app->get("/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new Page();

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));
});

$app->post("/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);	

	User::setFogotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = User::getPasswordHash($_POST["password"]);

	$user->setPassword($password);

	$page = new Page();

	$page->setTpl("forgot-reset-success");
});


$app->get("/comprovante", function() {

	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("comprovante-insc");	
});
/*
$app->get("/calendario", function() {

	User::verifyLogin(false);

	$page = new Page();

	$page->setTpl("calendario");	
});
*/




?>