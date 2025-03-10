<?php use Illuminate\Support\Facades\Auth; ?>
@guest   
    <?php header("Location: https://www.telberia.com/projects/boarding-cslb");
    exit(); ?> 
@else
    @extends('layouts.public')
    @section('content')
    <style type="text/css">
        .group-item .custom-control-label {
            top: -6px;
        }
    </style>
        <?php $home_link = "https://www.telberia.com/projects/boarding-cslb";
        $position = \App\Custom\DataDemo::getPosition();
        $list_task = \App\Custom\DataDemo::list_task(); 
        $list_date = \App\Custom\DataDemo::list_date(); 
        $signature = \App\Custom\DataDemo::signature(); 
        if(isset($_GET['id'])) {
            $id = $_GET['id']; ?>
            <div class="site-main wrap-checklist" id="checklist-page">
                <div class="container">

                    <!-- Infor setion -->
                    <div class="row block-title">
                        <div class="col-md-12">
                            <div class="row inner-block-title">
                                <!-- Name Mitarbeiter -->
                                <div class="group-item col-md-12">
                                    <div class="form-group input-group">
                                        <span class="has-float-label">
                                            <input type="text" class="form-control" id="floatingName" placeholder=" " value="<?php echo DB::table('users')->where('id', $id)->value('full_name'); ?>">
                                            <label for="floatingName">Name Mitarbeiter:</label>
                                        </span>
                                    </div>
                                </div>
                                <!-- end Name Mitarbeiter -->
                                <!-- Position -->
                                <div class="group-item col-md-12">
                                    <div class="form-group input-group">
                                        <span class="has-float-label">
                                            <select class="form-select form-control" id="floatingSelect" aria-label="Funktion/Position:">
                                                <?php $position_user = DB::table('users')->where('id', $id)->value('position');
                                                $index=0;
                                                foreach($position as $position) {
                                                    if (empty($position_user)) {
                                                        if($index==0) {
                                                            echo '<option value="'.$position.'" selected>'.$position.'</option>';
                                                        } else {
                                                            echo '<option value="'.$position.'">'.$position.'</option>';
                                                        }
                                                        $index++;                                                                
                                                    } else {
                                                        if($position == $position_user) {
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
                                <!-- end position -->
                                <!-- Name Vorgesetzter -->
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
                                <!-- end Name Vorgesetzter -->         
                            </div>
                        </div>
                    </div> 
                    <!-- end Infor setion -->

                    <!-- Tab setion -->
                    <div class="row block-content">
                        <div class="col-md-12">

                            <!-- Tab Nav -->
                            <div class="nav-tab-board row mb-5">
                                <ul class="nav nav-pills col-md-12" id="pills-tab" role="tablist">
                                <?php $i = 0;
                                foreach (DB::table('category_parent')->get() as $category_parent) { 
                                    echo '<li class="nav-item" role="presentation">';
                                    if($i==0) { 
                                        echo '<a class="nav-link active" id="'.$category_parent->key.'-tab" data-toggle="pill" href="#'.$category_parent->key.'" role="tab" aria-controls="'.$category_parent->key.'" aria-selected="true">'.$category_parent->name.'</a>';
                                    } else {
                                        echo '<a class="nav-link" id="'.$category_parent->key.'-tab" data-toggle="pill" href="#'.$category_parent->key.'" role="tab" aria-controls="'.$category_parent->key.'" aria-selected="false">'.$category_parent->name.'</a>';
                                    } 
                                    echo '</li>';
                                    $i++;
                                } ?>
                                </ul>
                            </div>
                            <!-- End Tab Nav -->


                            <!-- Tab Content -->
                            <div class="tab-content" id="pills-tabContent">
                                <?php $abbreviations = DB::table('users')->where('id', $id)->value('abbreviations');
                                $tab_i = 0; 
                                foreach (DB::table('category_parent')->get() as $type_group) { 

                                    //Check tab is active
                                    if($tab_i==0) { 
                                        echo '<div class="tab-pane fade active show" id="'.$type_group->key.'" role="tabpanel" aria-labelledby="'.$type_group->key.'-tab"> ';
                                    } else {
                                        echo '<div class="tab-pane fade" id="'.$type_group->key.'" role="tabpanel" aria-labelledby="'.$type_group->key.'-tab">';
                                    } 
                                    $tab_i++;
                                    //End Check tab is active

                                        //Field date
                                        echo '<div class="row date-field">';
                                        foreach($list_date as $date_field) {
                                            if(($date_field['id'] = $_GET['id']) && ($date_field['form_id'] = $_GET['form_id']) && ($type_group->id == $date_field['cat_parent_id'])) {
                                                echo '<div class="group-item col-md-6 mb-5">
                                                        <div class="input-group align-items-end">
                                                            <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                <input type="checkbox" 
                                                                class="custom-control-input" 
                                                                value="" 
                                                                id="checkbox_'.$date_field['date_id'].'_date">
                                                                <label class="custom-control-label" 
                                                                for="checkbox_'.$date_field['date_id'].'_date">
                                                                '.$date_field['name'].'
                                                                </label>
                                                            </div>
                                                            <input type="text" class="form-control datetimepicker-input" 
                                                            name="'.$date_field['date_id'].'_date"
                                                            value="" 
                                                            style="padding-right: 15px; text-align: left; padding-left: 15px;">
                                                        </div>
                                                    </div>';
                                            }
                                        }
                                        echo '</div>';
                                        //End Field date


                                        //Checklist
                                        echo '<div class="mb-5 tab-field">
                                            <h2 class="top-title">'.$type_group->name.'</h2>';
                                            $current_group = $type_group->id;
                                            foreach(DB::table('category')->get() as $category) {
                                                if($category->cat_parent_id == $current_group) {
                                                    echo'<div class="mb-5">
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3 d-none d-md-block">';
                                                                    if($category->name != 'Nach Probezeit') {
                                                                        echo '<h3 class="title-h3">DATUM und KÜRZEL erledigt</h3>';
                                                                    }
                                                                echo '</div>
                                                                <div class="col-md-8 d-flex align-items-start justify-content-between">
                                                                    <h3 class="title-h3">'.$category->name.'</h3>
                                                                    <div class="group-item">
                                                                        <input type="text" 
                                                                        class="form-control datetimepicker-input datetimepicker" 
                                                                        data-toggle="datetimepicker" 
                                                                        data-target="#datetimepicker-deadline" aria-label="Deadline" 
                                                                        style="padding-left: 15px;pointer-events: auto;" 
                                                                        placeholder="Please select a deadline">
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                            $cat_id = $category->id;
                                                            foreach($list_task as $task) {
                                                                if(($task['category_id'] == $cat_id)) {
                                                                    if($task['task_type'] == 'check') {
                                                                    echo '
                                                                    <div class="row group-item">  
                                                                        <div class="col-md-4 mb-3 wrap-datetimepicker">
                                                                            <input type="text" 
                                                                            class="form-control datetimepicker-input" 
                                                                            name="'.$task['category_id'].'_'.$task['task_id'].'" 
                                                                            value="">
                                                                            <input type="text" 
                                                                            name="abbreviation" 
                                                                            value="Elvan" 
                                                                            class="form-control">
                                                                        </div>
                                                                        <div class="col-md-8 mb-3 d-flex align-items-end">
                                                                            <div class="input-group" 
                                                                            data-id="'.$task['task_id'].'">
                                                                                <div class="custom-control custom-checkbox form-check">
                                                                                    <input class="custom-control-input" 
                                                                                    type="checkbox"  value="" 
                                                                                    id="'.$task['category_id'].'_'.$task['task_id'].'" 
                                                                                    name="'.$task['category_id'].'_'.$task['task_id'].'">

                                                                                    <label class="custom-control-label" 
                                                                                    for="'.$task['category_id'].'_'.$task['task_id'].'" 
                                                                                    name="'.$task['category_id'].'_'.$task['task_id'].'">
                                                                                    </label>

                                                                                    <span> '.$task["task_name"].'<span class="list-group"></span> </span>

                                                                                    <span class="add-group ml-2" style="cursor: pointer;" data-add="'.$task['task_id'].'">
                                                                                        <i class="far fa-plus"></i>
                                                                                    </span>
                                                                                </div>
                                                                                <input type="hidden" name="" id="'.$task['task_id'].'_group" 
                                                                                class="current_group">
                                                                                <input type="hidden" name="" id="'.$task['task_id'].'_user" 
                                                                                class="current_user">
                                                                            </div>
                                                                        </div>
                                                                    </div>';
                                                                    } else {
                                                                        echo '<div class="row group-item">
                                                                            <div class="col-md-12">
                                                                                <textarea class="form-control" value="" name="'.$task['category_id'].'_'.$task['task_id'].'"></textarea>
                                                                            </div>
                                                                        </div>';
                                                                    }
                                                                }
                                                            }
                                                 echo '</div>';
                                                }
                                            }
                                            
                                        echo '</div>';
                                        //End Checklist                                        

                                        //Signature
                                        echo '<div class="mb-5">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <h3 class="title-h3">VERMERKE</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3 group-item mb-0">';
                                                        foreach( $signature as $signature_comment ) {
                                                            if(($signature_comment['user_id'] == $id) && 
                                                                ($signature_comment['form_id'] = $_GET['form_id']) && 
                                                                ($type_group->id == $signature_comment['cat_parent_id']) && 
                                                                ($signature_comment['type'] =="note")) {

                                                                echo '<textarea class="form-control" 
                                                                value="'.$signature_comment['value'].'" 
                                                                id="note_'.$signature_comment['id_signature'].'"></textarea>';
                                                            }
                                                        }
                                                    echo '</div>
                                                    <div class="col-md-8">
                                                        <div class="row">';                                                    
                                                        foreach( $signature as $signature_second ) {
                                                            if(($signature_second['user_id'] = $id) && ($signature_second['form_id'] = $_GET['form_id']) && ($type_group->id == $signature_second['cat_parent_id'])  && 
                                                                ($signature_second['type'] !="note") ) {

                                                                if(empty($signature_second['has_note'])) {
                                                                echo '<div class="col-lg-6 mb-3 file-group">
                                                                <div class="e-signature">
                                                                        <div class="group mb-3">
                                                                            <canvas 
                                                                            onmouseenter="draw_sign(this.id)" 
                                                                            id="canvas-'.$signature_second['id_signature'].'" 
                                                                            class="canvas-signature" 
                                                                            width="290px" height="200px"> </canvas>
                                                                        </div>
                                                                        <div class="group mb-3">
                                                                            <button class="button-red" 
                                                                                onclick="submit_canvas(this)" 
                                                                                data-canvas="canvas-'.$signature_second['id_signature'].'" 
                                                                                data-sigimage="sigimage1-'.$signature_second['id_signature'].'" 
                                                                                data-sigdataurl="sig-dataUrl1-'.$signature_second['id_signature'].'">
                                                                                Einreichen
                                                                            </button>
                                                                            <button class="button-red" 
                                                                                id="sig-clearBtn-1" 
                                                                                onclick="clear_canvas(this)" 
                                                                                data-canvas="canvas-'.$signature_second['id_signature'].'" 
                                                                                data-sigimage="sigimage1-'.$signature_second['id_signature'].'" 
                                                                                data-sigdataurl="sig-dataUrl1-'.$signature_second['id_signature'].'">
                                                                                Löschen
                                                                            </button>
                                                                        </div>
                                                                        <input type="hidden" 
                                                                            name="" 
                                                                            id="sig-dataUrl1-'.$signature_second['id_signature'].'" 
                                                                            class="form-control" 
                                                                            rows="5" />
                                                                        <img id="sigimage1-'.$signature_second['id_signature'].'" src="" style="display: none;" width="290px" height="200px" />
                                                                    </div>
                                                                    <label class="my-4">
                                                                        <strong>'.$signature_second['name'].'</strong>
                                                                    </label>
                                                                </div>';
                                                                } else {
                                                                    echo '<div class="col-lg-6 mb-3 file-group">
                                                                    <div class="e-signature">
                                                                            <div class="group mb-3">
                                                                                <canvas onmouseenter="draw_sign(this.id)" 
                                                                                    id="canvas-'.$signature_second['id_signature'].'"
                                                                                    class="canvas-signature" 
                                                                                    width="290px" height="200px"> </canvas>
                                                                            </div>
                                                                            <div class="group mb-3">
                                                                                <button class="button-red" 
                                                                                    onclick="submit_canvas(this)" 
                                                                                    data-canvas="canvas-'.$signature_second['id_signature'].'" 
                                                                                    data-sigimage="sigimage1-'.$signature_second['id_signature'].'" 
                                                                                    data-sigdataurl="sig-dataUrl1-'.$signature_second['id_signature'].'">
                                                                                    Einreichen
                                                                                </button>
                                                                                <button class="button-red" 
                                                                                    id="sig-clearBtn-1" 
                                                                                    onclick="clear_canvas(this)" 
                                                                                    data-canvas="canvas-'.$signature_second['id_signature'].'" 
                                                                                    data-sigimage="sigimage1-'.$signature_second['id_signature'].'" 
                                                                                    data-sigdataurl="sig-dataUrl1-'.$signature_second['id_signature'].'">
                                                                                    Löschen
                                                                                </button>
                                                                            </div>
                                                                            <input type="hidden" 
                                                                                name="" 
                                                                                id="sig-dataUrl1-'.$signature_second['id_signature'].'" 
                                                                                class="form-control" rows="5" />
                                                                            <img 
                                                                                id="sigimage1-'.$signature_second['id_signature'].'" 
                                                                                src="" style="display: none;" width="290px" height="200px" />
                                                                        </div>
                                                                        <label class="my-4">
                                                                            <strong>'.$signature_second['name'].'</strong>
                                                                        </label>
                                                                    </div>';
                                                                }
                                                            }
                                                        }                                                
                                                        echo '</div>
                                                    </div>
                                                </div>';
                                    echo '</div>';

                                    //EndSignature
                                    echo '</div>';
                                }
                            ?>
                            </div>
                            <!-- End Tab Content -->
                        </div>
                    </div>
                    <!-- end Tab setion -->

                    <div class="action-checklist text-end">
                        <button id="Save" class="button-red">Speichern</button>
                        <button onclick="getPDF()" id="export-pdf" class="button-red">PDF-Export <i class="fas fa-file-export"></i></button>
                    </div>
                </div>
            </div>

            <!-- Modal Group User -->
            <div class="modal fade" id="popup-group-user" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                        <ul>
                            <?php foreach (DB::table('group_users')->get() as $group_users) { ?> 
                                <li>
                                    <div class="select-action">
                                        <div class="select-group" data-task_id="" data-group_id="<?php echo $group_users->group_id; ?>" data-group_name="<?php echo $group_users->group_name; ?>" data-user_id="<?php echo $group_users->use_id; ?>" >
                                            <?php echo $group_users->group_name; ?>
                                            <span class="loader" style="display:none;"></span>
                                        </div>                                  
                                    </div>
                                    <div class="list-user" style="display:none"></div>
                                </li>
                            <?php } ?>
                        </ul>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Modal Group User -->

            <!-- Modal Group User -->
           <!--  <div class="modal fade" id="create-new-group" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="edit-group-form form-group">
                            <form id="edit-form" method="post" action="/">
                                <div class="modal-body"></div>
                                <div class="modal-footer">
                                    <input type="submit" class="btn" id="update-group" value="Save">
                                    <input type="button" class="btn" id="new-group" value="New Group">
                                </div>
                                <input type="hidden" name="success" value="successs">
                            </form>
                        </div>
                        <div class="new-group-form form-group" style="display:none;">
                            <form id="form-group" method="post" action="{{ url('/users') }}">
                                <div class="modal-body">
                                    <h5 class="modal-title px-2 py-4">New Group</h5>
                                    <div class="name-item">
                                        <div class="group-item">
                                            <div class="form-group input-group">
                                                <span class="has-float-label">
                                                    <input type="text" class="form-control" id="group_name" placeholder="Group Name:" value="" required>
                                                    <label for="group_name">Group Name:</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-item">
                                        <div class="select-user">
                                            <span class="label-select">Select User <i class="fas fa-caret-down"></i></span>
                                            <ul class="list-user">
                                                <?php foreach (DB::table('users')->get() as $user) {
                                                    echo '<li data-id="'.$user->id.'" class="add-to-group" data-name="'.$user->name.'">'.$user->name.'</li>';
                                                } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="participants-item">
                                        <p class="modal-title mt-3 mb-2"><i class="fas fa-users"></i> <strong>Participants:</strong></p>
                                        <ul class="list-participants">                                    
                                        </ul>
                                        <input type="hidden" name="list_user" value="">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="submit" class="btn" id="save-group" value="Save">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- End Modal Group User -->
    <?php } ?>
        
    @endsection
@endguest