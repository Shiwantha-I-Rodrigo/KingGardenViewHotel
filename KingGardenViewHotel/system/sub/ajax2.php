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
    isset($_SESSION['customer_offset']) ? null : $_SESSION['customer_offset'] = 0;
    isset($_SESSION['employee_offset']) ? null : $_SESSION['employee_offset'] = 0;
    isset($_SESSION['res_offset']) ? null : $_SESSION['res_offset'] = 0;
    isset($_SESSION['invoice_offset']) ? null : $_SESSION['invoice_offset'] = 0;
    isset($_SESSION['room_offset']) ? null : $_SESSION['room_offset'] = 0;
    isset($_SESSION['review_offset']) ? null : $_SESSION['review_offset'] = 0;
    isset($_SESSION['dest_offset']) ? null : $_SESSION['dest_offset'] = 0;
    isset($_SESSION['blog_offset']) ? null : $_SESSION['blog_offset'] = 0;
    $db = dbConn();

    switch ($req) {

        case "customer":
            $_SESSION['customer_offset'] = 0;
        case "customer_back":
            $_SESSION['customer_offset'] >= 5 ? $_SESSION['customer_offset'] -= 5 : $_SESSION['customer_offset'] = 0;
            $continue = customer($opt, $_SESSION['customer_offset'], $db, $per_page);
            break;
        case "customer_fwd":
            $_SESSION['customer_offset'] < 0 ? $_SESSION['customer_offset'] = 0 : $_SESSION['customer_offset'] += 5;
            $continue = customer($opt, $_SESSION['customer_offset'], $db, $per_page);
            break;

        case "employee":
            $_SESSION['employee_offset'] = 0;
        case "employee_back":
            $_SESSION['employee_offset'] >= 5 ? $_SESSION['employee_offset'] -= 5 : $_SESSION['employee_offset'] = 0;
            $continue = employee($opt, $_SESSION['employee_offset'], $db, $per_page);
            break;
        case "employee_fwd":
            $_SESSION['employee_offset'] < 0 ? $_SESSION['employee_offset'] = 0 : $_SESSION['employee_offset'] += 5;
            $continue = employee($opt, $_SESSION['employee_offset'], $db, $per_page);
            break;

        case "res":
            $_SESSION['res_offset'] = 0;
        case "res_back":
            $_SESSION['res_offset'] >= 5 ? $_SESSION['res_offset'] -= 5 : $_SESSION['res_offset'] = 0;
            $content = reservation($opt, $_SESSION['res_offset'], $db, $per_page);
            break;
        case "res_fwd":
            $_SESSION['res_offset'] < 0 ? $_SESSION['res_offset'] = 0 : $_SESSION['res_offset'] += 5;
            $content = reservation($opt, $_SESSION['res_offset'], $db, $per_page);
            break;

        case "invoice":
            $_SESSION['invoice_offset'] = 0;
        case "invoice_back":
            $_SESSION['invoice_offset'] >= 5 ? $_SESSION['invoice_offset'] -= 5 : $_SESSION['invoice_offset'] = 0;
            $content = invoice($opt, $_SESSION['invoice_offset'], $db, $per_page);
            break;
        case "invoice_fwd":
            $_SESSION['invoice_offset'] < 0 ? $_SESSION['invoice_offset'] = 0 : $_SESSION['invoice_offset'] += 5;
            $content = invoice($opt, $_SESSION['invoice_offset'], $db, $per_page);
            break;

        case "room":
            $_SESSION['room_offset'] = 0;
        case "room_back":
            $_SESSION['room_offset'] >= 5 ? $_SESSION['room_offset'] -= 5 : $_SESSION['room_offset'] = 0;
            $content = room($opt, $_SESSION['invoice_offset'], $db, $per_page);
            break;
        case "room_fwd":
            $_SESSION['room_offset'] < 0 ? $_SESSION['room_offset'] = 0 : $_SESSION['room_offset'] += 5;
            $content = room($opt, $_SESSION['invoice_offset'], $db, $per_page);
            break;

        case "dest":
            $_SESSION['dest_offset'] = 0;
        case "dest_back":
            $_SESSION['dest_offset'] >= 5 ? $_SESSION['dest_offset'] -= 5 : $_SESSION['dest_offset'] = 0;
            $content = destination($opt, $_SESSION['dest_offset'], $db, $per_page);
            break;
        case "dest_fwd":
            $_SESSION['dest_offset'] < 0 ? $_SESSION['dest_offset'] = 0 : $_SESSION['dest_offset'] += 5;
            $content = destination($opt, $_SESSION['dest_offset'], $db, $per_page);
            break;

        case "review":
            $_SESSION['review_offset'] = 0;
        case "review_back":
            $_SESSION['review_offset'] >= 5 ? $_SESSION['review_offset'] -= 5 : $_SESSION['review_offset'] = 0;
            $content = review($opt, $_SESSION['review_offset'], $db, $per_page);
            break;
        case "review_fwd":
            $_SESSION['review_offset'] < 0 ? $_SESSION['review_offset'] = 0 : $_SESSION['review_offset'] += 5;
            $content = review($opt, $_SESSION['review_offset'], $db, $per_page);
            break;

        case "blog":
            $_SESSION['blog_offset'] = 0;
        case "blog_back":
            $_SESSION['blog_offset'] >= 5 ? $_SESSION['blog_offset'] -= 5 : $_SESSION['blog_offset'] = 0;
            $content = blog($opt, $_SESSION['blog_offset'], $db, $per_page);
            break;
        case "blog_fwd":
            $_SESSION['review_offset'] < 0 ? $_SESSION['review_offset'] = 0 : $_SESSION['review_offset'] += 5;
            $content = blog($opt, $_SESSION['blog_offset'], $db, $per_page);
            break;
    }

    $content = json_encode($content, JSON_UNESCAPED_SLASHES);
    $content = trim($content, "\"");
    echo '{"content":"' . $content . '"}';
}


function customer($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM users u JOIN customers c ON u.UserId=c.UserId $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content = " <tr><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
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
    return $content;
}


function employee($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM users u JOIN employees c ON u.UserId=c.UserId $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content = " <tr><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
        $UserId = $row['UserId'];
        $UserName = $row['UserName'];
        $Email = $row['Email'];
        $Type = getRole($row['Type']);
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
        $EmployeeStatus = getStatus($row['EmployeeStatus']);
        $content .= " <tr><td>$UserName</td><td>$Type</td><td>$EmployeeStatus</td><td>$Title $FirstName $LastName</td><td>$AddressLine1<br/>$AddressLine2<br/>$AddressLine3</td><td>$Email<br/>$Telephone<br/>$Mobile</td>
        <td><button class='success-btn m-1 edit' id='$UserId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$UserId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
    }
    return $content;
}


function reservation($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM reservations r JOIN (SELECT i.ReservationId, SUM(i.ItemPrice) AS Price, SUM(i.ItemPaid) AS Paid FROM reservations r JOIN items i on r.ReservationId=i.ReservationId GROUP BY r.ReservationId) AS t ON r.ReservationId=t.ReservationId $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content .= " <tr><th>Reservation Id</th><th>Status</th><th>Room Id</th><th>Check In</th><th>Check Out</th><th>Guests</th><th>Total</th><th>Paid</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content .= " <tr id='end'><th>Reservation Id</th><th>Status</th><th>Room Id</th><th>Check In</th><th>Check Out</th><th>Guests</th><th>Total</th><th>Paid</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
        $ReservationId = $row["ReservationId"];
        $GuestId = $row["GuestId"];
        $StaffId = $row["StaffId"];
        $RoomId = $row["RoomId"];
        $CheckIn = getTime($row["TimeSlotStart"]);
        $CheckOut = getTime($row["TimeSlotEnd"]);
        $ReservationStatus = getStatus($row['ReservationStatus']);
        $TotalPrice = $row["Price"];
        $TotalPaid = $row["Paid"];
        $Guests = $row["Guests"];
        $content .= " <tr><td>$ReservationId</td><td>$ReservationStatus</td><td>$RoomId</td><td>$CheckIn</td><td>$CheckOut</td><td>$Guests</td><td> Rs.$TotalPrice.00</td><td> Rs.$TotalPaid.00</td>
        <td><button class='fail-btn m-1 delete' id='$ReservationId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
    }
    return $content;
}


function invoice($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM items i $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content .= " <tr><th>Item Id</th><th>Reservation Id</th><th>Item Name</th><th>Item Price</th><th>Item Paid</th><th>Item Status</th><th>Item Discount</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content .= " <tr id='end'><th>Item Id</th><th>Reservation Id</th><th>Item Name</th><th>Item Price</th><th>Item Paid</th><th>Item Status</th><th>Item Discount</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
        $ItemId = $row["ItemId"];
        $ReservationId = $row["ReservationId"];
        $ItemName = $row["ItemName"];
        $ItemPrice = $row["ItemPrice"];
        $ItemPaid = $row["ItemPaid"];
        $ItemStatus = getItemStatus($row["ItemStatus"]);
        $ItemDiscount = $row['ItemDiscount'];
        $ItemComments = $row["ItemComments"];
        $content .= " <tr><td>$ItemId</td><td>$ReservationId</td><td>$ItemName</td><td>$ItemPrice</td><td>$ItemPaid</td><td> $ItemStatus</td><td> $ItemDiscount%</td>
        <td><button class='success-btn m-1 edit' id='$ItemId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$ItemId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
    }
    return $content;
}


function room($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM rooms i $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content .= " <tr><th>Room Id</th><th>Status</th><th>Name</th><th>Price</th><th>AC</th><th>WIFI</th><th>Capacity</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content .= " <tr id='end'><th>Room Id</th><th>Status</th><th>Name</th><th>Price</th><th>AC</th><th>WIFI</th><th>Capacity</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
        $RoomId = $row['RoomId'];
        $RoomName = $row['RoomName'];
        $RoomPrice = $row['RoomPrice'];
        $RoomAC = $row['RoomAC'];
        $RoomWIFI = $row['RoomWIFI'];
        $RoomCapacity = $row['RoomCapacity'];
        $RoomStatus = getStatus($row['RoomStatus']);
        $content .= " <tr><td>$RoomId</td><td>$RoomStatus</td><td>$RoomName</td><td>$RoomPrice</td><td>$RoomAC</td><td>$RoomWIFI</td><td>$RoomCapacity</td>
        <td><button class='success-btn m-1 edit' id='$RoomId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$RoomId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
    }
    return $content;
}


function destination($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM destinations i $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content .= " <tr><th>Destination Id</th><th>Destination Title</th><th>Destination Text</th><th>Destination Status</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content .= " <tr id='end'><th>Destination Id</th><th>Destination Title</th><th>Destination Text</th><th>Destination Status</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
        $DestinationId = $row['DestinationId'];
        $DestinationText = $row['DestinationText'];
        $DestinationTitle = $row['DestinationTitle'];
        $DestinationStatus = getStatus($row['DestinationStatus']);
        $content .= " <tr><td>$DestinationId</td><td>$DestinationTitle</td><td>$DestinationText</td><td>$DestinationStatus</td>
        <td><button class='success-btn m-1 edit' id='$DestinationId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$DestinationId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
    }
    return $content;
}


function review($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM reviews i $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content .= " <tr><th>Review Id</th><th>Review Status</th><th>Reservation Id</th><th>Review Title</th><th>Review Text</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content .= " <tr id='end'><th>Review Id</th><th>Review Status</th><th>Reservation Id</th><th>Review Title</th><th>Review Text</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
        $ReviewId = $row['ReviewId'];
        $ReservationId = $row['ReservationId'];
        $ReviewTitle = $row['ReviewTitle'];
        $ReviewText = $row['ReviewText'];
        $ReviewStatus = getStatus($row['Status']);
        $content .= " <tr><td>$ReviewId</td><td>$ReviewStatus</td><td>$ReservationId</td><td>$ReviewTitle</td><td>$ReviewText</td>
        <td><button class='success-btn m-1 edit' id='$ReviewId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$ReviewId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
    }
    return $content;
}


function blog($opt, $offset, $db, $per_page)
{
    $content = "";
    $sql = "SELECT * FROM blogs i $opt LIMIT 6 OFFSET $offset";
    $result = $db->query($sql);
    if ($result->num_rows > 5) {
        $content .= " <tr><th>Catelogue Id</th><th>Catelogue Title</th><th>Catelogue Text</th><th>Catelogue Status</th><th>Actions</th></tr>";
    } else if ($result->num_rows > 0) {
        $content .= " <tr id='end'><th>Catelogue Id</th><th>Catelogue Title</th><th>Catelogue Text</th><th>Catelogue Status</th><th>Actions</th></tr>";
    }
    $i = 0;
    while (($row = $result->fetch_assoc())) {
        if ($i++ >= $per_page) break;
        $BlogId = $row['BlogId'];
        $BlogText = $row['BlogText'];
        $BlogTitle = $row['BlogTitle'];
        $BlogStatus = getStatus($row['BlogStatus']);
        $content .= " <tr><td>$BlogId</td><td>$BlogTitle</td><td>$BlogText</td><td>$BlogStatus</td>
        <td><button class='success-btn m-1 edit' id='$BlogId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$BlogId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
    }
    return $content;
}