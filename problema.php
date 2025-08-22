<?php
    include __DIR__ . "/db.php";

    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, PUT");
    header("Access-Control-Allow-Headers: Content-Type");

    // Função que valida senha
    function validarSenha($senha) {
        $erros = [];

        if (strlen($senha) < 8) $erros[] = "A senha deve ter pelo menos 8 caracteres.";
        if (!preg_match("/[A-Z]/", $senha)) $erros[] = "A senha deve conter pelo menos uma letra maiúscula.";
        if (!preg_match("/[a-z]/", $senha)) $erros[] = "A senha deve conter pelo menos uma letra minúscula.";
        if (!preg_match("/[0-9]/", $senha)) $erros[] = "A senha deve conter pelo menos um número.";
        if (!preg_match("/[\W_]/", $senha)) $erros[] = "A senha deve conter pelo menos um caractere especial (ex: !@#$%^&*).";

        return $erros;
    }

    /* Função gerar UUID */
    function gerarUuid() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s%s%s-%s%s-%s%s-%s%s-%s%s%s%s%s%s', str_split(bin2hex($data),4));
    }

    $method = $_SERVER["REQUEST_METHOD"];
    if ($method !== "POST") {
        http_response_code(405);
        echo json_encode(["status" => "erro", "erros" => ["Método não permitido"]], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $erros = [];

    // Campos obrigatórios
    $campos = ["nome","email","senha","telefone","endereco","estado","data_nascimento"];
    foreach ($campos as $campo) {
        if (empty($data[$campo])) $erros[] = "O campo '$campo' é obrigatório.";
    }

    /* Validação email */
    if (!empty($data["email"]) && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) $erros[] = "Email inválido.";

    /* Validação senha */
    if (!empty($data["senha"])) $erros = array_merge($erros, validarSenha($data["senha"]));

    /* Validação telefone */
    if (!empty($data["telefone"]) && !preg_match('/^(?:\d{10}|\d{11})$/', $data["telefone"])) $erros[] = "Telefone inválido. Deve ter 10 ou 11 dígitos.";
    if (!empty($data["endereco"]) && !preg_match("/^[A-Za-zÀ-ÿ0-9\s,.-]{5,}$/", $data["endereco"])) $erros[] = "Endereço inválido. Deve ter pelo menos 5 caracteres.";

    /* Validação estado */
    if (!empty($data["estado"]) && !preg_match("/^[A-Z]{2}$/", strtoupper($data["estado"]))) $erros[] = "Estado inválido. Deve ter 2 letras maiúsculas (ex: SP, RJ).";

    /* Validação data de nascimento */
    if (!empty($data["data_nascimento"]) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data["data_nascimento"])) $erros[] = "Data de nascimento inválida. Formato: YYYY-MM-DD.";

    if (!empty($erros)) {
        http_response_code(400);
        echo json_encode(["status" => "erro", "erros" => $erros], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Preparar dados para o banco
    $nome = $data["nome"];
    $email = $data["email"];
    $senha = password_hash($data["senha"], PASSWORD_DEFAULT);
    $telefone = $data["telefone"];
    $endereco = $data["endereco"];
    $estado = strtoupper($data["estado"]);
    $data_nascimento = $data["data_nascimento"];

    // Verifica duplicidade de email
    $verifica = $conn->query("SELECT uuid FROM api_usuarios WHERE email='$email'");
    if ($verifica->num_rows > 0) {
        http_response_code(400);
        echo json_encode(["status" => "erro", "erros" => ["Email já cadastrado"]], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Inserir no banco
    $uuid = gerarUuid();
    $sql = "INSERT INTO api_usuarios(uuid,nome,email,senha,telefone,endereco,estado,data_nascimento)
            VALUES ('$uuid', '$nome','$email','$senha','$telefone','$endereco','$estado','$data_nascimento')";

    if ($conn->query($sql)) {
        $id = $conn->insert_id;
        $result = $conn->query("SELECT uuid,id,nome,email,telefone,endereco,estado,data_nascimento,criado_em
                                FROM api_usuarios WHERE uuid='$uuid'");
        $cliente = $result->fetch_assoc();

        echo json_encode([
            "status" => "sucesso",
            "mensagem" => "Cliente cadastrado com sucesso",
            "cliente" => $cliente
        ], JSON_UNESCAPED_UNICODE);

    } else {
        http_response_code(500);
        echo json_encode(["status" => "erro", "erros"=> ["Erro ao inserir no banco"]], JSON_UNESCAPED_UNICODE);
    }
?>
