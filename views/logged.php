<div class="col-lg-9 p-0">
    <div class="card">
        <div class="card-header p-3">
            <span class='icon-mail'></span> Gestion des mailing lists
        </div>
        <div class="card-body">
            <div class="col-12 px-0">
                <table class="table table-striped text-center">
                    <thead>
                        <th class="border-0">#</th>
                        <th class="border-0 text-left">Liste</th>
                        <th class="border-0">Actions</th>
                        <th class="border-0">Abonnés</th>
                        <th class="border-0">Modérateurs</th>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($mailingLists as $i => $mailingList) {
                            echo '<tr>';
                                echo "<td>" . ($i+1) . "</td>";
                                echo "<td class='text-left'><a href='/$mailingList/show'>" . $mailingList . "@" . $domain . "</a></td>";
                                echo "<td>
                                            <a class='btn-sm btn-info' href='/$mailingList/update' title='Modifier la mailingList'><span class='icon-pencil'></span></a>
                                            <a class='btn-sm btn-info' href='/$mailingList/options' title='Modifier les options de la mailinglist'><span class='icon-check'></span></a>
                                            <a class='btn-sm btn-danger' href='/$mailingList/delete' title='Supprimer la mailingList'><span class='icon-trash-empty'></span></a>
                                    </td>";
                                echo "<td>
                                        <a class='btn-sm btn-info' href='/$mailingList/subscriber' title='Liste des abonnés'><span class='icon-list-bullet'></span></a>
                                        <a class='btn-sm btn-primary' href='/$mailingList/subscriber/create' title='Ajouter un abonné'><span class='icon-user-plus'></span></a>
                                </td>";
                                echo "<td>
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