<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Book_DATA - Book</title>
    <script src="js/base.js"></script>
    <script src="js/livro.js"></script>
    <link rel="icon" type="image/x-icon" href="imagens/icone_novo.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/livro.css">
    <link rel="stylesheet" href="css/base.css">
</head>

<body onload="base_configuracao_inicial();configuracao_inicial()">

    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light" id="navbar">
      <img src="imagens/icone_novo.png" id="logo_nav" alt="website icon" onclick='window.location.assign("index.php");'>
      <a class="navbar-brand" href="index.php">Book_DATA</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" onclick="toogleSideBar()">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="recomendations.php">Recomendations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="favorites.php">Favorites</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="n_mode" onclick="toogle_nightMode()"></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" id="login" href="login.php">
                <?php
                    if(isset($_SESSION["username"])){
                      echo $_SESSION["username"];
                    }
                    else{
                      echo "Login";
                    }   
                ?>
            </a>
          </li>
          <?php
              if(isset($_SESSION["username"])){
                $string = <<<HEREDOC
                  <li class="nav-item">
                    <form action="api.php" method="post" id="loggoof">
                      <input type='hidden' value="loggoff" name="action">
                      <a class="nav-link" onclick="document.getElementById('loggoof').submit();">Loggoff</a>
                    </form>
                  </li>
                  HEREDOC;
                echo $string;
              }
          ?>
        </ul>
      </div>

    </nav>

    <nav class="d-flex flex-column flex-shrink-0 p-3" id="sidebar">
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
          <a href="index.php" class="nav-link botoesSidebar" aria-current="page">
            <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
            Home
          </a>
        </li>
        <?php
          if(isset($_SESSION["username"])){
            $string = <<<HEREDOC
            <li>
              <a class="nav-link botoesSidebar"onclick="document.getElementById('loggoof').submit()">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#logoff"></use></svg>
                {$_SESSION["username"]} | Logoff
              </a>
            </li>
            HEREDOC;
            echo $string;
          }
          else{
            $string = <<<HEREDOC
            <li>
              <a href="login.php" class="nav-link botoesSidebar">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#login"></use></svg>
                Login
              </a>
            </li>
            <li>
              <a href="register.php" class="nav-link botoesSidebar">
                <svg class="bi me-2" width="16" height="16"><use xlink:href="#register"></use></svg>
                Register
              </a>
            </li>
            HEREDOC;
            echo $string;
          }   
          ?>
        <li>
          <a href="recomendations.php" class="nav-link botoesSidebar">
            <svg class="bi me-2" width="16" height="16"><use xlink:href="#recomendacoes"></use></svg>
            Recomendations
          </a>
        </li>
        <li>
          <a href="favorites.php" class="nav-link botoesSidebar">
            <svg class="bi me-2" width="16" height="16"><use xlink:href="#favorites"></use></svg>
            Favorites
          </a>
        </li>
        <li>
          <a href="about.php" class="nav-link botoesSidebar">
            <svg class="bi me-2" width="16" height="16"><use xlink:href="#sobre"></use></svg>
            About
          </a>
        </li>
        <li>
          <a id="n_mode_S" class="nav-link botoesSidebar" onclick="toogle_nightMode()">
            <svg class="bi me-2" width="16" height="16"><use xlink:href="#sobre"></use></svg>
          </a>
        </li>
      </ul>
    </nav>

    <main>
      <div class="livro align-top">
        <img id="cover" alt="book cover" src="imagens/sem_capa.jpg">
      </div>
      <div id="informacoes">
          
          <h1>
          <?php
            if(isset($_SESSION["username"])){

              include 'bd_config.php';
              
              $conn = new mysqli($servername, $username, $password, $dbname);
              
              // Verificar se a conexão foi estabelecida com sucesso
              if ($conn->connect_error) {
                  die("Falha na conexão com o banco de dados: " . $conn->connect_error);
              }

              // pegar a lista de favoritos e verificar se o livro esta na lista
              $sql = "SELECT favoritos FROM usuarios WHERE nome_usuario = '".$_SESSION['username']."';";
              $result = $conn->query($sql);
              $isIn = false;
              $row = $result->fetch_assoc();
              if ($result->num_rows > 0) {
                  $favoritos = explode(';',$row['favoritos']);
                  foreach ($favoritos as $favorito) {
                      $idDB = explode('$',$favorito)[0];
                      //echo $idDB;
                      if( $idDB == $_GET['l'] ){
                          $isIn = true;
                      }
                  }
              }

              # o livro eh favorito
              if($isIn){
                $string = <<<HEREDOC
                      <form action="api.php" method="post" id="favForm">
                          <input type="hidden" name="action" value="favorite" id="favorite">
                          <input type="hidden" name="book" id="favoriteData">
                          <a class="nav-link" id="favorito" onclick="document.getElementById('favForm').submit();" style="color:#ff0">&#9733</a>
                      </form>
                  HEREDOC;
                echo $string;

              // o livro nao eh favorito
              }else{
                $string = <<<HEREDOC
                    <form action="api.php" method="post" id="favForm">
                        <input type="hidden" name="action" value="favorite" id="favorite">
                        <input type="hidden" name="book" id="favoriteData">
                        <a class="nav-link" id="favorito" onclick="document.getElementById('favForm').submit();" style="color:#444">&#9733</a>
                    </form>
                HEREDOC;
                echo $string;
              }
            }
            
            else{
              $string = <<<HEREDOC
                    <a class="nav-link" id="favorito" onclick="alert('Para adicionar este livro a sua lista de favoritos você deve entrar em uma conta ou criar uma conta primeiro. É de graça :)');"></a>
                HEREDOC;
              echo $string;
            }
          ?>
          <div id = 'titulo_livro'></div>
          </h1>
          
          <h2>
            Description
          </h2>
          <p id="descricao">
            This book doesn't have a description yet
          </p>

          <h2 id="h2_autor">
            Author
          </h2>
          <p id="autor">
            This book doesn't have a author name yet
          </p>

          <h2>
            Release date
          </h2>
          <p id="data_de_publicacao">
            This book doesn't have a release date yet
          </p>

          <h2>
            Subjects
          </h2>
          <p id="assuntos_abordados">
            This book doesn't have a list of subjects yet
          </p>

      </div>
    </main>

    <footer></footer>

</body>

</html>

