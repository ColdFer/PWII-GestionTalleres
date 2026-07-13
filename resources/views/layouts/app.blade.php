<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Talleres Automotrices</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f4f4;
            margin:0;
        }

        header{
            background:#0d6efd;
            color:white;
            padding:20px;
            text-align:center;
        }

        nav{
            background:#222;
            padding:10px;
            text-align:center;
        }

        nav a{
            color:white;
            text-decoration:none;
            margin:15px;
        }

        main{
            width:80%;
            margin:auto;
            background:white;
            margin-top:20px;
            padding:30px;
            border-radius:10px;
        }

        footer{
            background:#222;
            color:white;
            text-align:center;
            padding:20px;
            margin-top:20px;
        }

    </style>

</head>

<body>

<header>

<h1>Sistema de Gestión para Talleres Automotrices</h1>

</header>

<nav>

<a href="/">Inicio</a>

<a href="/fernando">Fernando</a>

</nav>

<main>

@yield('contenido')

</main>

<footer>

Programación Web II - Laravel 12

</footer>

</body>

</html>