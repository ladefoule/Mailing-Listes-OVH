<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header"><span class="icon-mail"></span> Mailing List</div>
        <div class="card-body">
            <form method="POST">
                <div class="form-row pb-3 d-flex align-items-center">
                    <label for="name" class="col-lg-4 text-lg-right col-form-label"><span class="text-danger">*</span> Nom : </label>
                    <input type="text" required <?php if(in_array($action, ['update', 'options'])) echo 'disabled' ?> class="form-control-sm col-6 col-lg-4" id="name" name="name" value="<?php echo $name ?>">@<?php echo $domain ?></span>
                </div>

                <?php if(in_array($action, ['create', 'update'])){ ?>
                    <div class="form-row pb-3 d-flex align-items-center">
                        <label for="ownerEmail" class="col-lg-4 text-lg-right col-form-label"><span class="text-danger">*</span> Propriétaire : </label>
                        <input type="text" required class="form-control-sm col-12 col-lg-6" id="ownerEmail" name="ownerEmail" value="<?php echo $ownerEmail ?>">
                    </div>

                    <div class="form-row pb-3 d-flex align-items-center">
                        <label for="replyTo" class="col-lg-4 text-lg-right col-form-label">Répondre à : </label>
                        <input type="email" class="form-control-sm col-12 col-lg-6" id="replyTo" name="replyTo" value="<?php echo $replyTo ?>" placeholder="Laisser vide pour répondre à la mailing list">
                    </div>
                <?php } ?>
                
                <?php if(in_array($action, ['create', 'options'])){ ?>
                    <div class="offset-lg-4 form-check pb-3 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" <?php if($subscribeByModerator) echo 'checked'; ?> id="subscribeByModerator" name="subscribeByModerator">
                        <label class="form-check-label" for="subscribeByModerator">
                            Modération des abonnés <span class="icon-info-circled" title="Le propriétaire, ou un modérateur, doit approuver les inscriptions à la mailing-list."></span>
                        </label>
                    </div>

                    <div class="offset-lg-4 form-check pb-3 d-flex align-items-center">
                        <input class="form-check-input" type="radio" <?php if($moderation == 'moderatorMessage') echo 'checked'; ?> name="moderation" value="moderatorMessage">
                        <label class="form-check-label" for="moderation">
                            Modérer tous les messages <span class="icon-info-circled" title="Le propriétaire ou un modérateur doit approuver l'e-mail envoyé à la mailing-list."></span>
                        </label>
                    </div>

                    <div class="offset-lg-4 form-check pb-3 d-flex align-items-center">
                        <input class="form-check-input" type="radio" <?php if($moderation == 'usersPostOnly') echo 'checked'; ?> name="moderation" value="usersPostOnly">
                        <label class="form-check-label" for="moderation">
                            Seuls les abonnés peuvent poster <span class="icon-info-circled" title="Restreint l'envoi d'e-mails sur la mailing list aux seules abonnés de celle-ci."></span>
                        </label>
                    </div>

                    <div class="offset-lg-4 form-check pb-3 d-flex align-items-center">
                        <input class="form-check-input" type="radio" <?php if($moderation == '') echo 'checked'; ?> name="moderation" value="">
                        <label class="form-check-label" for="moderation">
                            Pas de modération des messages
                        </label>
                    </div>
                <?php } ?>

                <div class="form-row pb-3 d-flex">
                    <button type="submit" class="offset-lg-4 btn-sm btn-primary px-4" name="action" value="create">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>