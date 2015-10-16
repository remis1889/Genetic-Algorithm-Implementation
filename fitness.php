<?php

    $rsize = $_POST['r_size'];
    $max_size = $_POST['max_size'];
?>    

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>GA Implementation</title>
    </head>
    <body>
        
        <table border="1" cellpadding="0" cellspacing="0" width="25%" align="center">
            <form name="fitness_form" method="post" action="generation.php">
                <tr>
                    <td colspan="3" align="center" height="90" style="font-size: 28px; font-weight: bold;">
                        Relevance Scores
                    </td>
                </tr>
                <tr>
                    <td height="30" align="center" colspan="3"> Enter relevance score for each result </td>
                </tr>
                <?php
                for($i=0;$i<$rsize;$i++) {
                ?>
                <tr>
                    <td height="30" align="center" colspan="2"><?php echo $i+1; ?></td>
                    <td colspan="2" align="center" width="50%"><input name="relevance[]" type="text" style="width:35px;" />
                        <input name="rsize" type="hidden" value="<?php echo $rsize; ?>"/>
                        <input name="gen" type="hidden" value="0"/>
                        <input name="max_size" type="hidden" value="<?php echo $max_size; ?>"/>
                    </td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="3" height="50" align="center"><input type="submit" value="Submit" name="psize_submit" id="psize_submit" /></td>
                </tr>
           </form>
        </table>
    </body>
</html>


