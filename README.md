## Instalação
Você pode clonar este repositório OU baixar o .zip

Ao descompactar, é necessário rodar o **composer** pra instalar as dependências e gerar o *autoload*.

Vá até a pasta do projeto, pelo *prompt/terminal* e execute:
> composer install

Depois é só aguardar.

## Configuração
Todos os arquivos de **configuração** e aplicação estão dentro da pasta *src*.

As configurações de Banco de Dados e URL estão no arquivo *src/Config.php* e *./devsbook.sql*

É importante configurar corretamente as constantes referentes à conexão ao banco de dados:
> const BASE_DIR = '/**PastaDoProjeto**/public';
> const DB_DRIVER = '**mysql**';
> const DB_HOST = '/**localhost**';
> const DB_DATABASE = '**devsbook**';
> const DB_USER = '**Usuario_do_Banco**';
> const DB_PASS = '';

## Uso
Já há usuários criados no banco de dados com publicações, é possivel criar um usuário do zero e pesquisar por nomes de usuários existentes.

