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
            } else if ($result->num_rows > 0) {
                $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            }

            $i=0;
            while (($row = $result->fetch_assoc()) ) {
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
            break;

        case "customer_fwd":
            $_SESSION['customer_offset'] < 0 ? $_SESSION['customer_offset'] = 0 : $_SESSION['customer_offset'] += 5;
            $offset = $_SESSION['customer_offset'];
            $sql = "SELECT * FROM users u JOIN customers c ON u.UserId=c.UserId $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content = " <tr><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0) {
                $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
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
            break;

        case "employee":
            $_SESSION['employee_offset'] = 0;

        case "employee_back":
            $_SESSION['employee_offset'] >= 5 ? $_SESSION['employee_offset'] -= 5 : $_SESSION['employee_offset'] = 0;
            $offset = $_SESSION['employee_offset'];
            $sql = "SELECT * FROM users u JOIN employees c ON u.UserId=c.UserId $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content = " <tr><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0) {
                $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
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
            break;

        case "employee_fwd":
            $_SESSION['employee_offset'] < 0 ? $_SESSION['employee_offset'] = 0 : $_SESSION['employee_offset'] += 5;
            $offset = $_SESSION['employee_offset'];
            $sql = "SELECT * FROM users u JOIN customers c ON u.UserId=c.UserId $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content = " <tr><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0) {
                $content = " <tr id='end'><th>User name</th><th>Role</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
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
            break;

        case "res":
            $_SESSION['res_offset'] = 0;

        case "res_back":
            $_SESSION['res_offset'] >= 5 ? $_SESSION['res_offset'] -= 5 : $_SESSION['res_offset'] = 0;
            $offset = $_SESSION['res_offset'];
            $sql = "SELECT * FROM reservations r JOIN (SELECT i.ReservationId, SUM(i.ItemPrice) AS Price, SUM(i.ItemPaid) AS Paid FROM reservations r JOIN items i on r.ReservationId=i.ReservationId GROUP BY r.ReservationId) AS t ON r.ReservationId=t.ReservationId $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content .= " <tr><th>Reservation Id</th><th>Status</th><th>Room Id</th><th>Check In</th><th>Check Out</th><th>Total</th><th>Paid</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0) {
                $content .= " <tr id='end'><th>Reservation Id</th><th>Status</th><th>Room Id</th><th>Check In</th><th>Check Out</th><th>Total</th><th>Paid</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
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

                $content .= " <tr><td>$ReservationId</td><td>$ReservationStatus</td><td>$RoomId</td><td>$CheckIn</td><td>$CheckOut</td><td> Rs.$TotalPrice.00</td><td> Rs.$TotalPaid.00</td>
                        <td><button class='fail-btn m-1 delete' id='$ReservationId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;

        case "res_fwd":
            $_SESSION['res_offset'] < 0 ? $_SESSION['res_offset'] = 0 : $_SESSION['res_offset'] += 5;
            $offset = $_SESSION['res_offset'];
            $sql = "SELECT * FROM reservations r JOIN (SELECT i.ReservationId, SUM(i.ItemPrice) AS Price, SUM(i.ItemPaid) AS Paid FROM reservations r JOIN items i on r.ReservationId=i.ReservationId GROUP BY r.ReservationId) AS t ON r.ReservationId=t.ReservationId opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content .= " <tr><th>Reservation Id</th><th>Status</th><th>Room Id</th><th>Check In</th><th>Check Out</th><th>Total</th><th>Paid</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0) {
                $content .= " <tr id='end'><th>Reservation Id</th><th>Status</th><th>Room Id</th><th>Check In</th><th>Check Out</th><th>Total</th><th>Paid</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
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

                $content .= " <tr><td>$ReservationId</td><td>$ReservationStatus</td><td>$RoomId</td><td>$CheckIn</td><td>$CheckOut</td><td> Rs.$TotalPrice.00</td><td> Rs.$TotalPaid.00</td>
                        <td><button class='fail-btn m-1 delete' id='$ReservationId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;

        case "invoice":
            $_SESSION['invoice_offset'] = 0;

        case "invoice_back":
            $_SESSION['invoice_offset'] >= 5 ? $_SESSION['invoice_offset'] -= 5 : $_SESSION['invoice_offset'] = 0;
            $offset = $_SESSION['invoice_offset'];
            $sql = "SELECT * FROM items i $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content .= " <tr><th>Item Id</th><th>Reservation Id</th><th>Item Name</th><th>Item Price</th><th>Item Paid</th><th>Item Status</th><th>Item Discount</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0) {
                $content .= " <tr id='end'><th>Item Id</th><th>Reservation Id</th><th>Item Name</th><th>Item Price</th><th>Item Paid</th><th>Item Status</th><th>Item Discount</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
                if ($i++ >= $per_page) break;

                $ItemId = $row["ItemId"];
                $ReservationId = $row["ReservationId"];
                $ItemName = $row["ItemName"];
                $ItemPrice = $row["ItemPrice"];
                $ItemPaid = $row["ItemPaid"];
                $ItemStatus = getStatus($row["ItemStatus"]);
                $ItemDiscount = $row['ItemDiscount'];
                $ItemComments = $row["ItemComments"];

                $content .= " <tr><td>$ItemId</td><td>$ReservationId</td><td>$ItemName</td><td>$ItemPrice</td><td>$ItemPaid</td><td> $ItemStatus</td><td> $ItemDiscount%</td>
                    <td><button class='success-btn m-1 edit' id='$ItemId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$ItemId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;

        case "invoice_fwd":
            $_SESSION['invoice_offset'] < 0 ? $_SESSION['invoice_offset'] = 0 : $_SESSION['invoice_offset'] += 5;
            $offset = $_SESSION['invoice_offset'];
            $sql = "SELECT * FROM items i $opt LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 5) {
                $content .= " <tr><th>Item Id</th><th>Reservation Id</th><th>Item Name</th><th>Item Price</th><th>Item Paid</th><th>Item Status</th><th>Item Discount</th><th>Actions</th></tr>";
            } else if ($result->num_rows > 0) {
                $content .= " <tr id='end'><th>Item Id</th><th>Reservation Id</th><th>Item Name</th><th>Item Price</th><th>Item Paid</th><th>Item Status</th><th>Item Discount</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
                if ($i++ >= $per_page) break;

                $ItemId = $row["ItemId"];
                $ReservationId = $row["ReservationId"];
                $ItemName = $row["ItemName"];
                $ItemPrice = $row["ItemPrice"];
                $ItemPaid = $row["ItemPaid"];
                $ItemStatus = getStatus($row["ItemStatus"]);
                $ItemDiscount = $row['ItemDiscount'];
                $ItemComments = $row["ItemComments"];

                $content .= " <tr><td>$ItemId</td><td>$ReservationId</td><td>$ItemName</td><td>$ItemPrice</td><td>$ItemPaid</td><td> $ItemStatus</td><td> $ItemDiscount%</td>
                    <td><button class='success-btn m-1 edit' id='$ItemId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$ItemId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;

        case "room_back":
            $_SESSION['room_offset'] >= 5 ? $_SESSION['room_offset'] -= 5 : $_SESSION['room_offset'] = 0;
            $offset = $_SESSION['room_offset'];
            $sql = "SELECT * FROM rooms LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
                $content .= " <tr><th>Room Id</th><th>Status</th><th>Room Name</th><th>Room Price</th><th>Room AC</th><th>Room WIFI</th><th>Room Capacity</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
                if ($i++ >= $per_page) break;

                $RoomId = $row['RoomId'];
                $RoomName = $row['RoomName'];
                $RoomPrice = $row['RoomPrice'];
                $RoomAC = $row['RoomAC'];
                $RoomWIFI = $row['RoomWIFI'];
                $RoomCapacity = $row['RoomCapacity'];
                $RoomStatus = getStatus($row['Status']);

                $content .= " <tr><td>$RoomId</td><td>$RoomStatus</td><td>$RoomName</td><td>$RoomPrice</td><td>$RoomAC</td><td>$RoomWIFI</td><td>$RoomCapacity</td>
                            <td><button class='success-btn m-1 edit' id='$RoomId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$RoomId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;

        case "room_fwd":
            $_SESSION['room_offset'] < 0 ? $_SESSION['room_offset'] = 0 : $_SESSION['room_offset'] += 5;
            $offset = $_SESSION['room_offset'];
            $sql = "SELECT * FROM rooms LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
                $content .= " <tr><th>Emp Id</th><th>Status</th><th>Name</th><th>Address</th><th>Contacts</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
                if ($i++ >= $per_page) break;

                $RoomId = $row['RoomId'];
                $RoomName = $row['RoomName'];
                $RoomPrice = $row['RoomPrice'];
                $RoomAC = $row['RoomAC'];
                $RoomWIFI = $row['RoomWIFI'];
                $RoomCapacity = $row['RoomCapacity'];
                $RoomStatus = getStatus($row['Status']);

                $content .= " <tr><td>$RoomId</td><td>$RoomStatus</td><td>$RoomName</td><td>$RoomPrice</td><td>$RoomAC</td><td>$RoomWIFI</td><td>$RoomCapacity</td>
                            <td><button class='success-btn m-1 edit' id='$RoomId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$RoomId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;

        case "review_back":
            $_SESSION['review_offset'] >= 5 ? $_SESSION['review_offset'] -= 5 : $_SESSION['review_offset'] = 0;
            $offset = $_SESSION['review_offset'];
            $sql = "SELECT * FROM reviews LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
                $content .= " <tr><th>Review Id</th><th>Status</th><th>Reservation Id</th><th>Review Title</th><th>Review</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
                if ($i++ >= $per_page) break;

                $ReviewId = $row['ReviewId'];
                $ReservationId = $row['ReservationId'];
                $ReviewTitle = $row['ReviewTitle'];
                $ReviewText = $row['ReviewText'];
                $ReviewStatus = getStatus($row['Status']);

                $content .= " <tr><td>$ReviewId</td><td>$ReviewStatus</td><td>$ReservationId</td><td>$ReviewTitle</td><td>$ReviewText</td>
                                <td><button class='success-btn m-1 edit' id='$ReviewId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$ReviewId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;

        case "review_fwd":
            $_SESSION['review_offset'] < 0 ? $_SESSION['review_offset'] = 0 : $_SESSION['review_offset'] += 5;
            $offset = $_SESSION['review_offset'];
            $sql = "SELECT * FROM reviews LIMIT 6 OFFSET $offset";
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
                $content .= " <tr><th>Review Id</th><th>Status</th><th>Reservation Id</th><th>Review Title</th><th>Review</th><th>Actions</th></tr>";
            }
            
            $i=0;
            while (($row = $result->fetch_assoc()) ) {
                if ($i++ >= $per_page) break;

                $ReviewId = $row['ReviewId'];
                $ReservationId = $row['ReservationId'];
                $ReviewTitle = $row['ReviewTitle'];
                $ReviewText = $row['ReviewText'];
                $ReviewStatus = getStatus($row['Status']);

                $content .= " <tr><td>$ReviewId</td><td>$ReviewStatus</td><td>$ReservationId</td><td>$ReviewTitle</td><td>$ReviewText</td>
                                <td><button class='success-btn m-1 edit' id='$ReviewId'><i class='material-icons p-2'>edit</i></button><button class='fail-btn m-1 delete' id='$ReviewId'><i class='material-icons p-2'>delete_forever</i></button></td></tr>";
                
            }
            break;
    }
    $content = json_encode($content, JSON_UNESCAPED_SLASHES);
    $content = trim($content, "\"");
    echo '{"content":"' . $content . '"}';
}
