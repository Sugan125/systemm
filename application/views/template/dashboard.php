
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="content mt-3">
            <?php
        $loginuser = $this->session->userdata('LoginSession');

        $roles = is_array($loginuser['roles']) ? $loginuser['roles'] : explode(',', $loginuser['roles']);
        $access = is_array($loginuser['access']) ? $loginuser['access'] : explode(',', $loginuser['access']);    
        
        
    
        if (isset($loginuser['roles']) && !empty($loginuser['roles'])) {
            if (in_array('Dashboard', $access) && (in_array('Admin', $roles) || in_array('Owner', $roles))){ 
                echo '<a href="' . base_url('index.php/orders/manage_orders') . '">';
            } elseif ($loginuser['roles'] == 'User') {
                echo '<a href="' . base_url('index.php/orders') . '">';
            } else {
                echo '<a href="">';
            }
        }
        ?>
        
        
           
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-flat-color-1">
                    <div class="card-body pb-0">
                        <p class="text-light" style="font-weight: 600;">View Orders</p>
                    </div>

                </div>
            </div></a>
            <!--/.col-->
           <?php
        $loginuser = $this->session->userdata('LoginSession');
       
         if (in_array('Dashboard', $access) && (in_array('Admin', $roles) || in_array('Owner', $roles))){ 
                echo '<a href="' . base_url('index.php/Productcontroller') . '">';
            } elseif ($loginuser['roles'] == 'User') {
                echo '<a href="' . base_url('index.php/Productcontroller/userproduct') . '">';
            } else {
                echo '<a href="">';
            }
        
        ?>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-flat-color-5">
                    <div class="card-body pb-0">
                        <p class="text-light" style="font-weight: 600;">View Products</p>

                    </div>
                </div>
            </div></a>
            <!--/.col-->
           
            <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-flat-color-3">
                <div class="card-body pb-0">
                    <p class="text-light" style="font-weight: 600;">
                        <?php if ($loginuser['roles'] == 'User') { ?>
                            <a href="<?php echo base_url('index.php/Userscontroller') ?>">
                                View My Details
                            </a>
                        <?php } else { ?>
                            View Customer Details
                        <?php } ?>
                    </p>
                </div>
            </div>
        </div>

            <!--/.col-->
           <?php
       
       if (in_array('Dashboard', $access) && (in_array('Admin', $roles) || in_array('Owner', $roles))){ 
               
        ?>
            <form method="post" action="<?php echo base_url(); ?>index.php/Dashboardcontroller/action">
   <div class="col-sm-6 col-lg-3">
    <div class="card text-white bg-flat-color-4">
        <div class="card-body pb-0" style="padding:0px!important;">
       
        
                <button type="submit" name="export" class="btn btn-block btn-danger" style="padding:17px; text-align:left;"><b>Sales Item Summary <?php echo strtoupper(date('F')); ?></b></button>
        
        </div>
    </div>
</div>
</form>


            <!--/.col-->
            <?php
             
                   }
                               ?>


        <?php
        
        if (in_array('Dashboard', $access) && (in_array('Admin', $roles) || in_array('Owner', $roles))){ 
            //    echo '<a href="' . base_url('index.php/Userrolecontroller') . '">';
            
        ?>

<div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-money text-primary border-primary"></i></div>
                            <div class="stat-content dib">
                            <div class="stat-text">Today delivery sales total</div>
                            <div class="stat-digit">
                                <?php echo '$' . number_format($total_amt_sales, 3); ?>
                            </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-check text-success border-success"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Today Orders</div>
                                <div class="stat-digit"><?php
                                echo $today_orders;
                                ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-shopping-cart text-warning border-warning"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Total active products</div>
                                <div class="stat-digit"><?php
                                echo $total_prods;
                                ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-user text-danger border-danger"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Total active customers</div>
                                <div class="stat-digit"><?php
                                echo $total_cust;
                                ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title mb-0">Total Sales</h4>
                            </div>
                            <!--/.col-->
                            <div class="col-sm-8 hidden-sm-down">
                            <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group mr-3" role="group" aria-label="First group">
                                    <label class="btn btn-outline-secondary">
                                        <input type="checkbox" name="salescheck" id="option1" checked onclick="updateChart('day')"> Day
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="checkbox" name="salescheck" id="option2"  onclick="updateChart('month')"> Month
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="checkbox" name="salescheck" id="option3" onclick="updateChart('year')"> Year
                                    </label>
                                </div>
                            </div>
                        </div>
                            <!--/.col-->


                        </div>
                        <!--/.row-->
                        <div class="chart-wrapper mt-4">
                            <canvas id="trafficCharts" style="height:200px;" height="200"></canvas>
                        </div>

                    </div>

                   
                </div>
            </div>

           
<div class="col-xl-6">
                <div class="card">
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title mb-0">Total Orders</h4>
                            </div>
                        </div>
                        <div class="chart-wrapper mt-4">
                            <canvas id="orderchart" style="height:200px;" height="200"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>

<?php
 
  } 
?>
  
 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dayData = <?php echo json_encode($data['day_data']); ?>;
    const monthData = <?php echo json_encode($data['month_data']); ?>;
    const yearData = <?php echo json_encode($data['year_data']); ?>;

    let chart; // Declare the chart variable globally
    let charts; // Declare the chart variable globally

    function prepareChartData(data, type) {
        let labels = [];
        let counts = [];
        let totals = [];
        let totalOrders = 0;
        let totalSales = 0;

        data.forEach(item => {
            if (type === 'day') {
                labels.push(item.date);
            } else if (type === 'month') {
                labels.push(item.month);
            } else if (type === 'year') {
                labels.push(item.year);
            }
            counts.push(item.count);
            totals.push(item.total_amt);
            totalOrders += item.count;
            totalSales += item.total_amt;
        });

        return { labels, counts, totals, totalOrders, totalSales };
    }
    function updateChart(type) {
    let data;
    if (type === 'day') {
        data = dayData;
    } else if (type === 'month') {
        data = monthData;
    } else if (type === 'year') {
        data = yearData;
    }

    const { labels, counts, totals, totalOrders, totalSales } = prepareChartData(data, type);

    const ctx = document.getElementById('trafficCharts').getContext('2d');
    const orderCtx = document.getElementById('orderchart').getContext('2d');

    if (chart) {
        chart.destroy();
    }

    if (charts) {
        charts.destroy();
    }


    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Sales',
                data: totals,
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                fill: false,
                yAxisID: 'y-axis-sales'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'y-axis-sales',
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Total Sales'
                    }
                }]
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(tooltipItem, data) {
                        const datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                        return `${datasetLabel}: $${tooltipItem.yLabel.toFixed(2)}`;
                    }
                }
            }
        }
    });

    charts = new Chart(orderCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Order Count',
                data: counts,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false,
                yAxisID: 'y-axis-count'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'y-axis-count',
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Order Count'
                    }
                }]
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(tooltipItem, data) {
                        const datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                        return `${datasetLabel}: ${tooltipItem.yLabel} Orders`;
                    }
                }
            }
        }
    });

    document.getElementById('totalOrders').innerText = `${totalOrders} Orders`;
    document.getElementById('totalSales').innerText = `$${totalSales.toFixed(2)}`;
}


    document.querySelectorAll('input[name="salescheck"]').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('input[name="salescheck"]').forEach((otherCheckbox) => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });
                updateChart(this.id === 'option1' ? 'day' : (this.id === 'option2' ? 'month' : 'year'));
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        updateChart('day'); // Default to month view
    });
</script>