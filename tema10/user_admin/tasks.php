<?php
$servername = "localhost";
$port = 7306;
$username = "root";
$password = "";
$dbname = "banco_de_dados";

try {
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}

function displayUsers($conn) {
    $sql = "SELECT * FROM users_app";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo "<table>";
    echo "<tr><th>Celular</th><th>Nome</th><th>CEP</th><th>Número</th><th>Complemento</th><th>Senha</th><th>Ações</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['celular']}</td>";
        echo "<td>{$row['nome']}</td>";
        echo "<td>{$row['cep']}</td>";
        echo "<td>{$row['numero']}</td>";
        echo "<td>{$row['complemento']}</td>";
        echo "<td>{$row['senha']}</td>";
        echo "<td>";
        echo "<button style='margin-right: 5px;' onclick='openModal(\"{$row['celular']}\", \"{$row['nome']}\", \"{$row['cep']}\", \"{$row['numero']}\", \"{$row['complemento']}\", \"{$row['senha']}\")'>Editar</button>";
        echo "<form method='post' action='index.php'>";
        echo "<input type='hidden' name='delete_celular' value='{$row['celular']}'>";
        echo "<button type='submit' name='delete'>Excluir</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function addUser($conn, $celular, $nome, $cep, $numero, $complemento, $senha) {
    $sql = "INSERT INTO users_app (celular, nome, cep, numero, complemento, senha) VALUES (:celular, :nome, :cep, :numero, :complemento, :senha)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':celular', $celular);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':complemento', $complemento);
    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
    $stmt->bindParam(':senha', $hashed_password);
    $stmt->execute();
}

function updateUser($conn, $celular, $nome, $cep, $numero, $complemento, $senha) {
    $sql = "UPDATE users_app SET nome = :nome, cep = :cep, numero = :numero, complemento = :complemento, senha = :senha WHERE celular = :celular";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':celular', $celular);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':complemento', $complemento);
    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
    $stmt->bindParam(':senha', $hashed_password);
    $stmt->execute();
}

function deleteUser($conn, $celular) {
    $sql = "DELETE FROM users_app WHERE celular = :celular";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':celular', $celular);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $celular = $_POST['celular'];
    $nome = $_POST['nome'];
    $cep = $_POST['cep'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $senha = $_POST['senha'];
    addUser($conn, $celular, $nome, $cep, $numero, $complemento, $senha);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $celular = $_POST['celular'];
    $nome = $_POST['nome'];
    $cep = $_POST['cep'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $senha = $_POST['senha'];
    updateUser($conn, $celular, $nome, $cep, $numero, $complemento, $senha);
    header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $celular = $_POST['delete_celular'];
    deleteUser($conn, $celular);
    header("Location: index.php");
}

displayUsers($conn);

$conn = null;
?>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Editar Usuário</h3>
        <form method="post" action="index.php">
            <input type="hidden" id="edit_celular" name="celular">
            <input type="text" id="edit_nome" name="nome" placeholder="Nome" required>
            <input type="text" id="edit_cep" name="cep" placeholder="CEP" required>
            <input type="number" id="edit_numero" name="numero" placeholder="Número" required>
            <input type="text" id="edit_complemento" name="complemento" placeholder="Complemento" required>
            <input type="password" id="edit_senha" name="senha" placeholder="Senha" required>
            <button type="submit" name="update">Atualizar</button>
        </form>
    </div>
</div>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="celular" placeholder="Celular" required>
    <input type="text" name="nome" placeholder="Nome" required>
    <input type="text" name="cep" placeholder="CEP" required>
    <input type="number" name="numero" placeholder="Número" required>
    <input type="text" name="complemento" placeholder="Complemento" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit" name="add">Adicionar</button>
</form>

<script>
    function openModal(celular, nome, cep, numero, complemento, senha) {
        document.getElementById('edit_celular').value = celular;
        document.getElementById('edit_nome').value = nome;
        document.getElementById('edit_cep').value = cep;
        document.getElementById('edit_numero').value = numero;
        document.getElementById('edit_complemento').value = complemento;
        document.getElementById('edit_senha').value = senha;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
