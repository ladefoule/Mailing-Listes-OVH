<div class="col-lg-9 p-0">
    <div class="card">
        <div class="card-header p-3">
            <span class='icon-mail'></span> Gestion des mailing lists
        </div>
        <div class="card-body">
            <!-- <div class="alert alert-info">Les différentes actions concernant les mailing lists (création d'une liste ou ajout d'un abonné par exemple) peuvent mettre plusieurs secondes avant d'être traitées par OVH. Si vous recevez un message de succès de votre requête, inutile donc de la renouveler même si le résultat n'est pas de suite visible.</div> -->
            <div class="col-12 px-0">
                <table class="table table-striped text-center">
                    <thead>
                        <th class="border-0">#</th>
                        <th class="border-0 text-left">liste</th>
                        <th class="border-0">actions</th>
                        <th class="border-0 d-none d-md-table-cell">abonnés</th>
                        <th class="border-0 d-none d-md-table-cell">modérateurs</th>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($mailingLists as $i => $mailingList) {
                            echo '<tr>';
                                echo "<td>" . ($i+1) . "</td>";
                                echo "<td class='text-left'><a href='/$mailingList/show'>" . $mailingList . "@" . $domain . "</a></td>";
                                echo "<td>
                                            <a class='btn-sm btn-info' href='/$mailingList/update' title='Modifier'><span class='icon-pencil'></span></a>
                                            <a class='btn-sm btn-info' href='/$mailingList/options' title='Modifier les options'><span class='icon-check'></span></a>
                                        </td>";
                                        // <a class='btn-sm btn-danger' href='/$mailingList/delete' title='Supprimer'><span class='icon-trash-empty'></span></a>
                                echo "<td class='d-none d-md-table-cell'>
                                        <a class='btn-sm btn-info' href='/$mailingList/subscriber' title='Liste des abonnés'><span class='icon-list-bullet'></span></a>
                                        <a class='btn-sm btn-primary' href='/$mailingList/subscriber/create' title='Ajouter un abonné'><span class='icon-user-plus'></span></a>
                                </td>";
                                echo "<td class='d-none d-md-table-cell'>
                                        <a class='btn-sm btn-info' href='/$mailingList/moderator' title='Liste des modérateurs'><span class='icon-list-bullet'></span></a>
                                        <a class='btn-sm btn-primary' href='/$mailingList/moderator/create' title='Ajouter un modérateur'><span class='icon-user-plus'></span></a>
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