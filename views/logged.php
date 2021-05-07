<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header p-3">
            Gestion des mailing lists
        </div>
        <div class="card-body">
            <div class="col-12 px-0">
                <table class="table table-striped text-center">
                    <thead>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Mailing List</th>
                        <th>Abonnés</th>
                        <th>Modérateurs</th>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($mailingLists as $i => $mailingList) {
                            echo '<tr>';
                                echo "<td>" . ($i+1) . "</td>";
                                echo "<td class='text-left'>" . $mailingList . "@" . $domain . "</td>";
                                echo "<td>
                                            <a class='btn-sm btn-info' href='/$mailingList/update' title='Modifier'><span class='icon-pencil'></span></a>
                                            <a class='btn-sm btn-info' href='/$mailingList/options' title='Modifier les options'><span class='icon-check'></span></a>
                                            <a class='btn-sm btn-danger' href='/$mailingList/delete'><span class='icon-trash-empty'></span></a>
                                    </td>";
                                echo "<td>
                                        <a class='btn-sm btn-info' href='/$mailingList/subscriber'><span class='icon-list-bullet'></span></a>
                                        <a class='btn-sm btn-primary' href='/$mailingList/subscriber/create'><span class='icon-user-plus'></span></a>
                                </td>";
                                echo "<td>
                                        <a class='btn-sm btn-info' href='/$mailingList/moderator'><span class='icon-list-bullet'></span></a>
                                        <a class='btn-sm btn-primary' href='/$mailingList/moderator/create'><span class='icon-user-plus'></span></a>
                                </td>";
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <a href="/create" class="btn-sm btn-primary"><span class='icon-list-add'></span> Nouvelle liste</a>
            </div>
        </div>
    </div>
</div>