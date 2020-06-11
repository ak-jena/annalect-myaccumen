@extends('app')
@section('title', 'Home')
@section('subtitle','Dashboard')
@section('content')

  <!-- Info boxes -->
  <div class="row">
      
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-eye"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Global Viewability</span>
          <span class="info-box-number">58.31<small>%</small></span>
          <span class="info-box-more"><small>Updated: 20th June 2016</small></span>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-thumbs-o-down"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Viewability under 50%</span>
          <span class="info-box-number">83</span>
        </div>
      </div>
    </div>

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>


    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-hand-peace-o"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Viewability between 50% - 65%</span>
          <span class="info-box-number">208</span>
        </div>
      </div>
    </div>    
    
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-thumbs-o-up"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Viewability over 65%</span>
          <span class="info-box-number">57</span>
        </div>
      </div>
    </div>
 
  </div>
  <!-- /.row -->

  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Budget Tracker Recap</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench"></i></button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
              </ul>
            </div>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-9">
              <p class="text-center">
                <strong>Period: 1 Jan, 2016 - 30 Jul, 2016</strong>
              </p>

              <div class="chart">
                <!-- Sales Chart Canvas -->
                <canvas id="salesChart" style="height: 180px;"></canvas>
              </div>
              <!-- /.chart-responsive -->
            </div>
            <!-- /.col -->
            <div class="col-md-3">
              </br></br>  
              <p class="text-center">
                <strong>Goal Completion</strong>
              </p>

              <!-- /.progress-group -->
              <div class="progress-group">
                <span class="progress-text">Budget Spending</span>
                <span class="progress-number"><b>80</b>%</span>

                <div class="progress sm">
                  <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                </div>
              </div>
              <!-- /.progress-group -->
              <div class="progress-group">
                <span class="progress-text">Viewability Desired</span>
                <span class="progress-number"><b>35</b>%</span>

                <div class="progress sm">
                  <div class="progress-bar progress-bar-yellow" style="width: 35%"></div>
                </div>
              </div>
              <!-- /.progress-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- ./box-body -->
        <div class="box-footer">
          <div class="row">
            <div class="col-sm-4 col-xs-4">
              <div class="description-block border-right">
                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                <h5 class="description-header">$35,210.43</h5>
                <span class="description-text">TOTAL REVENUE</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 col-xs-4">
              <div class="description-block border-right">
                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                <h5 class="description-header">$10,390.90</h5>
                <span class="description-text">TOTAL COST</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 col-xs-4">
              <div class="description-block">
                <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                <h5 class="description-header">$24,813.53</h5>
                <span class="description-text">TOTAL PROFIT</span>
              </div>
              <!-- /.description-block -->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-footer -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <div class="col-md-8">

      <!-- TABLE: LATEST ORDERS -->
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Top 5 To Spend</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover dataTable no-margin">
              <thead>
              <tr>
                <th>Advertiser</th>
                <th>Insertion Order</th>
                <th>Days left</th>
                <th>Budget</th>
                <th>Spent</th>
                <th>Recommended</th>
                <th>Track path</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td><a href="#">John Lewis</a></td>
                <td><a href="#">JL Samsung Addwash May 16</a></td>
                <td>10</td>
                <td>£110,039.72</td>
                <td>£70,012.22</td>
                <td>£1,120.14</td>
                <td>
                  <div class="sparkbar" data-color="#00a65a" data-height="20">180,-66,85,70,-41,83,63</div>
                </td>
              </tr>
              <tr>
                <td><a href="#">Sainsbury</a></td>
                <td><a href="#">Sainsburys - Summer started</a></td>
                <td>19</td>
                <td>£70,200</td>
                <td>£16,500.50</td>
                <td>£1,120.14</td>
                <td>
                  <div class="sparkbar" data-color="#00a65a" data-height="20">99,-20,90,78,-61,-80,-2</div>
                </td>
              </tr>
              <tr>
                <td><a href="#">Warner Bros</a></td>
                <td><a href="#">WB_Studio Tour_June_2016</a></td>
                <td>20</td>
                <td>£110,039.72</td>
                <td>£70,012.22</td>
                <td>£1,120.14</td>
                <td>
                  <div class="sparkbar" data-color="#00a65a" data-height="20">55,-80,-120,-70,-61,0,47</div>
                </td>
              </tr>
              <tr>
                <td><a href="#">HBO</a></td>
                <td><a href="#">HBO_Father's Day_June 2016</a></td>
                <td>22</td>
                <td>£110,039.72</td>
                <td>£70,012.22</td>
                <td>£1,120.14</td>
                <td>
                  <div class="sparkbar" data-color="#00a65a" data-height="20">33,66,88,200,-21,45,199</div>
                </td>
              </tr>              
              <tr>
                <td><a href="#">John Lewis</a></td>
                <td><a href="#">JL AEG May 16</a></td>
                <td>24</td>
                <td>£110,039.72</td>
                <td>£70,012.22</td>
                <td>£1,120.14</td>
                <td>
                  <div class="sparkbar" data-color="#00a65a" data-height="20">-12,-50,90,-70,-61,20,10</div>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
          <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Spend Now</a>
          <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">Full Budget Tracker Report</a>
        </div>
        <!-- /.box-footer -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->

    <div class="col-md-4">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Browser Usage</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-8">
              <div class="chart-responsive">
                <canvas id="pieChart" height="180"></canvas>
              </div>
              <!-- ./chart-responsive -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <ul class="chart-legend clearfix">
                <li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                <li><i class="fa fa-circle-o text-green"></i> IE</li>
                <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                <li><i class="fa fa-circle-o text-gray"></i> Navigator</li>
              </ul>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li><a href="#">Viewability
              <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
            <li><a href="#">Budget Spent <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
            </li>
          </ul>
        </div>
        <!-- /.footer -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
@endsection