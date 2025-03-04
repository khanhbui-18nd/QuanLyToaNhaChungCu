<?php 
include('../header_owner.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_owner_renter_details.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include './config.php';
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> <?php echo $_data['text_1'];?> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>o_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
        <li class="active"><?php echo $_data['text_1'];?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
    <div class="col-xs-12">
        <div align="right" style="margin-bottom:1%;"> 
            <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>o_dashboard.php" data-original-title="<?php echo $_data['owner_dashboard'];?>"><i class="fa fa-dashboard"></i></a> 
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"><?php echo $_data['text_1'];?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table sakotable table-bordered table-striped dt-responsive">
                    <thead>
                        <tr>
                            <th><?php echo $_data['image'];?></th>
                            <th><?php echo $_data['text_2'];?></th>
                            <th><?php echo $_data['text_3'];?></th>
                            <th><?php echo $_data['text_4'];?></th>
                            <th><?php echo $_data['text_5'];?></th>
                            <th><?php echo $_data['text_6'];?></th>
                            <th><?php echo $_data['text_7'];?></th>
                            <th><?php echo $_data['text_8'];?></th>
                            <th><?php echo $_data['text_9'];?></th>
                            <th><?php echo $_data['action_text'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $conn->prepare("SELECT *, our.unit_id, fl.floor_no AS fl_floor_no, u.unit_no AS u_unit_no FROM tbl_add_rent r 
                                                INNER JOIN tbl_add_owner_unit_relation our ON r.r_unit_no = our.unit_id 
                                                INNER JOIN tbl_add_floor fl ON fl.fid = r.r_floor_no 
                                                INNER JOIN tbl_add_unit u ON u.uid = r.r_unit_no 
                                                WHERE our.owner_id = ? 
                                                ORDER BY r.rid DESC");
                        $owner_id = (int)$_SESSION['objLogin']['ownid'];
                        $stmt->bind_param("i", $owner_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $image = WEB_URL . 'img/no_image.jpg';    
                            if (file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != '') {
                                $image = WEB_URL . 'img/upload/' . $row['image'];
                            }
                        ?>
                        <tr>
                            <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image; ?>" /></td>
                            <td><?php echo $row['r_name']; ?></td>
                            <td><?php echo $row['r_email']; ?></td>
                            <td><?php echo $row['r_contact']; ?></td>
                            <td><?php echo $row['r_address']; ?></td>
                            <td><?php echo $row['fl_floor_no']; ?></td>
                            <td><?php echo $row['u_unit_no']; ?></td>
                            <td><?php echo $row['r_advance'].' '.CURRENCY; ?></td>
                            <td><?php echo $row['r_date']; ?></td>
                            <td>
                                <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="$('#nurse_view_<?php echo $row['rid']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a>
                                <div id="nurse_view_<?php echo $row['rid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header orange_header">
                                                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                                                <h3 class="modal-title"><?php echo $_data['text_1'];?></h3>
                                            </div>
                                            <div class="modal-body model_view" align="center">&nbsp;
                                                <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image; ?>" /></div>
                                                <div class="model_title"><?php echo $row['r_name']; ?></div>
                                            </div>
                                            <div class="modal-body">
                                                <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                                                <div class="row">
                                                    <div class="col-xs-12"> 
                                                        <b><?php echo $_data['text_2'];?> :</b> <?php echo $row['r_name']; ?><br/>
                                                        <b><?php echo $_data['text_3'];?> :</b> <?php echo $row['r_email']; ?><br/>
                                                        <b><?php echo $_data['text_4'];?> :</b> <?php echo $row['r_contact']; ?><br/>
                                                        <b><?php echo $_data['text_5'];?> :</b> <?php echo $row['r_address']; ?><br/>
                                                        <b><?php echo $_data['text_6'];?> :</b> <?php echo $row['fl_floor_no']; ?><br/>
                                                        <b><?php echo $_data['text_7'];?> :</b> <?php echo $row['u_unit_no']; ?><br/>
                                                        <b><?php echo $_data['text_8'];?> :</b> <?php echo $row['r_advance'].' '.CURRENCY; ?><br/>
                                                        <b><?php echo $_data['text_10'];?> :</b><?php echo $row['r_rent_pm'].' '.CURRENCY; ?><br/>
                                                        <b><?php echo $_data['text_9'];?> :</b> <?php echo $row['r_date']; ?><br/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php } $stmt->close(); ?>
                    </tbody>
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
function deleteRent(Id){
    var iAnswer = confirm("Are you sure you want to delete this Rent?");
    if (iAnswer) {
        window.location = '<?php echo WEB_URL; ?>rent/rentlist.php?id=' + Id;
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
