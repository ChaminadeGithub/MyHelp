<?php 

session_start();
require "../Auth/db/connect_base.php";

// check if user is alredy logined
if(isset($_SESSION['user']))
{
    header("location: ../index.php");
}


// la fonction isset pour vérifier si l'utilisateur a cliqué sur login
// validation des données
if(isset($_POST['login']))
{
    // validation user login
    if(empty($_POST["user_login"]))
    {
        $error_user_login= "Remplir ce champ";
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
            $sql = "SELECT * from users where email = :email OR phone = :phone";
            $requete = $db->prepare($sql);
            // valeur des paramètres
            $requete->bindParam(":email", $user_login, PDO::PARAM_STR);
            $requete->bindParam(":phone", $user_login, PDO::PARAM_STR);
            // exécution de la requete
            $query = $requete->execute();
            if($query)
            {   
                
                // nombre de ligne
                if($requete->rowCount() > 0)
                {
                    // récupérer user
                    $user= $requete->fetch(PDO::FETCH_ASSOC);
                    // var_dump($user);
                    // return;

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
            echo "Quelques choses s'est mal passé : " . $e->getMessage();
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
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="../assets/css/forms.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="card shadow bg-info bg-transparent my-5 border-5 p-3">
                <div class="row align-items-center bg-black">
                    <span class="text-danger"><i><?= $error_query?? "" ?></i></span>

                    <div class="col-md-6 col-sm-6 d-none d-sm-block bg-info">
                        <img src="../assets/img/R (1).jpg" class="img-fluid" alt="">
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <img src="../images/" class="img-fluid" alt="">
                        <marquee behavior="alternate" scrollamount="13" direction="">
                            <h3 class="my-3 text-primary"><b>Incription</b></h3>
                        </marquee>

                        <form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                            <div class="form-group mb-3">
                                <input type="text" name="f_name" id="" class="form-control <?= $error_f_name? "is-invalid" : "" ?> " value="<?= $f_name?? "" ?>" placeholder="Votre Nom et prénom">
                                <span class="text-danger"><i><?= $error_f_name?? "" ?></i></span>
                            </div>
                            
                            <div class="form-group mb-3">
                                <input type="text" name="f_name" id="" class="form-control <?= $error_f_name? "is-invalid" : "" ?> " value="<?= $f_name?? "" ?>" placeholder="Votre Adresse (ville/pays/localite)">
                                <span class="text-danger"><i><?= $error_f_name?? "" ?></i></span>
                            </div>

                            <div class="form-group mb-3">
                                <input type="numeric" name="phone" id="" value="<?= $phone?? "" ?>" class="form-control <?= $error_phone? "is-invalid" : "" ?> " placeholder="Telephone (sans indicatif)">
                                <span class="text-danger"><i><?= $error_phone?? "" ?></i></span>
                            </div>

                            <div class="form-group mb-3">
                                <input type="email" name="email" id="" value="<?= $email?? "" ?>" class="form-control <?= $error_email? "is-invalid" : "" ?> " placeholder="Entrer votre email">
                                <span class="text-danger"><i><?= $error_email?? "" ?></i></span>
                            </div>


                            <div class="form-group mb-3">
                                <input type="password" name="password" id="" class="form-control <?= $error_password? "is-invalid" : "" ?> " placeholder="Mot de passe">
                                <span class="text-danger"><i><?= $error_password?? "" ?></i></span>
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" name="password_confirm" id="" class="form-control <?= $error_password_confirm? "is-invalid" : "" ?> " placeholder="Confirmer le mot de passe">
                                <span class="text-danger"><i><?= $error_password_confirm?? "" ?></i></span>
                            </div>

                            <button class="btn btn-outline-primary mb-1" type="submit" name="register">S'inscrire</button>
                            <p class="text-white">Déjà un compte ? <a href="Conn.php">Connecter</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>















        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>