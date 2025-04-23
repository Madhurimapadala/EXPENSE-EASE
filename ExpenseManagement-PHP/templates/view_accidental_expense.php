<?php 
session_start();
require_once '../init.php'; // Ensure this path is correct
require_once 'skeleton.php'; // Ensure this is the correct path for your skeleton file

if ($getFromU->loggedIn() === false) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['UserId'];

// Fetch accidental budget and spent amount
$accidental_budget = $getFromB->getAccidentalBudget($user_id) ? : 0; // Ensure this method exists in your Budget class
$accidental_spent = $getFromB->getAccidentalExpenses($user_id); // Ensure this method exists in your Budget class
?>

<div class="wrapper">
    <h2>Accidental Expenses Overview</h2>
    <div>
        <canvas id="accidentalExpensesChart"></canvas>
    </div>
    <div class="summary">
        <h3>Summary</h3>
        <p><strong>Accidental Budget:</strong> ₹<?php echo number_format($accidental_budget, 2); ?></p>
        <p><strong>Accidental Spent:</strong> ₹<?php echo number_format($accidental_spent, 2); ?></p>
        <p><strong>Remaining Amount:</strong> ₹<?php echo number_format($accidental_budget-$accidental_spent, 2); ?></p>
    </div>
</div>

<!-- Include Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('accidentalExpensesChart').getContext('2d');
    const accidentalExpensesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Accidental Budget', 'Accidental Spent'],
            datasets: [{
                label: 'Amount',
                data: [<?php echo $accidental_budget; ?>, <?php echo $accidental_spent; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Color for Budget
                    'rgba(255, 99, 132, 0.2)'  // Color for Spent
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)', // Border color for Budget
                    'rgba(255, 99, 132, 1)'  // Border color for Spent
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
