<?php
include('./inc/inc.configdb.php');
include('./inc/inc.configdir.php');
include('./inc/inc.lib.php');
include('./inc/inc.upload.php');
ini_set('max_execution_time', 0);
if (isset($_REQUEST['act']) && !empty($_REQUEST['act'])) {
    session_start(); //SEMPRE QUE FOR USAR A SESSION
    $act = $_REQUEST['act']; //server tanto pra POST quanto pra GET
    switch ($act) {

        case 'clientes_cadastrar':
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if ($_FILES['img']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }

            $dimensoes = getimagesize($_FILES['img']['tmp_name']);
            $largura = $dimensoes[0];
            $altura = $dimensoes[1];
            if ($largura != $altura) {
                echo "invalidimgdimensoes";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");
            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['img']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);

            $sql = "INSERT INTO clientes (titulo, texto) values ('$titulo', '$texto') "; //die($sql);          
            $res = $dba->query($sql);

            $ide = $dba->lastid();
            $img = $_FILES['img'];
            $destino = "../img/clientes/" . $ide . ".jpg";
            $ok = upload($img, $destino, 600, 600);

            echo "success";
            break;

        case 'clientes_delete_img':
            $id = $_REQUEST['id'];

            $imagem = '../img/clientes/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header("location: clientes_editar?id=$id");
            break;

        case 'usuarios_editar':
            $id= $_POST['id']; 
            if ($_POST['pass'] != $_POST['rpt_pass']) {
                echo "As senhas que você digitou não são iguais";
                exit;
            }
            
            $nome = $_POST['nome'];
            $cpf = $_POST['cpf'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $unidade = $_POST['loja'];
            $pass = $_POST['pass'];



            $sql = "UPDATE dedstudio13_usuarios SET  `nome` = '$nome', `usu_senha`=MD5('$pass'),  `cpf`='$cpf', `email`='$email', `telefone`='$telefone', `status`='1', `unidade`='$unidade' WHERE idusuarios=$id ";
            $res = $dba->query($sql); 
            echo "updated";
            break;
            /************************************/
            break;
        case 'user_insert':
            /************* CADASTRO *************/
            if ($_POST['pass'] != $_POST['rpt_pass']) {
                echo "As senhas que você digitou não são iguais";
                exit;
            }

            $nome = $_POST['nome'];
            $cpf = $_POST['cpf'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $unidade = $_POST['loja'];
            $pass = $_POST['pass'];



            $sql = "INSERT INTO dedstudio13_usuarios ( `usu_nome`, `usu_senha`, `nome`, `cpf`, `email`, `telefone`, `status`, `unidade`) 
                VALUES ( '$nome', MD5('$pass'), '$nome', '$cpf', '$email', '$telefone', '1', '$unidade'); "; //die($sql);			
            $res = $dba->query($sql);

            echo "success";
            /************************************/
            break;
        case 'usuario_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM dedstudio13_usuarios WHERE idusuarios = '$id'";
            $res = $dba->query($sql);



            header('location: usuarios?msg=c003');
            break;


        case 'clientes_editar':
            $ide = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            if (!file_exists('../img/clientes/' . $ide . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                $dimensoes = getimagesize($_FILES['img']['tmp_name']);
                $largura = $dimensoes[0];
                $altura = $dimensoes[1];
                if ($largura != $altura) {
                    echo "invalidimgdimensoes";
                    exit;
                }

                //array de extensões permitidas 
                $allowedExts = array(".jpg", ".JPG");
                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);

            $sql = "UPDATE clientes SET texto='$texto', titulo='$titulo' WHERE id ='$ide'"; //die($sql);          
            $res = $dba->query($sql);

            if (!file_exists('../img/clientes/' . $ide . '.jpg')) {
                $img = $_FILES['img'];
                $destino = "../img/clientes/" . $ide . ".jpg";
                $ok = upload($img, $destino, 600, 600);
            }

            echo "success_edit";
            break;



            // DESIGN
        case 'design_cadastrar':

            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if ($_FILES['img']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['img']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "INSERT INTO design (titulo, texto, ano, link, categoria, id_clientes) values ('$titulo', '$texto', '$ano', '$link', '$categoria', '$clientes') "; //die($sql);          
            $res = $dba->query($sql);

            $ide = $dba->lastid();

            $img = $_FILES['img'];
            $destino = "../img/design/" . $ide . ".jpg";
            $ok = upload($img, $destino, 1920, 6000);

            $img = "../img/design/" . $ide . ".jpg";
            $thumb = new m2brimagem("$img");
            $verifica = $thumb->valida();

            if ($verifica == "OK") {
                $thumb->redimensiona(900, 600, 'crop');
                $thumb->grava('../img/design/' . $ide . '_900x600.jpg', 60);
            } else {
                die($verifica);
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/design/" . $ide . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/design/" . $ide . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img2 = $_FILES['img3'];
                $destino = "../img/design/" . $ide . "_3.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img3 = $_FILES['img4'];
                $destino = "../img/design/" . $ide . "_4.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img4 = $_FILES['img5'];
                $destino = "../img/design/" . $ide . "_5.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            echo "success";
            break;

        case 'design_delete_img':
            $id = $_REQUEST['id'];
            $num = $_REQUEST['num'];

            if ($num == 0) {
                $imagem = '../img/design/' . $id . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
                $imagem = '../img/design/' . $id . '_900x600.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            } else {
                $imagem = '../img/design/' . $id . '_' . $num . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            }

            header("location: design_editar?id=$id");
            break;

        case 'design_editar':
            $id = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            if (!file_exists('../img/design/' . $id . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                //if (empty($_FILES['img']) && $_FILES['img']['size'] == 0) {echo "img"; exit;}     
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }


            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "UPDATE design SET titulo='$titulo', texto='$texto', ano='$ano', link='$link', categoria='$categoria', id_clientes='$clientes' WHERE id = $id"; //die($sql);          
            $res = $dba->query($sql);

            if (isset($_FILES['img'])) {
                $img = $_FILES['img'];
                $destino = "../img/design/" . $id . ".jpg";
                $ok = upload($img, $destino, 1920, 6000);

                $img = "../img/design/" . $id . ".jpg";
                $thumb = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->redimensiona(900, 600, 'crop');
                    $thumb->grava('../img/design/' . $id . '_900x600.jpg', 60);
                } else {
                    die($verifica);
                }
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/design/" . $id . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/design/" . $id . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img2 = $_FILES['img3'];
                $destino = "../img/design/" . $id . "_3.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img3 = $_FILES['img4'];
                $destino = "../img/design/" . $id . "_4.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img4 = $_FILES['img5'];
                $destino = "../img/design/" . $id . "_5.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            echo "success_edit";
            break;

        case 'design_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM design WHERE id = '$id'";
            $res = $dba->query($sql);

            $imagem = '../img/design/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/design/' . $id . '_900x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            $imagem = '../img/design/' . $id . '_1.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/design/' . $id . '_2.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/design/' . $id . '_3.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/design/' . $id . '_4.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/design/' . $id . '_5.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header('location: design?msg=d003');
            break;

        case 'desativar_design':
            $idn = $_GET['id'];

            $sql = "UPDATE design SET destaque = 0 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: design?msg=d005');
            break;

        case 'ativar_design':
            $idn = $_GET['id'];

            $sql = "UPDATE design SET destaque = 1 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: design?msg=d004');
            break;

            //FOTOGRAFIA
        case 'galeria_cadastrar':

            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }

            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if ($_FILES['img']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['img']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            $titulo = addslashes($_POST['titulo']);


            $sql = "INSERT INTO borghetti_galeria (titulo) values ('$titulo') "; //die($sql);          
            $res = $dba->query($sql);

            $ide = $dba->lastid();

            $img = $_FILES['img'];
            $destino = "../img/galeria/" . $ide . ".jpg";
            $ok = upload($img, $destino, 1920, 6000);

            $img = "../img/galeria/" . $ide . ".jpg";
            $thumb = new m2brimagem("$img");
            $verifica = $thumb->valida();

            if ($verifica == "OK") {
                $thumb->redimensiona(900, 600, 'crop');
                $thumb->grava('../img/galeria/' . $ide . '_900x600.jpg', 60);
            } else {
                die($verifica);
            }

            header("location: galeria");
            echo "success";
            break;

        case 'adicionarProdutoCha': 
            $idcha = $_GET['idcha'];
            $idproduto = $_GET['idproduto'];
            $quantidade= $_GET['quantidade']; 
            $sql = "INSERT INTO dedstudio13_cha_produtos (id_produto,id_cha,quantidade) VALUES($idproduto,$idcha,$quantidade) ";
            $res = $dba->query($sql);


            header('location: adicionar-produtos-cha.php?id='.$idcha);
            break;
        case 'fotografia_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM dedstudio13_cha WHERE id = '$id'";
            $res = $dba->query($sql);

            $sql = "DELETE FROM dedstudio13_cha_produtos WHERE id_cha = '$id'";
            $res = $dba->query($sql);


            header('location: cha');
            break;

        case 'desativar_fotografia':
            $idn = $_GET['id'];

            $sql = "UPDATE borghetti_galeria SET status = 0 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: borghetti_galeria?msg=f005');
            break;

        case 'ativar_borghetti_galeria':
            $idn = $_GET['id'];

            $sql = "UPDATE borghetti_galeria SET status = 1 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: borghetti_galeria?msg=f004');
            break;

            //arquitetura
        case 'arquitetura_cadastrar':

            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if ($_FILES['img']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['img']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo = addslashes($_POST['titulo']);
            $subtitulo = addslashes($_POST['subtitulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "INSERT INTO arquitetura (titulo, subtitulo, texto, ano, link, categoria, id_clientes) values ('$titulo', '$subtitulo', '$texto', '$ano', '$link', '$categoria', '$clientes') "; //die($sql);          
            $res = $dba->query($sql);

            $ide = $dba->lastid();

            $img = $_FILES['img'];
            $destino = "../img/arquitetura/" . $ide . ".jpg";
            $ok = upload($img, $destino, 1920, 6000);

            $img = "../img/arquitetura/" . $ide . ".jpg";
            $thumb = new m2brimagem("$img");
            $verifica = $thumb->valida();

            if ($verifica == "OK") {
                $thumb->redimensiona(900, 600, 'crop');
                $thumb->grava('../img/arquitetura/' . $ide . '_900x600.jpg', 60);
            } else {
                die($verifica);
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/arquitetura/" . $ide . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/arquitetura/" . $ide . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img2 = $_FILES['img3'];
                $destino = "../img/arquitetura/" . $ide . "_3.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img3 = $_FILES['img4'];
                $destino = "../img/arquitetura/" . $ide . "_4.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img4 = $_FILES['img5'];
                $destino = "../img/arquitetura/" . $ide . "_5.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            echo "success";
            break;

        case 'arquitetura_delete_img':
            $id = $_REQUEST['id'];
            $num = $_REQUEST['num'];

            if ($num == 0) {
                $imagem = '../img/arquitetura/' . $id . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
                $imagem = '../img/arquitetura/' . $id . '_900x600.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            } else {
                $imagem = '../img/arquitetura/' . $id . '_' . $num . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            }

            header("location: arquitetura_editar?id=$id");
            break;

        case 'arquitetura_editar':
            $id = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            if (!file_exists('../img/arquitetura/' . $id . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                //if (empty($_FILES['img']) && $_FILES['img']['size'] == 0) {echo "img"; exit;}     
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }


            $titulo = addslashes($_POST['titulo']);
            $subtitulo = addslashes($_POST['subtitulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "UPDATE arquitetura SET titulo='$titulo', subtitulo='$subtitulo', texto='$texto', ano='$ano', link='$link', categoria='$categoria', id_clientes='$clientes' WHERE id = $id"; //die($sql);          
            $res = $dba->query($sql);

            if (isset($_FILES['img'])) {
                $img = $_FILES['img'];
                $destino = "../img/arquitetura/" . $id . ".jpg";
                $ok = upload($img, $destino, 1920, 6000);

                $img = "../img/arquitetura/" . $id . ".jpg";
                $thumb = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->redimensiona(900, 600, 'crop');
                    $thumb->grava('../img/arquitetura/' . $id . '_900x600.jpg', 60);
                } else {
                    die($verifica);
                }
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/arquitetura/" . $id . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/arquitetura/" . $id . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img2 = $_FILES['img3'];
                $destino = "../img/arquitetura/" . $id . "_3.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img3 = $_FILES['img4'];
                $destino = "../img/arquitetura/" . $id . "_4.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img4 = $_FILES['img5'];
                $destino = "../img/arquitetura/" . $id . "_5.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            echo "success_edit";
            break;

        case 'arquitetura_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM arquitetura WHERE id = '$id'";
            $res = $dba->query($sql);

            $imagem = '../img/arquitetura/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/arquitetura/' . $id . '_900x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            $imagem = '../img/arquitetura/' . $id . '_1.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/arquitetura/' . $id . '_2.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/arquitetura/' . $id . '_3.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/arquitetura/' . $id . '_4.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/arquitetura/' . $id . '_5.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header('location: arquitetura?msg=a003');
            break;

        case 'desativar_arquitetura':
            $idn = $_GET['id'];

            $sql = "UPDATE arquitetura SET destaque = 0 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: arquitetura?msg=a005');
            break;

        case 'ativar_arquitetura':
            $idn = $_GET['id'];

            $sql = "UPDATE arquitetura SET destaque = 1 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: arquitetura?msg=a004');
            break;

            //filme
        case 'filme_cadastrar':

            if (empty($_POST['link'])) {
                echo "link";
                exit;
            }
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if ($_FILES['img']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['img']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "INSERT INTO filme (titulo, texto, ano, link, categoria, id_clientes) values ('$titulo', '$texto', '$ano', '$link', '$categoria', '$clientes') "; //die($sql);          
            $res = $dba->query($sql);

            $ide = $dba->lastid();

            $img = $_FILES['img'];
            $destino = "../img/filme/" . $ide . ".jpg";
            $ok = upload($img, $destino, 1920, 6000);

            $img = "../img/filme/" . $ide . ".jpg";
            $thumb = new m2brimagem("$img");
            $verifica = $thumb->valida();

            if ($verifica == "OK") {
                $thumb->redimensiona(900, 600, 'crop');
                $thumb->grava('../img/filme/' . $ide . '_900x600.jpg', 60);
            } else {
                die($verifica);
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/filme/" . $ide . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/filme/" . $ide . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img3 = $_FILES['img3'];
                $destino = "../img/filme/" . $ide . "_3.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img4 = $_FILES['img4'];
                $destino = "../img/filme/" . $ide . "_4.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img5 = $_FILES['img5'];
                $destino = "../img/filme/" . $ide . "_5.jpg";
                upload($img5, $destino, 1920, 6000);
            }

            echo "success";
            break;

        case 'filme_delete_img':
            $id = $_REQUEST['id'];
            $num = $_REQUEST['num'];

            if ($num == 0) {
                $imagem = '../img/filme/' . $id . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
                $imagem = '../img/filme/' . $id . '_900x600.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            } else {
                $imagem = '../img/filme/' . $id . '_' . $num . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            }

            header("location: filme_editar?id=$id");
            break;

        case 'filme_editar':
            $id = $_POST['id'];
            if (empty($_POST['link'])) {
                echo "link";
                exit;
            }
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            if (!file_exists('../img/filme/' . $id . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                //if (empty($_FILES['img']) && $_FILES['img']['size'] == 0) {echo "img"; exit;}     
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }


            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "UPDATE filme SET titulo='$titulo', texto='$texto', ano='$ano', link='$link', categoria='$categoria', id_clientes='$clientes' WHERE id = $id"; //die($sql);          
            $res = $dba->query($sql);

            if (isset($_FILES['img'])) {
                $img = $_FILES['img'];
                $destino = "../img/filme/" . $id . ".jpg";
                $ok = upload($img, $destino, 1920, 6000);

                $img = "../img/filme/" . $id . ".jpg";
                $thumb = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->redimensiona(900, 600, 'crop');
                    $thumb->grava('../img/filme/' . $id . '_900x600.jpg', 60);
                } else {
                    die($verifica);
                }
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/filme/" . $id . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/filme/" . $id . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img3 = $_FILES['img3'];
                $destino = "../img/filme/" . $id . "_3.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img4 = $_FILES['img4'];
                $destino = "../img/filme/" . $id . "_4.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img5 = $_FILES['img5'];
                $destino = "../img/filme/" . $id . "_5.jpg";
                upload($img5, $destino, 1920, 6000);
            }

            echo "success_edit";
            break;

        case 'filme_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM filme WHERE id = '$id'";
            $res = $dba->query($sql);

            $imagem = '../img/filme/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/filme/' . $id . '_900x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            $imagem = '../img/filme/' . $id . '_1.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/filme/' . $id . '_2.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/filme/' . $id . '_3.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/filme/' . $id . '_4.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/filme/' . $id . '_5.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header('location: filme?msg=flm3');
            break;

        case 'desativar_filme':
            $idn = $_GET['id'];

            $sql = "UPDATE filme SET destaque = 0 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: filme?msg=flm5');
            break;

        case 'ativar_filme':
            $idn = $_GET['id'];

            $sql = "UPDATE filme SET destaque = 1 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: filme?msg=flm4');
            break;

            // TECNOLOGIA
        case 'tecnologia_cadastrar':

            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            if (!isset($_FILES['img'])) {
                echo "file";
                exit;
            }
            if ($_FILES['img']['size'] > 2097152) {
                echo "invalidimgsize";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['img']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "invalidimg";
                exit;
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "INSERT INTO tecnologia (titulo, texto, ano, link, categoria, id_clientes) values ('$titulo', '$texto', '$ano', '$link', '$categoria', '$clientes') "; //die($sql);          
            $res = $dba->query($sql);

            $ide = $dba->lastid();

            $img = $_FILES['img'];
            $destino = "../img/tecnologia/" . $ide . ".jpg";
            $ok = upload($img, $destino, 1920, 6000);

            $img = "../img/tecnologia/" . $ide . ".jpg";
            $thumb = new m2brimagem("$img");
            $verifica = $thumb->valida();

            if ($verifica == "OK") {
                $thumb->redimensiona(900, 600, 'crop');
                $thumb->grava('../img/tecnologia/' . $ide . '_900x600.jpg', 60);
            } else {
                die($verifica);
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/tecnologia/" . $ide . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/tecnologia/" . $ide . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img2 = $_FILES['img3'];
                $destino = "../img/tecnologia/" . $ide . "_3.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img3 = $_FILES['img4'];
                $destino = "../img/tecnologia/" . $ide . "_4.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img4 = $_FILES['img5'];
                $destino = "../img/tecnologia/" . $ide . "_5.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            echo "success";
            break;

        case 'tecnologia_delete_img':
            $id = $_REQUEST['id'];
            $num = $_REQUEST['num'];

            if ($num == 0) {
                $imagem = '../img/tecnologia/' . $id . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
                $imagem = '../img/tecnologia/' . $id . '_900x600.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            } else {
                $imagem = '../img/tecnologia/' . $id . '_' . $num . '.jpg';
                if (is_file($imagem)) {
                    unlink($imagem);
                }
            }

            header("location: tecnologia_editar?id=$id");
            break;

        case 'tecnologia_editar':
            $id = $_POST['id'];
            if (empty($_POST['titulo'])) {
                echo "titulo";
                exit;
            }
            if (empty($_POST['ano'])) {
                echo "ano";
                exit;
            }
            if (empty($_POST['categoria']) || $_POST['categoria'] == '') {
                echo "categoria";
                exit;
            }

            //array de extensões permitidas 
            $allowedExts = array(".jpg", ".JPG");

            if (!file_exists('../img/tecnologia/' . $id . '.jpg')) {
                if (!isset($_FILES['img'])) {
                    echo "file";
                    exit;
                }
                //if (empty($_FILES['img']) && $_FILES['img']['size'] == 0) {echo "img"; exit;}     
                if ($_FILES['img']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }

                //Atribui uma array com os nomes dos arquivos à variável
                $name = $_FILES['img']['name'];
                $ext = strtolower(substr($name, -4));
                //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                if (!in_array($ext, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img1
            if (isset($_FILES['img1'])) {
                if ($_FILES['img1']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img1']['name'];
                $ext1 = strtolower(substr($name1, -4));
                if (!in_array($ext1, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }

            //img2
            if (isset($_FILES['img2'])) {
                if ($_FILES['img2']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name1 = $_FILES['img2']['name'];
                $ext2 = strtolower(substr($name1, -4));
                if (!in_array($ext2, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img3
            if (isset($_FILES['img3'])) {
                if ($_FILES['img3']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name3 = $_FILES['img3']['name'];
                $ext3 = strtolower(substr($name3, -4));
                if (!in_array($ext3, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img4
            if (isset($_FILES['img4'])) {
                if ($_FILES['img4']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name4 = $_FILES['img4']['name'];
                $ext4 = strtolower(substr($name4, -4));
                if (!in_array($ext4, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }
            //img5
            if (isset($_FILES['img5'])) {
                if ($_FILES['img5']['size'] > 2097152) {
                    echo "invalidimgsize";
                    exit;
                }
                $name5 = $_FILES['img5']['name'];
                $ext5 = strtolower(substr($name5, -4));
                if (!in_array($ext5, $allowedExts)) {
                    echo "invalidimg";
                    exit;
                }
            }


            $titulo = addslashes($_POST['titulo']);
            $texto = addslashes($_POST['texto']);
            $ano = addslashes($_POST['ano']);
            $link = addslashes($_POST['link']);
            $categoria = addslashes($_POST['categoria']);
            $clientes = addslashes($_POST['clientes']);

            $sql = "UPDATE tecnologia SET titulo='$titulo', texto='$texto', ano='$ano', link='$link', categoria='$categoria', id_clientes='$clientes' WHERE id = $id"; //die($sql);          
            $res = $dba->query($sql);

            if (isset($_FILES['img'])) {
                $img = $_FILES['img'];
                $destino = "../img/tecnologia/" . $id . ".jpg";
                $ok = upload($img, $destino, 1920, 6000);

                $img = "../img/tecnologia/" . $id . ".jpg";
                $thumb = new m2brimagem("$img");
                $verifica = $thumb->valida();

                if ($verifica == "OK") {
                    $thumb->redimensiona(900, 600, 'crop');
                    $thumb->grava('../img/tecnologia/' . $id . '_900x600.jpg', 60);
                } else {
                    die($verifica);
                }
            }

            if (isset($_FILES['img1'])) {
                $img1 = $_FILES['img1'];
                $destino = "../img/tecnologia/" . $id . "_1.jpg";
                upload($img1, $destino, 1920, 6000);
            }

            if (isset($_FILES['img2'])) {
                $img2 = $_FILES['img2'];
                $destino = "../img/tecnologia/" . $id . "_2.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img3'])) {
                $img2 = $_FILES['img3'];
                $destino = "../img/tecnologia/" . $id . "_3.jpg";
                upload($img2, $destino, 1920, 6000);
            }

            if (isset($_FILES['img4'])) {
                $img3 = $_FILES['img4'];
                $destino = "../img/tecnologia/" . $id . "_4.jpg";
                upload($img3, $destino, 1920, 6000);
            }

            if (isset($_FILES['img5'])) {
                $img4 = $_FILES['img5'];
                $destino = "../img/tecnologia/" . $id . "_5.jpg";
                upload($img4, $destino, 1920, 6000);
            }

            echo "success_edit";
            break;

        case 'tecnologia_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM tecnologia WHERE id = '$id'";
            $res = $dba->query($sql);

            $imagem = '../img/tecnologia/' . $id . '.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/tecnologia/' . $id . '_900x600.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            $imagem = '../img/tecnologia/' . $id . '_1.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/tecnologia/' . $id . '_2.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/tecnologia/' . $id . '_3.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/tecnologia/' . $id . '_4.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }
            $imagem = '../img/tecnologia/' . $id . '_5.jpg';
            if (is_file($imagem)) {
                unlink($imagem);
            }

            header('location: tecnologia?msg=t003');
            break;

        case 'desativar_tecnologia':
            $idn = $_GET['id'];

            $sql = "UPDATE tecnologia SET destaque = 0 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: tecnologia?msg=t005');
            break;

        case 'ativar_tecnologia':
            $idn = $_GET['id'];

            $sql = "UPDATE tecnologia SET destaque = 1 WHERE id = $idn";
            $res = $dba->query($sql);

            header('location: tecnologia?msg=t004');
            break;

        case 'cadastrar_produto';

            if (empty($_POST['codigo'])) {
                echo "codigo";
                exit;
            } else {
                $codigo = $_POST['codigo'];
            }
            if (empty($_POST['nome'])) {
                echo "nome";
                exit;
            } else {
                $nome =  $_POST['nome'];
            }
            if (empty($_POST['preco'])) {
            } else {
                $preco =  $_POST['preco'];
            }

            $sql = "INSERT INTO dedstudio13_produtos (codigo_sistema , nome , preco)  VALUES ('$codigo' , '$nome' , '$preco')";
            $res = $dba->query($sql);
            $ide = $dba->lastid();

            if (!empty($_POST['barras'])) {
                $barras = $_POST['barras'];
                $max   = sizeof($barras);
                for ($i = 0; $i < $max; $i++) {
                    $id = $barras[$i];

                    $sql = "INSERT INTO dedstudio13_codigo_de_barras (id_produto , codigo)  VALUES ( '$ide' , '$id')";
                    $dba->query($sql);
                }
            }


            header("location: ./produtos");
            echo "success";
            break;

        case 'produtos_multiplos_cadastrar':
            if (empty($_FILES['file']) || $_FILES['file']['size'] == 0) {
                echo "file";
                exit;
            }
            //array de extensões permitidas 
            $allowedExts = array(".zip", ".ZIP");
            //Atribui uma array com os nomes dos arquivos à variável
            $name = $_FILES['file']['name'];
            $ext = strtolower(substr($name, -4));
            //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
            if (!in_array($ext, $allowedExts)) {
                echo "file_zip";
                exit;
            }

            move_uploaded_file($_FILES['file']['tmp_name'], "./uploads/tmp/" . $name);

            $z = new ZipArchive();
            $z->open("./uploads/tmp/" . $name); // Abrindo arquivo para leitura/escrita

            $extract = $z->extractTo('./uploads/tmp/', array('produtos_maria.csv')); // Extraindo apenas o arquivo estoque.csv

            if ($extract === true) { // Verifica se foi extraído o arquivo estoque.csv
                if (($handle = fopen("./uploads/tmp/produtos_maria.csv", "r")) !== FALSE) { // Abre arquivo csv
                    while (($data = fgetcsv($handle, 0, "\n")) !== FALSE) { // Verifica enquanto houver registro do csv 
                        $dados = $data[0]; // Linha do arquivo atual
                        $array = explode(";", $dados); // Separa colunas por ;
                        $codigo_produto = addslashes(trim($array[0])); // Código do produto
                        if (is_numeric($codigo_produto)) {
                            $codigo_barras = addslashes(trim($array[1])); // Título do produto
                            $nome = addslashes(trim($array[2])); // Id grupo
                            if (!empty($array[3])) {

                                $preco = addslashes(trim($array[3])); // Título do grupo    

                                $preco = str_replace(",", ".", $preco);

                                if ($preco > 0) {
                                    // Verifica se já existe registro do produto no bd
                                    $sql = "SELECT codigo_sistema FROM dedstudio13_produtos WHERE codigo_sistema = $codigo_barras";
                                    $query = $dba->query($sql);
                                    $qntd = $dba->rows($query);
                                    if ($qntd == 0) {
                                        // Grava registro no bd
                                        $sql2 = "INSERT INTO dedstudio13_produtos (codigo_sistema , nome ,preco) VALUES ('$codigo_barras','$nome','$preco')"; //print_r($sql2);
                                        $dba->query($sql2);
                                        $ide = $dba->lastid();

                                        $sq3 = "INSERT INTO dedstudio13_codigo_de_barras (id_produto , codigo) VALUES ('$ide','$codigo_barras')"; //print_r($sq3);
                                        $dba->query($sq3);
                                    } else {
                                        // Atualiza registro no bd
                                        $sql2 = "UPDATE dedstudio13_produtos SET codigo_sistema='$codigo_barras', nome='$nome' , preco='$preco' WHERE codigo_sistema = $codigo_barras"; //print_r($sql2);
                                        $dba->query($sql2);
                                    }
                                }
                            } else {


                                // Verifica se já existe registro do produto no bd
                                $sql = "SELECT codigo_sistema FROM dedstudio13_produtos WHERE codigo_sistema = $codigo_produto";
                                $query = $dba->query($sql);
                                $qntd = $dba->rows($query);
                                if ($qntd == 0) {
                                    // Grava registro no bd
                                    $sql2 = "INSERT INTO dedstudio13_produtos (codigo_sistema , nome) VALUES ('$codigo_produto','$nome')"; //print_r($sql2);
                                    $dba->query($sql2);
                                    $ide = $dba->lastid();

                                    $sq3 = "INSERT INTO dedstudio13_codigo_de_barras (id_produto , codigo) VALUES ('$ide','$codigo_barras')"; //print_r($sq3);
                                    $dba->query($sq3);
                                } else {
                                    // Atualiza registro no bd
                                    $sql2 = "UPDATE dedstudio13_produtos SET codigo_sistema='$codigo_produto', nome='$nome' WHERE codigo_sistema = $codigo_produto"; //print_r($sql2);
                                    $dba->query($sql2);
                                }
                            }
                        }
                    }
                } else {
                    echo "read_csv";
                    exit;
                }

                $z->close(); // fecha o arquivo .zip
                fclose($handle); // fecha arquivo csv

            } else {
                echo "file_csv";
                exit;
            }

            unlink("./uploads/tmp/" . $name);
            unlink("./uploads/tmp/produtos_maria.csv");

            echo "success";
            break;




        case 'cadastrar_unidade';

            if (empty($_POST['unidade'])) {
                echo "unidade";
                exit;
            } else {
                $unidade = $_POST['unidade'];
            }

            $sql = "INSERT INTO dedstudio13_unidades (unidade)  VALUES ('$unidade')";
            $res = $dba->query($sql);




            echo "success";

            break;

        case 'unidade_delete':
            $id = $_GET['id'];
            $sql = "DELETE FROM dedstudio13_unidades WHERE id = '$id'";
            $res = $dba->query($sql);



            header('location: unidades');
            break;

        case 'cadastrar_cha':
            if (empty($_POST['noivo'])) {
                echo "anfitrião";
                exit;
            } else {
                $noivo = $_POST['noivo'];
            }

            if (empty($_POST['telefone1'])) {
                echo "telefone anfitrião";
                exit;
            } else {
                $telefone1 = telefone($_POST['telefone1']);
            }

            if (empty($_POST['email1'])) {
                echo "email anfitrião";
                exit;
            } else {
                $email1 = $_POST['email1'];
            }

            $noiva = $_POST['noiva'];

            $telefone2 = telefone($_POST['telefone2']);


            $email2 = $_POST['email2'];

            if (empty($_POST['data_fim'])) {
                echo "data do evento";
                exit;
            } else {
                $data_fim = dataMY($_POST['data_fim']);
            }

            if (empty($_POST['loja'])) {
                echo "unidade";
                exit;
            } else {
                $loja = $_POST['loja'];
            }
            if (empty($_POST['desconto'])) {
                $desconto = 0;
            } else {
                $desconto = $_POST['desconto'];
            }




            $sql = "INSERT INTO dedstudio13_cha (noivo,noiva,loja,email1,email2,telefone1,telefone2,data_fim,desconto)  VALUES ('$noivo','$noiva','$loja','$email1','$email2','$telefone1','$telefone2','$data_fim','$desconto')";
            $res = $dba->query($sql);
            $ide = $dba->lastid();

            if (!empty($_POST['produtos'])) {
                $produtos = $_POST['produtos'];
                $max   = sizeof($produtos);
                for ($i = 0; $i < $max; $i++) {
                    $id = $produtos[$i];

                    $sql = "INSERT INTO dedstudio13_cha_produtos (id_cha , id_produto)  VALUES ( '$ide' , '$id')";
                    $dba->query($sql);
                }
            }




            echo "success";

            break;

        case 'cadastrar_cha_produtos':

            $ide = $_POST['id'];
            $produtos = $_POST['produtos'];
            $max   = sizeof($produtos);
            for ($i = 0; $i < $max; $i++) {
                $id = $produtos[$i];

                $sql = "INSERT INTO dedstudio13_cha_produtos (id_cha , id_produto)  VALUES ( '$ide' , '$id')";
                $dba->query($sql);
            }
            echo "success";
            break;

        case 'comprarProd':
            $idn = $_GET['id'];
            $prod = $_GET['prod'];
            $status = $_GET['status'];


            $sql = "UPDATE dedstudio13_cha_produtos SET status = 1 WHERE id_cha = $idn and id_produto= $prod";
            $res = $dba->query($sql);
            header("location: visualizar-cha?id=" . $idn . "&status=1");
            echo "success";
            break;
        case 'desativar_cuppom':
            $idn = $_GET['id'];
            $bonificacao=$_GET['bonificacao'];
            $sql = "UPDATE dedstudio13_cha SET status = 0 , desconto = $bonificacao WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Desativou administrador ID: '.$id);

            header('Location: ./visualizar-cha?id=' . $idn . '&status=0');
            break;

        case 'ativar_cuppom':
            $idn = $_GET['id'];
            $sql = "UPDATE dedstudio13_cha SET status = 1 WHERE id = $idn";
            $dba->query($sql);

            // grava no log do sistema
            // logs('Ativou administrador ID: '.$id);

            header('Location: ./cha');
            break;



        default:
            header("location: dashboard");
    }
    //fim do switch
} else {
    header("location: ./?msg=000");
}
