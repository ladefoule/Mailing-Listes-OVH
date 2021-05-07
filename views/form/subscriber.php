<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header"><span class="icon-mail"></span> Ajouter un abonné</div>
        <div class="card-body">
            <form method="POST">
                <div class="form-row pb-3 d-flex align-items-center">
                    <label for="name" class="col-lg-4 text-lg-right col-form-label"><span class="text-danger">*</span> Nom : </label>
                    <input type="text" disabled class="form-control-sm col-6 col-lg-4" id="name" name="name" value="<?php echo $name ?>">@<?php echo $domain ?></span>
                </div>

                <div class="form-row pb-3 d-flex align-items-center">
                    <label for="email" class="col-lg-4 text-lg-right col-form-label">Email : </label>
                    <input type="email" class="form-control-sm col-12 col-lg-6" id="email" name="email" value="<?php echo $email ?>">
                </div>

                <div class="form-row pb-3 d-flex">
                    <button type="submit" class="offset-lg-4 btn-sm btn-primary px-4" name="action" value="create">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>