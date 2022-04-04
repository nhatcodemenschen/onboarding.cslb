@extends('layouts.public')
@section('content')
<style type="text/css">
    .group-item .custom-control-label {
        top: -6px;
    }
</style>
<form action="/boarding-unterlagen/update/{{ $form_id }}" method="post" id="new-form">
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
                                    <input type="text" class="form-control" name="floatingName" id="floatingName" placeholder="" value="{{ $name_checklist }}">
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
                                        @if( $list_position )
                                            @foreach( $list_position as $position )
                                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                                            @endforeach
                                        @endif
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
                                    <input type="text" class="form-control" name="manager_name" id="floatingSuperior" value="{{ $full_name_checklist }}">                                             
                                    <label for="floatingName">Name Vorgesetzter:</label>
                                    <input type="hidden" name="manager_id" value="{{ $manager_id }}">
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
                            @if( $list_tab_form )
                                @foreach( $list_tab_form as $tab_form)
                                    <li class="nav-item" role="presentation">
                                        @if( $loop->index == 0 )
                                        <a class="nav-link active" id="{{ $tab_form->key }}-tab" data-toggle="pill" href="#{{ $tab_form->key }}" role="tab" aria-controls="{{ $tab_form->key }}" aria-selected="true">{{ $tab_form->name }}</a>
                                        @else
                                        <a class="nav-link" id="{{ $tab_form->key }}-tab" data-toggle="pill" href="#{{ $tab_form->key }}" role="tab" aria-controls="{{ $tab_form->key }}" aria-selected="false">{{ $tab_form->name }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                            
                        </ul>
                    </div>
                    <!-- End Tab Nav -->

                    <!-- Tab Content -->
                    <div class="tab-content" id="pills-tabContent">
                        @if( $list_tab_form )
                            @foreach( $list_tab_form as $tab_form)
                                @if( $loop->index == 0 )
                                <div class="tab-pane fade active show" id="{{ $tab_form->key }}" role="tabpanel" aria-labelledby="{{ $tab_form->key }}-tab">
                                @else
                                <div class="tab-pane fade" id="{{ $tab_form->key }}" role="tabpanel" aria-labelledby="{{ $tab_form->key }}-tab">
                                @endif

                                    <!-- Field date -->
                                    <div class="row date-field">
                                        <?php                                       
                                        $category_date_detail = json_decode($checklist->category_date_detail, true);
                                        $item = (string)'cat_'.($loop->index + 1);
                                        $item_data = $category_date_detail[$item];
                                        if(!empty($item_data)) {
                                            foreach ($item_data as $key => $item) {
                                                if($key == 'eintritt') {
                                                    echo '<div class="group-item col-md-6 mb-5">
                                                        <div class="input-group align-items-end">';
                                                            if(!empty($item['checkbox'])) {
                                                                echo'
                                                                <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                    <input type="checkbox" name="category_date_detail[cat_'.$tab_form->tab_id.'][eintritt][checkbox]"
                                                                    class="custom-control-input" value="'.$item['datetime'].'" 
                                                                    id="entry_date_'.$tab_form->key.'" checked>
                                                                    <label class="custom-control-label" 
                                                                    for="entry_date_'.$tab_form->key.'">Eintritt Datum</label>
                                                                </div>
                                                                <input type="text" class="form-control datetimepicker-input" name="category_date_detail[cat_'.$tab_form->tab_id.'][eintritt][datetime]"
                                                                value="'.$item['datetime'].'" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                                            } else {                                                        
                                                                echo'
                                                                <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                    <input type="checkbox" name="category_date_detail[cat_'.$tab_form->tab_id.'][eintritt][checkbox]"
                                                                    class="custom-control-input" value="" id="entry_date_'.$tab_form->key.'">
                                                                    <label class="custom-control-label" for="entry_date_'.$tab_form->key.'">Eintritt Datum</label>
                                                                </div>
                                                                <input type="text" class="form-control datetimepicker-input" name="category_date_detail[cat_'.$tab_form->tab_id.'][eintritt][datetime]"
                                                                value="" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                                            }
                                                        echo '</div>
                                                    </div>';
                                                  
                                                } else {
                                                    echo '<div class="group-item col-md-6 mb-5">
                                                        <div class="input-group align-items-end">';
                                                        if(!empty($item['checkbox'])) {
                                                            echo '
                                                            <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                <input type="checkbox" name="category_date_detail[cat_'.$tab_form->key.'][austritt][checkbox]"
                                                                class="custom-control-input" value="'.$item['datetime'].'" 
                                                                id="exit_date_'.$tab_form->key.'" checked>
                                                                <label class="custom-control-label" 
                                                                for="exit_date_'.$tab_form->key.'">Austritt Datum</label>
                                                            </div>
                                                            <input type="text" class="form-control datetimepicker-input" 
                                                            name="category_date_detail[cat_'.$tab_form->tab_id.'][austritt][datetime]" value="'.$item['datetime'].'" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                                        } else {
                                                            echo '
                                                            <div class="custom-control custom-checkbox mr-sm-2 form-check">
                                                                <input type="checkbox" name="category_date_detail[cat_'.$tab_form->tab_id.'][austritt][checkbox]"
                                                                class="custom-control-input" value="" 
                                                                id="exit_date_'.$tab_form->key.'">
                                                                <label class="custom-control-label" 
                                                                for="exit_date_'.$tab_form->key.'">Austritt Datum</label>
                                                            </div>
                                                            <input type="text" class="form-control datetimepicker-input" 
                                                            name="category_date_detail[cat_'.$tab_form->tab_id.'][austritt][datetime]" value="" style="padding-right: 15px; text-align: left; padding-left: 15px;">';
                                                        }
                                                        echo '    
                                                        </div>
                                                    </div>';
                                                }
                                            }
                                        } 
                                        ?>
                                    </div>
                                    <!-- End Field date -->

                                    <!-- Checklist -->
                                    <div class="mb-5 tab-field">
                                        <h2 class="top-title">{{ $tab_form->name }}</h2>
                                        @foreach( $list_category as $category)
                                        <div class="mb-5">
                                            <!-- Sub category -->
                                            <div class="row">
                                                <div class="col-md-4 mb-3 d-none d-md-block">
                                                    @if( $category->name != 'Nach Probezeit' )
                                                        <h3 class="title-h3">DATUM und KÜRZEL erledigt</h3>
                                                    @endif
                                                </div>
                                                <div class="col-md-8 d-flex align-items-start justify-content-between">
                                                    <h3 class="title-h3">{{ $category->name }}</h3>
                                                    <div class="group-item">
                                                        <?php                                       
                                                        $category_sub_detail = json_decode($checklist->category_sub_detail, true);
                                                        if(!empty($category_sub_detail)) {
                                                            foreach ($category_sub_detail as $key => $sub) {
                                                                if($key == $category->id) {
                                                                    if(!empty($sub)) {
                                                                        echo 
                                                                        '<input type="text" class="form-control datetimepicker-input datetimepicker" 
                                                                            data-toggle="datetimepicker" 
                                                                            data-target="#datetimepicker-deadline" aria-label="Deadline" 
                                                                            style="padding-left: 15px;pointer-events: auto;" 
                                                                            placeholder="Please select a deadline" name="category_sub_detail['.$category->id.']" value="'.$sub.'">';
                                                                    } else {
                                                                        echo 
                                                                        '<input type="text" 
                                                                            class="form-control datetimepicker-input datetimepicker" 
                                                                            data-toggle="datetimepicker" 
                                                                            data-target="#datetimepicker-deadline" aria-label="Deadline" 
                                                                            style="padding-left: 15px;pointer-events: auto;" 
                                                                            placeholder="Please select a deadline" name="category_sub_detail['.$category->id.']" value="">';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Task  -->
                                            @isset( $list_task[$category->id] )  
                                                @foreach( $list_task[$category->id] as $task)   
                                                    <?php 
                                                    $task_detail = json_decode($checklist->task_detail, true);
                                                    if(isset($task_detail[$task['id']])) {
                                                        $task_checkbox = $task_detail[$task['id']]['checkbox'] ? $task_detail[$task['id']]['checkbox'] : 0;
                                                        $task_checkbox_class = $task_detail[$task['id']]['checkbox'] ? 'checked=checked' : '';
                                                        $task_checkbox_show = $task_detail[$task['id']]['checkbox'] ? 'show-name' : '';
                                                        $task_datetime = $task_detail[$task['id']]['datetime'] ? $task_detail[$task['id']]['datetime'] : '';
                                                        $task_abbreviation = $task_detail[$task['id']]['abbreviation'] ? $task_detail[$task['id']]['abbreviation'] : $abbreviations;
                                                        $ids = $task_detail[$task['id']]['ids'] ? $task_detail[$task['id']]['ids'] : '';
                                                    } else {
                                                        $task_checkbox = 0;
                                                        $task_checkbox_class = '';
                                                        $task_checkbox_show = '';
                                                        $task_datetime = '';
                                                        $task_abbreviation = $abbreviations;
                                                        $ids = ''; 
                                                    }
                                                    ?>
                                                    <div class="row group-item">  
                                                        <div class="col-md-4 mb-3 wrap-datetimepicker {{ $task_checkbox_show }}">
                                                            <input type="text" class="form-control datetimepicker-input" name="task_detail[{{ $task['id'] }}][datetime]" value="{{ $task_datetime }}">
                                                            <input type="text" name="task_detail[{{ $task['id'] }}][abbreviation]" value="{{ $task_abbreviation }}" class="form-control input-abbreviation">
                                                        </div>
                                                        <div class="col-md-8 mb-3 d-flex align-items-end">
                                                            <div class="input-group" 
                                                            data-id="{{ $task['id'] }}">
                                                                <div class="custom-control custom-checkbox form-check">
                                                                    <input class="custom-control-input" 
                                                                    type="checkbox" value="{{ $task_checkbox }}" id="{{ $category->id }}_{{ $task['id'] }}" name="task_detail[{{ $task['id'] }}][checkbox]" {{ $task_checkbox_class }}>
                                                                    <label class="custom-control-label" for="{{ $category->id }}_{{ $task['id'] }}">
                                                                    </label>
                                                                    <span>{{ $task['name'] }}
                                                                        @isset( $group_users_task[$task['id']] ) 
                                                                            <span class="list-group has-group">
                                                                                @foreach( $group_users_task[$task['id']] as $user_group)
                                                                                    <span class="edit-user" data-group-id="{{ $user_group['sub_group_id'] }}" data-group_name="{{ $user_group['sub_group_name'] }}" data-id_user="{{ $user_group['sub_user_id'] }}">{{ $user_group['sub_full_name'] }}<i class="far fa-plus"></i></span>
                                                                                @endforeach 
                                                                            </span> 
                                                                        @else 
                                                                            <span class="list-group"></span> 
                                                                        @endisset    
                                                                        
                                                                    </span>
                                                                    <span class="add-group ml-2" style="cursor: pointer;" data-add="{{ $task['id'] }}">
                                                                        <i class="far fa-plus"></i>
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" name="task_detail[{{ $task['id'] }}][cat_id]" id="{{ $task['id'] }}_group" class="current_group" value="'{{ $category->id }}">
                                                                <input type="hidden" name="task_detail[{{ $task['id'] }}][ids]" id="{{ $task['id'] }}_user" class="current_user" value="{{ $ids }}">
                                                                <input type="hidden" name="task_detail[{{ $task['id'] }}][task_id]" id="{{ $task['id'] }}_id" class="current_task" value="{{ $task['id'] }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach  
                                            @endisset      
                                        </div>
                                        @endforeach
                                        <!-- Note -->
                                        @if( $tab_form->tab_id != 2 ) 
                                        <div class="row group-item note-field">
                                            <div class="col-md-12">
                                                <?php 
                                                $note_detail = json_decode($checklist->note_detail, true);
                                                ?>
                                                @isset($note_detail[$loop->index + 1])
                                                    <textarea class="form-control" name="note_detail[{{ $tab_form->tab_id }}]">{{ $note_detail[$loop->index + 1] ?? '' }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="note_detail[{{ $tab_form->tab_id }}]"></textarea>
                                                @endisset
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <!-- End Checklist   -->
                                    
                                    <!-- Signature -->
                                    <div class="mb-5">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <h3 class="title-h3">VERMERKE</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3 group-item mb-0">
                                            @if( $tab_form->tab_id == 2)
                                                <textarea class="form-control" style="min-height:100%" name="note_detail[{{ $tab_form->tab_id }}]"></textarea>
                                            @endif
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                <?php 
                                                $signature_detail = json_decode($checklist->signature_detail, true);
                                                ?>   
                                                @if( $tab_form->tab_id == 2 )                                        
                                                    <div class="col-lg-12 mb-3 file-group">
                                                        <div class="e-signature">
                                                        @isset( $signature_detail[$tab_form->tab_id][0] ) 
                                                            <img id="sigimage_employee-{{ $tab_form->tab_id }}" src="{{ $signature_detail[$tab_form->tab_id][0] ?? '' }}">
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][0]" id="sigUrl_employee-{{ $tab_form->tab_id }}" class="form-control" value="{{ $signature_detail[$tab_form->tab_id][0] ?? '' }}"/>
                                                        @else 
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_employee-{{ $tab_form->tab_id }}"  class="border border-dark canvas_employee-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_employee-{{ $tab_form->tab_id }}">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_employee-{{ $tab_form->tab_id }}" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_employee-{{ $tab_form->tab_id }}">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][0]" id="sigUrl_employee-{{ $tab_form->tab_id }}" class="form-control" rows="5" />
                                                            <img id="sigimage_employee-{{ $tab_form->tab_id }}" src="" style="display: none;">
                                                        @endisset                                                                    
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Mitarbeite</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-12 mb-3 file-group">
                                                        <div class="e-signature">
                                                        @isset($signature_detail[$tab_form->tab_id][1])
                                                            <img id="sigimage_manager-{{ $tab_form->tab_id }}" src="{{ $signature_detail[$tab_form->tab_id][1] ?? '' }}">
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][0]" id="sigUrl_employee-{{ $tab_form->tab_id }}" class="form-control" value="{{ $signature_detail[$tab_form->tab_id][1] ?? '' }}"/>
                                                        @else 
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_manager-{{ $tab_form->tab_id }}" class="border border-dark canvas_manager-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_manager-{{ $tab_form->tab_id }}">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_manager-{{ $tab_form->tab_id }}" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_manager-{{ $tab_form->tab_id }}">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][1]" id="sigUrl_manager-{{ $tab_form->tab_id }}" class="form-control" rows="5" />
                                                            <img id="sigimage_manager-{{ $tab_form->tab_id }}" src="" style="display: none;">
                                                        @endisset                                                                       
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Manager</strong>
                                                        </label>
                                                    </div>
                                                @else                                                          
                                                    <div class="col-lg-6 mb-3 file-group">
                                                        <div class="e-signature">
                                                        @isset($signature_detail[$tab_form->tab_id][0])
                                                            <img id="sigimage_employee-{{ $tab_form->tab_id }}" src="{{ $signature_detail[$tab_form->tab_id][0] ?? '' }}">
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][0]" id="sigUrl_employee-{{ $tab_form->tab_id }}" class="form-control" value="{{ $signature_detail[$tab_form->tab_id][0] ?? '' }}"/>
                                                        @else
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_employee-{{ $tab_form->tab_id }}"  class="border border-dark canvas_employee-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_employee-{{ $tab_form->tab_id }}">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_employee-{{ $tab_form->tab_id }}" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_employee-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_employee-{{ $tab_form->tab_id }}">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][0]" id="sigUrl_employee-{{ $tab_form->tab_id }}" class="form-control" rows="5" />
                                                            <img id="sigimage_employee-{{ $tab_form->tab_id }}" src="" style="display: none;">
                                                        @endisset                                                                    
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Mitarbeite</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6 mb-3 file-group">
                                                        <div class="e-signature">
                                                        @isset($signature_detail[$tab_form->tab_id][1])
                                                            <img id="sigimage_manager-{{ $tab_form->tab_id }}" src="{{ $signature_detail[$tab_form->tab_id][1] ?? '' }}">
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][0]" id="sigUrl_employee-{{ $tab_form->tab_id }}" class="form-control" value="{{ $signature_detail[$tab_form->tab_id][1] ?? '' }}"/>
                                                        @else
                                                            <div class="group mb-3">
                                                                <canvas onmouseenter="draw_sign(this.id)" id="canvas_manager-{{ $tab_form->tab_id }}" class="border border-dark canvas_manager-signature" width="290px" height="200px"> 
                                                                </canvas>
                                                            </div>
                                                            <div class="group mb-3">
                                                                <button type="button" class="button-red" 
                                                                    onclick="submit_canvas(this)" 
                                                                    data-canvas="canvas_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_manager-{{ $tab_form->tab_id }}">
                                                                    Einreichen
                                                                </button>
                                                                <button type="button" class="button-red" 
                                                                    id="sigclear_manager-{{ $tab_form->tab_id }}" 
                                                                    onclick="clear_canvas(this)" 
                                                                    data-canvas="canvas_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigimage="sigimage_manager-{{ $tab_form->tab_id }}" 
                                                                    data-sigdataurl="sigUrl_manager-{{ $tab_form->tab_id }}">
                                                                    Löschen
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="signature_detail[{{ $tab_form->tab_id }}][1]" id="sigUrl_manager-{{ $tab_form->tab_id }}" class="form-control" rows="5" />
                                                            <img id="sigimage_manager-{{ $tab_form->tab_id }}" src="" style="display: none;">
                                                        @endisset                                                                     
                                                        </div>
                                                        <label class="my-4">
                                                            <strong>Unterschrift Manager</strong>
                                                        </label>
                                                    </div>
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <!-- EndSignature -->
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- End Tab Content -->
                    
                </div>
            </div>
            <!-- end Tab setion -->

            <div class="action-checklist text-end">
                <input type="submit" id="save" name="save_db" class="button-red" value="Speichern">
                <button onclick="getPDF()" id="export-pdf" class="button-red">PDF-Export <i class="fas fa-file-export"></i></button>
            </div>
        </div>
    </div>
</form>

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
                @if( $list_group_users )
                    @foreach( $list_group_users as $group_users)
                        <li>
                            <div class="select-action">
                                <div class="select-group" data-task_id="" data-group_id="{{ $group_users['id'] }}" data-group_name="{{ $group_users['name'] }}" data-user_id="@isset($group_users['users']){{ implode(',', $group_users['users']) }}@endisset">
                                    {{ $group_users['name'] }}
                                    <span class="loader" style="display:none;"></span>
                                </div>
                            </div>
                            <div class="list-user" style="display:none"></div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    </div>
</div>
<!-- End Modal Group User -->

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
