<?php
if(isset($loginSpace)) {
?>

<div class="container">
    
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