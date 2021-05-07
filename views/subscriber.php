<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header p-3">
            Les abonnés
        </div>
        <div class="card-body">
            <div class="col-12 px-0">
                <table class="table table-striped text-center">
                    <thead>
                        <th>#</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($emails as $i => $email) {
                            echo '<tr>';
                                echo "<td>" . ($i+1) . "</td>";
                                echo "<td class='text-left'>" . $email . "</td>";
                                echo "<td>
                                        <a class='btn-sm btn-danger' href='/$name/subscriber/$email/delete' title=\"Supprimer l'abonné\"><span class='icon-trash-empty'></span></a>
                                    </td>";
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <a href="<?php echo '/'.$name.'/subscriber/create' ?>" class="btn-sm btn-primary"><span class='icon-list-add'></span> Nouvel abonné</a>
            </div>
        </div>
    </div>
</div>