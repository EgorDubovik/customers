@extends('layout.main')

@section('content')

    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header" style="margin-top: 10px; margin-bottom: 10px;">
            <h1 class="page-title">Payments
               <span style="font-weight: 100;position relative"> <span style="cusrsor:pointer" onclick="$('.data-range-payments').toggle()">date</span>
                  <div class="data-range-payments">
                     <div class="row">
                        <div class="col-sm">
                           <ul class="list-group list-group-flush">
                              <li class="list-group-item list-group-item-action" onclick="setNewRange('today')">Today</li>
                              <li class="list-group-item list-group-item-action" onclick="setNewRange('thisWeek')">This week</li>
                              <li class="list-group-item list-group-item-action" onclick="setNewRange('thisMonth')">This month</li>
                              <li class="list-group-item list-group-item-action" onclick="setNewRange('last30Days')">Last 30 days</li>
                           </ul>
                        </div>
                        <p>or</p>
                        <div class="col-sm">
                           <div id="calendar"></div>
                        </div>
                     </div>
                  </div>
               </span>
            </h1>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- CONTENT -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                   
                    <div class="card-body">
                        <div id="morrisBar2" class="chartsh"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card">
               <div class="card-body">
                  
               </div>
            </div>
        </div>

    @stop
    @section('scripts')
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/datedreamer@0.2.6/dist/datedreamer.js"></script>
      <script src="{{ URL::asset('assets/plugins/morris/morris.js') }}"></script>
      <script src="{{ URL::asset('assets/plugins/morris/raphael-min.js') }}"></script>

      <script>            
         var morris = new Morris.Area({
            element: 'morrisBar2',
            data: @json($paymentForGraph),
            xkey: 'day',
            ykeys: ['total'],
            lineColors: ['#6c5ffc'],
            labels: ['amount'],
            xaxisLabel: 'Day',
            xLabelFormat: d => moment(d).format('MMM D'), 
            yLabelFormat: d => '$'+d/100, 
            xLabels: 'day',
            resize: true
         }).on('click', function(i, row) {
            console.log(i, row);
         });

         var calendar = new datedreamer.range({
                           element: "#calendar",
                           theme:"lite-purple",
                           onChange: (e) => {
                              getNewData({
                                 startDate : moment(e.detail.startDate).format('YYYY-MM-DD'),
                                 endDate : moment(e.detail.endDate).format('YYYY-MM-DD'),
                              });
                           },
                        });
         function setNewRange(rangeType){
            switch (rangeType) {
               case 'today':
                  var result = {
                     startDate : moment().format('YYYY-MM-DD'),
                     endDate   : moment().format('YYYY-MM-DD'),
                  }
               break;
               case 'thisWeek':
                  var result = {
                     startDate : moment().startOf('week').format('YYYY-MM-DD'),
                     endDate   : moment().endOf('week').format('YYYY-MM-DD'),
                  }
               break;
               case 'thisMonth':
                  var result = {
                     startDate : moment().startOf('month').format('YYYY-MM-DD'),
                     endDate   : moment().endOf('month').format('YYYY-MM-DD'),
                  }
               break;

               case 'last30Days':
                  var result = {
                     startDate : moment().format('YYYY-MM-DD'),
                     endDate   : moment().add(-30, 'days').format('YYYY-MM-DD'),
                  }
               break;
               
            }
            getNewData(result)
         }
         
         function getNewData(range){
            console.log(range);
         }
      </script>
    @endsection
