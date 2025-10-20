<?php
require_once "../../includes/auth_check.php";
require_role('admin');
require_once "../../config/db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM users WHERE id_user=$id");
}

header("Location: user.php");
exit;