<?php 
include('../header.php');
if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}

include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_unit_list.php');

$delinfo = 'none';
$addinfo = 'none';
$msg = "";

// Kết nối đến cơ sở dữ liệu
include('../config.php'); // Bao gồm file config để sử dụng biến $conn

if (isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0) {
    $stmt = $conn->prepare("DELETE FROM `tbl_add_unit` WHERE uid = ?");
    $stmt->bind_param("i", $_GET['id']); // Liên kết tham số
    $stmt->execute();
    $delinfo = 'block';
}

if (isset($_GET['m']) && $_GET['m'] == 'add') {
    $addinfo = 'block';
    $msg = $_data['add_unit_successfully'];
}
if (isset($_GET['m']) && $_GET['m'] == 'up') {
    $addinfo = 'block';
    $msg = $_data['update_unit_successfully'];
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $_data['unit_list_title']; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><?php echo $_data['add_new_unit_information_breadcam']; ?></li>
        <li class="active"><?php echo $_data['unit_list_title']; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Full Width boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                <h4><i class="icon fa fa-ban"></i> <?php echo $_data['delete_text']; ?>!</h4>
                <?php echo $_data['delete_unit_information']; ?>
            </div>
            <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                <h4><i class="icon fa fa-check"></i><?php echo $_data['success']; ?>!</h4>
                <?php echo $msg; ?>
            </div>
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>unit/addunit.php" data-original-title="<?php echo $_data['add_unit']; ?>"><i class="fa fa-plus"></i></a>
                <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['home_breadcam']; ?>"><i class="fa fa-dashboard"></i></a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['unit_list_title']; ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table sakotable table-bordered table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th><?php echo $_data['floor_no']; ?></th>
                                <th><?php echo $_data['unit_no']; ?></th>
                                <th><?php echo $_data['action_text']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT f.floor_no, u.unit_no, u.uid FROM tbl_add_unit u INNER JOIN tbl_add_floor f ON f.fid = u.floor_no WHERE u.branch_id = ? ORDER BY u.uid ASC");
                            $branch_id = (int)$_SESSION['objLogin']['branch_id'];
                            $stmt->bind_param("i", $branch_id); // Liên kết tham số
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['floor_no']; ?></td>
                                    <td><?php echo $row['unit_no']; ?></td>
                                    <td>
                                        <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="$('#nurse_view_<?php echo $row['uid']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text']; ?>"><i class="fa fa-eye"></i></a>
                                        <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>unit/addunit.php?id=<?php echo $row['uid']; ?>" data-original-title="<?php echo $_data['edit_text']; ?>"><i class="fa fa-pencil"></i></a>
                                        <a class="btn btn-danger" data-toggle="tooltip" onclick="deleteUnit(<?php echo $row['uid']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text']; ?>"><i class="fa fa-trash-o"></i></a>

                                        <div id="nurse_view_<?php echo $row['uid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header orange_header">
                                                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                                                        <h3 class="modal-title"><?php echo $_data['unit_details']; ?></h3>
                                                    </div>
                                                    <div class="modal-body model_view" align="center">&nbsp;
                                                        <div>&nbsp;</div>
                                                        <div class="model_title"><?php echo $row['unit_no']; ?></div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3 style="text-decoration:underline;"><?php echo $_data['details_info']; ?></h3>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <b><?php echo $_data['floor_no']; ?> :</b> <?php echo $row['floor_no']; ?><br/>
                                                                <b><?php echo $_data['unit_no']; ?>:</b> <?php echo $row['unit_no']; ?><br/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo $_data['floor_no']; ?></th>
                                <th><?php echo $_data['unit_no']; ?></th>
                                <th><?php echo $_data['action_text']; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <script type="text/javascript">
        function deleteUnit(Id) {
            var iAnswer = confirm("Are you sure you want to delete this Unit?");
            if (iAnswer) {
                window.location = '<?php echo WEB_URL; ?>unit/unitlist.php?id=' + Id;
            }
        }

        $(document).ready(function() {
            setTimeout(function() {
                $("#me").hide(300);
                $("#you").hide(300);
            }, 3000);
        });
    </script>
    <?php include('../footer.php'); ?>
