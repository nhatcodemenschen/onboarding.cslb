<?php use Illuminate\Support\Facades\Auth; ?>
@guest   
    <?php header("Location: https://www.telberia.com/projects/boarding-cslb");
    exit(); ?> 
@else
    @extends('layouts.public')
    @section('content')
        <?php $home_link = "https://www.telberia.com/projects/boarding-cslb";
        $data = \App\Custom\DataDemo::getTask();
        $position = \App\Custom\DataDemo::getPosition();
        if(isset($_GET['id'])) {
            $id = $_GET['id']; 
            $task_id = $_GET['task_id'];
            foreach ($data as $data) { 
                $current_id = $data['task_id'];
                if($current_id == $task_id) {
                    foreach ($data['task_user'] as $data_item) {
                        if($id == $data_item['id']) { ?>
                            <div class="site-main wrap-checklist" id="checklist-page">
                                <div class="container">
                                    <div class="row block-title">
                                        <div class="col-md-12">
                                            <div class="row inner-block-title">
                                                <div class="group-item col-md-12">
                                                    <div class="form-group input-group">
                                                        <span class="has-float-label">
                                                            <input type="text" class="form-control" id="floatingName" placeholder=" " value="<?php echo DB::table('users')->where('id', $id)->value('full_name'); ?>">
                                                            <label for="floatingName">Name Mitarbeiter:</label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="group-item col-md-12">
                                                    <div class="form-group input-group">
                                                        <span class="has-float-label">
                                                            <select class="form-select form-control" id="floatingSelect" aria-label="Funktion/Position:">
                                                                <?php 
                                                                $position_user = DB::table('users')->where('id', $id)->value('position');
                                                                if($position_user=="") {
                                                                    $current_position = -1;
                                                                } else {
                                                                    $current_position = $position_user;
                                                                }
                                                                $index=0;
                                                                foreach($position as $position) {
                                                                    if ($current_position == -1) {
                                                                        if($index==0) {
                                                                            echo '<option value="'.$position.'" selected>'.$position.'</option>';
                                                                        } else {
                                                                            echo '<option value="'.$position.'">'.$position.'</option>';
                                                                        }
                                                                        $index++;                                                                
                                                                    } else {
                                                                        if($position == $current_position) {
                                                                            echo '<option value="'.$position.'" selected>'.$position.'</option>';
                                                                        } else {
                                                                            echo '<option value="'.$position.'">'.$position.'</option>';
                                                                        }
                                                                    }
                                                                } ?>
                                                            </select>
                                                            <label for="floatingSelect">Funktion/Position:</label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="group-item col-md-12">
                                                    <div class="form-group input-group">
                                                        <span class="has-float-label">
                                                           <?php $manager_user = DB::table('users')->where('id', $id)->value('manager');
                                                            if($manager_user=="") {
                                                                echo ' <input type="text" class="form-control" id="floatingSuperior" placeholder=" ">';
                                                            } else {
                                                                echo ' <input type="text" class="form-control" id="floatingSuperior" placeholder="" value="'.$manager_user.'">';
                                                            } ?>                                                     
                                                          <label for="floatingName">Name Vorgesetzter:</label>
                                                        </span>
                                                    </div>
                                                </div>                            
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row block-content">
                                        <div class="col-md-12">                        
                                            <div class="nav-tab-board row mb-5">
                                                <ul class="nav nav-pills col-md-12" id="pills-tab" role="tablist">
                                                <?php $i = 0;
                                                foreach ($data_item['form_details'] as $tab_nav) { 
                                                    echo '<li class="nav-item" role="presentation">';
                                                    if($i==0) { 
                                                        echo '<a class="nav-link active" id="'.$tab_nav['status_key'].'-tab" data-toggle="pill" href="#'.$tab_nav['status_key'].'" role="tab" aria-controls="'.$tab_nav['status_key'].'" aria-selected="true">'.$tab_nav['status_key_label'].'</a>';
                                                    } else {
                                                        echo '<a class="nav-link" id="'.$tab_nav['status_key'].'-tab" data-toggle="pill" href="#'.$tab_nav['status_key'].'" role="tab" aria-controls="'.$tab_nav['status_key'].'" aria-selected="false">'.$tab_nav['status_key_label'].'</a>';
                                                    } 
                                                    echo '</li>';
                                                    $i++;
                                                } ?>
                                                </ul>
                                            </div>
                                            <div class="tab-content" id="pills-tabContent">
                                            <?php $abbreviations = DB::table('users')->where('id', $id)->value('abbreviations');
                                            $tab_i = 0;
                                            foreach ($data_item['form_details'] as $tab_item) { 
                                                if($tab_i == 0) {
                                                    echo '<div class="tab-pane fade show active" id="'.$tab_item['status_key'].'" role="tabpanel" aria-labelledby="'.$tab_item['status_key'].'-tab">';
                                                } else {
                                                    echo '<div class="tab-pane fade" id="'.$tab_item['status_key'].'" role="tabpanel" aria-labelledby="'.$tab_item['status_key'].'-tab">';
                                                }                                                 
                                                if($tab_item['status_key'] == 'onboarding') { ?>
                                                    <div class="row">
                                                        <div class="group-item col-md-6 mb-5">
                                                            <div class="input-group align-items-end">
                                                               <?php if($data_item['start_date']=="") { ?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Eintritt Datum" id="<?php echo $tab_item['status_key']; ?>_entry-date" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_entry-date">Eintritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="start_date" aria-label="Eintritt Datum">
                                                                <?php } else { ?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Eintritt Datum" id="<?php echo $tab_item['status_key']; ?>_entry-date" checked>
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_entry-date">Eintritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="start_date" aria-label="Eintritt Datum" value="<?php echo $data_item['start_date']; ?>" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                <?php } ?>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                <?php } 
                                                if($tab_item['status_key'] == 'offboarding') { ?>
                                                    <div class="row"> 
                                                        <div class="group-item col-md-6 mb-5">
                                                            <div class="input-group align-items-end">
                                                               <?php if($data_item['end_date']=="") {?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Austritt Datum" id="<?php echo $tab_item['status_key']; ?>_exit-date">
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_exit-date">Austritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="end_date" aria-label="Austritt Datum" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                <?php } else { ?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Austritt Datum" id="<?php echo $tab_item['status_key']; ?>_exit-date" checked>
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_exit-date">Austritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="end_date" aria-label="Austritt Datum" value="<?php echo $data_item['end_date']; ?>" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                <?php } ?>                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } 
                                                if($tab_item['status_key'] == 'maternity-leave') { ?>   
                                                    <div class="row"> 
                                                        <div class="group-item col-md-6 mb-5">
                                                            <div class="input-group align-items-end">
                                                               <?php if($data_item['start_date']=="") { ?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Eintritt Datum" id="<?php echo $tab_item['status_key']; ?>_entry-date" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_entry-date">Eintritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="start_date" aria-label="Eintritt Datum">
                                                                <?php } else { ?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Eintritt Datum" id="<?php echo $tab_item['status_key']; ?>_entry-date" checked>
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_entry-date">Eintritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="start_date" aria-label="Eintritt Datum" value="<?php echo $data_item['start_date']; ?>" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                <?php } ?>
                                                            </div>
                                                        </div>    
                                                        <div class="group-item col-md-6 mb-5">
                                                            <div class="input-group align-items-end">
                                                               <?php if($data_item['end_date']=="") {?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Austritt Datum" id="<?php echo $tab_item['status_key']; ?>_exit-date">
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_exit-date">Austritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="end_date" aria-label="Austritt Datum" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                <?php } else { ?>
                                                                    <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                        <input type="checkbox" class="custom-control-input" value="" aria-label="Austritt Datum" id="<?php echo $tab_item['status_key']; ?>_exit-date" checked>
                                                                        <label class="custom-control-label" for="<?php echo $tab_item['status_key']; ?>_exit-date">Austritt Datum:</label>
                                                                    </div>
                                                                    <input type="text" class="form-control datetimepicker-input" name="end_date" aria-label="Austritt Datum" value="<?php echo $data_item['end_date']; ?>" style="padding-right: 15px;text-align: left;padding-left: 15px;">
                                                                <?php } ?>                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }  
                                                echo '<div class="mb-5 tab-field"> 
                                                    <h2 class="top-title">'.$tab_item['status_key_label'].'</h2>';
                                                    foreach($tab_item['group_job'] as $group_job) {
                                                        echo '<div class="mb-5">
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3 d-none d-md-block">';
                                                                    if ($group_job['group_has_label'] == 'yes') {
                                                                        echo '<h3 class="title-h3">DATUM und KÜRZEL erledigt</h3>';
                                                                    }
                                                                echo '</div>
                                                                <div class="col-md-8">
                                                                    <h3 class="title-h3">'.$group_job['group_job_name'].'</h3>
                                                                </div>
                                                            </div>';
                                                            foreach($group_job['group_item'] as $group_item) {
                                                                if ($group_item['job_type'] == "textarea") { 
                                                                    echo '<div class="row group-item">                                                        
                                                                        <div class="col-md-12">                                    
                                                                            <textarea class="form-control" value="'.$group_item['job_value'].'"></textarea>
                                                                        </div>
                                                                    </div>';
                                                                 } else { 
                                                                    echo '<div class="row group-item">';
                                                                        if (empty($group_item['job_value'])) {
                                                                            echo '<div class="col-md-4 mb-3 wrap-datetimepicker">';
                                                                                echo '<input type="text" class="form-control datetimepicker-input datetimepicker" name="'.$group_item['job_id'].'">';     
                                                                                echo '<input type="text" name="abbreviation" value="'.$abbreviations.'" class="form-control">'; 
                                                                            echo "</div>";
                                                                        } else {
                                                                            echo '<div class="col-md-4 mb-3 wrap-datetimepicker show-name">';
                                                                                echo '<input type="text" class="form-control datetimepicker-input" name="'.$group_item['job_id'].'" value="'.$group_item['job_date'].'">';
                                                                                echo '<input type="text" name="abbreviation" value="'.$abbreviations.'" class="form-control">';
                                                                            echo "</div>";
                                                                        } ?>
                                                                        <div class="col-md-8 mb-3 d-flex align-items-end">
                                                                            <div class="input-group">
                                                                                <div class="custom-control custom-checkbox form-check">
                                                                                    <?php if($group_item['job_value'] =="yes") {
                                                                                        echo'<input class="custom-control-input" type="checkbox" value="yes" id="check_'.$group_item['job_id'].'" name="'.$group_item['job_id'].'" checked>';
                                                                                    } else {
                                                                                        echo'<input class="custom-control-input" type="checkbox" value="" id="check_'.$group_item['job_id'].'" name="'.$group_item['job_id'].'" class="input-group-append">';
                                                                                    }
                                                                                        echo '<label class="custom-control-label" for="check_'.$group_item['job_id'].'" name="'.$group_item['job_id'].'" >'.$group_item['job_description'].'</label>';
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php }
                                                            } ?>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="mb-5">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <h3 class="title-h3">VERMERKE</h3>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <?php if($tab_item['has_comment']=="yes") { ?>
                                                                <div class="col-md-4 mb-3 group-item mb-0">                           
                                                                    <textarea class="form-control" style="min-height:100%" value="<?php echo $tab_item['comment_value']; ?>"></textarea>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <div class="file-group" style="width: 100%;">
                                                                        <div class="e-signature">
                                                                        <?php if(!empty($tab_item['employee_signature_value'])){ 
                                                                            echo '<img id="sigimage1-'.$tab_item['status_key'].'" src="'.$tab_item['employee_signature_value'].'">';
                                                                        } else { ?>
                                                                            <div class="group mb-3">
                                                                                <canvas onmouseenter="draw_sign(this.id)" 
                                                                                id="canvas-<?php echo $tab_item['status_key']; ?>" 
                                                                                class="canvas-signature" 
                                                                                width="290px" height="200px"></canvas>
                                                                            </div>
                                                                            <div class="group mb-3">
                                                                                <button class="button-red" 
                                                                                onclick="submit_canvas(this)" 
                                                                                data-canvas="canvas-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-sigimage="sigimage1-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-sigdataurl="sig-dataUrl1-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-canvas="canvas-<?php echo $tab_item['status_key']; ?>">
                                                                                    Einreichen
                                                                                </button>
                                                                                <button class="button-red" 
                                                                                id="sig-clearBtn-1" 
                                                                                onclick="clear_canvas(this)" 
                                                                                data-canvas="canvas-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-sigimage="sigimage1-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-sigdataurl="sig-dataUrl1-<?php echo $tab_item['status_key']; ?>" >
                                                                                    Löschen
                                                                                </button>
                                                                            </div>
                                                                            <input type="hidden" id="sig-dataUrl1-<?php echo $tab_item['status_key']; ?>" class="form-control" rows="5">
                                                                            <img id="sigimage1-<?php echo $tab_item['status_key']; ?>" src="" style=" display: none;">
                                                                        <?php } ?>
                                                                        </div>

                                                                        <label class="my-4">
                                                                            <strong><?php echo $tab_item['employee_signature_label']; ?></strong>
                                                                        </label>
                                                                    </div>
                                                                    <div class="file-group" style="width: 100%;">
                                                                        <div class="e-signature">
                                                                        <?php if(!empty($tab_item['signature_manager_value'])){ 
                                                                            echo '<img id="sigimage-2-'.$tab_item['status_key'].'" src="'.$tab_item['signature_manager_value'].'">';
                                                                        } else { ?>
                                                                            <div class="group mb-3">
                                                                                <canvas onmouseenter="draw_sign(this.id)" 
                                                                                id="sigcanvas-2-<?php echo $tab_item['status_key']; ?>" 
                                                                                class="canvas-signature" 
                                                                                width="290px" height="200px"></canvas>
                                                                            </div>
                                                                            <div class="group mb-3">
                                                                                <button class="button-red" 
                                                                                onclick="submit_canvas(this)" 
                                                                                data-canvas="sigcanvas-2-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-sigimage="sigimage-2-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-sigdataurl="sigdataUrl-2-<?php echo $tab_item['status_key']; ?>">
                                                                                    Einreichen
                                                                                </button>
                                                                                <button class="button-red" 
                                                                                onclick="clear_canvas(this)" 
                                                                                data-canvas="sig-canvas-2" 
                                                                                data-sigimage="sigimage-2-<?php echo $tab_item['status_key']; ?>" 
                                                                                data-sigdataurl="sigdataUrl-2-<?php echo $tab_item['status_key']; ?>">
                                                                                    Löschen
                                                                                </button>
                                                                            </div>
                                                                            <input type="hidden" id="sigdataUrl-2-<?php echo $tab_item['status_key']; ?>" class="form-control" rows="5">
                                                                            <img id="sigimage-2-<?php echo $tab_item['status_key']; ?>" src="" style=" display: none;">
                                                                        <?php } ?>
                                                                        </div>
                                                                        <label class="my-4">
                                                                            <strong><?php echo $tab_item['signature_manager_label']; ?></strong>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="col-md-4 mb-3"></div>
                                                                <div class="col-lg-4 mb-3 file-group">
                                                                    <div class="e-signature">
                                                                        <?php if(!empty($tab_item['employee_signature_value'])){ 
                                                                            echo '<img id="sigimage1-'.$tab_item['status_key'].'" src="'.$tab_item['employee_signature_value'].'">';
                                                                        } else { ?>
                                                                        <div class="group mb-3">
                                                                            <canvas onmouseenter="draw_sign(this.id)" 
                                                                                id="canvas-<?php echo $tab_item['status_key']; ?>" 
                                                                                class="canvas-signature" 
                                                                                width="290px" height="200px">                                                                          
                                                                            </canvas>
                                                                        </div>
                                                                        <div class="group mb-3">
                                                                            <button class="button-red" 
                                                                            onclick="submit_canvas(this)" 
                                                                            data-canvas="canvas-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigimage="sigimage1-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigdataurl="sig-dataUrl1-<?php echo $tab_item['status_key']; ?>">
                                                                                Einreichen
                                                                            </button>
                                                                            <button class="button-red" 
                                                                            id="sig-clearBtn-1" 
                                                                            onclick="clear_canvas(this)" 
                                                                            data-canvas="canvas-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigimage="sigimage1-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigdataurl="sig-dataUrl1-<?php echo $tab_item['status_key']; ?>" >
                                                                                Löschen
                                                                            </button>
                                                                        </div>
                                                                        <input type="hidden" name="" id="sig-dataUrl1-<?php echo $tab_item['status_key']; ?>" class="form-control" rows="5">
                                                                        <img id="sigimage1-<?php echo $tab_item['status_key']; ?>" src="" style=" display: none;" width="290px" height="200px">
                                                                    <?php } ?>
                                                                    </div>                                                         
                                                                    <label class="my-4">
                                                                        <strong><?php echo $tab_item['employee_signature_label']; ?></strong>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-4 mb-3 file-group">
                                                                    <div class="e-signature">
                                                                        <?php if(!empty($tab_item['signature_manager_value'])){ 
                                                                            echo '<img id="sigimage-2-'.$tab_item['status_key'].'" src="'.$tab_item['signature_manager_value'].'">';
                                                                        } else { ?>
                                                                        <div class="group mb-3">
                                                                            <canvas onmouseenter="draw_sign(this.id)" 
                                                                            id="sigcanvas-2-<?php echo $tab_item['status_key']; ?>" 
                                                                            class="canvas-signature" 
                                                                            width="290px" height="200px"></canvas>
                                                                        </div>
                                                                        <div class="group mb-3">
                                                                            <button class="button-red" 
                                                                            onclick="submit_canvas(this)" 
                                                                            data-canvas="sigcanvas-2-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigimage="sigimage-2-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigdataurl="sigdataUrl-2-<?php echo $tab_item['status_key']; ?>">
                                                                                Einreichen
                                                                            </button>
                                                                            <button class="button-red" 
                                                                            onclick="clear_canvas(this)" 
                                                                            data-canvas="sigcanvas-2-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigimage="sigimage-2-<?php echo $tab_item['status_key']; ?>" 
                                                                            data-sigdataurl="sigdataUrl-2-<?php echo $tab_item['status_key']; ?>">
                                                                                Löschen
                                                                            </button>
                                                                        </div>

                                                                        <input type="hidden" name="" id="sigdataUrl-2-<?php echo $tab_item['status_key']; ?>" class="form-control" rows="5">
                                                                        <img id="sigimage-2-<?php echo $tab_item['status_key']; ?>" src="" style=" display: none;">
                                                                    <?php } ?>
                                                                    </div>
                                                                    <label class="my-4">
                                                                        <strong><?php echo $tab_item['signature_manager_label']; ?></strong>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>                                          
                                            <?php $tab_i++; } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action-checklist text-end">
                                        <button id="Save" class="button-red">Speichern</button>
                                        <button onclick="getPDF()" id="export-pdf" class="button-red">PDF-Export <i class="fas fa-file-export"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php }                    
                    }                    
                }
            }
        } ?>
        
    @endsection
@endguest