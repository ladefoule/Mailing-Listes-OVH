<div class="col-lg-9 p-0">
    <div class="card">
        <div class="card-header p-3">
            <span class='icon-mail'></span> Liste complète des mailing listes
        </div>
        <div class="card-body">
            <!-- <div class="alert alert-info">Les différentes actions concernant les mailing lists (création d'une liste ou ajout d'un abonné par exemple) peuvent mettre plusieurs secondes avant d'être traitées par OVH. Si vous recevez un message de succès de votre requête, inutile donc de la renouveler même si le résultat n'est pas de suite visible.</div> -->
            <div class="col-12 px-0">
                <table class="table table-striped text-center">
                    <thead>
                        <th class="border-0">#</th>
                        <th class="border-0 text-left">mailing list</th>
                        <th class="border-0 d-none d-md-table-cell">abonnés</th>
                        <th class="border-0 d-none d-md-table-cell">modérateurs</th>
                    </thead>
                    <tbody>
                    <?php
                        $i = 1;
                        foreach ($mailingLists as $mailingList) {
                            $name = $mailingList['name'];
                            $nbSubscribers = $mailingList['nb_subscribers'];
                            $nbModerators = $mailingList['nb_moderators'];
                            echo '<tr>';
                                echo "<td>" . ($i++) . "</td>";
                                echo "<td class='text-left'>$name</td>";
                                echo "<td class='d-none d-md-table-cell'>
                                        $nbSubscribers
                                </td>";
                                echo "<td class='d-none d-md-table-cell'>
                                        $nbModerators
                                </td>";
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="col-12 px-0">
                <a href="/create" class="btn-sm btn-primary text-decoration-none"><span class='icon-list-add'></span> Nouvelle liste</a>
            </div>
        </div>
    </div>
</div>