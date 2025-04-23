<?php
include_once '../init.php'; // Ensure this file initializes $pdo and any other necessary components
include_once '../expense.php'; // Adjust the path to where your Expense class is defined

if ($getFromU->loggedIn() === false) {
    header('Location: ../index.php');
    exit;
}

// Create an instance of the Expense class
$getFromE = new Expense($pdo);

// Fetch monthly expenses by category
$monthlyExpensesByCategory = $getFromE->getMonthlyExpensesByCategory($_SESSION['UserId']);

// Prepare data for the pie chart
$chartData = [];
foreach ($monthlyExpensesByCategory as $category => $amount) {
    $chartData[] = [
        'category' => $category,
        'amount' => (float)$amount
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Categories</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <?php include_once 'skeleton.php'; // Include the sidebar ?>
    <div class="container">
        <h1>Monthly Expense Categories</h1>
        <canvas id="expenseChart"  height="125px"></canvas>
    </div>
    <div><center><h2>Monthly Expense Categories</h2></center></div>
    <script>
        const chartData = <?php echo json_encode($chartData); ?>; // Pass PHP data to JavaScript

        const labels = chartData.map(data => data.category);
        const dataValues = chartData.map(data => data.amount);

        const ctx = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expenses',
                    data: dataValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Expenses by Category'
                    }
                }
            }
        });
    </script>
</body>
</html>
