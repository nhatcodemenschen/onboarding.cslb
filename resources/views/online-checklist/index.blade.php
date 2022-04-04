@extends('layouts.public')
@section('content')

    <div class="site-main" id="boarding-unterlagen-page">
        <div class="container py-5">
            <h2 class="top-title mb-5">Übersicht</h2>
            <div class="row mb-2">
                <div class="col-md-12 error">
                    @if ( $errors->count() > 0 )
                    <p>The following errors have occurred:</p>
                    <ul>
                        @foreach( $errors->all() as $message )
                        <li class="alert alert-warning">{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if (session('msg_create'))
                        @if (session('msg_create')['code'] == 200)
                            <div class="alert alert-success">
                                {{ session('msg_create')['msg'] }}
                            </div>
                        @else
                            <div class="alert alert-warning">
                                {{ session('msg_create')['msg'] }}
                            </div>
                        @endif
                    @endif

                    @if (session('msg_update'))
                        @if (session('msg_update')['code'] == 200)
                            <div class="alert alert-success">
                                {{ session('msg_update')['msg'] }}
                            </div>
                        @else
                            <div class="alert alert-warning">
                                {{ session('msg_update')['msg'] }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="row mb-5">
                @if($current_id == '1')
                <div class="col-md-12">
                    <a href="{{ url('/boarding-unterlagen/create?manager_id=1&form_id=4') }}" class="button-main">Neuer Mitarbeiter</a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12 label-checklist text-uppercase pb-2 mb-2">
                    <div class="row">
                        <div class="checklist-id col-lg-2" data-label="AUFGABE NAME"><strong>AUFGABE NAME</strong></div> 
                        <div class="checklist-name col-lg-4" data-label="Name Mitarbeiter"><strong>Name Mitarbeiter</strong></div> 
                        <div class="entry-date col-lg-3" data-label="Eintritt Datum"><strong>Eintritt Datum</strong></div> 
                        <div class="checklist-action col-md-3"></div>                 
                    </div>
                </div>
                @if( $data )
                    @foreach( $data as $item )
                        <div class="col-md-12 list-checklist">
                            <div class="row mb-4 py-4 item align-items-center">
                                <div class="checklist-id col-lg-2" data-label="AUFGABE NAME">{{ $item['name'] }}</div> 
                                <div class="checklist-name col-lg-4" data-label="Name Mitarbeiter">{{ $item['full_name'] }}</div>
                                <div class="entry-date col-lg-3" data-label="Eintritt Datum">{{ $item['created_at'] }}</div>
                                <div class="checklist-action col-lg-3 text-right">
                                    <a href="{{ url('/boarding-unterlagen/edit') }}/{{ $item['id'] }}" class="edit-checklist">Edit</a>
                                </div>                 
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
