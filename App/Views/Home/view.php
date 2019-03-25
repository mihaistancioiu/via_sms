<?php
require(dirname(__DIR__) . '\header.php');
?>

<?php
if (empty($content)) {
    echo '
        <div> 
           <div class="list">
                <div class="errors">Error uploading file! </div>
                <br />
                <a href="./">Return to homepage</a>
            </div>
        </div>
        ';

    return;
}
?>
    <form action="save" method="post">
        <table>
            <tr>
                <th colspan="4" style="border-right: solid 3px #000">Exists</th>
                <th colspan="5">Uploaded</th>
            </tr>
            <?php
            foreach ($content as $row) {
                echo '<tr >';
                if ($row['is_duplicate']) {
                    echo '
                            <td>' . $row['date'] . '</td>
                            <td>' . $row['name'] . ' ' . $row['surname'] . '</td>
                            <td>' . $row['amount'] . '</td>
                            <td style="border-right: solid 3px #000">' . $row['account'] . '</td>
                            <td>' . $row['date'] . '</td>
                            <td>' . $row['name'] . ' ' . $row['surname'] . '</td>
                            <td>' . $row['amount'] . '</td>
                            <td>' . $row['account'] . '</td>
                            <td>&nbsp;</td>
                            </tr>
                    ';
                } else {
                    echo '
                            <td colspan="4" class="not_exists">Not Exists</td>
                            <td>' . $row['date'] . '</td>
                            <td>' . $row['name'] . ' ' . $row['surname'] . '</td>
                            <td>' . $row['amount'] . '</td>
                            <td>' . $row['account'] . '</td>
                            <td><input type="checkbox" name="add[]" value=\'' . serialize($row) . '\'></td>
                    ';
                }
                echo '</tr>';
            }
            ?>
            <tr class="last">
                <td colspan="9">
                    <table>
                        <tr class="last">
                            <td style="text-align: right; width: 85%;"><input type="checkbox" class="selectall">
                                Check/Uncheck All
                            </td>
                            <td style="text-align: center;"><input type="submit" name="submit" value="Save" disabled="disabled"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>

    <script type="text/javascript">
        $('.selectall').change(function () {
            if ($(this).prop('checked')) {
                $('input:checkbox').prop('checked', true);
            } else {
                $('input:checkbox').prop('checked', false);
            }
        });

        var checkBoxes = $('input:checkbox');
        checkBoxes.change(function () {
            $('input:submit').prop('disabled', checkBoxes.filter(':checked').length < 1);
        });
    </script>
<?php
require(dirname(__DIR__) . '\footer.php')
?>