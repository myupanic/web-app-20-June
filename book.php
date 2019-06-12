<?php
    include 'lib/utils.php';
    session_start();
    $id = $_REQUEST['cell'];
    $buy = $_REQUEST['buy'];
    $user_logged = $_SESSION['user'];

    $conn = connectDB();
    $query = "SELECT status, username FROM BOOKING where seatid=?";
	if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $id);
        if(!mysqli_stmt_execute($stmt)){
            return false;
        }
        mysqli_stmt_bind_result($stmt, $status, $user);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        if($status == null || $status == 'R' && $user != $user_logged){
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
        echo $color;
    }
    else{
        return false;
    }
    mysqli_close($conn);

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

?>