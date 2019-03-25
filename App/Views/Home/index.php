<?php
require(dirname(__DIR__) . '\header.php');
?>

    <div>
        <div class="errors">
            <?php
            if (!empty($errors)) {
                echo implode('<br />', $errors);
            }
            ?>
        </div>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="bankId">Bank Name:</label>
            <select required name="bankId">
                <option value="">-</option>
                <?php
                $selected = '';
                foreach ($banksInfo as $key => $bankInfo) {
                    if ($bankInfo['id'] == $selectedBank) {
                        $selected = 'selected="selected"';
                    }
                    echo '<option ' . $selected . ' value="' . $bankInfo['id'] . '">' . $bankInfo['name'] . '(' . $bankInfo['statement_format'] . ')</option>';
                    $selected = '';
                }
                ?>
            </select>

            <label for="statement"> Choose a file to upload:</label>
            <input required type="file" name="statement"/>

            <input type="submit" name="submit" value="Submit"/>
        </form>
    </div>
<?php
require(dirname(__DIR__) . '\footer.php')
?>