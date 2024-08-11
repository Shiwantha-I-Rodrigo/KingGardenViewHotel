<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
isset($_SESSION['user_id']) ? $user_id = $_SESSION['user_id'] : reDirect("/system/modules/login.php");

if (isset($_POST['req'])) {
    $req = $_POST['req'];
    isset($_POST['opt']) ? $opt = $_POST['opt'] : $opt = '';
    $content = '';
    $per_page = 5;
    $item_count = 0;
    isset($_SESSION['customer_offset']) ? $msg_offset = $_SESSION['customer_offset'] : $_SESSION['customer_offset'] = 0;
    $db = dbConn();

    switch ($req) {

        case "customer":
            $_SESSION['customer_offset'] = 0;

        case "customer_back":
            $_SESSION['customer_offset'] >= 5 ? $_SESSION['customer_offset'] -= 5 : $_SESSION['customer_offset'] = 0;
            $offset = $_SESSION['customer_offset'];
            $sql = "SELECT * FROM users u JOIN customers c ON u.UserId=c.UserId $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content = " <tr><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0){
                $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            }
            while (($row = $result->fetch_assoc()) && ($item_count < 5)) {

                $UserId = $row['UserId'];
                $UserName = $row['UserName'];
                $Email = $row['Email'];
                $Type = getRole($row['Type']);
                $UserStatus = getStatus($row['UserStatus']);
                $CustomerId = $row['CustomerId'];
                $FirstName = $row['FirstName'];
                $LastName = $row['LastName'];
                $AddressLine1 = $row['AddressLine1'];
                $AddressLine2 = $row['AddressLine2'];
                $AddressLine3 = $row['AddressLine3'];
                $Telephone = $row['Telephone'];
                $Mobile = $row['Mobile'];
                $Title = getTitle($row['Title']);
                $RegNo = $row['RegNo'];
                $ProfilePic = $row['ProfilePic'];
                $Token = $row['Token'];
                $CustomerStatus = $row['CustomerStatus'];

                $content .= " <tr><td>$UserName</td><td>$Type</td><td>$UserStatus</td><td>$Title $FirstName $LastName</td><td>$AddressLine1<br/>$AddressLine2<br/>$AddressLine3</td><td>$Email<br/>$Telephone<br/>$Mobile</td>
                <td><button class='success-btn m-1 edit' id='$UserId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$UserId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                $item_count++;
            }
            break;

        case "customer_fwd":
            $_SESSION['customer_offset'] < 0 ? $_SESSION['customer_offset'] = 0 : $_SESSION['customer_offset'] += 5;
            $offset = $_SESSION['customer_offset'];
            $sql = "SELECT * FROM users u JOIN customers c ON u.UserId=c.UserId $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content = " <tr><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0){
                $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            }
            while (($row = $result->fetch_assoc()) && ($item_count < 5)) {

                $UserId = $row['UserId'];
                $UserName = $row['UserName'];
                $Email = $row['Email'];
                $Type = getRole($row['Type']);
                $UserStatus = getStatus($row['UserStatus']);
                $CustomerId = $row['CustomerId'];
                $FirstName = $row['FirstName'];
                $LastName = $row['LastName'];
                $AddressLine1 = $row['AddressLine1'];
                $AddressLine2 = $row['AddressLine2'];
                $AddressLine3 = $row['AddressLine3'];
                $Telephone = $row['Telephone'];
                $Mobile = $row['Mobile'];
                $Title = getTitle($row['Title']);
                $RegNo = $row['RegNo'];
                $ProfilePic = $row['ProfilePic'];
                $Token = $row['Token'];
                $CustomerStatus = $row['CustomerStatus'];

                $content .= " <tr><td>$UserName</td><td>$Type</td><td>$UserStatus</td><td>$Title $FirstName $LastName</td><td>$AddressLine1<br/>$AddressLine2<br/>$AddressLine3</td><td>$Email<br/>$Telephone<br/>$Mobile</td>
                    <td><button class='success-btn m-1 edit' id='$UserId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$UserId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
            }
            break;
    }
    $content = json_encode($content, JSON_UNESCAPED_SLASHES);
    $content = trim($content, "\"");
    echo '{"content":"' . $content . '"}';
}
