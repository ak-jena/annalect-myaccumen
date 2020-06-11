<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800'>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300">
<link href="{{ asset('/css/timeline.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/admin-forms.css')}}" rel="stylesheet" type="text/css" />

@extends('app')
@section('title','Accuen')
@section('subtitle', '<i class="fa fa-comments-o"></i> Comments: <span class="text-olive">' .$brief->campaign_name. '</span>')
@section('content')

    <div class="box box-info">
        <div class="box-header">
            @include('partials.alerts.errors')
        </div>
        <div class="box-body bg-gray-light">
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-default" href="{{ route('workflow', ['campaign_id' => $brief->campaign->id]) }}" role="button">View Campaign</a>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-9">
                    <div class="timeline-page mt30 bg-gray-light">
                        <div id="tl-main">
                            <section id="content_wrapper">
                                <section id="tl-content">
                                    <div class="mt5 timeline-single" id="timeline">
                                        <div class="timeline-divider mtn">
                                            <div  id='msg_top' href='#msg_top' class="divider-label">
                                                Today<br>
                                                <small>{{ date('d/m/Y') }}</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 left-column">
                                                <div class="timeline-item">
                                                    <div class="timeline-icon">
                                                        <img src="{{ Baselib::get_gravatar(Auth::user()->email) }}" class="accuen-user-image"/>
                                                    </div>
                                                    <div class="panel panel-transparent">
                                                        <div class="p0">
                                                            {!! Form::open([
                                                                'route' => 'add-comment'

                                                            ]) !!}
                                                            <input type="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                                                            <input type="hidden" name="brief_id" value="{{ $brief->id }}">
                                                            <input type="hidden" name="redirect" value="{{ $redirect }}">
                                                            <div class="admin-form">
                                                                <label for="comment_msg" class="field prepend-icon">
                                                                    <span class="input-group-addon"><i class="fa fa-edit"></i> <span class="text-bold">{{\Baselib::getRealUserFirstName()}}, would you like to add something?</span></span>
                                                                    <div class="box">
                                                                        <div class="box-body pad">
                                                                            <textarea id="comment" name="comment" rows="10" cols="80" style="visibility: hidden; display: none;"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            {!! Form::submit('Submit', ['class' => 'btn-sm btn-primary pull-right']) !!}<br>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @php $sorted_comments = $brief->comments->sortByDesc('created_at'); @endphp
                                            @foreach($sorted_comments as $comment)
                                                <div class="col-sm-6 left-column">
                                                    <div class="timeline-item">
                                                        <div class="timeline-icon">
                                                            <img src="{{Baselib::get_gravatar($comment->author->email)}}" class="accuen-user-image"/>
                                                        </div>
                                                        <div class="panel panel-success">
                                                            <div class="@if($comment->author->name == 'System Message') system-message @endif panel-body p20">
                                                                <p>
                                                                    <small><span class="fa fa-clock-o text-danger text-bold"> {{$comment->created_at->format('d/m/Y H:i:s')}}</span></small>
                                                                </p>
                                                                <span class="text-olive text-bold">{{$comment->author->name}}</span>: {!! $comment->body !!}
                                                                @if($loop->first)
                                                                    @if($comment->author->id == \Auth::user()->id)
                                                                        <a href="{{route('edit-comment', ['brief_id' => $brief->id, 'comment_id'=>$comment->id])}}"><span class="fa fa-pencil text-orange pull-right tooltips" data-original-title="Edit this comment" data-toggle="tooltip" data-placement="top"></span></a>
                                                                        <a href="javascript:void(0);" class="confirmation" data-remote="{{$comment->id}}"><span class="fa fa-trash text-danger pull-right tooltips" data-original-title="Delete this comment" data-toggle="tooltip" data-placement="top"></span>
                                                                            {{ Form::open(['id'=>'form-'.$comment->id, 'method'  => 'delete', 'route' => ['destroy-comment', $brief->id, $comment->id] ]) }}
                                                                            {{ Form::close() }}
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            @if(isset($comment->updated_at) && !empty($comment->updated_at))
                                                                <div class="panel-footer">
                                                                    <small>Last edited: <span  class="text-bold text-purple">{{ $comment->author->name }}</span>
                                                                        on <span  class="text-bold text-purple">{{ $comment->updated_at->format('d/m/Y H:i:s') }}</span>
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="timeline-divider">
                                            <div id='msg_bottom' href='#msg_bottom' class="divider-label">
                                                Brief Created<br>
                                                <small>{{ \Carbon\Carbon::parse($brief->created_at)->format('d/m/Y')  }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>

    <script>
        $(function () {
            // Replace the <textarea id="comment"> with a CKEditor instance
            CKEDITOR.replace('comment');
        });
        $('.confirmation').click(function (e) {
            var id = $(this).attr('data-remote');
            swal({
                        title: "Are you sure?",
                        text: "Campaign comment will be permanently deleted",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "Cancel",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $('#form-'+id).submit();
                        }
                    });
            return false;
        });
    </script>

@endsection('content')