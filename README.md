<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Sobre a aplicação Laravel

Está é uma aplicação que faz consulta em uma API de cotações de moedas, não utoiliza banco de dados, apenas cache.
Esta aplicação trabalha com AJAX, garantindo a atualização da página sem ter que recarregá-la totalmente!
 
- [Google Chart](https://developers.google.com/chart/interactive/docs) - É utilizado para renderização de gráficos da aplicação.
- [Testes Unitários]() - Esta aplicação possui testes Unitários simples para garantir a efetividade da mesma.
- [Cache]() - Como cache padrão está sendo utilizado o file podendo adaptar a utilização para o uso do Redis.
- [Docker / Docker-compose]() - Esta aplicação possui arquivos para subir o projeto com docker de forma mais facilitada. 

## Como subir o projeto
#### Configurando o ambiente
- É necessário que ja se tenha o docker e o git instalados em sua máquina;
- Deve-se clonar este projeto e acessar a pasta raiz.
- Dentro da pasta raiz existem dois arquivos Dockerfile, que contém o código para a criação do container e o docker-compose.yml que
possui as especificações do projeto. Por padrão a porta do container para acessar a aplicação é 8099.
- Para configurar o ambiente do projeto deve-se buildar o docker-compose para que a imagem do container seja criada
[docker-compose build]() em seguida, após finalizado o build iniciar o container e a aplicacação [docker-compose up -d]().
  
#### Configurando a aplicação
- Renomear o arquivo env-example para .env
- Instalar as bibliotecas [composer install]()
- Gerar uma key para o arquivo .en [php artisan key:generate]()
- Acessar o a url local [http://localhost:8099](http://localhost:8099) 


## Para finalizar

!!!! NESSE MOMENTO SUA APLICAÇÃO DEVERÁ ESTAR FUNCIONANDO !!!!

Obrigado pelo seu tempo !!!  