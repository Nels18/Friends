<?php
    require_once '_connec.php';
    $pdo = new \PDO(DSN, USER, PASS);

    // Inputs values
    $friendFirstname = '';
    $friendLastname = '';

    // Inputs errors messages
    $friendFirstnameErr = '';
    $friendLastnameErr = '';

    // Messages
    $failMessage = '';

    // Fetch all datas from database
    $query = "SELECT * FROM friend";
    $statement = $pdo->query($query);
    $friends = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Fetch datas from form
    if ('POST' == $_SERVER['REQUEST_METHOD']) {

        if (empty($_POST['friendFirstname'])) {
            $friendFirstnameErr .= "Firstname is required !<br>";
            $failMessage .= $friendFirstnameErr;
        } else {
            $friendFirstname = trim($_POST['friendFirstname']); 
            if (45 < strlen($_POST['friendFirstname'])) {
                $friendFirstnameErr .= "Firstname too long, it must be less than 45 characters !<br>";
                $failMessage .= $friendFirstnameErr;
            }
        }

        if (empty($_POST['friendLastname'])) {
            $friendLastnameErr .= "Laststname is required !<br>";
            $failMessage .= $friendLastnameErr;
        } else {
            $friendLastname = trim($_POST['friendLastname']); 
            if (45 < strlen($_POST['friendLastname'])) {
                $friendLastnameErr .= "Laststname too long, it must be less than 45 characters !<br>";
                $failMessage .= $friendLastnameErr;
            }
        }

        if ('' == $failMessage) {
            $friendLastname = trim($_POST['friendLastname']);
            $query = 'INSERT INTO friend (firstname, lastname) VALUES (:friendFirstname, :friendLastname)';
            $statement = $pdo->prepare($query);
            $statement->bindValue(':friendFirstname', $friendFirstname, \PDO::PARAM_STR);
            $statement->bindValue(':friendLastname', $friendLastname, \PDO::PARAM_STR);
            $statement->execute();
            $friends = $statement->fetchAll();
            header('Location: index.php');
        } else {
            var_dump(strlen($_POST['friendLastname']));
            echo $failMessage;
        }

    }
?>

<ul>
    <?php foreach ($friends as $friend): ?>
        <li><?php echo $friend['firstname'] . " " . $friend['lastname']; ?></li>
    <?php endforeach; ?>
</ul>

<form action=<?php echo '"' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"'; ?> method="post">
    <label for="friendFirstname">Firstname</label>
    <input type="text" id="friendFirstname" name="friendFirstname" value="<?php if (isset($_POST['friendFirstname'])) echo $_POST['friendFirstname'];?>" >

    <label for="friendLastname">Lastname</label>
    <input type="text" id="friendLastname" name="friendLastname" value="<?php if (isset($_POST['friendLastname'])) echo $_POST['friendLastname'];?>" >

    <input type="submit" value="Submit">
</form>