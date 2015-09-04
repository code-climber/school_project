<h1>Connexion</h1>

<?php if ($bConnectError): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>Erreur!</strong> Impossible de vous connecter. Vérifier votre login et votre mot de passe.
    </div>
<?php endif; ?>

<form class="form-horizontal" action="index.php?page=login" method="post" name="login" role="form">
    <div class="form-group">
        <label for="login" class="col-sm-2 control-label">Login</label>

        <div class="col-sm-10">
            <input type="login" class="form-control" name="login" id="login" placeholder="Login">
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-sm-2 control-label">Mot de passe</label>

        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="connect">Connexion</button>
        </div>
    </div>
</form>



