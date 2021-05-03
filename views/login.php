<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header p-3">
            Connexion
        </div>
        <div class="card-body p-3">
            <form action="/" method="POST">
                <div class="form-row pb-3">
                    <label for="account" class="col-md-3 col-form-label text-md-right"><span class="text-danger">*</span> Email</label>
                    <div class="col-md-9 d-flex align-items-center">
                        <input type="text" required class="form-control col-6" id="account" name="account" <?php echo $account ?>>
                        <span class="col-6">@<?php echo $domain ?></span>
                    </div>
                </div>

                <div class="form-row pb-3">
                    <label for="password" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Mot de passe</label>
                    <div class="col-md-9">
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>
                </div>

                <div class="form-row pb-3">
                    <div class="offset-md-3 col-md-9">
                        <button type="submit" class="btn btn-success">Se connecter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>