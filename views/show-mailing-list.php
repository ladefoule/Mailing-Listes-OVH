<?php 
    $name = $mailingList['name'];
    $replyTo = $mailingList['replyTo'];
    $ownerEmail = $mailingList['ownerEmail'];
    $nbSubscribers = $mailingList['nbSubscribers'];
    $subscribeByModerator = $mailingList['subscribeByModerator'];
    $usersPostOnly = $mailingList['usersPostOnly'];
    $moderatorMessage = $mailingList['moderatorMessage'];
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
        <?php
            // var_dump($mailingList);exit();
        ?>
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
                <li class="list-group-item"><a href="/<?php echo $name ?>/subscriber"><?php echo $nbSubscribers ?> abonnés</a> (mis à jour le )</li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Le propriétaire ou un modérateur doit approuver l'e-mail envoyé à la mailing-list ?</li>
                <li class="list-group-item"><?php echo $moderatorMessage ? 'Oui' : 'Non' ?></li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Le propriétaire, ou un modérateur, doit approuver les inscriptions à la mailing-list ?</li>
                <li class="list-group-item"><?php echo $subscribeByModerator ? 'Oui' : 'Non' ?></li>
            </ul>

            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">Restreint l'envoi d'e-mails sur la mailing list aux seules abonnés de celle-ci ?</li>
                <li class="list-group-item"><?php echo $usersPostOnly ? 'Oui' : 'Non' ?></li>
            </ul>
        </div>
    </div>
</div>