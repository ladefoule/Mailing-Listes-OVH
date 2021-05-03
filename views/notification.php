<?php
    // Personnalisation du message
    if(isset($message) && isset($class)){
        ?>
        <div class="col-12 p-0 d-flex justify-content-center">
            <div class="col-md-6 alert alert-<?php echo $class ?> alert-block">    
                <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong><?php echo $message ?></strong>
            </div>
        </div>
        <?php 
    }
?>