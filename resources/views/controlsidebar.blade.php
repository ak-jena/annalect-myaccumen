<script type="text/javascript">
$(document).ready(function() {    
    var elem = document.querySelector('.js-switch-csb');
    var init = new Switchery(elem, {size: 'small', color: '#00a65a', jackColor: '#ffffff' });
});    
</script>

<!-- Control Sidebar -->
  <aside class=" @if(Route::currentRouteName() == 'dashboard') sidebar-margin-top-filter @endif control-sidebar control-sidebar-light">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-tv"></i></a></li>        
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-tasks"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent activities</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-aqua"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job #15 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Assigned JIRA tasks</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Dashboard KPI chart
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Gridview improvement
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->

      <!-- Settings tab content -->
      <div class="tab-pane active" id="control-sidebar-settings-tab">
        <form method="POST" name="user-pref" id="user-pref" action="{{url('/user/pref')}}">
          {!! csrf_field() !!}
          <h3 class="control-sidebar-heading">Display Settings</h3>
          <div class="form-group">
            <label class="control-sidebar-subheading">Site theme</label>
            <select name="site_skin" id="site_skin" autocomplete="off">
                <?php
                    $theme = Auth::user()->site_skin;
                    foreach(array(
                        'skin-blue' => 'Blue',
                        'skin-blue-light' => 'Light Blue',                    
                        'skin-red' => 'Red',
                        'skin-red-light' => 'Light Red',
                        'skin-yellow' => 'Yellow',
                        'skin-yellow-light' => 'Light Yellow',
                        'skin-purple' => 'Purple',
                        'skin-purple-light' => 'Light Purple',
                        'skin-green' => 'Green',
                        'skin-green-light' => 'Light Green',
                        'skin-black' => 'Black',
                        'skin-black-light' => 'White',                    
                    ) as $key => $val){
                        ?><option value="<?php echo $key; ?>"<?php
                            if($key==$theme) echo ' selected="selected"';
                        ?>><?php echo $val; ?></option><?php
                    }
                ?>                
            </select>
            <br><small class="text-muted">Minerva web site color theme</small>
          </div>
          
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Use compact navigation menu <br>
              <input type="checkbox" name="menubar_collapse" id="menubar_collapse" value="1" class="js-switch-csb" <?php if(\Auth::user()->menubar_collapse) echo "checked"; ?>>
            </label>
            <small class="text-muted">Turn on compact menu bar gives you more view space</small>
          </div>          

          <div class="form-group">
            <label class="control-sidebar-subheading">Number precision</label>
            <select name="num_cutoff" id="num_cutoff">
                <?php
                    $cutoff = Auth::user()->num_cutoff;
                    foreach(array(
                        '0' => '0',
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',                        
                    ) as $key => $val){
                        ?><option value="<?php echo $key; ?>"<?php
                            if($key==$cutoff) echo ' selected="selected"';
                        ?>><?php echo $val; ?></option><?php
                    }
                ?>                
            </select>             
            <br><small class="text-muted">Max digits after decimal point</small>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">Default pagination</label>
            <select name="pagination" id="pagination">
                <?php
                    $pagination = Auth::user()->pagination;
                    foreach(array(
                        '10' => '10',
                        '25' => '25',
                        '50' => '50',
                        '75' => '75',
                        '100' => '100',                        
                    ) as $key => $val){
                        ?><option value="<?php echo $key; ?>"<?php
                            if($key==$pagination) echo ' selected="selected"';
                        ?>><?php echo $val; ?></option><?php
                    }
                ?>                  
            </select>
            <br><small class="text-muted">Records to display per page</small>
          </div>

          <div class="form-group">
                <input type="submit" value="Save" class="btn btn-sm btn-primary center">
          </div>

        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>