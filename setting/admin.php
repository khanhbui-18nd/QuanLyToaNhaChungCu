<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_language.php');

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$name = '';
$email = '';
$password = '';
$branch_id = '';
$bname = '';
$button_text = "Save Information";
$form_url = WEB_URL . "setting/admin.php";
$hval = 0;

if (isset($_POST['txtAdminName'])) {
    $name = $_POST['txtAdminName'];
    $email = $_POST['txtAdminEmail'];
    $password = $_POST['txtAdminPassword'];
    $branch_id = $_POST['ddlBranch'];

    if ($_POST['hdnSpid'] == '0') {        
        $stmt = $conn->prepare("INSERT INTO `tbl_add_admin`(`name`, `email`, `password`, `branch_id`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $password, $branch_id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: " . WEB_URL . 'setting/admin.php?m=add');
        exit();
    } else {        
        $stmt = $conn->prepare("UPDATE `tbl_add_admin` SET name = ?, email = ?, password = ?, branch_id = ? WHERE aid = ?");
        $stmt->bind_param("sssii", $name, $email, $password, $branch_id, $_POST['hdnSpid']);
        $stmt->execute();
        $stmt->close();
        
        header("Location: " . WEB_URL . 'setting/admin.php?m=up');
        exit();
    }
}

if (isset($_GET['spid']) && $_GET['spid'] != '') {
    $stmt = $conn->prepare("SELECT *, a.added_date as p_added_date, b.branch_name FROM tbl_add_admin a INNER JOIN tblbranch b ON b.branch_id = a.branch_id WHERE a.aid = ?");
    $stmt->bind_param("i", $_GET['spid']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $email = $row['email'];
        $password = $row['password'];
        $branch_id = $row['branch_id'];
        $bname = $row['branch_name'];
        $button_text = "Update Information";
        $form_url = WEB_URL . "setting/admin.php?id=" . $_GET['spid'];
        $hval = $row['aid'];
    }
    $stmt->close();
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> Admin Setup </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo WEB_URL?>setting/setting.php">Settings</a></li>
        <li class="active">Admin Setup</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;"> 
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>setting/setting.php" data-original-title="Back"><i class="fa fa-reply"></i></a> 
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Admin Setup Form</h3>
                </div>
                <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="txtAdminName">Name :</label>
                            <input type="text" name="txtAdminName" id="txtAdminName" value="<?php echo $name; ?>" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="txtAdminEmail">Email :</label>
                            <input type="text" name="txtAdminEmail" id="txtAdminEmail" value="<?php echo $email; ?>" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="txtAdminPassword">Password :</label>
                            <input type="password" name="txtAdminPassword" value="<?php echo $password; ?>" id="txtAdminPassword" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="ddlBranch">Branch :</label>
                            <select name="ddlBranch" id="ddlBranch" class="form-control">
                                <option value="">--Select--</option>
                                <?php
                                $result_page = $conn->query("SELECT * FROM tblbranch ORDER BY branch_name ASC");
                                while ($row_page = $result_page->fetch_assoc()) {
                                    $selected = ($branch_id == $row_page['branch_id']) ? 'selected="selected"' : '';
                                    echo '<option value="' . $row_page['branch_id'] . '" ' . $selected . '>' . $row_page['branch_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group pull-right">
                            <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
                            &nbsp;
                            <input type="reset" onClick="javascript:window.location.href='<?php echo WEB_URL; ?>setting/admin.php';" name="btnReset" id="btnReset" value="Reset" class="btn btn-primary"/>
                        </div>
                    </div>
                    <input type="hidden" name="hdnSpid" value="<?php echo $hval; ?>"/>
                </form>
                <h4 style="text-align:center; color:red;">Please Reset First Before Insert</h4>

                <?php
                $delinfo = 'none';
                $addinfo = 'none';
                $msg = "";

                if (isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0) {
                    $stmt = $conn->prepare("DELETE FROM `tbl_add_admin` WHERE aid = ?");
                    $stmt->bind_param("i", $_GET['delid']);
                    $stmt->execute();
                    $stmt->close();
                    $delinfo = 'block';
                }

                if (isset($_GET['m']) && $_GET['m'] == 'add') {
                    $addinfo = 'block';
                    $msg = "Added Admin Information Successfully";
                }
                if (isset($_GET['m']) && $_GET['m'] == 'up') {
                    $addinfo = 'block';
                    $msg = "Updated Admin Information Successfully";
                }
                ?>

                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                                <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
                                Deleted Admin Information Successfully. 
                            </div>
                            <div class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                <?php echo $msg; ?> 
                            </div>
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Admin List</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table sakotable table-bordered table-striped dt-responsive">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Branch</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = $conn->query("SELECT *, a.added_date as p_addted_date, b.branch_name FROM tbl_add_admin a INNER JOIN tblbranch b ON b.branch_id = a.branch_id ORDER BY a.aid DESC");
                                            while ($row = $result->fetch_assoc()) {
                                                $phpdate = strtotime($row['p_addted_date']);
                                                $date = date('d/m/Y H:i:s', $phpdate); ?>
                                                <tr>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td><?php echo $row['branch_name']; ?></td>
                                                    <td>
                                                        <a href="<?php echo WEB_URL; ?>setting/admin.php?spid=<?php echo $row['aid']; ?>" title="Edit" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                                                        <a href="<?php echo WEB_URL; ?>setting/admin.php?delid=<?php echo $row['aid']; ?>" title="Delete" class="btn btn-xs btn-danger" onClick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

<?php include('../footer.php'); ?>
