<div class="col-lg-9 p-0">
    <div class="card">
        <div class="card-header p-3">
            Connexion
        </div>
        <div class="card-body p-3">
            <form action="/" method="POST">
                <div class="form-row pb-3">
                    <label for="account" class="col-md-3 col-form-label text-md-right"><span class="text-danger">*</span> Email</label>
                    <div class="col-md-8 d-flex flex-wrap align-items-center">
                        <input type="text" required class="form-control-sm col-sm-7" id="account" name="account" placeholder="Votre email perso">
                        <span class="col-sm-4 pl-0 pl-sm-1">@<?php echo $domain ?></span>
                    </div>
                </div>

                <div class="form-row pb-3">
                    <label for="password" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Mot de passe</label>
                    <div class="col-md-8 d-flex align-items-center">
                        <input id="password" type="password" class="col-sm-7 form-control-sm" name="password" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-8 offset-md-3">
                        <div class="form-check px-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                            <label class="form-check-label" for="remember">
                                Garder ma session active
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-row pb-3">
                    <div class="offset-md-3 col-md-9">
                        <button type="submit" class="btn-sm btn-success">Se connecter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>