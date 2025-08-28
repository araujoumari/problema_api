# API DE USUÁRIOS

Projeto feito para realizar um cadastro de algum usuário, essa API permite cadastrar, listar, deletar e buscar usuários.

## Headers

```json
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, PUT");
    header("Access-Control-Allow-Headers: Content-Type");
```

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

- GET (Usuários)

```json
{
  "status": "sucesso",
  "usuarios": [
    {
      "id": "0a29-22-77-11-ab4365",
      "nome": "João victor",
      "email": "joa.treeex@ample.com",
      "telefone": "1234567891",
      "endereco": "Rua A, 123",
      "estado": "SP",
      "data_nascimento": "1990-01-01",
      "criado_em": "2025-08-28 19:39:24"
    },
    {
      "id": "9bd3-1a-0e-8f-6a469e",
      "nome": "João victor",
      "email": "joa.treerex@ample.com",
      "telefone": "1234567891",
      "endereco": "Rua A, 123",
      "estado": "SP",
      "data_nascimento": "1990-01-01",
      "criado_em": "2025-08-26 19:53:25"
    }
  ]
}

```

- GET (Id)

```json
{
  "status": "sucesso",
  "usuario": {
    "id": "9bd3-1a-0e-8f-6a469e",
    "nome": "João victor",
    "email": "joa.treerex@ample.com",
    "telefone": "1234567891",
    "endereco": "Rua A, 123",
    "estado": "SP",
    "data_nascimento": "1990-01-01",
    "criado_em": "2025-08-26 19:53:25"
  }
}
```

- DELETE
```json
{
  "status": "sucesso",
  "mensagem": "Usuário excluído com sucesso."
}5
```

### Erros

- POST
```json
{
  "status": "erro",
  "erros": [
    "A senha deve ter pelo menos 8 caracteres.",
    "A senha deve conter pelo menos uma letra maiúscula.",
    "A senha deve conter pelo menos uma letra minúscula.",
    "A senha deve conter pelo menos um caractere especial (ex: !@#$%^&*)."
  ]
}
```
- GET (Id) e DELETE

```json
{
  "status": "erro",
  "erros": [
    "Usuário não encontrado"
  ]
}
```


## Observações

- Todas as senhas são armazenadas com hash seguro.
- O campo criado_em é gerado automaticamente pelo banco.