<?php

    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "todolist";

    $conn = new mysqli($host, $usuario, $senha, $banco);
    
    if ($conn->connect_error) {
        die("Falha na conexao com o banco de dados:  " . $conn->connect_error);
    }

    //Inserir tarefa
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['descricao'])){
        
        $descricao = $conn->real_escape_string($_POST['descricao']);

        $sqlInsert = "INSERT INTO tarefas (descricao) VALUES ('$descricao')";

        if($conn->query($sqlInsert) === TRUE){
            header("Location: todo_crud.php");
            exit();
        }
    }

    if(isset($_GET['delete'])){
        $id = intval($_GET['delete']);
        
        $sqlDelete = "DELETE FROM tarefas WHERE id = $id";

        if($conn->query($sqlDelete) === TRUE){
            header("Location: todo_crud.php");
            exit();
        }
    }

    $tarefas= [];

    //Pegando as tarefas
    $sqlSelect = "SELECT * FROM tarefas ORDER BY data_criacao DESC";
    $result = $conn->query($sqlSelect); 

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $tarefas[] = $row;
        }
    }

?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <h1>Todo List</h1>
        <form action="todo_crud.php" method="POST">
            <input type="text" placeholder="Descrição da tarefa" name="descricao" required>
            <button type="submit">Adicionar</button>
        </form>

        <h2>Suas tarefas</h2>
        <?php if(!empty($tarefas)): ?>
            <ul>
                <?php foreach($tarefas as $tarefa): ?>
                    <li>
                        <?php echo $tarefa['descricao']; ?>
                        <a href="todo_crud.php?delete=<?php echo $tarefa['id'] ?>">Excluir</a>
                    </li>
                <?php endforeach; ?>    
            </ul>
        <?php else: ?>
            <p>Você não tem tarefas cadastradas.</p>
        <?php endif; ?>

    </body>
    </html>