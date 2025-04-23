<?php  
  class Budget extends Base {
    function __construct($pdo) {
      $this->pdo = $pdo;
    }

    // To check validity of the set budget
    public function budget_validity_checker($UserId){
      $stmt = $this->pdo->prepare("SELECT EXTRACT(MONTH FROM RDATE) AS mon FROM budget WHERE UserId = :user");
      $stmt->bindParam(":user", $UserId, PDO::PARAM_INT);
      $stmt->execute();
      $r = $stmt->fetch(PDO::FETCH_OBJ);
      if($r == NULL)
      {
        return true;
      }
      else
      {
        $val1 = $r->mon;
      }
      
      $stmt2 = $this->pdo->prepare("SELECT EXTRACT(MONTH FROM CURRENT_TIMESTAMP()) AS current");
      $stmt2->execute();
      $z = $stmt2->fetch(PDO::FETCH_OBJ);
      $val2 = $z->current;

      if($val1 === $val2)
      {
        return true;
      }
      else
      {
        return false;
      }
    }

    // To set the budget
    public function setbudget($UserId, $budget) {
      $stmt = $this->pdo->prepare("INSERT INTO budget(UserId, Budget) VALUES(:user , :amount)");
      $stmt->bindParam(":user", $UserId, PDO::PARAM_INT);
      $stmt->bindParam(":amount", $budget, PDO::PARAM_INT);
      $stmt->execute();
    }

    // To check the current budget
    public function checkbudget($UserId) {
      $stmt = $this->pdo->prepare("SELECT Budget AS currentbudget FROM budget WHERE UserId=:user");
      $stmt->bindParam(":user", $UserId, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetch(PDO::FETCH_OBJ);
      if($rows == NULL)
      {
        return NULL;
      }
      else
      {
        return $rows->currentbudget;
      }
    }

    // To update current budget
    public function updatebudget($UserId, $budget) {
      $stmt = $this->pdo->prepare("UPDATE budget SET Budget = :amount, RDATE = CURRENT_TIMESTAMP() WHERE UserId = :user");
      $stmt->bindParam(":user", $UserId, PDO::PARAM_INT);
      $stmt->bindParam(":amount", $budget, PDO::PARAM_INT);
      $stmt->execute();
    }
    
    // To delete the monthly budget record (Once the month changes)
    public function del_budget_record($UserId){
      $stmt = $this->pdo->prepare("DELETE FROM budget WHERE UserId = :user");
      $stmt->bindParam(":user", $UserId, PDO::PARAM_INT);
      $stmt->execute();
    }
    public function setAccidentalBudget($userId, $amount) {
      try {
          $stmt = $this->pdo->prepare("UPDATE budget SET AccidentalBudget = :amount WHERE UserId = :userId");
          $stmt->bindParam(':amount', $amount);
          $stmt->bindParam(':userId', $userId);
          return $stmt->execute(); // Returns true on success
      } catch (PDOException $e) {
          return $e->getMessage(); // Return error message on failure
      }
  }
  
  public function setAccidentalExpenses($UserId, $accidentalExpense) {
    $stmt = $this->pdo->prepare("INSERT INTO budget (UserId, AccidentalExpenses) VALUES (:user, :expense)");
    $stmt->bindParam(':user', $UserId, PDO::PARAM_INT);
    $stmt->bindParam(':expense', $accidentalExpense, PDO::PARAM_STR);
    $stmt->execute();
}
  // To set expenses
  public function setExpenses($UserId, $item, $cost) {
    try {
        $stmt = $this->pdo->prepare("INSERT INTO expense (UserId, Item, Cost, Date, category) VALUES (:user, :item, :cost, NOW(), :category)");
        
        // Prepare your variables
        $category = "Accidental Expense"; // Provide a category for the expense
        
        $stmt->bindParam(':user', $UserId);
        $stmt->bindParam(':item', $item); // This could be something like "Accidental Expense"
        $stmt->bindParam(':cost', $cost); // Use the correct amount for the expense
        $stmt->bindParam(':category', $category);
        
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return $e->getMessage(); // Return the error message for debugging
    }
}
public function getAccidentalBudget($user_id) {
  $stmt = $this->pdo->prepare("SELECT AccidentalBudget FROM budget WHERE UserId = :user_id");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchColumn(); // Returns the accidental budget amount
}
public function getAccidentalExpenses($UserId) {
  $stmt = $this->pdo->prepare("SELECT SUM(Cost) FROM expense WHERE UserId = :user AND category = 'Accidental Expense'");
  $stmt->bindParam(':user', $UserId);
  $stmt->execute();
  return $stmt->fetchColumn(); // Returns the total accidental expenses
}



}
?>