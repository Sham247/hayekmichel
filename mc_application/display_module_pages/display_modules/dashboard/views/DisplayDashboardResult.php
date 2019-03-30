<div class="page admin page-dashboard" data-ng-controller="DashboardCtrl">
    <!-- stats -->
   <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <div class="panel-heading">
                  <strong>
                    <a href='javascript:;' class='chart_title' view-chart='view_chart_first'>
                      <span class="glyphicon glyphicon-th"></span> 
                      <span data-i18n="Gender Based Visit Expenditure"></span>
                    </a>
                  </strong>
                </div>
                <div class="panel-body view_chart_first" data-ng-controller="morrisChartCtrl" id="graphs" style="height:300px;"> 
                <script type="text/javascript">
                $( document ).ready(function() {
                    Morris.Bar({
                              element: 'graphs',
                              data: [
                                { m: 'Male Population', a: 29,  b: 16,c: 21,  d: 3,e: 31},
                                { m: 'Female Population', a: 27,  b: 13,c: 19,  d: 3,e:38},
                              ],
                              xkey: 'm',
                              ykeys: ['a', 'b','c','d','e'],
                              barColors: ["#18840D","#759806","#D09E19","#D05D26","#C12526"],
                              labels: ["Office Visits", "ER Visits","Op Visits","IP Visits","Rx Claims"]
                            });
                    });
                </script>
                </div>
            </section> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" data-ng-controller="flotChartCtrl">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <strong>
                    <a href='javascript:;' class='chart_title' view-chart='view_chart_second'>
                      <span class="glyphicon glyphicon-th"></span> 
                      <span data-i18n="Top Ten Chronic condition"></span>
                    </a>
                  </strong>
                </div>
                <div class="panel-body view_chart_second">
                    <div data-flot-chart
                         data-data="pieChart.data"
                         data-options="pieChart.options"
                         style="width: 100%; height: 300px;"
                         ></div>
                </div>
            </div>
        </div>
        <div class="col-md-6" data-ng-controller="flotChartCtrl">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <strong>
                    <a href='javascript:;' class='chart_title' view-chart='view_chart_third'>
                      <span class="glyphicon glyphicon-th"></span> 
                      <span data-i18n="Top Ten Acute condition"></span>
                    </a>
                  </strong>
                </div>
                <div class="panel-body view_chart_third">
                    <div data-flot-chart
                         data-data="donutChart.data"
                         data-options="donutChart.options"
                         style="width: 100%; height: 300px;"
                         ></div>
                </div>
            </div>            
        </div>
    </div>      
    <div class="row">
        <div class="col-md-6 page page-charts" data-ng-controller="flotChartCtrl">
            <section class="panel panel-default">
                <div class="panel-heading">
                  <strong>
                    <a href='javascript:;' class='chart_title' view-chart='view_chart_fourth'>
                      <span class="glyphicon glyphicon-th"></span> 
                      <span data-i18n="Specialty Class - Claims Amount"></span>
                    </a>
                  </strong>
                </div>
                <div class="panel-body view_chart_fourth">
                    <div data-flot-chart
                         data-data="pieChart2.data"
                         data-options="pieChart2.options"
                         style="width: 100%; height: 350px;"
                         ></div>
                </div>
            </section>
        </div>
   <div class="col-md-6 page page-charts" data-ng-controller="morrisChartCtrl">
            <section class="panel panel-default">
                <div class="panel-heading">
                  <strong>
                    <a href='javascript:;' class='chart_title' view-chart='view_chart_five'>
                      <span class="glyphicon glyphicon-th"></span>CLAIMS EXPENDITURE
                    </a>
                  </strong>
                </div>
                <div class="panel-body view_chart_five">
                    <div morris-chart
                         data-data="donutData"
                         data-type="donut"
                         data-xkey="year"
                         data-colors='["#C0C000","#6B8E23"]'
                         style="width: 100%; height: 350px;"
                         ></div>
                         <!--                       <div morris-chart
                          data-data="comboData"
                         data-type="bar"
                         data-xkey="year"
                          data-ykeys='["a", "b", "c"]'
                          data-labels='["Clinic A", "Clinic B", "Clinic C"]'
                          data-bar-colors='["#176799","#42A4BB","#78D6C7"]'
                          style="height: 300px;">
                          </div> -->
                </div>
            </section>
        </div>
	</div>
<!--        <div class="row">
        <div class="col-md-12 page" data-ng-controller="flotChartCtrl">
            <section class="panel panel-default">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-th"></span> Top 10 Members Based On Expenditures</div>
                <div class="panel-body">
                    <div data-flot-chart
                         data-data="area.data"
                         data-options="area.options"
                         style="width: 100%; height: 300px;"
                         ></div>
                </div>
            </div>
            </section>
        </div>
        </div> -->
    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <div class="panel-heading">
                  <strong>
                    <a href='javascript:;' class='chart_title' view-chart='view_chart_six'>
                      <span class="glyphicon glyphicon-th"></span> 
                      <span data-i18n="Top 5 POS Based On Claims"></span>
                    </a>
                  </strong>
                </div>
                <div class="panel-body view_chart_six" data-ng-controller="morrisChartCtrl" id="graph" style="height:300px;"> 
<!--                       <div morris-chart
                          data-data="comboData"
                         data-type="bar"
                         data-xkey="year"
                          data-ykeys='["a", "b", "c"]'
                          data-labels='["Clinic A", "Clinic B", "Clinic C"]'
                          data-bar-colors='["#176799","#42A4BB","#78D6C7"]'
                          style="height: 300px;">
                          </div> -->
                <script type="text/javascript">
                $( document ).ready(function() {
                    Morris.Bar({
                              element: 'graph',
                              data: [
                                { m: 'Outpatient', a: 2636954.48,  b: 2960019.46, },
                                { m: 'Inpatient', a: 2453298.57,  b: 2083054.15,},
                                { m: 'Office', a: 1744952.66,  b: 1519914.01,},
                                { m: 'Emergency Room', a: 256202.21,  b: 232609.44,},
                                { m: 'Independent Laboratory', a: 92938.39,  b: 156587.38,}
                              ],
                              xkey: 'm',
                              ykeys: ['a', 'b'],
                              barColors: ["#FFB61C","#23AE89"],
                              labels: ["Male", "Female"]
                            });
                    });
                </script>
                </div>
            </section> 
        </div>
    </div>

</div>
  