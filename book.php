<?php
    include 'lib/utils.php';
    session_start();

    $conn = connectDB();

    if(isset($_REQUEST['buy'])){
        purchaseSeats($conn);
    }
    else if(isset($_REQUEST['cell'])){
        checkStatusSeat($conn);
    }
    mysqli_close($conn);

    function checkStatusSeat($conn){
        $user_logged = $_SESSION['user'];
        $id = $_REQUEST['cell'];
        $query = "SELECT status, username FROM BOOKING WHERE seatid=?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $id);
            if(!mysqli_stmt_execute($stmt)){
                return false;
            }
            mysqli_stmt_bind_result($stmt, $status, $user);
            mysqli_stmt_fetch($stmt);
            if($status == null || ($status == 'R' && $user != $user_logged)){
                $color = "yellow";
                reserve($conn);
            }
            else if($status == 'R' && $user == $user_logged){
                $color = "lightgreen";
                removeReservation($conn);
            }
            else if($status == 'P'){
                $color = "red";
            }
            mysqli_stmt_close($stmt);
            echo $color;
        }
        else{
            return false;
        }
    }

    function reserve($conn){
        $id = $_REQUEST['cell'];
        $user_logged = $_SESSION['user'];
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "INSERT INTO BOOKING(SeatId, Status, Username) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE Username = ?";
        $status_new = 'R';
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ssss", $id, $status_new, $user_logged, $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                return false;
            }
            mysqli_stmt_close($stmt);
            $conn->commit();
        }
        else{
            return false;
        }
    }

    function removeReservation($conn){
        $id = $_REQUEST['cell'];
        $user_logged = $_SESSION['user'];
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "DELETE FROM BOOKING WHERE SeatId = ? AND Username = ?";
        $status_new = 'R';
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ss", $id, $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                return false;
            }
            mysqli_stmt_close($stmt);
            $conn->commit();
        }
        else{
            return false;
        }
    }

    function purchaseSeats($conn){
        $n_cells = $_REQUEST['ncells'];
        $user_logged = $_SESSION['user'];
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "UPDATE BOOKING SET Status = ? WHERE Username = ?";
        $status_new = 'P';
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ss", $status_new, $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                return false;
            }
            mysqli_stmt_close($stmt);
            $conn->commit();
        }
        else{
            return false;
        }
    }

?>