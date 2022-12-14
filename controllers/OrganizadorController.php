<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . "./../repository/OrganizadorRepository.php";

    $organizador = new ControllerOrganizador();

    class ControllerOrganizador{

        function __construct(){

            if(isset($_POST["action"])){

                $action = $_POST["action"];

            }else if(isset($_GET["action"])){

                $action = $_GET["action"];

            }

            if(isset($action)){

                $this->callAction($action);

            }else{

                $msg = "Nenhuma acao a ser processada...";

                print_r($msg);

            }

        }

        public function callAction(string $functionName = null){

            if (method_exists($this, $functionName)) {

                $this->$functionName();

            } else if(method_exists($this, "preventDefault")) {

                $met = "preventDefault";

                $this->$met();

            } else {

                throw new BadFunctionCallException("Usecase not exists");

            }
        }

        public function loadView(string $path, array $data = null, string $msg = null){

            $caminho = __DIR__ . "/../views/" . $path;


            if(file_exists($caminho)){

                 require $caminho;

            } else {

                print "Erro ao carregar a view";

            }
        }


        private function create(){

        $organizador = new OrganizadorModel(); //usar como referencia

		$organizador->setNome($_POST["nome"]);

		$organizador->setEmail($_POST["email"]);

        $organizador->setSenha($_POST["senha"]); //$organizador->setSenha($_POST["senha"]);

        $organizadorRepository = new OrganizadorRepository();

        $id = $organizadorRepository->create($organizador);

        if($id){

			$msg = "Registro inserido com sucesso.";

		}else{

			$msg = "Erro ao inserir o registro no banco de dados.";

		}

        $this->findAll($msg);

       }

        private function loadForm(){ //loadFormNew

            $this->loadView("organizadores/CadastroOrganizador.php", null, "teste");

        }

        private function findAll(string $msg = null){

            $organizadorRepository = new OrganizadorRepository();

            $organizadores = $organizadorRepository->findAll();

            $data['titulo'] = "listar organizadores"; // ver sobre isso

            $data['organizadores'] = $organizadores;

            $this->loadView("organizadores/list.php", $data, $msg);

        }

        private function findOrganizadorById(){

            $idParam = $_GET['id'];

            $organizadorRepository = new OrganizadorRepository();

            $organizador = $organizadorRepository->findOrganizadorById($idParam);

            print "<pre>";

            print_r($organizador);

            print "</pre>";

        }

        private function deleteOrganizadorById(){

        $idParam = $_GET['id'];

        $organizadorRepository = new OrganizadorRepository();

        $qt = $organizadorRepository->deleteById($idParam);

        if($qt){

			$msg = "Registro exclu??do com sucesso.";

		}else{

			$msg = "Erro ao excluir o registro no banco de dados.";

		}

        $this->findAll($msg);

        }

        private function edit(){

            $idParam = $_GET['id'];

            $organizadorRepository = new OrganizadorRepository();

            $organizador = $organizadorRepository->findOrganizadorById($idParam);
            
            $data['organizadores'][0] = $organizador;

            $this->loadView("organizadores/EditarOrganizador.php", $data);

        }

        private function update(){

            $organizador = new OrganizadorModel();

            $organizador->setId($_GET["id"]);

            $organizador->setNome($_POST["nome"]);

            $organizador->setEmail($_POST["email"]);

            $organizador->setSenha($_POST["senha"]); // $organizador->setSenha($_POST["senha"]);

            $organizadorRepository = new OrganizadorRepository();
           
            $atualizou = $organizadorRepository->update($organizador);

            if($atualizou){

                $msg = "Registro atualizado com sucesso.";

            }else{

                $msg = "Erro ao atualizar o registro no banco de dados.";

            }

            $this->findAll($msg);

        }

        private function preventDefault() {

            print ("A????o indefinida...");

        }

        private function login(){

            $organizador = new OrganizadorRepository();

            if(isset($_POST['login'])){
                
                $organizador->loginOfOrg($_POST['']);
            }
            
            /*$organizador->setNome($_POST["nome"]);

            $organizador->setSenha($_POST["senha"]); 

            $organizadorRepository = new OrganizadorRepository();

            $e = $organizadorRepository->login($organizador);


            /*if($e){

                print ("bem vindo ao bkmeventos!");

            } else {

                print ("Erro ao fazer o login");

            }*/
         
        $this->loadView("login/login.php" /*,$data*/);


        }


}

?>