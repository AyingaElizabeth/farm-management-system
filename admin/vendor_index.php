<?php
// config.php
require_once('init.php');
require_once('top.inc.php');

// Authentication check
function checkVendorAuth() {
    if (!isset($_SESSION['ADMIN_ID'])) {
        die("Vendor ID not set in session");
    }
    return $_SESSION['ADMIN_ID'];
}

// Data access layer
class DashboardData {
    private $con;
    private $vendor_id;

    public function __construct($connection, $vendor_id) {
        $this->con = $connection;
        $this->vendor_id = $vendor_id;
    }

    public function getOrderStatusData() {
        $query = "SELECT 
                    order_status.name AS status, 
                    COUNT(*) AS count 
                 FROM `order` 
                 JOIN order_status ON order_status.id = `order`.order_status 
                 JOIN order_detail ON `order`.id = order_detail.order_id
                 JOIN product ON order_detail.product_id = product.id
                 WHERE product.added_by = ?
                 GROUP BY `order`.order_status 
                 ORDER BY count DESC";
        
        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, 's', $this->vendor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $orderDataPoints = [];
        $totalOrders = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $orderDataPoints[] = ["label" => $row['status'], "y" => $row['count']];
            $totalOrders += $row['count'];
        }

        // Calculate percentages
        foreach ($orderDataPoints as &$point) {
            $point['percentage'] = round(($point['y'] / $totalOrders) * 100, 1);
        }

        return $orderDataPoints;
    }

    public function getProductCategoryData() {
        $query = "SELECT 
                    categories.categories AS category,
                    product.name AS product_name,
                    COUNT(*) as product_count
                 FROM product
                 JOIN categories ON product.categories_id = categories.id
                 WHERE product.added_by = ?
                 GROUP BY categories.categories, product.name
                 ORDER BY categories.categories, product.name";

        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, 's', $this->vendor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $categoryData = [];
        $products = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if (!isset($categoryData[$row['category']])) {
                $categoryData[$row['category']] = [];
            }
            $categoryData[$row['category']][$row['product_name']] = $row['product_count'];
            $products[$row['product_name']] = true;
        }

        return $this->formatProductData($categoryData, $products);
    }

    public function getSalesData() {
        $query = "SELECT 
                    product.name AS product_name,
                    SUM(order_detail.qty * order_detail.price) AS total_sales,
                    SUM(order_detail.qty) AS total_quantity,
                    product.qty AS initial_quantity,  -- Add initial quantity
                    COUNT(DISTINCT `order`.id) AS order_count
                 FROM `order`
                 JOIN order_status ON order_status.id = `order`.order_status
                 JOIN order_detail ON `order`.id = order_detail.order_id 
                 JOIN product ON order_detail.product_id = product.id
                 WHERE product.added_by = ?
                 AND `order`.order_status = 5
                 GROUP BY product.id, product.name
                 ORDER BY total_sales DESC";
    
        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, 's', $this->vendor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        $salesData = [];
        while($row = mysqli_fetch_assoc($result)) {
            $salesData[] = [
                'product_name' => $row['product_name'],
                'total_sales' => (float)$row['total_sales'],
                'total_quantity' => (int)$row['total_quantity'],
                'initial_quantity' => (int)$row['initial_quantity'],  // Store initial quantity
                'order_count' => (int)$row['order_count']
            ];
        }
    
        return $salesData;
    }
    
    

    private function formatProductData($categoryData, $products) {
        $dataSeriesArray = [];
        foreach ($products as $product => $value) {
            $dataPoints = [];
            foreach ($categoryData as $category => $productData) {
                $dataPoints[] = [
                    "label" => $category,
                    "y" => isset($productData[$product]) ? $productData[$product] : 0
                ];
            }
            $dataSeriesArray[] = [
                "type" => "column",
                "name" => $product,
                "showInLegend" => true,
                "dataPoints" => $dataPoints
            ];
        }
        return $dataSeriesArray;
    }
}

// Initialize dashboard
$vendor_id = checkVendorAuth();
$dashboard = new DashboardData($con, $vendor_id);

// Fetch all required data
$orderDataPoints = $dashboard->getOrderStatusData();
$dataSeriesArray = $dashboard->getProductCategoryData();
$salesData = $dashboard->getSalesData();

// Calculate summary metrics
$total_revenue = array_sum(array_column($salesData, 'total_sales'));
$total_orders = array_sum(array_column($salesData, 'order_count'));
$total_quantity = array_sum(array_column($salesData, 'total_quantity'));
?>

<!-- Template -->
<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard</title>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="content pb-0">
        <div class="orders">
            <!-- Charts Section -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="box-title">Order Status Dashboard</h4>
                            <div id="orderChartContainer" style="height: 370px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="box-title">Product Distribution by Category</h4>
                            <div id="productChartContainer" style="height: 370px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Analysis Section -->
            <div class="card">
                <div class="card-body">
                    <h4 class="box-title">Sales Analysis - Completed Orders</h4>
                    
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Total Revenue</h5>
                                    <h3>UGX<?php echo number_format($total_revenue, 2); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Total Orders</h5>
                                    <h3><?php echo number_format($total_orders); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Total Units Sold</h5>
                                    <h3><?php echo number_format($total_quantity); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Chart and Table -->
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="salesChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="table-stats order-table ov-h">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Sales</th>
                                            <th>Units</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($salesData as $data): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                                            <td>UGX<?php echo number_format($data['total_sales'], 2); ?></td>
                                            <td><?php echo number_format($data['total_quantity']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button onclick="downloadSalesData()" class="btn btn-primary">Download Sales Analysis</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Initialization Script -->
    <script>

        window.onload = function() {
            // Order Status Chart
            const orderChart = new CanvasJS.Chart("orderChartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                title: { text: "Order Status Distribution" },
                subtitles: [{ text: "For your products" }],
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{y}</strong>",
                    indexLabel: "{y}",
                    legendText: "{label} - {percentage}%",
                    indexLabelFontSize: 16,
                    dataPoints: <?php echo json_encode($orderDataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });

            // Product Category Chart
            const productChart = new CanvasJS.Chart("productChartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                title: { text: "Product Distribution by Category" },
                axisY: {
                    title: "Number of Products",
                    includeZero: true
                },
                axisX: {
                    title: "Categories",
                    interval: 1
                },
                toolTip: { shared: true },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                data: <?php echo json_encode($dataSeriesArray, JSON_NUMERIC_CHECK); ?>
            });

            const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesData = <?php echo json_encode($salesData); ?>;

new Chart(salesCtx, {
    type: 'bar',
    data: {
        labels: salesData.map(item => item.product_name),
        datasets: [{
            label: 'Sales (UGX)',
            data: salesData.map(item => item.total_sales),
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            yAxisID: 'y'
        }, {
            label: 'Quantity Sold',
            data: salesData.map(item => item.total_quantity),
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Sales Amount (UGX)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Units Sold'
                },
                grid: {
                    drawOnChartArea: false
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const product = salesData[context.dataIndex];
                        const initialQty = product.initial_quantity;
                        const soldQty = product.total_quantity;
                        return `${context.dataset.label}: ${context.raw} (Quantity Sold: ${soldQty}/${initialQty})`;
                    }
                }
            }
        }
    }
});

            // Render initial charts
            orderChart.render();
            productChart.render();
        }

        // Chart utility functions
        function explodePie(e) {
            if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || 
               !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
                e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
            } else {
                e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
            }
            e.chart.render();
        }

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }

        function downloadSalesData() {
    // Sales data from PHP
    const salesData = <?php echo json_encode($salesData); ?>;
    
    // Define CSV content
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Product,Sales (UGX),Units Sold\n";
    
    // Populate CSV rows
    salesData.forEach(row => {
        csvContent += `${row.product_name},${row.total_sales},${row.total_quantity}\n`;
    });

    // Encode URI and create download link
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "sales_analysis.csv");

    // Append to the document and trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

    </script>
</body>
</html>

<?php require('footer.inc.php'); ?>