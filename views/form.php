<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header"><span class="icon-list"></span> Mailing List</div>
        <div class="card-body">
            <form method="POST">
                <div class="form-row pb-3 d-flex align-items-center">
                    <label for="name" class="col-lg-4 text-lg-right col-form-label"><span class="text-danger">*</span> Nom : </label>
                    <input type="text" required class="form-control col-12 col-lg-6" id="name" value="<?php echo $name ?>">
                </div>

                <div class="form-row pb-3 d-flex align-items-center">
                    <label for="ownerEmail" class="col-lg-4 text-lg-right col-form-label"><span class="text-danger">*</span> Propriétaire : </label>
                    <input type="text" required class="form-control col-12 col-lg-6" id="ownerEmail" value="<?php echo $ownerEmail ?>">
                </div>
                
                <div class="offset-lg-4 form-check pb-3 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" <?php if($moderatorMessage) echo 'checked'; ?> id="moderatorMessage" name="moderatorMessage">
                    <label class="form-check-label" for="moderatorMessage">
                        Modérer tous les messages <span class="icon-mail" title="Le propriétaire ou un modérateur doit approuver l'e-mail envoyé à la mailing-list."></span>
                    </label>
                </div>

                <div class="offset-lg-4 form-check pb-3 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" <?php if($subscribeByModerator) echo 'checked'; ?> id="subscribeByModerator" name="subscribeByModerator">
                    <label class="form-check-label" for="subscribeByModerator">
                        Modération des abonnés <span class="icon-mail" title="Le propriétaire, ou un modérateur, doit approuver les inscriptions à la mailing-list."></span>
                    </label>
                </div>

                <div class="offset-lg-4 form-check pb-3 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" <?php if($usersPostOnly) echo 'checked'; ?> id="usersPostOnly" name="usersPostOnly">
                    <label class="form-check-label" for="usersPostOnly">
                        Seuls les abonnés peuvent poster <span class="icon-mail" title="Restreint l'envoi d'e-mails sur la mailing list aux seules abonnés de celle-ci."></span>
                    </label>
                </div>

                <div class="form-row pb-3 d-flex align-items-center">
                    <label for="replyTo" class="col-lg-4 text-lg-right col-form-label">Répondre à : </label>
                    <input type="email" class="form-control col-12 col-lg-6" id="replyTo" value="<?php echo $replyTo ?>">
                </div>

                <div class="form-row pb-3 d-flex">
                    <button type="submit" class="offset-lg-4 btn btn-primary px-4" name="action" value="create">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>