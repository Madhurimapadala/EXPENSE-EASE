<?php 
require_once '../init.php'; // Ensure this path is correct
require_once 'skeleton.php'; // Ensure this is the correct path for your skeleton file

if ($getFromU->loggedIn() === false) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['UserId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['log_budget'])) {
        // Handle the budget logging
        $accidentalBudget = $_POST['accidental_budget'];
        $getFromB = new Budget($pdo);
        $resultBudget = $getFromB->setAccidentalBudget($user_id, $accidentalBudget);

        if ($resultBudget === true) {
            echo '<script>
                Swal.fire({
                    title: "Success!",
                    text: "Accidental budget set successfully!",
                    icon: "success",
                    confirmButtonText: "Close"
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    title: "Error!",
                    text: "Failed to set accidental budget: ' . $resultBudget . '",
                    icon: "error",
                    confirmButtonText: "Close"
                });
            </script>';
        }
    } elseif (isset($_POST['log_expense'])) {
        // Handle the expense logging
        $accidentalExpense = $_POST['accidental_expenses'];
        $itemDescription = "Accidental Expense"; // Description for the expense

        $getFromB = new Budget($pdo);
        $resultExpense = $getFromB->setExpenses($user_id, $itemDescription, $accidentalExpense);

        if ($resultExpense === true) {
            echo '<script>
                Swal.fire({
                    title: "Success!",
                    text: "Accidental expense logged successfully!",
                    icon: "success",
                    confirmButtonText: "Close"
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    title: "Error!",
                    text: "Failed to log accidental expense: ' . $resultExpense . '",
                    icon: "error",
                    confirmButtonText: "Close"
                });
            </script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log Accidental Budget and Expense</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>
    <div class="counter" style="height: 45vh; display: flex; align-items: center; justify-content: center;">
        <center>
            <h2>Set Accidental Budget</h2>
            <form action="" method="post">
                <label for="accidental_budget">Accidental Budget Amount:</label>
                <input type="number" name="accidental_budget" required step="0.01" placeholder="Enter amount" onkeypress='validate(event)' class="text-input" style="color:black;font-size: 1.2em;;background: rgba(0,0,0,0);text-align: center; border: none; outline: none; border-bottom: 2px solid black; " required/><br><br>
                <button type="submit" name="log_budget" class="pressbutton">Set Budget</button>
            </form>
        </center>
    </div>

    <div class="counter" style="height: 40vh; display: flex; align-items: center; justify-content: center;">
        <center>
            <h2>Log Accidental Expense</h2>
            <form action="" method="post">
                <label for="accidental_expenses">Accidental Expense Amount:</label>
                <input type="number" name="accidental_expenses" required step="0.01" placeholder="Enter amount" onkeypress='validate(event)' class="text-input" style="color:black;font-size: 1.2em;;background: rgba(0,0,0,0);text-align: center; border: none; outline: none; border-bottom: 2px solid black;" required/><br><br>
                <button type="submit" name="log_expense" class="pressbutton">Log Expense</button>
            </form>
        </center>
    </div>
</body>
</html>
