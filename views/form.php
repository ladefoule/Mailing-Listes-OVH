<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header"><span class="icon-mail"></span> Mailing List</div>
        <div class="card-body">
            <form method="<?php echo $formMethod ?>">
                <div class="form-row pb-3 d-flex align-items-center">
                    <label for="account" class="col-lg-3 text-lg-right col-form-label">Email <span class="text-danger">*</span></label>
                    <input type="text" required disabled class="form-control col-6 col-lg-5" id="account" value="<?php echo $account ?>"><span class="col-6 col-lg-4">@<?php echo $domain ?></span>
                </div>

                <div class="form-row pb-3">
                    <label class="col-lg-3 col-form-label text-lg-right" for="content">Message <span class="text-danger">*</span></label>
                    <textarea class="col-lg-9 form-control" required <?php if($action == 'show') echo 'disabled'; ?> rows="8" id="content" name="content"><?php echo $content ?></textarea>
                </div>
                
                <div class="offset-lg-3 form-check pb-3 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" <?php if($action == 'show') echo 'disabled'; ?> <?php if($copy) echo 'checked'; ?> id="copy" name="copy">
                    <label class="form-check-label" for="copy">
                        Sauvegarder les mails reçus
                    </label>
                </div>

                <div class="form-row pb-3">
                    <label class="col-lg-3 col-form-label text-lg-right" for="from">Début <span class="text-danger">*</span></label>
                    <input type="date" required <?php if($action == 'show') echo 'disabled'; ?> class="col-lg-9 form-control" id="from" name="from" value="<?php echo $from ?>">
                </div>

                <div class="form-row pb-3">
                    <label class="col-lg-3 col-form-label text-lg-right" for="to">Fin (inclus) <span class="text-danger">*</span></label>
                    <input type="date" required <?php if($action == 'show') echo 'disabled'; ?> class="col-lg-9 form-control" id="to" name="to" value="<?php echo $to ?>">
                </div>

                <div class="form-row pb-3 d-flex">
                    <?php if($action == 'create'){ ?>
                        <button type="submit" class="offset-lg-3 btn btn-primary px-4" name="action" value="create">Créer</button>
                    <?php }else if($action == 'show'){ ?>
                        <button type="submit" class="offset-lg-3 btn btn-danger px-4" name="action" value="delete">Supprimer</button>
                    <?php } ?>

                    <!-- <a class="ml-auto btn btn-secondary" href="/"><span class="icon-home"></span>Accueil</a> -->
                </div>
            </form>
        </div>
    </div>
</div>