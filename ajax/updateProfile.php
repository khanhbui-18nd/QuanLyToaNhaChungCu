<?php
include("../config.php");
session_start();
if (isset($_SESSION['objLogin'])) {
    if (isset($_POST['txtProfileName'])) {
        $name = $_POST['txtProfileName'];
        $email = $_POST['txtProfileEmail'];
        $password = $_POST['txtProfilePassword'];
        $sql = '';

        if ($_SESSION['login_type'] == '1') {
            $sql = "UPDATE `tbl_add_admin` SET name = ?, email = ?, password = ? WHERE aid = ?";
        } else if ($_SESSION['login_type'] == '2') {
            $sql = "UPDATE `tbl_add_owner` SET o_name = ?, o_email = ?, o_password = ? WHERE ownid = ?";
        } else if ($_SESSION['login_type'] == '3') {
            $sql = "UPDATE `tbl_add_employee` SET e_name = ?, e_email = ?, e_password = ? WHERE eid = ?";
        } else if ($_SESSION['login_type'] == '4') {
            $sql = "UPDATE `tbl_add_rent` SET r_name = ?, r_email = ?, r_password = ? WHERE rid = ?";
        } else if ($_SESSION['login_type'] == '5') {
            $sql = "UPDATE `tblsuper_admin` SET name = ?, email = ?, password = ? WHERE user_id = ?";
        }

        // Chuẩn bị và thực thi câu lệnh
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $name, $email, $password, $_POST['user_id']);
            $stmt->execute();
            $stmt->close();
            echo "1";
            die();
        } else {
            echo '-99'; // Lỗi chuẩn bị câu lệnh
        }
    } else {
        echo '-99';
    }
} else {
    echo '-99';
    die();
}
?>
