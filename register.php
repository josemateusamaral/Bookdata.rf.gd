<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <meta charset="UTF-8">
    <title>Book_DATA</title>
    <script src="js/index.js"></script>
    <script src="js/base.js"></script>
    <link rel="icon" type="image/x-icon" href="imagens/icone_novo.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/base.css">
</head>

<body onload="base_configuracao_inicial();configuracao_inicial()">
    
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light" id="navbar">
      <img src="imagens/icone_novo.png" alt="website icon" id="logo_nav" onclick='window.location.assign("index.php");'>
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
                      <a class="nav-link" onclick="document.getElementById('loggoof').submit();" id="botaoFavorito">Loggoff</a>
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
              <a href="register.php" class="nav-link botoesSidebar active text-light">
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
        <li>
          <a id="login" class="nav-link botoesSidebar" href="login.php">
            <svg class="bi me-2" width="16" height="16"><use xlink:href="#login"></use></svg>
          </a>
        </li>
      </ul>
    </nav>

    <main style="margin-top:10vh">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-dark">
                            <h4 class="text-center">Create Account</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" action="api.php">
                                <input type="hidden" name="action" value="register">
                                
                                
                                <div class="form-group text-dark">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Type your username">
                                    <span id="username-error" class="text-danger"></span>
                                </div>
                                <script>
                                document.querySelector('#username').addEventListener('blur', function() {
                                    const username = this.value;
                                    const usernameError = document.querySelector('#username-error');
                                    if (username.length < 6 || username.length > 20) {
                                        usernameError.textContent = "The username must have between 6 and 20 characters";
                                    } else {
                                        usernameError.textContent = "";
                                    }
                                });
                                </script>



                                <div class="form-group text-dark">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Type wour email">
                                </div>


                                <div class="form-group text-dark">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Type your password">
                                    <span id="password-error" class="text-danger"></span>
                                </div>

                                <script>
                                document.querySelector('#password').addEventListener('blur', function() {
                                    const password = this.value;
                                    const passwordError = document.querySelector('#password-error');
                                    const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,<.>])/
                                    
                                    if (password.length < 6 || password.length > 20) {
                                        passwordError.textContent = "A senha deve ter entre 6 e 20 caracteres.";
                                    } else if (!passwordPattern.test(password)) {
                                        passwordError.textContent = "A senha deve conter números, letras maiúsculas, letras minúsculas e símbolos.";
                                    } else {
                                        passwordError.textContent = "";
                                    }
                                });
                                </script>


                                <!--div class="form-group" text-dark>
                                    <label for="confirm-password">Confirme a Senha</label>
                                    <input type="password" class="form-control" id="confirm-password" placeholder="Confirme sua senha">
                                </div-->
                                
                                <button type="submit" class="btn btn-primary btn-block">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer></footer>

  </body>

</html>
