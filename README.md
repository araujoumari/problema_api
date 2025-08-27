# API DE USUÁRIOS

Projeto feito para realizar um cadastro de algum usuário, essa API permite cadastrar, listar, deletar e buscar usuários.

## URL

http://localhost/resolvendo_problema/problema_api/problema_api/problema.php

## Uso/Exemplos
### Endpoints
- `POST /usuarios` → Cadastra um usuário
- `GET /usuarios` → Lista todos os usuários
- `GET /usuarios?id=UUID` → Busca usuário por ID
- `DELETE /usuarios` → Exclui usuário

## Parâmetros

- POST

```json

{
    "nome": "João victor",
    "email": "joa.treerex@ample.com",
    "senha": "Senha_123",
    "telefone": "1234567891",
    "endereco": "Rua A, 123",
    "estado": "SP",
    "data_nascimento": "1990-01-01"
}

```

- GET (Usuários e ID)

```json
if ($method === "GET") {
            $id = $_GET['id'] ?? null;

            if ($id) {
                // Buscar apenas o usuário com o ID informado
                $sql = "SELECT id, nome, email, telefone, endereco, estado, data_nascimento, criado_em
                        FROM api_usuarios WHERE id = '$id' LIMIT 1";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    $usuario = $result->fetch_assoc();
                    echo json_encode([
                        "status" => "sucesso",
                        "usuario" => $usuario
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(404);
                    echo json_encode([
                        "status" => "erro",
                        "erros" => ["Usuário não encontrado"]
                    ], JSON_UNESCAPED_UNICODE);
                }
            } else {
                $sql = "SELECT id, nome, email, telefone, endereco, estado, data_nascimento, criado_em
                        FROM api_usuarios ORDER BY criado_em DESC";
                $result = $conn->query($sql);

                $usuarios = [];
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $usuarios[] = $row;
                    }
                }

                echo json_encode([
                    "status" => "sucesso",
                    "usuarios" => $usuarios
                ], JSON_UNESCAPED_UNICODE);
            }

            exit;
        }

```

- DELETE
```json
if ($method === "DELETE") {
        parse_str(file_get_contents("php://input"), $input);
        $id = $input['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                "status" => "erro",
                "erros" => ["É necessário informar o ID do usuário."]
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $verifica = $conn->query("SELECT id FROM api_usuarios WHERE id='$id'");
        if ($verifica->num_rows === 0) {
            http_response_code(404);
            echo json_encode([
                "status" => "erro",
                "erros" => ["Usuário não encontrado."]
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($conn->query("DELETE FROM api_usuarios WHERE id='$id'")) {
            echo json_encode([
                "status" => "sucesso",
                "mensagem" => "Usuário excluído com sucesso."
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "erro",
                "erros" => ["Erro ao excluir usuário."]
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
        
        }
```
## Observações

- Todas as senhas são armazenadas com hash seguro.
- O campo criado_em é gerado automaticamente pelo banco.