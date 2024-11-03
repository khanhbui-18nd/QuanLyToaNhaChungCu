<?php
session_start();
include("../config.php");
if (isset($_SESSION['objLogin'])) {
    if (isset($_POST['token']) && $_POST['token'] == 'getunitinfo') {
        $html = '<option value="">--Select Unit--</option>';
        if (isset($_POST['floor_no']) && (int)$_POST['floor_no'] > 0) {
            $unit_no = '';
            $result = $conn->query("SELECT * FROM tbl_add_unit WHERE floor_no = '" . (int)$_POST['floor_no'] . "' AND status = 0 ORDER BY unit_no ASC");
            while ($rows = $result->fetch_assoc()) {
                $html .= '<option value="' . $rows['uid'] . '">' . $rows['unit_no'] . '</option>';
            }
            echo $html;
            die();
        }
        echo '';
        die();
    } else if (isset($_POST['token']) && $_POST['token'] == 'getbookedunit') {
        $html = '<option value="">--Select Unit--</option>';
        if (isset($_POST['floor_no']) && (int)$_POST['floor_no'] > 0) {
            $unit_no = '';
            $result = $conn->query("SELECT * FROM tbl_add_unit WHERE floor_no = '" . (int)$_POST['floor_no'] . "' AND status = 1 ORDER BY unit_no ASC");
            while ($rows = $result->fetch_assoc()) {
                $html .= '<option value="' . $rows['uid'] . '">' . $rows['unit_no'] . '</option>';
            }
            echo $html;
            die();
        }
        echo '';
        die();
    } else if (isset($_POST['token']) && $_POST['token'] == 'getunitinforeport') {
        $html = '<option value="">--Select Unit--</option>';
        if (isset($_POST['floor_no']) && (int)$_POST['floor_no'] > 0) {
            $unit_no = '';
            $result = $conn->query("SELECT * FROM tbl_add_unit WHERE floor_no = '" . (int)$_POST['floor_no'] . "' ORDER BY unit_no ASC");
            while ($rows = $result->fetch_assoc()) {
                $html .= '<option value="' . $rows['uid'] . '">' . $rows['unit_no'] . '</option>';
            }
            echo $html;
            die();
        }
        echo '';
        die();
    } else if (isset($_POST['token']) && $_POST['token'] == 'getRentInfo') {
        $html = array(
            'rid' => '0',
            'name' => '',
            'fair' => '0.00'
        );
        if (isset($_POST['floor_id']) && (int)$_POST['floor_id'] > 0 && isset($_POST['unit_id']) && (int)$_POST['unit_id'] > 0) {
            $result = $conn->query("SELECT * FROM tbl_add_rent WHERE r_floor_no = '" . (int)$_POST['floor_id'] . "' AND r_unit_no = '" . (int)$_POST['unit_id'] . "' AND r_status = 1");
            if ($rows = $result->fetch_assoc()) {
                $html = array(
                    'rid' => $rows['rid'],
                    'name' => $rows['r_name'],
                    'fair' => $rows['r_rent_pm']
                );
            }
        }
        echo json_encode($html);
        die();
    } else if (isset($_POST['token']) && $_POST['token'] == 'getOwnerInfo') {
        $html = array(
            'ownid' => '0',
            'name' => ''
        );
        if (isset($_POST['unit_id']) && (int)$_POST['unit_id'] > 0) {
            $result = $conn->query("SELECT * FROM tbl_add_owner_unit_relation ur INNER JOIN tbl_add_owner ao ON ao.ownid = ur.owner_id WHERE ur.unit_id = '" . (int)$_POST['unit_id'] . "'");
            if ($rows = $result->fetch_assoc()) {
                $html = array(
                    'ownid' => $rows['owner_id'],
                    'name' => $rows['o_name']
                );
            }
        }
        echo json_encode($html);
        die();
    } else if (isset($_POST['token']) && $_POST['token'] == 'getDesgInfo') {
        $html = '';
        if (isset($_POST['emp_id']) && (int)$_POST['emp_id'] > 0) {
            $result_emp = $conn->query("SELECT *, mt.member_type FROM tbl_add_employee e INNER JOIN tbl_add_member_type mt ON mt.member_id = e.e_designation WHERE eid = '" . (int)$_POST['emp_id'] . "'");
            if ($row_emp = $result_emp->fetch_assoc()) {
                $html = $row_emp['member_type'];
            }
        }
        echo $html;
        die();
    }
} else {
    echo '-99';
    die();
}
?>
