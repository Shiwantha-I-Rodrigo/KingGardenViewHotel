<!DOCTYPE html>
<html>

<head>
    <title>Page Title</title>
</head>

<body>

    <h1>My First Heading</h1>
    <p>My first paragraph.</p>
    <?php
    include_once 'common.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $db = dbConn();
    $sql = "SELECT * FROM users";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    echo $row['UserId'];
    echo $row['UserName'];
    echo $row['Password'];
    ?>

</body>

</html>