<?php
require_once 'Conexao.php';
class Administrador{    
    public $matricula;	
    public $email;	
    public $senha;	
    public $nome;

    public function cadastrar(){
        $cx = new Conexao();
        $cmdSql = 'INSERT INTO administrador(email, senha, nome) VALUES (:email, :senha, :nome)';
        $dados = [
            ':email' => $this->email, 
            ':senha' => $this->senha, 
            ':nome' => $this->nome
        ];
        
        if($cx->insert($cmdSql,$dados)){
            $this->matricula = $cx->getLastInsertId();
            return true;
        }
        else{
            return false;
        }
    }

    

    public function consultarTodos($filtro=''){
        $cx = new Conexao();
        $cmdSql = "SELECT * FROM administrador WHERE administrador.nome LIKE concat('%',:filtro,'%') OR administrador.matricula LIKE concat('%',:filtro,'%') OR administrador.email LIKE concat('%',:filtro,'%');";
        $dados = [
            ':filtro' => $filtro            
        ];
        $result = $cx->select($cmdSql,$dados);
        if($result){
            return $result->fetchAll(PDO::FETCH_CLASS, 'Administrador');
        }
        return false;        
    }

    
}