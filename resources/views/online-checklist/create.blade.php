<?php use Illuminate\Support\Facades\Auth; ?>
@extends('layouts.public')
@section('content')
<style type="text/css">
    .group-item .custom-control-label {
        top: -6px;
    }
</style>
<?php $home_link = url('/');
$current_id = Auth::id();
$form_id = $_GET['form_id'];
$manager_id = $_GET['manager_id'];
$currentURL = url()->current();

$abbreviations = DB::table('users')->where('id', $current_id)->value('abbreviations');
$form_data = DB::table('form_data')->where("form_id",$form_id)->get();
$category = DB::table('category')->get();
$tab_form = DB::table('tab_form')->get();
$args_form = array();
foreach($form_data as $form_data) {
    $args_form['form_id'] = $form_data->form_id;
    $args_form['entry_date'] = $form_data->entry_date;
    $args_form['exit_date'] = $form_data->exit_date;
    $args_form['group_deadline'] = $form_data->group_deadline;
    $args_form['note'] = $form_data->note;
    $args_form['employee_signature'] = $form_data->employee_signature;
    $args_form['manager_signature'] = $form_data->manager_signature;
    $args_form['manage_id'] = $form_data->manage_id;
}
$group_deadline = json_decode($args_form['group_deadline']);
$list_note = json_decode($args_form['note']);
if(isset($_GET['id'])) {
    $id = $_GET['id']; 
} else {
    $id ="";
} ?>
<form action="{{ route('store.checklist') }}" method="post" id="new-form">
    {{ csrf_field() }}
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
                                    <input type="text" class="form-control" name="floatingName" id="floatingName" placeholder="" value="<?php echo DB::table('users')->where('id', $id)->value('full_name') ?>">
                                    <label for="floatingName">Name Mitarbeiter:</label>
                                </span>
                            </div>
                        </div>
                        <!-- end Name Mitarbeiter -->
                        <!-- Position -->
                        <div class="group-item col-md-12">
                            <div class="form-group input-group">
                                <span class="has-float-label">
                                    <select class="form-select form-control" name="floatingSelect" id="floatingSelect" aria-label="Funktion/Position:">
                                        <?php $index=0;
                                        foreach(DB::table('position')->get() as $position) {
                                            if($index==0) {
                                                echo '<option value="'.$position->id.'" selected>'.$position->name.'</option>';
                                            } else {
                                                echo '<option value="'.$position->id.'">'.$position->name.'</option>';
                                            }
                                            $index++; 
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
                                    <?php $manager_user = DB::table('users')->where('id', $args_form['manage_id'])->value('full_name');
                                        echo ' <input type="text" class="form-control" name="manager_name" id="floatingSuperior" placeholder="" value="'.$manager_user.'">'; 
                                        ?>                                                     
                                    <label for="floatingName">Name Vorgesetzter:</label>
                                    <input type="hidden" name="manager_id" value="{{$manager_id}}">
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
                        foreach ($tab_form as $tab_form) { 
                            echo '<li class="nav-item" role="presentation">';
                            if($i==0) { 
                                echo '<a class="nav-link active" id="'.$tab_form->key.'-tab" data-toggle="pill" href="#'.$tab_form->key.'" role="tab" aria-controls="'.$tab_form->key.'" aria-selected="true">'.$tab_form->name.'</a>';
                            } else {
                                echo '<a class="nav-link" id="'.$tab_form->key.'-tab" data-toggle="pill" href="#'.$tab_form->key.'" role="tab" aria-controls="'.$tab_form->key.'" aria-selected="false">'.$tab_form->name.'</a>';
                            } 
                            echo '</li>';
                            $i++;
                        } ?>
                        </ul>
                    </div>
                    <!-- End Tab Nav -->


                    <!-- Tab Content -->
                    <div class="tab-content" id="pills-tabContent">
                        <?php 
                        $tab_i = 0; 
                        foreach (DB::table('tab_form')->get() as $type_group) { 
                            $tab_form_id = $type_group->tab_id;
                            $note_tab = $list_note->$tab_form_id;
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
                                $entry_date = json_decode($args_form['entry_date']);
                                $exit_date = json_decode($args_form['exit_date']);
                                if(isset($entry_date->$tab_form_id)) {
                                    $tab_entry = $entry_date->$tab_form_id;
                                    echo '<div class="group-item col-md-6 mb-5">
                                        <div class="input-group align-items-end">';
                                            if( isset($tab_entry->$id) && !empty($tab_entry->$id) ) {
                                                echo'
                                                <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                    <input type="checkbox" name="category_date_detail[cat_'.$type_group->tab_id.'][eintritt][checkbox]"
                                                    class="custom-control-input" value="" 
                                                    id="entry_date_'.$type_group->key.'" checked>
                                                    <label class="custom-control-label" 
                                                    for="entry_date_'.$type_group->key.'">Eintritt Datum</label>
                                                </div>
                                                <input type="text" class="form-control datetimepicker-input" name="category_date_detail[cat_'.$type_group->tab_id.'][eintritt][datetime]"
                                                value="'.$tab_entry->$id.'" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                            } else {                                                        
                                                echo'
                                                <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                    <input type="checkbox" name="category_date_detail[cat_'.$type_group->tab_id.'][eintritt][checkbox]"
                                                    class="custom-control-input" value="" id="entry_date_'.$type_group->key.'">
                                                    <label class="custom-control-label" for="entry_date_'.$type_group->key.'">Eintritt Datum</label>
                                                </div>
                                                <input type="text" class="form-control datetimepicker-input" name="category_date_detail[cat_'.$type_group->tab_id.'][eintritt][datetime]"
                                                value="" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                            }
                                        echo '</div>
                                    </div>';
                                }
                                if(isset($exit_date->$tab_form_id)) {
                                    $exit_entry = $exit_date->$tab_form_id;
                                    echo '<div class="group-item col-md-6 mb-5">
                                        <div class="input-group align-items-end">';
                                        if( isset($exit_entry->$id) && !empty($exit_entry->$id) ) {
                                            echo '
                                            <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                <input type="checkbox" name="category_date_detail[cat_'.$type_group->key.'][austritt][checkbox]"
                                                class="custom-control-input" value="" 
                                                id="exit_date_'.$type_group->key.'" checked>
                                                <label class="custom-control-label" 
                                                for="exit_date_'.$type_group->key.'">Austritt Datum</label>
                                            </div>
                                            <input type="text" class="form-control datetimepicker-input" 
                                            name="category_date_detail[cat_'.$type_group->tab_id.'][austritt][datetime]" value="'.$exit_entry->$id.'" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                        } else {
                                            echo '
                                            <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                <input type="checkbox" name="category_date_detail[cat_'.$type_group->tab_id.'][austritt][checkbox]"
                                                class="custom-control-input" value="" 
                                                id="exit_date_'.$type_group->key.'">
                                                <label class="custom-control-label" 
                                                for="exit_date_'.$type_group->key.'">Austritt Datum</label>
                                            </div>
                                            <input type="text" class="form-control datetimepicker-input" 
                                            name="category_date_detail[cat_'.$type_group->tab_id.'][austritt][datetime]" value="" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                        }
                                        echo '    
                                        </div>
                                    </div>';
                                }
                                echo '</div>';
                                //End Field date


                                //Checklist
                                echo '<div class="mb-5 tab-field">';
                                    echo '<h2 class="top-title">'.$type_group->name.'</h2>';
                                    foreach(DB::table('category')->where('tab_id',$tab_form_id)->get() as $category) {
                                    $cat_id = $category->id;
                                    echo'<div class="mb-5">
                                        <div class="row">
                                            <div class="col-md-4 mb-3 d-none d-md-block">';
                                                if($category->name != 'Nach Probezeit') {
                                                    echo '<h3 class="title-h3">DATUM und KÜRZEL erledigt</h3>';
                                                }
                                            echo '</div>';
                                            echo '<div class="col-md-8 d-flex align-items-start justify-content-between">
                                                <h3 class="title-h3">'.$category->name.'</h3>
                                                <div class="group-item">';
                                                    if(!empty($group_deadline->$cat_id)) {
                                                        echo 
                                                        '<input type="text" class="form-control datetimepicker-input datetimepicker" 
                                                            data-toggle="datetimepicker" 
                                                            data-target="#datetimepicker-deadline" aria-label="Deadline" 
                                                            style="padding-left: 15px;pointer-events: auto;" 
                                                            placeholder="Please select a deadline" name="category_sub_detail['.$cat_id.']" value="'.$group_deadline->$cat_id.'">';
                                                    } else {
                                                        echo 
                                                        '<input type="text" 
                                                            class="form-control datetimepicker-input datetimepicker" 
                                                            data-toggle="datetimepicker" 
                                                            data-target="#datetimepicker-deadline" aria-label="Deadline" 
                                                            style="padding-left: 15px;pointer-events: auto;" 
                                                            placeholder="Please select a deadline" name="category_sub_detail['.$cat_id.']" value="">';
                                                    }
                                                echo'
                                                </div>
                                            </div>';
                                        echo '</div>';

                                        foreach(DB::table('list_task')->where("category_id",$cat_id)->get() as $task) {
                                            echo ' <div class="row group-item">  
                                                <div class="col-md-4 mb-3 wrap-datetimepicker">
                                                    <input type="text" class="form-control datetimepicker-input" name="task_detail['.$task->id.'][datetime]" value="">
                                                    <input type="text" name="task_detail['.$task->id.'][abbreviation]" value="'.$abbreviations.'" class="form-control input-abbreviation">
                                                </div>
                                                <div class="col-md-8 mb-3 d-flex align-items-end">
                                                    <div class="input-group" 
                                                    data-id="'.$task->id.'">
                                                        <div class="custom-control custom-checkbox form-check">
                                                            <input class="custom-control-input" 
                                                            type="checkbox" value="" id="'.$cat_id.'_'.$task->id.'" name="task_detail['.$task->id.'][checkbox]">
                                                            <label class="custom-control-label" for="'.$cat_id.'_'.$task->id.'">
                                                            </label>
                                                            <span> '.$task->name.'<span class="list-group"></span> </span>
                                                            <span class="add-group ml-2" style="cursor: pointer;" data-add="'.$task->id.'">
                                                                <i class="far fa-plus"></i>
                                                            </span>
                                                        </div>
                                                        <input type="hidden" name="task_detail['.$task->id.'][cat_id]" id="'.$task->id.'_group" class="current_group" value="'.$cat_id.'">
                                                        <input type="hidden" name="task_detail['.$task->id.'][ids]" id="'.$task->id.'_user" class="current_user">
                                                        <input type="hidden" name="task_detail['.$task->id.'][task_id]" id="'.$task->id.'_id" class="current_task" value="'.$task->id.'">
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    echo '</div>';
                                    }
                                    if($tab_form_id != 2) {
                                        echo '<div class="row group-item note-field">
                                            <div class="col-md-12">';
                                            if(isset($note_tab->$id)) {
                                                echo '<textarea class="form-control" name="note_detail['.$tab_form_id.']">'.$note_tab->$id.'</textarea>';
                                            } else {
                                                echo '<textarea class="form-control" name="note_detail['.$tab_form_id.']"></textarea>';
                                            }
                                            echo '
                                            </div>
                                        </div>';
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
                                            if($tab_form_id == 2) {
                                                if(isset($note_tab->$id)) {
                                                    echo '<textarea class="form-control" style="min-height:100%" name="note_detail['.$tab_form_id.']">'.$note_tab->$id.'</textarea>';
                                                } else {
                                                    echo '<textarea class="form-control" style="min-height:100%" name="note_detail['.$tab_form_id.']"></textarea>';
                                                }
                                            }
                                            echo '</div>';
                                            echo '<div class="col-md-8">
                                                <div class="row">';
                                                //Get value signature
                                                $employee_signature = json_decode(DB::table('form_data')->where("form_id",$form_id)->value('employee_signature'));
                                                $employee_signature_tab = $employee_signature->$tab_form_id;
                                                if(isset($employee_signature_tab->$id) && !empty($employee_signature_tab->$id)) {
                                                    $employee_signature_value = $employee_signature_tab->$id;
                                                } 
                                                $manager_signature = json_decode(DB::table('form_data')->where("form_id",$form_id)->value('manager_signature'));
                                                $manager_signature_tab = $manager_signature->$tab_form_id;
                                                if(isset($manager_signature_tab->$id) && !empty($manager_signature_tab->$id)) {
                                                    $manager_signature_value = $manager_signature_tab->$id;
                                                } 
                                                //display signature if form_id = 2
                                                if($tab_form_id==2) {                                                            
                                                    echo 
                                                    '<div class="col-lg-12 mb-3 file-group">
                                                        <div class="e-signature">';
                                                        if(!empty(isset($employee_signature_value))) {
                                                            echo '
                                                            <img id="sigimage_employee-'.$tab_form_id.'" src="'.$employee_signature_value.'">

                                                            ';
                                                        } else {
                                                            echo '
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_employee-'.$tab_form_id.'"  class="border border-dark canvas_employee-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_employee-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_employee-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_employee-'.$tab_form_id.'">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_employee-'.$tab_form_id.'" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_employee-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_employee-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_employee-'.$tab_form_id.'">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail['.$tab_form_id.'][0]" id="sigUrl_employee-'.$tab_form_id.'" class="form-control" rows="5" />
                                                            <img id="sigimage_employee-'.$tab_form_id.'" src="" style="display: none;">
                                                            ';
                                                        }                                                                    
                                                        echo' 
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Mitarbeite</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-12 mb-3 file-group">
                                                        <div class="e-signature">';
                                                        if(!empty(isset($manager_signature_value))) {
                                                            echo '
                                                            <img id="sigimage_manager-'.$tab_form_id.'" src="'.$manager_signature_value.'">';
                                                        } else {
                                                            echo '
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_manager-'.$tab_form_id.'" class="border border-dark canvas_manager-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_manager-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_manager-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_manager-'.$tab_form_id.'">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_manager-'.$tab_form_id.'" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_manager-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_manager-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_manager-'.$tab_form_id.'">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail['.$tab_form_id.'][1]" id="sigUrl_manager-'.$tab_form_id.'" class="form-control" rows="5" />
                                                            <img id="sigimage_manager-'.$tab_form_id.'" src="" style="display: none;">
                                                            ';
                                                        }                                                                    
                                                        echo' 
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Manager</strong>
                                                        </label>
                                                    </div> ';
                                                } else {                                                          
                                                    echo 
                                                    '<div class="col-lg-6 mb-3 file-group">
                                                        <div class="e-signature">';
                                                        if(!empty(isset($employee_signature_value))) {
                                                            echo '
                                                            <img id="sigimage_employee-'.$tab_form_id.'" src="'.$employee_signature_value.'">

                                                            ';
                                                        } else {
                                                            echo '
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_employee-'.$tab_form_id.'"  class="border border-dark canvas_employee-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_employee-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_employee-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_employee-'.$tab_form_id.'">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_employee-'.$tab_form_id.'" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_employee-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_employee-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_employee-'.$tab_form_id.'">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail['.$tab_form_id.'][0]" id="sigUrl_employee-'.$tab_form_id.'" class="form-control" rows="5" />
                                                            <img id="sigimage_employee-'.$tab_form_id.'" src="" style="display: none;">
                                                            ';
                                                        }                                                                    
                                                        echo' 
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Mitarbeite</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6 mb-3 file-group">
                                                        <div class="e-signature">';
                                                        if(!empty(isset($manager_signature_value))) {
                                                            echo '
                                                            <img id="sigimage_manager-'.$tab_form_id.'" src="'.$manager_signature_value.'">';
                                                        } else {
                                                            echo '
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_manager-'.$tab_form_id.'" class="border border-dark canvas_manager-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_manager-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_manager-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_manager-'.$tab_form_id.'">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_manager-'.$tab_form_id.'" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_manager-'.$tab_form_id.'" 
                                                                    data-sigimage="sigimage_manager-'.$tab_form_id.'" 
                                                                    data-sigdataurl="sigUrl_manager-'.$tab_form_id.'">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail['.$tab_form_id.'][1]" id="sigUrl_manager-'.$tab_form_id.'" class="form-control" rows="5" />
                                                            <img id="sigimage_manager-'.$tab_form_id.'" src="" style="display: none;">
                                                            ';
                                                        }                                                                    
                                                        echo' 
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Manager</strong>
                                                        </label>
                                                    </div> ';
                                                }
                                                echo'
                                                </div>
                                            </div>';
                                        echo '
                                    </div>';
                            echo '
                            </div>';

                            // //EndSignature
                            echo '</div>';  
                        }
                    ?>
                    </div>
                    <!-- End Tab Content -->
                </div>
            </div>
            <!-- end Tab setion -->

            <div class="action-checklist text-end">
                <input type="hidden" name="form_id" value="{{$form_id}}">
                <input type="submit" id="save" name="save_db" class="button-red" value="Speichern">
                <button onclick="getPDF()" id="export-pdf" class="button-red">PDF-Export <i class="fas fa-file-export"></i></button>
            </div>
        </div>
    </div>
</form>
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
                    <?php foreach (DB::table('group_users')->get() as $group_users) {
                        $group_id = $group_users->group_id;
                        $exits_group_id = DB::table('users')->where('group_id',$group_id)->value('id');
                        $data_id = DB::table('users')->where('group_id',$group_id)->get();
                        if(isset($exits_group_id)) { 
                            $list_id = array();
                            foreach($data_id as $data_id) {
                                array_push($list_id,$data_id->id);
                            }
                            ?>
                            <li>
                                <div class="select-action">
                                    <div class="select-group" data-task_id="" data-group_id="<?php echo $group_users->group_id; ?>" data-group_name="<?php echo $group_users->group_name; ?>" data-user_id="<?php echo implode(",", $list_id); ?>" >
                                        <?php echo $group_users->group_name; ?>
                                        <span class="loader" style="display:none;"></span>
                                    </div>
                                </div>
                                <div class="list-user" style="display:none"></div>
                            </li>
                        <?php } else {
                        }
                    } ?>
                </ul>
            </div>
        </div>
        </div>
    </div>
    <!-- End Modal Group User -->
    <script type="text/javascript">
        // jQuery(document).ready(function(){
        //     $('#new-form').on('submit',function(event){
        //         var form_data =  $('#new-form').serializeArray();
        //         event.preventDefault();
        //         var BASE_URL = {!! json_encode(url('/')) !!}
        //         var user_current = $(this).attr('id');
        //         var token = $('meta[name="csrf-token"]').attr('content');
        //         $.ajax({
        //             header:{
        //                 'X-CSRF-TOKEN': token
        //             },
        //             url: BASE_URL + "/save-new-form",
        //             type: 'POST',
        //             dataType: 'json',
        //             data: {
        //                 form_data
        //             },
        //             success: function(res) {
        //                 console.log('yes');
        //                 location.reload();
        //                 // if(res.success == 1) {
        //                 //     console.log('yes');
        //                 //     // setTimeout(function(){
        //                 //     //     location.reload();
        //                 //     // }, 1000);
        //                 // }else{
        //                 //     console.log('no')
        //                 // }
                        
        //             }
        //         });
        //     })
        // });
    </script>
<script>
    $( document ).ready(function() {
        $(document).on('click', '.custom-checkbox.form-check', function(){
            let checkbox_custom = $(this).find('.custom-control-input');
            if(checkbox_custom.prop("checked")) {
                checkbox_custom.attr('checked', 'checked');
                checkbox_custom.val(1);
            } else {
                checkbox_custom.removeAttr('checked');
                checkbox_custom.val(0);
            }
        });
    });
</script>
@endsection
