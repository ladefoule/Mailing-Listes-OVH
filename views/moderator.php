<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header p-3">
            <span class='icon-list-bullet'></span> Les modérateurs
        </div>
        <div class="card-body">
            <div class="col-12 px-0">
                <table class="table table-striped text-center">
                    <thead>
                        <th class="border-0">#</th>
                        <th class="border-0 text-left">Email</th>
                        <th class="border-0">Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($emails as $i => $email) {
                            echo '<tr>';
                                echo "<td>" . ($i+1) . "</td>";
                                echo "<td class='text-left'>" . $email . "</td>";
                                echo "<td>
                                        <a class='btn-sm btn-danger' href='/$name/moderator/$email/delete' title='Supprimer le modérateur'><span class='icon-trash-empty'></span></a>
                                    </td>";
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="col-12 px-0">
                <a href="<?php echo '/'.$name.'/moderator/create' ?>" class="btn-sm btn-primary text-decoration-none"><span class='icon-list-add'></span> Ajouter un modérateur</a>
            </div>
        </div>
    </div>
</div>