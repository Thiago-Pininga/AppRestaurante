## 🍽️ Funcionalidades Básicas do Programa

### ✅ Cadastro de Produtos:
- Permitir que o usuário adicione produtos com os seguintes campos:
  - 📌 Nome
  - 📂 Tipo (Comida ou Bebida)
  - 📦 Quantidade Inicial
  - 📉 Estoque Mínimo
  - 💲 Valor (Preço de Venda)
  - 🖼️ Imagem (Upload de Arquivo ou Link de Imagem)

### 💸 Registro de Vendas:
- Permitir que o usuário aumente ou diminua a quantidade vendida de um produto.
- 📊 Registrar automaticamente a quantidade vendida a cada 3 segundos.

### 📊 Controle de Estoque:
- Diferenciar produtos entre Comidas e Bebidas.
- Mostrar a quantidade inicial e a quantidade vendida de cada produto.
- 🚨 Exibir um alerta ou indicador quando a quantidade de um produto cair abaixo do estoque mínimo.

### 💾 Persistência dos Dados:
- Salvamento dos produtos e das vendas no banco de dados.
- 🔄 Atualização automática dos dados via AJAX sem recarregar a página.

### 🖼️ Imagens dos Produtos:
- Upload de imagens personalizadas para cada produto ou utilização de links externos.
- Exibição de imagem padrão quando nenhuma imagem é fornecida.

### 💻 Interface Simples e Responsiva:
- Exibição organizada de produtos com botões para incremento e decremento da quantidade vendida.
- Visualização separada para Comidas e Bebidas.

### 🔒 Segurança e Validação:
- Validação dos dados enviados pelo usuário antes de salvá-los no banco de dados.
- Tratamento adequado de erros e exibição de mensagens de sucesso ou falha.

---

## ⚙️ Rodando o Projeto com XAMPP

### 📋 Pré-requisitos:
- Ter o XAMPP instalado. [Download aqui](https://www.apachefriends.org/pt_br/index.html)

### 🚀 Passos:
1. Instale o XAMPP.
2. Inicie Apache e MySQL no XAMPP.
3. Copie o projeto para a pasta `htdocs`.
4. Acesse o phpMyAdmin (`http://localhost/phpmyadmin`), crie um banco chamado `restaurante` e importe o arquivo `restaurante.sql` que está dentro da pasta `docker-entrypoint-initdb.d`.
5. No navegador, acesse:  
   `http://localhost/nome-da-pasta-do-projeto`

---

## 🐳 Rodando o Projeto com Docker

### 📋 Pré-requisitos:
- Docker e Docker Compose instalados. [Download aqui](https://www.docker.com/products/docker-desktop)

### 🚀 Passos:
1. Clone ou baixe este repositório.
2. No terminal, acesse a pasta do projeto e execute:
   ```
   docker-compose down -v  # apaga os volumes antigos
   docker-compose up --build
   ```
3. Acesse o sistema no navegador:

   ```http://localhost:8080```
