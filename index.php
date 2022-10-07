<?php

/**
 * CONNEXION TO THE DATABASE
 */

require_once '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

/**
 * DISPLAY ALL THE DATA
 */

$query = "SELECT firstname, lastname FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

// var_dump($friends);

echo '<ul class=\'friends-list\'>';
foreach ($friends as $friend) {
    echo '<li>' . $friend['firstname'] . ' ' . $friend['lastname'] . '</li>';
}
echo '</ul>';

/**
 * ADDING FORM DATA TO THE DATABASE
 */

function saveFriend(array $friend): void
{
    $pdo = new \PDO(DSN, USER, PASS);

    if (!empty($friend[0]) && !empty($friend[1])) {
        $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
        $statement = $pdo->prepare($query);
    
        $statement->bindValue(':firstname', $friend[0], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $friend[1], \PDO::PARAM_STR);
    
        $statement->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend = [trim(htmlspecialchars($_POST['firstname'])), trim(htmlspecialchars($_POST['lastname']))];

    if (empty($errors)) {
        saveFriend($friend);
        header('Location: /');
    }
}

?>

<form action="" method="post" style="display: flex; flex-direction: column; max-width: 300px; gap: 0.5rem;">
    <label for="firstname">Firstname:</label>
    <input type="text" name="firstname" id="firstname">
    <label for="lastname">Lastname:</label>
    <input type="text" name="lastname" id="firstname">
    <input type="submit" value="Send">
</form>