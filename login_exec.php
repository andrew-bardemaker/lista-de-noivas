<?php
include('./inc/inc.configdb.php');
include('./inc/inc.lib.php');

if (isset($_REQUEST['act']) && !empty($_REQUEST['act'])) {
    session_start(); //SEMPRE QUE FOR USAR A SESSION
    $act = $_REQUEST['act']; //server tanto pra POST quanto pra GET
    switch ($act) {

		case 'login':
			$ema = addslashes($_POST['user']);
			$sen = addslashes(trim(md5($_POST['pass'])));
			//monta o comando sql e executa
			$sql = "select * from dedstudio13_usuarios where usu_nome='$ema' and usu_senha='$sen' "; 
			$query = $dba->query($sql);
			$qntd  = $dba->rows($query);
			
			//testar: se deu certo, vai, sen�o, volta
			if ($qntd > 0) {
				$vet = $dba->fetch($query);
				$_SESSION['usercod'] = addslashes($_POST['idusuarios']);
				$_SESSION['usernom'] = addslashes($_POST['usu_nome']);
				header('location: dashboard.php');
			} else {
				header("location: index.php?msg=123&usuario=".$ema."&senha=".$sen);
			}
			break;

		case 'logout':
			session_destroy();
			header('location: ./');
			break;


		default:
            header("location: ./?msg=000");
    }
    //fim do switch
    
} else {
    header("location: ./?msg=000");
}

?>