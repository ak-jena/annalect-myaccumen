<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800'>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300">
<link href="{{ asset('/css/timeline.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/admin-forms.css')}}" rel="stylesheet" type="text/css" />


@extends('app')
@section('title','Accuen')
@section('subtitle', '<i class="fa fa-edit"></i> Edit your comment')
@section('content')

    <div class="box box-info">
        <div class="box-header">
            @include('partials.alerts.errors')
        </div>
        <div class="box-body bg-gray-light">
            <div class="row">
                <div class="form-group col-md-9">

                    {!! Form::open([
                        'route' => 'update-comment'
                    ]) !!}
                    <input type="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <div class="admin-form">
                        <label for="comment_msg" class="field prepend-icon">
                            <span class="input-group-addon"><i class="fa fa-edit"></i> <span class="text-bold">Edit your comment</span></span>
                            <div class="box">
                                <div class="box-body pad">
                                    <textarea id="comment" name="comment" rows="10" cols="80" style="visibility: hidden; display: none;">{!! $comment->body !!}</textarea>
                                </div>
                            </div>
                        </label>
                    </div>
                    {!! Form::submit('Update', ['class' => 'btn-sm btn-primary pull-right']) !!}<br>
                    {!! Form::close() !!}

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
    </script>
@endsection('content')