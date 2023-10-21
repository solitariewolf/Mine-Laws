<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Mine Laws MOD 1.0</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>
<body>

<div class="login">
    <div class="container">
        <div class="row">            
            <div class="col-lg-4 offset-lg-4">    
                    <div class="card">
                        <div class="card-body">
                            <div class="logo-init">
                            <h3>Área De Membros</h3>
                            <div><!--logo init-área de membros-->
                        <div class="card-body">
                            <img src="img/logo.png" alt="">
                            </div><!--logo-init-->
            
                        </div><!--logo-->
                        <div class="card-body"> 
                            <form action="login.php" method="POST">

                                <div>
                                    <div class="mb-3">
                                        <label>Usuário:</label>
                                        <input type="text" name="usuario" class="form-control">
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label>Senha:</label>
                                        <input type="password" name="senha" class="form-control">
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary" >Enviar</button>
                                </div>
                                    
                                </div>
                            </form>
                        </div><!--card-body-->
                    </div><!--card-->    
                    <p class="copy">&copy;Todos os direitos reservados a <a class="copy" href="https://github.com/solitariewolf/" target="_blank">@Solitariewolf</a></p> 
            </div><!--col-lg-4 offset-lg-4-->           
        </div><!--row-->
    </div><!--container-->
</div><!--login-->
    <!--Background-video-->
    <video id="background-video" autoplay loop muted poster="">
    <source src="img/mine.mp4" type="video/mp4">
    </video>
    <!--Background-video-->
<script src="js/validation.js"></script>
</body>
</html>

<!--Developer @Solitariewolf - Github-->