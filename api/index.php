<?php 

$type = $_GET['tp']; 
if($type=='login') login();
elseif($type=='criarcliente') criarcliente(); 
elseif($type=='registrarendereco') registrarendereco(); 
elseif($type=='listarclientes') listarclientes(); 
elseif($type=='excluircliente') excluircliente(); 
elseif($type=='pesquisar') pesquisar(); 


function login() 
{ 
       require 'config.php'; 
       $json = json_decode(file_get_contents('php://input'), true); 
       $login_usuario = $json['login_usuario'];
       $senha = $json['senha']; 
       $userData =''; $query = "select * from usuario where login_usuario='$login_usuario' and senha='$senha'"; 
       $result= $db->query($query);
       $rowCount=$result->num_rows;
             
        if($rowCount>0)
        {
            $userData = $result->fetch_object();
            $id_usuario=$userData->id_usuario;
            $userData = json_encode($userData);
            echo '{"userData":'.$userData.'}';
  
        }
        else 
        {
            echo '{"error":"Usuario e senha errados"}';
        }

}

function pesquisar() 
{ 
       require 'config.php'; 
       $json = json_decode(file_get_contents('php://input'), true); 
       $cpf = $json['cpf']; 
       $userData =''; $query = "select * from cliente where cpf='$cpf'"; 
       $result= $db->query($query);
       $rowCount=$result->num_rows;
             
        if($rowCount>0)
        {
            $userData = $result->fetch_object();
            $id_cliente=$userData->id_cliente;
            $userData = json_encode($userData);
            echo '{"userData":'.$userData.'}';    
        }
        else 
        {
            echo '{"error":"Cliente não existe"}';
        }

}


function criarcliente() {
    
    require 'config.php';

    $json = json_decode(file_get_contents('php://input'), true);
    $nome_cliente = $json['nome_cliente'];
    $data_nascimento = $json['data_nascimento'];
    $cpf = $json['cpf'];
    $rg = $json['rg'];
    $telefone = $json['telefone'];
    
    if (strlen(trim($nome_cliente))>0 && strlen(trim($cpf))>0 && strlen(trim($rg))>0 )
    {
       
        $userData = '';
        
        $result = $db->query("select * from cliente where nome_cliente='$nome_cliente' or cpf='$cpf'");
        $rowCount=$result->num_rows;
        //echo '{"text": "'.$rowCount.'"}';
       
        if($rowCount==0)
        {
                            
            $db->query("INSERT INTO cliente(nome_cliente,data_nascimento,cpf,rg,telefone)
                        VALUES('$nome_cliente','$data_nascimento','$cpf','$rg','$telefone')");

            $userData ='';
            $query = "(select * from cliente where rg='$rg' or cpf='$cpf')";
            $result= $db->query($query);
            $userData = $result->fetch_object();
            $id_cliente=$userData->id_cliente;
            $userData = json_encode($userData);
            echo '{"userData":'.$userData.'}';
        } 
        else {
           echo '{"error":"cpf e / ou rg já está(ão) cadastrado(s)"}';
        }

    }
    else{
        echo '{"text":"Enter valid data2"}';
    }

}


function registrarendereco() {
    
    require 'config.php';

    $json = json_decode(file_get_contents('php://input'), true);
    $id_cliente = $json['id_cliente'];
    $pais = $json['pais'];
    $estado = $json['estado'];
    $cidade = $json['cidade'];
    $rua = $json['rua'];
    $complemento = $json['complemento'];
    $numero = $json['numero'];
    
    if (strlen(trim($pais))>0 && strlen(trim($cidade))>0 && strlen(trim($rua))>0 )
    {
       
        $userData = '';
        
        $result = $db->query("select * from endereco where rua='$rua'");
        $rowCount=$result->num_rows;
        //echo '{"text": "'.$rowCount.'"}';
       
        if($rowCount==0)
        {
                            
            $db->query("INSERT INTO endereco(id_cliente, pais , estado , cidade ,rua ,complemento, numero)
                        VALUES('$id_cliente','$pais','$estado','$cidade','$rua', '$complemento', '$numero')");

            $userData ='';
            $query = "(select * from endereco where rua='$rua')";
            $result= $db->query($query);
            $userData = $result->fetch_object();
            $id_endereco=$userData->id_endereco;
            $userData = json_encode($userData);
            echo '{"userData":'.$userData.'}';
        } 
        else {
           echo '{"error":"cpf e / ou rg já está(ão) cadastrado(s)"}';
        }

    }
    else{
        echo '{"text":"Enter valid data2"}';
    }

}



function listarclientes(){
    
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_cliente=$json['id_cliente'];
    
    $query = "SELECT * FROM cliente ORDER BY id_cliente DESC LIMIT 10";
    $result = $db->query($query); 

    $userData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $userData=json_encode($userData);
    
    echo '{"userData":'.$userData.'}';
}

function excluircliente(){
    require 'config.php';
    $json = json_decode(file_get_contents('php://input'), true);
    $id_cliente=$json['id_cliente'];
         
    $query = "Delete FROM cliente WHERE id_cliente=$id_cliente";
    $result = $db->query($query);
    if($result)       
    {        
        echo '{"success":"Cliente excluido"}';
    } else{
     
        echo '{"error":"Erro ao excluir cliente"}';
    }
       
}

?>
