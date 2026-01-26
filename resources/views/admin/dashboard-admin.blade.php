@extends('dashboard.layouts.index')

@section('content')
 <div class="page-inner">
            <div class="container-fluid" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
              <div
                class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
              >
                <div>
                  <h3 class="fw-bold mb-3">PPIC DELIVERY DEPARTMENT</h3>
                  <h6 class="op-7 mb-2">Monitoring & Management System</h6>
                </div>
                {{-- <div class="ms-md-auto py-2 py-md-0">
                  <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                  <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                </div> --}}
              </div>
              <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small"
                        >
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Total Driver</p>
                          <h4 class="card-title">{{ $totalDriver }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-shipping-fast"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Total Armada</p>
                          <h4 class="card-title">{{ $totalArmada }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-success bubble-shadow-small"
                        >
                          <i class="fas fa-box"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Total Item</p>
                          <h4 class="card-title">{{ $totalItem }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-warning bubble-shadow-small"
                        >
                          <i class="fas fa-calendar-check"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Laporan Hari Ini</p>
                          <h4 class="card-title">{{ $laporanHariIni }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- User Statistics Chart --}}
          <div class="row mt-4">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-0"> User Statistics (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                  <div id="userStatsChart" style="height: 400px;"></div>
                  <div class="mt-4">
                    <div class="row text-center">
                      <div class="col-md-4">
                        <div class="p-3" style="background: #f5f7fa; border-radius: 8px;">
                          <h6 class="text-muted mb-2">Total Login Minggu Ini</h6>
                          <h3 class="fw-bold text-danger">{{ array_sum($chartData['loginCounts']) }}</h3>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="p-3" style="background: #f5f7fa; border-radius: 8px;">
                          <h6 class="text-muted mb-2">Rata-rata Per Hari</h6>
                          <h3 class="fw-bold text-warning">{{ round(array_sum($chartData['loginCounts']) / 7, 1) }}</h3>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="p-3" style="background: #f5f7fa; border-radius: 8px;">
                          <h6 class="text-muted mb-2">Peak Login</h6>
                          <h3 class="fw-bold text-info">{{ max($chartData['activeUsersCounts']) }}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.0/apexcharts.min.js"></script>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const chartOptions = {
                chart: {
                  type: 'area',
                  height: 400,
                  toolbar: {
                    show: false
                  },
                  animations: {
                    enabled: true,
                    speed: 800,
                    animateGradually: {
                      enabled: true,
                      delay: 150
                    },
                    dynamicAnimation: {
                      enabled: true,
                      speed: 150
                    }
                  }
                },
                series: [
                  {
                    name: 'Login Users',
                    data: {!! json_encode($chartData['loginCounts']) !!}
                  },
                  {
                    name: 'New Visitors',
                    data: {!! json_encode($chartData['newVisitorsCounts']) !!}
                  },
                  {
                    name: 'Active Users',
                    data: {!! json_encode($chartData['activeUsersCounts']) !!}
                  }
                ],
                xaxis: {
                  categories: {!! json_encode($chartData['dates']) !!},
                  title: {
                    text: 'Hari'
                  }
                },
                yaxis: {
                  title: {
                    text: 'Jumlah User'
                  }
                },
                colors: ['#ef5350', '#ff9800', '#2196f3'],
                fill: {
                  type: 'gradient',
                  gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                  }
                },
                stroke: {
                  curve: 'smooth',
                  width: 2
                },
                legend: {
                  position: 'bottom',
                  horizontalAlign: 'center'
                },
                responsive: [{
                  breakpoint: 1024,
                  options: {
                    chart: {
                      height: 300
                    }
                  }
                }]
              };

              const chart = new ApexCharts(document.querySelector("#userStatsChart"), chartOptions);
              chart.render();
            });
          </script>
@endsection
