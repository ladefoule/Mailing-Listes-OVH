<div class="col-lg-8 p-0">
    <div class="card">
        <div class="card-header p-3">
            <span class='icon-list-bullet'></span> Les abonnés <a href="/<?php echo $name ?>/subscriber/create" class="btn-sm btn-primary text-decoration-none"><span class='icon-plus'></span></a>
        </div>
        <div class="card-body">
            <h1 class="h5 text-center"><em><?php echo $name.'@'.$domain ?></em></h1>

            <div class="col-12 px-0 mt-4">
                <table class="table table-striped text-center">
                    <!-- <thead>
                        <th class="border-0">#</th>
                        <th class="border-0">email</th>
                        <th class="border-0">actions</th>
                    </thead> -->
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

            <div class="col-12 px-0">
                <a href="/<?php echo $name ?>/subscriber/create" class="btn-sm btn-primary text-decoration-none"><span class='icon-list-add'></span> Nouvel abonné</a>
            </div>
        </div>
    </div>
</div>