<?php
session_start();
require "../Auth/db/connect_base.php" ;

// verifier si le user est deja connecter
if (isset($_SESSION['user'])){
    header("location: ../index.php");
}

// la fonction isset pour vérifier si l'utilisateur a cliqué sur login
// validation des données
if(isset($_POST['Acceder']))
{
    // validation user login
    if(empty($_POST["user_login"]))
    {
        $error_user_login= "Veuillez remplir ce champs";
    }
    else if(!is_numeric(validate($_POST["user_login"])))
    {
        if(!filter_var(validate($_POST["user_login"]), FILTER_VALIDATE_EMAIL))
        {
            $error_user_login= "Email invalide";
        }
        else
        {
            $user_login = validate($_POST["user_login"]);
        }
    }
    else
    {
        $user_login = validate($_POST["user_login"]);
    }
   
    // validtaion de password
    if(empty($_POST["password"]))
    {
        $error_password = "Entrer le mot de passe";
    }
    else 
    {
        $password = validate($_POST["password"]);   
    }

    // vérification 
    if(isset($user_login) && $password != "")
    {
        try{
            // préparation de la requete
            $sql = "SELECT * from utilisateurs where Email = :Email OR Telephone_Util = :Telephone_Util";
            $requete = $db->prepare($sql);

            // valeur des paramètres
            $requete->bindParam(":Email", $user_login, PDO::PARAM_STR);
            $requete->bindParam(":Telephone_Util", $user_login, PDO::PARAM_STR);

            // exécution de la requete
            $query = $requete->execute();
            if($query)
            {   
                
                // nombre de ligne
                if($requete->rowCount() > 0)
                {
                    // récupérer user
                    $user= $requete->fetch(PDO::FETCH_ASSOC);

                    // vérifier le mot de passe
                    if(password_verify($password, $user["password"]))
                    {
                        $_SESSION["user"] = $user;

                        // redirection sur la index page
                        header("location: ../index.php");

                    }
                    else
                    {
                        $error_password= "mot de passe incorrect";
                    }
                }
                else
                {
                    $error_user_login= "Aucune information trouvé";
                }
            }
            
        }
        catch (PDOException $e)
        {
            echo "Quelque chose s'est mal passé : " . $e->getMessage();
        }
    }

}







// validation du formulare
function validate($data)
{
    $data =trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}





?>



<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
    <link href="assets/img/R.jpg" rel="icon">
    <link href="assets/img/R (1).jpg" rel="apple-touch-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="../assets/css/forms.css" rel="stylesheet"> 
    <link href="assets/css/style.css" rel="stylesheet">  
</head>
  <body>
    <div class="container">
        <div class="card shadow bg-transparent my-5 border-5 p-4">
            <div class="row align-items-center">
                <span class="text-danger"><i><?= $error_query?? "" ?></i></span>
                <div class="bg-info col-md-6 col-sm-6 d-none d-sm-block">
                    <img src="../assets/img/auth_img/log_img.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-md-6 col-sm-6">
                    <img src="../assets/img/" class="img-fluid" alt="">
                    <marquee behavior="alternate" scrollamount="7" direction="">
                        <h3 class="my-3 text-primary"><b>Connexion</b></h3>
                    </marquee>
                    <form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                       
                        <div class="form-group mb-3">
                            <input type="text" name="user_login" id="" value="<?= $user_login?? "" ?>" class="form-control <?= $error_user_login? "is-invalid" : "" ?> placeholder="Adresse email ou numéro">
                            <span class="text-danger"><i><?= $error_user_login?? "" ?></i></span>
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" name="password" id="" class="form-control <?= $error_password? "is-invalid" : "" ?> " placeholder="Mot de passe">
                            <span class="text-danger"><i><?= $error_password?? "" ?></i></span>
                        </div>
                        
                        <button class="btn btn-outline-primary mb-1" type="submit" name="Acceder">Acceder</button>
                        <p class="text-white">Pas encore un compte ? <a href="Inscript.php">S'inscrire</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>















    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>