<?php
if(isset($loginSpace) && empty( $_SESSION['userId'] )) {
?>

<div class="container-fluid text-center mt-5">
    <div class="row justify-content-center">
        <div class="col-6 bg-secondary-subtle text-emphasis-secondary">
            <div class="h2 m-5 text-center">Se connecter</div>

            <?php
            if( isset( $message ) ) {
            ?>
            
            <div class="alert alert-<?=$message['type']?> alert-dismissible fade show mb-4" role="alert">
                <?=$message['message']?>
            </div>
            
            <?php
            }
            ?>

            <form name="accesform" method="post" action="index.php">
                <input type="hidden" value="index" name="controller">
                <input type="hidden" value="verifyLogin" name="action">
                <div class="mb-4 row justify-content-center">
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="login" placeholder="Login" name="login" required>
                    </div>
                </div>
                <div class="mb-4 row justify-content-center">
                    <div class="col-sm-4">
                        <input type="password" class="form-control" id="inputPassword" placeholder="Mot de passe" name="password" required>
                    </div>
                </div>
                
                <div class="mb-5 row justify-content-center">
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-outline-dark mb-3">Suivant</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
} else {
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-6 m-0 p-0 bg-secondary-subtle text-emphasis-secondary">
            <div class="container text-center" style="height: 100%;">
                <div class="row align-items-center justify-content-center" style="height: 100%;">
                    <div class="col-10">
                        <div class="h1 m-3">THE EASIEST WAY TO CREATE YOUR OWN WEBSITE !</div>
                        <div class="p m-3">Notre CMS permet la création de site web de manière claire et intuitif.</div>
                        <a class="btn btn-outline-dark m-3" role="button" aria-disabled="true">Créer un compte</a>
                    </div>
                        
                </div>
            </div>    
        </div>
        <div class="col-6 m-0 p-0">
            <img src="assets/images/background-picture.jpg" alt="Background Picture" class="img-fluid">
        </div>
    </div>
</div>

<?php
}
?>