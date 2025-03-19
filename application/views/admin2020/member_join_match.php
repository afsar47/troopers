<!DOCTYPE html>
<?php
    if($this->session->userdata('site_lang') && in_array($this->session->userdata('site_lang'),json_decode($this->system->rtl_supported_language,true))) {
        $dir = 'rtl';
    } else {
        $dir = 'ltr';
    }
?>
<html dir='<?php echo $dir; ?>'>
    <head>
        <?php $this->load->view($this->path_to_view_admin . 'header'); ?>
        <style>      
            .loader
            {
                background: url(<?php echo $this->ADMINPATH_images; ?>assets/layouts/layout/img/loading-spinner-grey.gif);
                background-repeat: no-repeat;
                background-position: center;
                z-index: 5;
            }       
            .order-main-tbl input {
                border: 1px solid #ccc;
            }
            .order-main-tbl select {
                border: 1px solid #ccc;
            }
            .order-main-tbl input {
                width: 99%;
                height: 95%;
                margin-top: 1px;
            }
            input[readonly] {
                background-color: #DEDEDE;
                border: 1px solid #ccc;
            }
            .handsontable {
                margin-bottom: 10px;
                color: #000;
                z-index: 2;
            }
            .handsontable {
                position: relative;
                font-family: Arial, Helvetica, sans-serif;
                line-height: 1.3em;
                font-size: 13px;
            }
            .handsontable table {
                position: relative;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -o-user-select: none;
                -ms-user-select: none;
                border-spacing: 0;
                margin: 0;
                border-width: 0;
                table-layout: fixed;
                width: 0;
            }
            thead {
                display: table-header-group;
                vertical-align: middle;
                border-color: inherit;
            }
            tr {
                display: table-row;
                vertical-align: inherit;
                border-color: inherit;
            }
            .handsontable thead tr:last-child th {
                border-bottom-width: 0;
            }
            .handsontable tr:first-child th,
            .handsontable tr:first-child td {
                border-top: 1px solid #CCC;
            }
            .handsontable th:first-child,
            .handsontable td:first-child,
            .handsontable .htNoFrame+th,
            .handsontable .htNoFrame+td {
                border-left: 1px solid #CCC;
            }
            .handsontable thead th {
                padding: 0;
            }
            .handsontable th {
                background-color: #EEE;
                color: #222;
                text-align: center;
                font-weight: normal;
                white-space: nowrap;
            }
            .handsontable th,
            .handsontable td {
                border-right: 1px solid #CCC;
                border-bottom: 1px solid #CCC;
                height: 22px;
                line-height: 21px;
                padding: 0 1px 0 2px;
                background-color: #FFF;
                font-size: 12px;
                vertical-align: top;
                overflow: hidden;
            }
            .handsontable * {
                box-sizing: content-box;
                -webkit-box-sizing: content-box;
                -moz-box-sizing: content-box;
            }
            user agent stylesheet th {
                font-weight: bold;
                text-align: -internal-center;
            }
            user agent stylesheet td,
            th {
                display: table-cell;
                vertical-align: inherit;
            }
        </style>
        <link href="<?php echo $this->admin_css; ?>toastr.min.css" rel="stylesheet" type="text/css" />        
    </head>
    <body>
        <?php $this->load->view($this->path_to_view_admin . 'header_body'); ?>

        <div class="d-flex" id="wrapper">
            <?php $this->load->view($this->path_to_view_admin . 'sidebar'); ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $match['match_name'] . ' - Match#' . $match['m_id']; ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class=" table-responsive">
                                <strong>Match Details:</strong>
                                <table id="example" class="table  table-striped table-bordered">
                                    <thead style="background-color: #343a40;color: #fff;">
                                        <tr>
                                            <th><?php echo $this->lang->line('text_match_id'); ?></th>
                                            <th><?php echo $this->lang->line('text_type'); ?></th>
                                            <th><?php echo $this->lang->line('text_map'); ?></th>
                                            <th><?php echo $this->lang->line('text_match_type'); ?></th>
                                            <th><?php echo $this->lang->line('text_total_player'); ?></th>
                                            <th><?php echo $this->lang->line('text_total_player_joined'); ?></th>
                                            <th><?php echo $this->lang->line('text_entry_fee') . '(' . $this->functions->getPoint() . ')'; ?></th>
                                            <th><?php echo $this->lang->line('text_win_prize') . '(%)'; ?></th>
                                            <th><?php echo $this->lang->line('text_per_kill') . '(%)'; ?></th>
                                            <th><?php echo $this->lang->line('text_status'); ?></th>
                                            <th><?php echo $this->lang->line('text_match_schedule'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd">
                                            <td><?php echo $match['m_id']; ?></td>
                                            <td><?php echo $match['type']; ?></td>
                                            <td><?php echo $match['map_name']; ?></td>
                                            <td><?php if ($match['match_type'] == '0') echo 'Free';else if ($match['match_type'] == '1') echo 'Paid'; ?></td>
                                            <td><?php echo $match['number_of_position']; ?></td>
                                            <td><?php echo $match['no_of_player']; ?></td>
                                            <td><?php echo $this->functions->getPoint() . ' ' . utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $match['entry_fee'])); ?></td>
                                            <td><?php echo utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $match['win_prize'])) . '(%)'; ?></td>
                                            <td><?php echo utf8_encode(sprintf('%.' . $this->functions->getCurrencyDecimal($this->system->currency) . 'F', $match['per_kill'])) . '(%)'; ?></td>
                                            <td><?php if ($match['match_status'] == '0') echo 'Deactive';else if ($match['match_status'] == '1') echo 'Active';else if ($match['match_status'] == '2') echo 'Complete';else if ($match['match_status'] == '3') echo 'Start';else if ($match['match_status'] == '4') echo 'Cancel'; ?></td>
                                            <td><?php echo $match['match_time']; ?></td>
                                        </tr>     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form name="frmmatchlist" method="post" >
                                <div id="" class="handsontable">
                                    <!--                                    <div class="wtHolder" style="position: relative; display: table;width: 100%;">
                                                                            <div class="wtHider" style="position: relative; overflow: hidden; width: 100%;">
                                                                                <div class="wtSpreader">-->
                                    <div class="dataTables_wrapper dt-bootstrap no-footer">
                                        <div class="row">
                                            <div class="offset-md-5 col-sm-6"><div id="manage_tbl_filter" class="dataTables_filter"><label>Search:<input type="search" id="myInput" class="form-control input-sm" placeholder="" aria-controls="manage_tbl" style="height: auto;"></label></div></div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table style="display:table;width: 100%;" id="myTable" class="table table-striped table-bordered order-main-tbl">
                                            <colgroup>
                                                <col style="width: 1%;">
                                                <col style="width: 2%;">
                                                <col style="width: 2%;">
                                                <col style="width: 2%;">
                                                <col <?php echo $this->system->place_point_show != 'yes' ? "style='display:none'" : ''; ?> style="width: 2%;">
                                                <col <?php echo $this->system->place_point_show != 'yes' ? "style='display:none'" : ''; ?> style="width: 2%;">
                                                <col style="width: 2%;">
                                                <col style="width: 2%;">
                                                <col style="width: 2%;">  
                                                <col style="width: 2%;">  
                                                <col style="width: 2%;">  
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('text_sr_no'); ?></th>
                                                    <th><?php echo $this->lang->line('text_game_id'); ?></th>
                                                    <th><?php echo $this->lang->line('text_user_name'); ?></th>
                                                    <th <?php echo $this->system->place_point_show != 'yes' ? "style='display:none'" : ''; ?>><?php echo $this->lang->line('text_place'); ?></th>
                                                    <th <?php echo $this->system->place_point_show != 'yes' ? "style='display:none'" : ''; ?>><?php echo $this->lang->line('text_place_point'); ?></th>
                                                    <th><?php echo $this->lang->line('text_killed'); ?></th>
                                                    <th><?php echo $this->lang->line('text_kill_win') . '(' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_win_prize') . '(' . $this->functions->getPoint() . ')'; ?></th>
                                                    <th><?php echo $this->lang->line('text_bonus') . '(' . $this->functions->getPoint() . ')'; ?></th>  
                                                    <th><?php echo $this->lang->line('text_total_win') . '(' . $this->functions->getPoint() . ')'; ?></th>  
                                                    <th><?php echo $this->lang->line('text_refund') . '(' . $this->functions->getPoint() . ')'; ?></th>  
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <input type="hidden" name="no_of_player" id="no_of_player" value="<?php echo $match['no_of_player']; ?>" >
                                                <input type="hidden" name="entry_fee" id="entry_fee" value="<?php echo $match['entry_fee']; ?>" >
                                                <?php
                                                $cnt = 1;
                                                if (isset($match_details) && !empty($match_details)) {
                                                    foreach ($match_details as $match_detail) {
                                                        ?>
                                                        <tr>
                                                            <td id="id<?php echo $cnt; ?>" class="match_member"><?php echo $cnt; ?>                                                                                    
                                                                <input type="hidden"  id="match_join_member_id<?php echo $cnt; ?>" value="<?php echo $match_detail->match_join_member_id; ?>" >
                                                                <input type="hidden"  id="member_id<?php echo $cnt; ?>" value="<?php echo $match_detail->member_id; ?>" >
                                                                <input type="hidden"  id="match_id<?php echo $cnt; ?>" value="<?php echo $match_detail->m_id; ?>" >
                                                            </td>
                                                            <td id="pubg_id<?php echo $cnt; ?>"><?php echo $match_detail->pubg_id; ?></td>                                                                                    
                                                            <td id="user_name<?php echo $cnt; ?>"><a href="<?php echo base_url() . $this->path_to_view_admin . 'members/member_detail/' . $match_detail->member_id ?>"><?php echo $match_detail->user_name; ?></a></td>
                                                            <td <?php echo $this->system->place_point_show != 'yes' ? "style='display:none'" : ''; ?>><input type="text" id="place<?php echo $cnt; ?>" class="numbers" value="<?php echo isset($match_detail->place) && $match_detail->place != '' ? $match_detail->place : 0; ?>" ></td>
                                                            <td <?php echo $this->system->place_point_show != 'yes' ? "style='display:none'" : ''; ?>><input type="text" id="place_point<?php echo $cnt; ?>" class="numbers" value="<?php echo isset($match_detail->place_point) && $match_detail->place_point != '' ? $match_detail->place_point : 0; ?>" onKeyUp="count_total_win(<?php echo $cnt; ?>);" ></td>                                                                        
                                                            <td><input type="text" id="killed<?php echo $cnt; ?>" class="numbers killed" value="<?php echo isset($match_detail->killed) && $match_detail->killed != '' ? $match_detail->killed : 0; ?>"  onKeyUp="count_total_win_kill(<?php echo $cnt; ?>);" >
                                                                <input type="hidden" id="per_kill<?php echo $cnt; ?>" value="<?php echo isset($match_detail->per_kill) && $match_detail->per_kill != '' ? $match_detail->per_kill : 0; ?>" >
                                                            </td>
                                                            <td><input type="text" id="win<?php echo $cnt; ?>" readonly="" class="numbers" value="<?php echo isset($match_detail->win) && $match_detail->win != '' ? $match_detail->win : 0; ?>" ></td>
                                                            <td><input type="text" id="win_prize<?php echo $cnt; ?>" class="numbers" value="<?php echo isset($match_detail->win_prize) && $match_detail->win_prize != '' ? $match_detail->win_prize : 0; ?>" onKeyUp="count_total_win(<?php echo $cnt; ?>);" ></td>
                                                            <td><input type="text" id="bonus<?php echo $cnt; ?>" class="numbers" value="<?php echo isset($match_detail->bonus) && $match_detail->bonus != '' ? $match_detail->bonus : 0; ?>" onKeyUp="count_total_win(<?php echo $cnt; ?>);" ></td>
                                                            <td><input type="text" id="total_win<?php echo $cnt; ?>" readonly="" class="numbers" value="<?php echo isset($match_detail->total_win) && $match_detail->total_win != '' ? $match_detail->total_win : 0; ?>" ></td>
                                                            <td><input type="text" id="refund<?php echo $cnt; ?>" class="numbers" value="<?php echo isset($match_detail->refund) && $match_detail->refund != '' ? $match_detail->refund : 0; ?>" ></td>
                                                        </tr>
                                                        <?php
                                                        $cnt++;
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="<?php echo $this->system->place_point_show == 'yes' ? '11' : '9'; ?>" style="text-align:center">No Data </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--                                            </div>
                                                                            </div>
                                                                        </div>-->
                                </div>
                                <div class="form-group text-center">
                                    <input type="button" value="<?php echo $this->lang->line('text_btn_update'); ?>" onclick="update_match()" name="update" class="btn btn-primary " <?php
                                    if ($this->system->demo_user == 1 && $match['m_id'] <= 7) {
                                        echo 'disabled';
                                    }
                                    ?>>   
                                    <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>matches/" name="cancel"><?php echo $this->lang->line('text_btn_cancel'); ?></a>                                                   
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="card card-primary">
                                <div class="card-header"><h4><?php echo $this->lang->line('text_result_notes'); ?></h4></div>
                                <div class="card-body">
                                    <form id="result-notification" method="post" >
                                        <input type="hidden" id="notification_id" name="notification_id" value="<?php echo $this->uri->segment(4); ?>">
                                        <label for="result_notification"><?php echo $this->lang->line('text_result_notes'); ?></label>
                                        <textarea class="form-control rounded-0" id="result_notification" name="result_notification" rows="5"></textarea>
                                        <span class="error" style="display: none;"><?php echo $this->lang->line('text_result_notes_err'); ?></span>
                                        <div class="form-group text-center mt-3">
                                            <input type="button" value="Send" onclick="resultNotification()" name="add_result_notification" class="btn btn-primary ">
                                            <a class="btn btn-secondary" href="<?php echo base_url() . $this->path_to_view_admin; ?>matches/" name="cancel">Cancel</a>                                                   
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php $this->load->view($this->path_to_view_admin . 'footer_body'); ?>
            </div>
        </div>
        <?php $this->load->view($this->path_to_view_admin . 'footer'); ?>
        <script src = "<?php echo $this->admin_js; ?>toastr.min.js" type = "text/javascript" ></script>
        <script>
            $(document).ready(function () {
                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tbody tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() . $this->path_to_view_admin; ?>/matches/get_result_notification",
                    data: {
                        match_id: $('#notification_id').val(),
                    },
                    success: function funSuccess(response) {
                        var data = JSON.parse(response);
                        $('#result_notification').val(data['result_notification'])
                    }
                });
            });
            
            function count_total_win(i) {
                var place_point = $('#place_point' + i).val();                
                var win = $('#win' + i).val();
                var win_prize = $('#win_prize' + i).val();
                var bonus = $('#bonus' + i).val();

                if(place_point == '')
                    place_point = 0;
                if(win == '')
                    win = 0;                
                if(win_prize == '')
                    win_prize = 0;
                if(bonus == '')
                    bonus = 0;

                $('#total_win' + i).val((parseFloat(place_point) + parseFloat(win) + parseFloat(win_prize) + parseFloat(bonus)).toFixed(2));
            }

            function count_total_win_kill(i) {                               

                var per_kill = $('#per_kill' + i).val();                                               
                var no_of_player = $('#no_of_player').val();
                var entry_fee = $('#entry_fee').val();

                                
                if(per_kill == '')
                    per_kill = 0;

                if(per_kill > 0){
                                         
                    var result = $(".killed").get();

                    var columns = $.map(result, function (element) {
                        return $(element).attr("id");
                    });

                    var total_killed = 0;
                    var total_earn = entry_fee * no_of_player;

                    for (column in columns) {   

                        if($('#' + columns[column]).val() > 0 || $('#' + columns[column]).val() != '') {
                            var sum_kill = $('#' + columns[column]).val();
                        } else {
                            var sum_kill = 0;
                        }

                        total_killed =  parseInt(total_killed) + parseInt(sum_kill);                                    
                    } 

                    if(total_killed > 0)
                        var per_kill_amount = (total_earn * per_kill / 100) / total_killed;
                    else
                        var per_kill_amount = 0;
                    
                    for (column in columns) { 
                        if($('#' + columns[column]).val() > 0 || $('#' + columns[column]).val() != '') {
                            var kill_win = parseInt($('#' + columns[column]).val()) * parseFloat(per_kill_amount);
                        } else {
                            var kill_win = 0;
                        }

                        $('#' + columns[column]).parent().next().children().val(kill_win.toFixed(2));

                        var place_point = $('#' + columns[column]).parent().prev().children().val();
                        var win_prize = $('#' + columns[column]).parent().next().next().children().val();
                        var bonus = $('#' + columns[column]).parent().next().next().next().children().val();

                        if(place_point == '')
                            place_point = 0;
                        if(win_prize == '')
                            win_prize = 0;
                        if(bonus == '')
                            bonus = 0;

                        var win_total = parseFloat(place_point) + parseFloat(kill_win) + parseFloat(win_prize) + parseFloat(bonus);

                        $('#' + columns[column]).parent().next().next().next().next().children().val(win_total.toFixed(2));
                    }  
                }                           
            }

            function update_match() {
                var match_join_member_ids = new Array();
                var member_ids = new Array();
                var pubg_ids = new Array();
                var match_ids = new Array();
                var places = new Array();
                var place_points = new Array();
                var killeds = new Array();
                var wins = new Array();
                var total_wins = new Array();
                var bonuses = new Array();
                var win_prizes = new Array();
                var total_refunds = new Array();

                var j = 0;
                var ids = $('.match_member').map(function () {
                    return this.id;
                }).get();
                var id;

                var expense = 0;

                for (id in ids) {
                    var match_join_member_id_id = $('#' + ids[id]).children().attr('id');
                    var match_join_member_id = $('#' + match_join_member_id_id).val();

                    var member_id_id = $('#' + match_join_member_id_id).next().attr('id');
                    var member_id = $('#' + member_id_id).val();

                    var match_id_id = $('#' + member_id_id).next().attr('id');
                    var match_id = $('#' + match_id_id).val();

                    var pubg_id_id = $('#' + ids[id]).next().attr('id');
                    var pubg_id = $('#' + pubg_id_id).text();

                    var place_id = $('#' + ids[id]).next().next().next().children().attr('id');
                    var place = $('#' + place_id).val();

                    var place_point_id = $('#' + place_id).parent().next().children().attr('id');
                    var place_point = $('#' + place_point_id).val();

                    var killed_id = $('#' + ids[id]).next().next().next().next().next().children().attr('id');
                    var killed = $('#' + killed_id).val();

                    if (killed == null || killed == '') {
                        Swal.fire({
                            icon: 'error',
                            title: '<?php echo $this->lang->line('text_oops'); ?>',
                            text: '<?php echo $this->lang->line('text_kill_err'); ?>',
                        });
                        return;
                    }

                    var win_id = $('#' + killed_id).parent().next().children().attr('id');
                    var win = $('#' + win_id).val();

                    var win_prize_id = $('#' + win_id).parent().next().children().attr('id');
                    var win_prize = $('#' + win_prize_id).val();

                    var bonus_id = $('#' + win_id).parent().next().next().children().attr('id');
                    var bonus_win = $('#' + bonus_id).val();

                    var total_win_id = $('#' + bonus_id).parent().next().children().attr('id');
                    var total_win = $('#' + total_win_id).val();

                    var refund_id = $('#' + total_win_id).parent().next().children().attr('id');
                    var total_refund = $('#' + refund_id).val();

                    if (total_refund > <?php echo $match['entry_fee']; ?>) {
                        Swal.fire({
                            icon: 'error',
                            title: '<?php echo $this->lang->line('text_oops'); ?>',
                            text: '<?php echo $this->lang->line('text_refund_err'); ?>',
                        });
                        return;
                    }
                    match_join_member_ids[j] = match_join_member_id;
                    member_ids[j] = member_id;
                    pubg_ids[j] = pubg_id;
                    match_ids[j] = match_id;
                    places[j] = place;
                    place_points[j] = place_point;
                    killeds[j] = killed;
                    wins[j] = win;
                    win_prizes[j] = win_prize;
                    bonuses[j] = bonus_win;
                    total_wins[j] = total_win;
                    total_refunds[j] = total_refund;
                    j++;

                    expense = parseFloat(expense) + parseFloat(total_win) + parseFloat(total_refund);
                    
                }

                    var admin_profit = parseFloat('<?php echo $this->system->admin_profit?>');                    

                    var no_of_player = $('#no_of_player').val();

                    if(no_of_player > 0) {
                        var income_total = parseInt(no_of_player) * parseFloat(<?php echo $match['entry_fee']; ?>);

                        if(admin_profit > 0){
                            var amount_after_charge = parseFloat(income_total) - (parseFloat(income_total) * admin_profit / 100);
                        } else {
                            var amount_after_charge = parseFloat(income_total);
                        }

                        if (parseFloat(expense).toFixed(2) != parseFloat(amount_after_charge).toFixed(2)) {
                            Swal.fire({
                                icon: 'error',
                                title: '<?php echo $this->lang->line('text_oops'); ?>',
                                text: 'Total of total win and refund should be ' + amount_after_charge.toFixed(2),
                            });
                            return;
                        }

                        var jsonObjects = [{"match_join_member_ids": match_join_member_ids, "member_ids": member_ids, "pubg_ids": pubg_ids, "match_ids": match_ids, "places": places, "place_points": place_points, "killeds": killeds, "win_prizes": win_prizes, "bonuses": bonuses, "wins": wins, "total_wins": total_wins, "total_refunds": total_refunds}];
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url() . $this->path_to_view_admin; ?>matches/update_member_join_match",
                            data: {data: JSON.stringify(jsonObjects)},
                            success: function funSuccess(response) {
                                // var obj = JSON.parse(response);
                                if (response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '<?php echo $this->lang->line('text_success'); ?>',
                                        // text: obj.msg,
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '<?php echo $this->lang->line('text_oops'); ?>',
                                        // text: obj.msg,
                                    });
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                                icon: 'error',
                                title: '<?php echo $this->lang->line('text_oops'); ?>',
                                text: 'Member not available',
                            });
                    }
            }

            function resultNotification() {
                console.log($('#notification_id').val());
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url() . $this->path_to_view_admin; ?>/matches/add_result_notification',
                    data: {
                        result_notification: $('#result_notification').val(),
                        match_id: $('#notification_id').val(),
                    },
                    success: function (response) {
                        console.log(response);
                        if (response != true) {
                            $('.error').css('display', 'block');
                        } else {
                            toastr.success('<?php echo $this->lang->line('text_result_notes_succ'); ?>');
                            $('.error').css('display', 'none');
                        }
                    }
                });
            }
        </script>
        <script>
            $('.numbers').keypress(function (event) {
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
                if (($(this).val().indexOf('.') != -1) && ($(this).val().substring($(this).val().indexOf('.'), $(this).val().indexOf('.').length).length > 2)) {
                    event.preventDefault();
                }
            });
        </script>
    </body>
</html>