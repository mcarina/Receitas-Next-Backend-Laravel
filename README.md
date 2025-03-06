<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Sobre o projeto
Este projeto é uma API desenvolvida com Laravel 11, focada em um CRUD (Create, Read, Update, Delete). Ele tem como objetivo demonstrar minhas habilidades com o Laravel e o desenvolvimento de APIs RESTful.

Atualmente, o projeto possui funcionalidades, como autenticação.

### Funcionalidades:
- Criação de usuários (Create);
- Criação de novas receitas (Create);
- Listar as receitas (Read);
- Atualizar as receitas (Update);
- Exclusão de receitas(Delete).

### Tecnologias Utilizadas:
- PHP 8.2;
- Laravel 11;
- PostgreSQL;
- Docker;
- Documentação de endpoints com Swagger.


## 📌 Estrutura do Banco de Dados

Este documento descreve a estrutura do banco de dados para um sistema de receitas culinárias, incluindo entidades, atributos e relacionamentos.

![image](https://github.com/user-attachments/assets/d0f79446-1c9f-485f-bf7a-5152f65fb0cd)


## Instruções de Instalação:
- Com docker.
1. ```
   sudo docker-compose up --build -d
   ```

- Dentro do terminal do Docker, terminal do projeto.
2. ```
   composer update ou composer install
   ```
3. ```
   php artisan migrate
   ```
4. ```
    php artisan db:seed
   ```
5. Acessar a rota de documentação da api:
   ```
    http://host:port/api/documentation
   ```
6. Acessar as rotas;

7. Finalizado, caso ocorra tudo bem, já pode acessar as rotas sem problemas.

> [!NOTE]
> Não esqueça de configurar seu arquivo .env e criar seu app_key

## 🚀 Considerações Finais

Este banco de dados foi modelado para suportar um sistema de receitas robusto, permitindo que os usuários criem, salvem e compartilhem receitas, além de adicionar imagens e organizar por categorias.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
