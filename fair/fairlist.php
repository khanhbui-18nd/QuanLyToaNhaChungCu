<?php 
include('../header.php');
if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
?>

<?php
include '../config.php';
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_fare_list.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";

if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
    $stmt = $conn->prepare("DELETE FROM `tbl_add_fair` WHERE f_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $stmt->close();
    $delinfo = 'block';
}

if(isset($_GET['m']) && $_GET['m'] == 'add'){
    $addinfo = 'block';
    $msg = $_data['added_rent_successfully'];
}

if(isset($_GET['m']) && $_GET['m'] == 'up'){
    $addinfo = 'block';
    $msg = $_data['update_rent_successfully'];
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $_data['rent_list'];?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
        <li class="active"><?php echo $_data['add_new_rent_information_breadcam'];?></li>
        <li class="active"><?php echo $_data['rent_list'];?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                <h4><i class="icon fa fa-ban"></i><?php echo $_data['delete_text'];?> !</h4>
                <?php echo $_data['delete_rent_information'];?> 
            </div>
            <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                <h4><i class="icon fa fa-check"></i><?php echo $_data['success'];?> !</h4>
                <?php echo $msg; ?> 
            </div>
            <div align="right" style="margin-bottom:1%;"> 
                <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>fair/addfair.php" data-original-title="<?php echo $_data['add_new_rent_breadcam'];?>"><i class="fa fa-plus"></i></a> 
                <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['home_breadcam'];?>"><i class="fa fa-dashboard"></i></a> 
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['rent_list'];?></h3>
                </div>
                <div class="box-body">
                    <table class="table sakotable table-bordered table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th><?php echo $_data['image'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_6'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_16'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_1'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_3'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_17'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_18'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_7'];?></th>
                                <th><?php echo $_data['add_new_form_field_text_14'];?></th>
                                <th><?php echo $_data['action_text'];?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $stmt = $conn->prepare("SELECT *, ar.image AS r_image, ar.r_name, fl.floor_no AS fl_floor, u.unit_no AS u_unit, m.month_name 
                                                FROM tbl_add_fair f 
                                                INNER JOIN tbl_add_floor fl ON fl.fid = f.floor_no 
                                                INNER JOIN tbl_add_unit u ON u.uid = f.unit_no 
                                                INNER JOIN tbl_add_month_setup m ON m.m_id = f.month_id 
                                                INNER JOIN tbl_add_rent ar ON ar.rid = f.rid 
                                                WHERE f.type = 'Rented' AND f.branch_id = ? 
                                                ORDER BY f.f_id DESC");
                        $stmt->bind_param("i", $_SESSION['objLogin']['branch_id']);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while($row = $result->fetch_assoc()){
                            $r_image = WEB_URL . 'img/no_image.jpg';    
                            if(file_exists(ROOT_PATH . '/img/upload/' . $row['r_image']) && $row['r_image'] != ''){
                                $r_image = WEB_URL . 'img/upload/' . $row['r_image'];
                            }
                        ?>
                            <tr>
                                <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $r_image; ?>" /></td>
                                <td><?php echo $row['r_name']; ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><?php echo $row['fl_floor']; ?></td>
                                <td><?php echo $row['u_unit']; ?></td>
                                <td><?php echo $row['month_name']; ?></td>
                                <td><?php echo $row['xyear']; ?></td>
                                <td><?php echo ($currency_position == 'left') ? $global_currency.$row['rent'] : $row['rent'].$global_currency; ?></td>
                                <td><?php echo ($currency_position == 'left') ? $global_currency.$row['total_rent'] : $row['total_rent'].$global_currency; ?></td>
                                <td>
                                    <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['f_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>fair/addfair.php?id=<?php echo $row['f_id']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" data-toggle="tooltip" href="javascript:;" onclick="if(confirm('Delete?')) { window.location = '<?php echo WEB_URL; ?>fair/fairlist.php?id=<?= $row['f_id'] ?>'; }" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a>

                                    <div id="nurse_view_<?php echo $row['f_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header orange_header">
                                                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                                                    <h3 class="modal-title"><?php echo $_data['fare_details'];?></h3>
                                                </div>
                                                <div class="modal-body model_view" align="center">&nbsp;
                                                    <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $r_image; ?>" /></div>
                                                    <div class="model_title"><?php echo $row['r_name']; ?></div>
                                                </div>
                                                <div class="modal-body">
                                                    <h3 style="text-decoration:underline;text-align:left"><?php echo $_data['details_information'];?></h3><br/>
                                                    <div class="row">
                                                        <div class="col-xs-6"> 
                                                            <b><?php echo $_data['add_new_form_field_text_6'];?> :</b> <?php echo $row['r_name']; ?><br/>
                                                            <b><?php echo $_data['add_new_form_field_text_1'];?> :</b> <?php echo $row['fl_floor']; ?><br/>
                                                            <b><?php echo $_data['add_new_form_field_text_3'];?> :</b> <?php echo $row['u_unit']; ?><br/>
                                                            <b><?php echo $_data['add_new_form_field_text_17'];?> :</b> <?php echo $row['month_name'];?><br/>
                                                            <b><?php echo $_data['add_new_form_field_text_14'];?> :</b> 
                                                            <?php echo ($currency_position == 'left') ? $global_currency.$row['total_rent'] : $row['total_rent'].$global_currency; ?><br/>
                                                        </div>
                                                        <div class="col-xs-6"> 
                                                            <b><?php echo $_data['add_new_form_field_text_18'];?> :</b> <?php echo $row['xyear']; ?><br/>
                                                            <b><?php echo $_data['add_new_form_field_text_7'];?> :</b> 
                                                            <?php echo ($currency_position == 'left') ? $global_currency.$row['rent'] : $row['rent'].$global_currency; ?><br/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-danger" type="button">Đóng</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        $stmt->close();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include('../footer.php'); ?>
