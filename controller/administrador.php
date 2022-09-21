<?php
header('Content-type: application/json');
$dadosRecebidos = file_get_contents('php://input');

$dadosRecebidos = json_decode($dadosRecebidos, true);

$result = [
    'result' => false,
    'data' => '',
    'error' =>''     
];

require_once '../model/Administrador.php';
if($dadosRecebidos['acao'] == 'cadastrar'){
    $adm = new Administrador();
    // $adm->matricula = $dadosRecebidos['matricula'];
    $adm->email = $dadosRecebidos['email'];
    $adm->senha = $dadosRecebidos['senha'];
    $adm->nome = $dadosRecebidos['nome']; 
    
    if($adm->cadastrar()){
        $result['result'] = true;
        $result['data'] = $adm;
    }
    else{        
        if($adm->consultarPorEmail($dadosRecebidos['email'])){
            $result['error'] = 'E-mail já cadastrado';
        }
        else{
            $result['error'] = 'Erro de cadastro inesperado';
        }
    }
}
if($dadosRecebidos['acao'] == 'alterar'){
    $adm = new Administrador();
    $adm->matricula = $dadosRecebidos['matricula'];
    $adm->email = $dadosRecebidos['email'];
    $adm->senha = $dadosRecebidos['senha'];
    $adm->nome = $dadosRecebidos['nome'];    

    if($adm->alterar()){
        $result['result'] = true;
        $result['data'] = $adm;
    }
    else
    {
        if(!$adm->consultarPorMatricula($dadosRecebidos['matricula'])){
            $result['error'] = 'Matrícula não encontrada';
        }
        else{
            $result['error'] = 'Erro de alteração inesperado';
        }
    }
}
if($dadosRecebidos['acao'] == 'alterarSenha'){
    $adm = new Administrador();
    $adm->matricula = $dadosRecebidos['matricula'];
    $adm->senha = $dadosRecebidos['senha'];           
    if($adm->alterarSenha()){
        $adm->consultarPorMatricula($dadosRecebidos['matricula']);
        $result['result'] = true;
        $result['data'] = $adm;
    }
    else
    {
        if(!$adm->consultarPorMatricula($dadosRecebidos['matricula'])){
            $result['error'] = 'Matrícula não encontrada';
        }
        else{
            $result['error'] = 'Erro inesperado';
        }
    }
}
if($dadosRecebidos['acao'] == 'delete'){
    $adm = new Administrador();
    if($adm->delete($dadosRecebidos['matricula'])){
        $result['result'] = true;        
    }
    else
    {
        if(!$adm->consultarPorMatricula($dadosRecebidos['matricula'])){
            $result['error'] = 'Matrícula não encontrada';
        }
        else{
            $result['error'] = 'Erro inesperado';
        }
    }               
    
}
elseif($dadosRecebidos['acao'] == 'consutarTodos'){
    $adm = new Administrador();
    $filtro = $dadosRecebidos['filtro'];
    $dados = $adm->consultarTodos($filtro);                
    if($dados){
        $result['result'] = true;
        $result['data'] = $dados;
    }            
}
elseif($dadosRecebidos['acao'] == 'consutarPorEmail'){
    $adm = new Administrador();
    $email = $dadosRecebidos['email'];        
    $result['result'] = false;                  
    $result['data'] = "";                  
    if($adm->consultarPorEmail($email)){
        $result['result'] = true;
        $result['data'] = $adm;
    }        
}
elseif($dadosRecebidos['acao'] == 'consutarPorMatricula'){
    $adm = new Administrador();
    $matric = $dadosRecebidos['matricula'];
    $result['result'] = false;                  
    $result['data'] = "";                  
    if($adm->consultarPorMatricula($matric)){
        $result['result'] = true;
        $result['data'] = $adm;
    }        
}
elseif($dadosRecebidos['acao'] == 'logar'){
    $adm = new Administrador();
    $usuario = $dadosRecebidos['usuario'];
    $senha = $dadosRecebidos['senha'];
    $result['result'] = false;                  
    $result['data'] = "";                  
    if($adm->login($usuario,$senha)){
        $result['result'] = true;
        $result['data'] = $adm;
    }        
}

echo json_encode($result);