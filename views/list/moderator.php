<div class="col-lg-9 p-0">
    <div class="card">
        <div class="card-header p-3">
            <span class='icon-list-bullet'></span> Les modérateurs <a href="/<?php echo $name ?>/moderator/create" class="btn-sm btn-primary text-decoration-none"><span class='icon-plus'></span></a>
        </div>
        <div class="card-body">
            <h1 class="h5 text-center"><em><?php echo $name.'@'.$domain ?></em></h1>

            <div class="col-12 px-0">
                <table class="table table-striped text-center mt-4">
                    <!-- <thead>
                        <th class="border-0">#</th>
                        <th class="border-0 text-left">email</th>
                        <th class="border-0">supprimer</th>
                    </thead> -->
                    <tbody>
                    <?php
                        $i = 1;
                        foreach ($emails as $email) {
                            echo '<tr>';
                                echo "<td>" . ($i++) . "</td>";
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