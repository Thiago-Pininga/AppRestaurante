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

## 🚀 Instalação e Configuração do XAMPP para o Projeto

### 📋 Pré-requisitos:
- Ter o XAMPP instalado no seu computador. [Baixar aqui](https://www.apachefriends.org/pt_br/index.html)

### 📝 Instalando e Configurando o XAMPP:
1. **📥 Instalação do XAMPP:**
   - Faça o download e instale o XAMPP seguindo as instruções do site oficial.

2. **🟢 Iniciando o XAMPP:**
   - Abra o Painel de Controle do XAMPP e inicie o servidor Apache e o MySQL.

3. **📂 Clonando o Projeto:**
   - Faça o download do código fonte do projeto pelo GitHub: [AppRestaurante](https://github.com/Thiago-Pininga/AppRestaurante)
   - Extraia o conteúdo baixado e copie a pasta do projeto para o diretório `htdocs` do XAMPP. Geralmente localizado em:  
     - `C:\xampp\htdocs\` (Windows)  
     - `/opt/lampp/htdocs/` (Linux)

4. **📊 Criando o Banco de Dados:**
   - Acesse o phpMyAdmin abrindo o navegador e indo até: `http://localhost/phpmyadmin`
   - Crie um novo banco de dados chamado `restaurante` (ou outro nome da sua escolha).
   - Importe o arquivo `restaurante.sql` incluído na pasta do projeto para criar as tabelas necessárias.


5. **🌐 Acessando o Projeto:**
   - No navegador, digite: `http://localhost/AppRestaurante` (ou o nome que você deu à pasta do projeto).

🎉 Pronto! Agora o projeto está configurado e rodando localmente usando o XAMPP. Para acessar as funcionalidades, basta navegar pelas páginas do sistema.
