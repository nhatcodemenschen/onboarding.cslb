@extends('layouts.private-2')

@section('content')

<div class="py-4">
    <div class="site-main" id="boarding-unterlagen-page">
        <div class="container py-5 position-relative">
            <?php if(isset($_POST['success'])) { ?>
                <div class="alert alert-success">
                    <p><i class="fas fa-check-circle mr-2"></i>Successful!</p>
                </div>
            <?php } ?>
            <h2 class="top-title pt-2">Benutzerliste</h2>
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-right mb-4">
                        <a class="btn btn-success" href="{{ url('/users/create') }}">Neuen Benutzer Anlegen</a>
                        <button class="btn btn-success" data-toggle="modal" data-target="#create-new-group">Add New Group</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 label-checklist text-uppercase pb-2 mb-2">
                    <div class="row">
                        <div class="checklist-id col-md-1 col-lg-2"><strong>Id</strong></div> 
                        <div class="checklist-name col-md-4 col-lg-3"><strong>Name Mitarbeiter</strong></div> 
                        <div class="checklist-email col-md-3 col-lg-3"><strong>Email</strong></div>
                        {{-- <div class="entry-date col-lg-2"><strong>Eintritt Datum</strong></div>  --}}
                        <div class="text-right col-md-4 col-lg-4"><strong>Action</strong></div>                 
                    </div>
                </div>
                <div class="col-md-12 list-checklist">
                    @isset ($users)
                        @foreach ($users as $user)
                            <div class="row mb-4 py-4 item align-items-center">
                                <div class="checklist-id col-md-1 col-lg-2" data-label="ID">{{ $user->id }}</div>
                                <div class="checklist-name col-md-4 col-lg-3" data-label="Name Mitarbeiter">{{ $user->name }}</div>
                                <div class="checklist-email col-md-3 col-lg-3" data-label="Email">{{ $user->email }}</div>
                                {{-- <div class="entry-date col-lg-2" data-label="Eintritt Datum">10.01.2022</div> --}}
                                <div class="text-right col-md-4 col-lg-4">
                                    <a class="btn btn-primary" href="{{ url('/users/edit') }}/{{ $user->id }}">Edit</a>
                                    <button type="submit" class="btn btn-danger delete-user remove-checklist" id="{{ $user->id }}">Remove</button>
                                </div>           
                            </div>
                        @endforeach
                    @endisset   
                </div>
                <div class="navigation">
                    @isset ($users)
                        {!! $users->links() !!}
                    @endisset
                </div>
            </div>
            <!-- Popup Delete User -->
            <div class="modal popup-delete" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Are you sure you want to delete this user?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary deletebtn">Yes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
    		<!-- End Popup Delete User -->

            <!-- Modal Group User -->
            <div class="modal fade" id="create-new-group" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">New Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form-group" class="form-group" method="post" action="{{ url('/users') }}">
                            <div class="modal-body">
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
                            <input type="hidden" name="success" value="successs">
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal Group User -->
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $(".delete-user").click(function(e){
            e.preventDefault();
            $('.popup-delete').modal('show');
            var user_id = $(this).attr('id');
            var add_userId = $('.deletebtn').attr('id', user_id);
        });
        $(".deletebtn").click(function(){
            var BASE_URL = {!! json_encode(url('/')) !!}
            var user_current = $(this).attr('id');
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                header:{
                    'X-CSRF-TOKEN': token
                },
                url: BASE_URL + "/delete",
                type: 'POST',
                dataType: 'json',
                data: {
                    UserId: user_current,
                },
                success: function(res) {
                    $('.popup-delete').modal('hide');
                    if(res.delete == 1) {
                        alert("Delete user success!");
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }else{
                        // $('.popup-delete').modal('hide');
                        alert('Cant deleted User!');
                    }
                    
                }
            });
        });
        $('.add-to-group').click(function(){
            var list_added = $('input[name="list_user"]').val();
            var arg_group_id = list_added.split(",");

            if(arg_group_id[0] == "") {
                arg_group_id.shift();
            }
            var current_id = $(this).data('id')
            arg_group_id.push(current_id);

            $('input[name="list_user"]').val(arg_group_id.toString());

            var current_text = $(this).data('name');

            $(this).css({"pointer-events":"none","opacity":"0.4"});

            $(this).closest('#form-group').find('ul.list-participants').append('<li>'+current_text+'<span class="remove-item" data-id="'+current_id+'"><i class="far fa-times-circle"></i></span></li>');
        });
        $(document).on('click','.remove-item', function(){
            $('.list-user').slideUp();
            var current_id = $(this).data('id');
            var list_added = $('input[name="list_user"]').val();
            var arg_group_id = list_added.split(",");
            var index = arg_group_id.indexOf(current_id);
            arg_group_id.splice(index, 1);

            $('input[name="list_user"]').val(arg_group_id.toString());

            $(this).closest('#form-group').find('li[data-id="'+current_id+'"]').removeAttr('style');
            $(this).closest('li').remove();
        })
        $('#create-new-group').on('hidden.bs.modal', function (e) {
            $('input[name="list_user"]').val("");
            $('#group_name').val("");
            $('#create-new-group .error').remove();
            $('.list-user').slideUp();
            $('.list-user li').removeAttr('style');
            $('.list-participants li').remove();

        })
        $('.label-select').click(function(){
            $(this).next().slideToggle();
        });

        if($('.alert-success').length) {
            setTimeout(function(){
                $('.alert-success').remove();
            }, 2000);            
        }
        $("#create-new-group #form-group").submit(function(event){
            event.preventDefault();
            var BASE_URL = {!! json_encode(url('/')) !!};
            var name_group = $('#group_name').val();
            var list_user = $('input[name="list_user"]').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            if(name_group=="") {
                $('#group_name').css('border-color','red');
                $('#form-group .input-group').append('<p class="error" style="padding: 7px 15px;color:red;font-size: 13px;">Please enter the group name</p>');
            } else {
                $('#form-group .input-group .error').remove();
                $('#group_name').removeAttr('style');
                $.ajax({
                    header:{
                        'X-CSRF-TOKEN': token
                    },
                    url: BASE_URL + "/create-group",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        name_group:name_group,
                        list_user:list_user,
                    },
                    success: function(res) {
                        if(res.create == 1) {
                            setTimeout(function(){
                                location.reload();
                            }, 2000);
                        }else{
                            $('#form-group .input-group').append('<p class="error" style="padding: 7px 15px;color:red;font-size: 13px;">Group already exists</p>');
                        }          
                    },
                    error: function() {
                        console.log("error");
                    }
                });
            }
        });

    });
</script>
@endsection