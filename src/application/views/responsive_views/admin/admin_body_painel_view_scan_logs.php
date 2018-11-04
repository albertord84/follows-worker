<script type="text/javascript"> var DATAS= JSON.parse('<?php echo json_encode($DATAS)?>');</script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/js/chart_admin.js?'.$SCRIPT_VERSION; ?>"></script>
    <br><br>    
    <div class="row">
        <div class="col-xs-3"></div>
        <div class="col-xs-6">
            <div class="center">
                <form action="<?php echo base_url().'index.php/admin/view_scan_logs'?>" method="post">
                    <div class="col-xs-4">
                        <input type="text" id="user_id" name="user_id" class="form-control">
                    </div>
                    <div class="col-xs-4">
                        <input id="date_to" type="text" name="date_to" placeholder="mm/dd/yyyy" class="form-control" value="<?php if (isset($form_filter) && $form_filter[date_to] != "") { echo $form_filter[date_to]; } ?>">
                        <!--<input type="text" id="date_from" name="date_from" placeholder="mm/dd/yyyy" class="form-control">-->
                    </div>                    
                    <div class="col-xs-2">
                        <input id="see_statistics" class="btn btn-primary" type="submit" value="VER">
                    </div>                    
                </form>
            </div>
        </div>
        <div class="col-xs-3">
        </div>
    </div>
    <br><br><hr><br><br>
    <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-8">
            <div class="justify">
                <?php echo $DATAS?>
            </div>
        </div>
        <div class="col-xs-2">
        </div>
    </div>
    
    <br><br>    
    <br><br>    

        