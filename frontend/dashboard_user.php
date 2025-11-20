<?php
session_start();
require "../backend/includes/conexao.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['nome']) || !isset($_SESSION['email']) || !isset($_SESSION['tipo_user']) ){

    session_unset();
    session_destroy();

    header('Location: ../frontend/index.html');
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Página Restrita</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />

    <style>
      .navbar {
        background-color: #b87792 !important;
      }

      .offcanvas-body,
      .offcanvas-header {
        background-color: rosybrown;
      }
      a{
        text-decoration: none;
        color:black
      }

      .offcanvas-body {
        ul,
        li {
          background-color: inherit;
        }



        }
      
      button {
        position: relative;
        display: inline-block;
        cursor: pointer;
        outline: none;
        border: 0;
        vertical-align: middle;
        text-decoration: none;
        font-family: inherit;
        font-size: 75%;
        color: black
      }

      button.learn-more {
        width: 130px;
        height: auto;
        font-weight: 600;
        color: #cf5a07ff;
        text-transform: uppercase;
        padding: 1.25em 2em;
        background: #fff0f0;
        border: 2px solid #b18597;
        border-radius: 0.75em;
        -webkit-transform-style: preserve-3d;
        transform-style: preserve-3d;
        -webkit-transition: background 150ms cubic-bezier(0, 0, 0.58, 1),
          -webkit-transform 150ms cubic-bezier(0, 0, 0.58, 1);
        transition: transform 150ms cubic-bezier(0, 0, 0.58, 1),
          background 150ms cubic-bezier(0, 0, 0.58, 1),
          -webkit-transform 150ms cubic-bezier(0, 0, 0.58, 1);
      }

      button.learn-more::before {
        position: absolute;
        content: "";
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #f9c4d2;
        border-radius: inherit;
        -webkit-box-shadow: 0 0 0 2px #b18597, 0 0.625em 0 0 #ffe3e2;
        box-shadow: 0 0 0 2px #b18597, 0 0.625em 0 0 #ffe3e2;
        -webkit-transform: translate3d(0, 0.75em, -1em);
        transform: translate3d(0, 0.75em, -1em);
        transition: transform 150ms cubic-bezier(0, 0, 0.58, 1),
          box-shadow 150ms cubic-bezier(0, 0, 0.58, 1),
          -webkit-transform 150ms cubic-bezier(0, 0, 0.58, 1),
          -webkit-box-shadow 150ms cubic-bezier(0, 0, 0.58, 1);
      }

      button.learn-more:hover {
        background: #ffe9e9;
        -webkit-transform: translate(0, 0.25em);
        transform: translate(0, 0.25em);
      }

      button.learn-more:hover::before {
        -webkit-box-shadow: 0 0 0 2px #b18597, 0 0.5em 0 0 #ffe3e2;
        box-shadow: 0 0 0 2px #b18597, 0 0.5em 0 0 #ffe3e2;
        -webkit-transform: translate3d(0, 0.5em, -1em);
        transform: translate3d(0, 0.5em, -1em);
      }

      button.learn-more:active {
        background: #ffe9e9;
        -webkit-transform: translate(0em, 0.75em);
        transform: translate(0em, 0.75em);
      }

      button.learn-more:active::before {
        -webkit-box-shadow: 0 0 0 2px #b18597, 0 0 #ffe3e2;
        box-shadow: 0 0 0 2px #b18597, 0 0 #ffe3e2;
        -webkit-transform: translate3d(0, 0, -1em);
        transform: translate3d(0, 0, -1em);
      }
      .btn.navbar-toggler {
        width: 135px;
        height: 50px;
        border-radius: 5px;
        border: none;
        transition: all 0.5s ease-in-out;
        font-size: 18px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        background: #040f16;
        color: #f5f5f5;
        position: relative;
      }

      .btn.navbar-toggler:hover {
        box-shadow: 0 0 20px 0px #2e2e2e3a;
      }

      .btn.navbar-toggler .icon {
        position: absolute;
        height: 40px;
        width: 65px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.5s;
      }

      .btn.navbar-toggler .text {
        transform: translateX(55px);
      }

      .btn.navbar-toggler:hover .icon {
        width: 175px;
      }

      .btn.navbar-toggler:hover .text {
        transition: all 0.5s;
        opacity: 0;
      }

      .btn.navbar-toggler:focus {
        outline: none;
        box-shadow: none;
      }

      .btn.navbar-toggler:active .icon {
        transform: scale(0.83);
      }
      .Btns {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 45px;
        height: 45px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition-duration: 0.3s;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
        background-color: white;
      }

      /* exit sign */
      .sign {
        width: 100%;
        transition-duration: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .sign svg {
        width: 17px;
      }

      .sign svg path {
        fill: black;
      }
      /* text */
      .texts {
        position: absolute;
        right: 0%;
        width: 0%;
        opacity: 0;
        color: white;
        font-size: 1.2em;
        font-weight: 600;
        transition-duration: 0.3s;
      }
      /* hover effect on button width */
      .Btns:hover {
        background-color: black;
        width: 125px;

        transition-duration: 0.3s;
      }

      .Btns:hover .sign {
        width: 30%;
        transition-duration: 0.3s;
        padding-left: 20px;
      }

      .Btns:hover .sign svg path {
        fill: white;
      }

      /* hover effect button's text */
      .Btns:hover .texts {
        opacity: 1;
        width: 70%;
        transition-duration: 0.3s;
        padding-right: 10px;
      }
      /* button click effect*/
      .Btns:active {
        transform: translate(2px, 2px);
      }

      section{
        margin-top: 5%;
      }

      h1{
        margin-top: 10%;
      }
      li{
        margin:2%
      }
    </style>
  </head>
  <body>
    <?php
    include_once '..\backend\includes/header.php';
    ?> 
    <h1>
      Bem-vindo,
      <?php echo $_SESSION['nome']; ?>!
    </h1>

    <section class="container text-center">
     
    
      <!-- Columns are always 50% wide, on mobile and desktop -->
      <div class="column">
        <div class="col-10">
          <p>
            Esta é a sua área de usuário(a) , nela você poderá realizar consultas de instituições ,solicitacões de interesse em receber ou doar leite materno , tirar duvidas e entender sobre o universo da amamentação. Siga as dicas abaixo sobre como navegar pelo site. 
          </p>
        </div>
        <div class="col-10">
          <div class="card" >
            <img src="..." class="card-img-top" alt="...">
            <div class="card-body">
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
            </div>
          </div>
        </div>
      </div>
    </section>



    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
