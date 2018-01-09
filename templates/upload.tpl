<h1>Upload users: </h1>
<br />
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <td><label for="profile_pic">Choose file CSV to upload:</label></td>
    <td><input type="file" id="profile_pic" name="users_file" accept=".text/csv"></td>
    <button>Submit</button>
    <br>
    <?php echo 'Users insert: '.$itogInsert; ?>
    <br>
    <?php echo 'Users no valid: '.$itogNoValid; ?>
    <br>
</form>