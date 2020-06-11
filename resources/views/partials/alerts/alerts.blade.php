<script type="text/javascript">
$(document).ready(function() {       
    $("#app-alert").fadeTo(10000, 500).slideUp(500, function(){
        $("#app-alert").alert('close');
    });        
});

</script>

@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible" id="app-alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-thumbs-o-up"></i> Success:</h4>
        {!! Session::get('success') !!}
    </div>                    
@endif
@if (Session::has('info'))
    <div class="alert alert-info alert-dismissible" id="app-alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-info-circle"></i> Infomation:</h4>
        {!! Session::get('info') !!}
    </div>                    
@endif 
@if (Session::has('warning'))
    <div class="alert alert-warning alert-dismissible" id="app-alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-warning"></i> Warning:</h4>
        {!! Session::get('warning') !!}
    </div>                    
@endif  
@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible" id="app-alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-ban"></i> Error:</h4>
        {!! Session::get('error') !!}
    </div>                    
@endif  