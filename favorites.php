<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="imagens/icone_novo.ico">
    <title>Book_DATA - Recomendations</title>

    <script src="js/favoritos.js"></script>
    <script src="js/base.js"></script>
    <script src="js/data.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    

    <link rel="stylesheet" href="css/recomendacoes.css">
    <link rel="stylesheet" href="css/base.css">
</head>

<body onload="base_configuracao_inicial();configuracao_inicial()">
    
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light" id="navbar">
      <img src="imagens/icone_novo.png" alt="website icon" id="logo_nav" onclick='window.location.assign("index.php");'>
      <a class="navbar-brand" href="index.php">Book_DATA</a>
      <button class="navbar-toggler" type="button" onclick="toogleSideBar()">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="recomendations.php">Recomendations</a>
          </li>
          <li class="nav-item active">
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
          <a href="favorites.php" class="nav-link active text-light botoesSidebar">
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
      <div id="lista">
        <div id="livrosContainer">
          <?php

            if(isset($_SESSION["username"])){

              include 'bd_config.php';

              $conn = new mysqli($servername, $username, $password, $dbname);

              // Verificar se a conexão foi estabelecida com sucesso
              if ($conn->connect_error) {
                  die("Falha na conexão com o banco de dados: " . $conn->connect_error);
              }

              $sql = "SELECT favoritos FROM usuarios WHERE nome_usuario = '".$_SESSION['username']."';";
              $result = $conn->query($sql);
              $novoFavoritos = '';
              $isIn = false;
              $row = $result->fetch_assoc();
              //echo $row['favoritos'];
              //echo"<br><br>";
              //echo '<br>';
              if ($result->num_rows > 0) {
                  $favoritos = explode(';',$row['favoritos']);
                  
                  if($favoritos != []){
                    foreach ($favoritos as $favorito) {
                      //echo $favorito;
                      //echo "<br>";

                      if($favorito!=''){
                        $fav = explode('$',$favorito);
                        $fav_id = $fav[0];
                        $fav_title = $fav[1];
                        $fav_cover = $fav[2];
                        
                        //echo $fav_cover;
                        //echo "<br>";

                        $string = <<<HEREDOC
                        <div class="item">
                            <div class="default_img">
                                <img
                                    class = "cover" 
                                    src = "https://covers.openlibrary.org/b/id/$fav_cover.jpg" 
                                    onclick = 'window.location.assign("book.php?l=$fav_id")'
                                >
                            </div>
                            <p class="titulo">$fav_title</p>
                        </div>
                        HEREDOC;

                        echo $string;
                        
                      }
                    }
                  }
              }
            }
            
            else{
              $string = <<<HEREDOC
                <div class="container">
                    <h1>Favorites Feature</h1>
                    <p>To use the favorites features you need to have an account.</p>
                    <div class="alert alert-info" role="alert">
                        <strong>Dica:</strong> The Favorites Feature allows you to save your favorite books, letting you access then from anywhere!
                    </div>
                    <a href="login.php" class="btn btn-primary">Login</a>
                    <a href="register.php" class="btn btn-primary">Create Account</a>
                </div>
                HEREDOC;
              echo $string;
            }
            
            
          ?>
        </div>
      </div>
    </main>

    <footer></footer>

  </body>

</html>
