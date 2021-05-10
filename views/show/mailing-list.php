<?php 
    // var_dump($mailingList);exit();
    $name = $mailingList['name'];
    $replyTo = $mailingList['replyTo'];
    $ownerEmail = $mailingList['ownerEmail'];
    $nbSubscribers = $mailingList['nbSubscribers'];
    $nbModerators = $mailingList['nbModerators'];
    $subscribeByModerator = $mailingList['options']['subscribeByModerator'];
    $usersPostOnly = $mailingList['options']['usersPostOnly'];
    $moderatorMessage = $mailingList['options']['moderatorMessage'];
    $nbSubscribersUpdateDate = new DateTime($mailingList['nbSubscribersUpdateDate']);
?>
<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="d-inline mr-3 crud-titre"><i class="icon-mail"></i> Mailing List</span>
            <a href="/<?php echo $name ?>/update" title="Modifier" class="text-decoration-none">
                <button class="btn-sm btn-info text-white">
                    <i class="icon-pencil"></i>
                    <span class="d-none d-lg-inline ml-1">Modifier</span>
                </button>
            </a>
            <a href="/<?php echo $name ?>/options" title="Modifier les options" class="ml-3 text-decoration-none">
                <button class="btn-sm btn-info text-white">
                    <i class="icon-check"></i>
                    <span class="d-none d-lg-inline ml-1">Modifier les options</span>
                </button>
            </a>
        </div>
        <div class="card-body">
            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Liste</li>
                <li class="list-group-item"><?php echo $name.'@'.$domain ?></li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Propriétaire</li>
                <li class="list-group-item"><?php echo $ownerEmail ?></li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Répondre à</li>
                <li class="list-group-item"><?php echo $replyTo ?></li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Les abonnés</li>
                <li class="list-group-item"><a href="/<?php echo $name ?>/subscriber"><?php echo $nbSubscribers ?> abonné<?php echo ($nbSubscribers > 1) ? 's' : '' ?></a> (depuis le <?php echo $nbSubscribersUpdateDate->format('d/m/Y') ?>)</li>
            </ul>
            
            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Les modérateurs</li>
                <li class="list-group-item"><a href="/<?php echo $name ?>/moderator"><?php echo $nbModerators ?> modérateur<?php echo ($nbModerators > 1) ? 's' : '' ?></a></li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Modération des messages</li>
                <li class="list-group-item">
                    <?php
                        if($moderatorMessage)
                            echo "Le propriétaire ou un modérateur doit approuver l'e-mail envoyé à la mailing-list.";
                        else if($usersPostOnly)
                            echo "Restreint l'envoi d'e-mails sur la mailing list aux seuls abonnés de celle-ci.";
                        else
                            echo "Pas de modération des messages."
                    ?>
                </li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Le propriétaire, ou un modérateur, doit approuver les inscriptions à la mailing-list ?</li>
                <li class="list-group-item"><?php echo $subscribeByModerator ? 'Oui' : 'Non' ?></li>
            </ul>

            <a href="/<?php echo $name ?>/delete" class="btn btn-danger">Supprimer définitivement la mailing list</a>
        </div>
    </div>
</div>