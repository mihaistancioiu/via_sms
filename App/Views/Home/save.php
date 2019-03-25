<?php
require(dirname(__DIR__) . '\header.php');
?>


    <div>
        <div class="list">
            <?php
            if ($status) {
                echo '<div class="success">Data saved successfully!</div>';
            } else {
                echo '<div class="errors">An error occurred when trying to save data!</div>';
            }
            ?>
            <a href="./">Return to homepage</a>

        </div>
    </div>

<?php
require(dirname(__DIR__) . '\footer.php')
?>