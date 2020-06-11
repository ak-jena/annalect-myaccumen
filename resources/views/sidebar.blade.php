<script type="text/javascript">
    $(document).ready(function() {
        $("#viewas").select2({
            placeholder: 'View as...',
            allowClear: true
        });
        $(function() {
          $('#viewas').on('change', function(e) {
              $(this).closest('form').trigger('submit');
          });
        });      
    });
</script>

<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ Baselib::get_gravatar(Auth::user()->email) }}" class="img-circle"/>
      </div>
      <div class="pull-left info">
        <p>{{Auth::user()->name}}</p>
        <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <?php if(\Baselib::hasViewAs()): ?>  
        <!-- View-as form -->
        @inject('DB','DB')
        <?php
            $roles = DB::table('roles')->orderBy('id','asc')->pluck('name', 'id')->toArray();
            $current_role_id = (\Session::has('user_role_id') ? \Session::get('user_role_id') : '');

        ?>
        <form action="{{url('/viewas')}}" method="get" class="sidebar-form">
          {!! csrf_field() !!}  
          <div class="input-group">
            <select id="viewas" name="viewas" class="form-control" style="width: 210px">
                <option value=""></option>
                @foreach($roles as $role_id => $role_name)
                    <option value="{{ $role_id }}" <?php if($role_id==$current_role_id){echo ' selected="selected"';}?> >{{$role_name}}</option>
                @endforeach
            </select>
          </div>
        </form>
    <?php endif; ?>
        
    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">MENU</li>
      <!-- Optionally, you can add icons to the links -->
      <li><a href="{{url('/')}}"><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>
      <li><a href="{{url('cancelled-campaigns') }}/"><i class='fa fa-dashboard'></i> <span>Cancelled</span></a></li>
      <li><a href="{{url('/live-campaigns')}}"><i class='fa fa-dashboard'></i> <span>Live</span></a></li>
      <li><a href="{{url('/completed-campaigns')}}"><i class='fa fa-dashboard'></i> <span>Complete</span></a></li>
      <li><a href="{{ Storage::disk('public')->url('misc/myaccuen-booking-engine-user-guide-agency-v4.pdf') }}" target="_blank"><i class='fa fa-question'></i> <span>Help</span></a></li>
      @if(\Baselib::isDeveloperUser() || Baselib::hasUserMgmt())
        <li class="header">ADVANCED MENU</li>
        <li><a href="{{url('/user')}}"><i class='fa fa-users'></i> <span>User Management</span></a></li>
      @endif

      @if(\Baselib::isDeveloperUser() || Baselib::isHeadOfActivation())
        <li><a href="{{url('/reporting')}}"><i class='fa fa-columns'></i> <span>Reporting</span></a></li>
      @endif

      @if(\Baselib::isDeveloperUser())
        <li><a href="{{url('/agency')}}"><i class='fa fa-institution'></i> <span>Agency Management</span></a></li>
        <li><a href="{{url('/client')}}"><i class='fa fa-suitcase'></i> <span>Client Management</span></a></li>
        <li><a href="{{url('targeting-grid-poc')}}"><i class='fa fa-bug'></i> <span>Targeting Grid POC</span></a></li>
        <li><a href="{{url('grid/960')}}"><i class='fa fa-table'></i> <span>Targeting Grid</span></a></li>
        <li><a href="{{url('ag-grid')}}"><i class='fa fa-table'></i> <span>Targeting Grid (Ag-Grid)</span></a></li>


        <li><a href="{{url('/announcement')}}"><i class='fa fa-bullhorn'></i> <span>Announcements</span></a></li>
        <li><a href="{{url('/debug')}}"><i class='fa fa-bug'></i> <span>Debug</span></a></li>
      @endif
</ul><!-- /.sidebar-menu -->
</section>
</aside>