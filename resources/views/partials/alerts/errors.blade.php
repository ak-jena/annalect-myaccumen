@if($errors->any())
    <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="fa fa-ban"></i> Error detected:</h4>                        
        @foreach($errors->all() as $error)
            <p>{!! $error !!}</p>
        @endforeach
    </div>
@endif  