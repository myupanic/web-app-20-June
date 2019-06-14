<?php
    include 'lib/utils.php';
    session_start();

    $conn = connectDB();


    if(isset($_REQUEST['buy'])){
        if(purchaseSeats() == false){
            echo "error";
        }
    }
    else if(isset($_REQUEST['cell'])){
        checkStatusSeat();
    }
    mysqli_close($conn);

    function checkStatusSeat(){
        global $conn;
        $user_logged = $_SESSION['user'];
        $id = $_REQUEST['cell'];
        $query = "SELECT Status, Username FROM booking WHERE SeatId=?";
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $id);
            if(!mysqli_stmt_execute($stmt)){
                return false;
            }
            mysqli_stmt_bind_result($stmt, $status, $user);
            mysqli_stmt_fetch($stmt);
            if($status == null || ($status == 'R' && $user != $user_logged)){
                $color = "yellow";
                mysqli_stmt_close($stmt);
                $conn->commit();
                reserve();
            }
            else if($status == 'R' && $user == $user_logged){
                $color = "lightgreen";
                mysqli_stmt_close($stmt);
                $conn->commit();
                removeReservation();
            }
            else if($status == 'P'){
                mysqli_stmt_close($stmt);
                $conn->commit();
                $color = "red";
            }
            echo $color;
        }
        else{
            return false;
        }
    }

    function reserve(){
        global $conn;
        $id = $_REQUEST['cell'];
        $user_logged = $_SESSION['user'];
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "INSERT INTO booking(SeatId, Status, Username) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE Username=?";
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

    function removeReservation(){
        global $conn;
        $id = $_REQUEST['cell'];
        $user_logged = $_SESSION['user'];
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "DELETE FROM booking WHERE SeatId=? AND Username=?";
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

    function purchaseSeats(){
        global $conn;
        $n_cells = $_REQUEST['ncells'];
        $user_logged = $_SESSION['user'];
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "SELECT COUNT(*) FROM booking WHERE Username=? AND Status='R'";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                return false;
            }
            mysqli_stmt_bind_result($stmt, $n_reserved);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            if($n_reserved != $n_cells){
                $query = "DELETE FROM booking WHERE Username=? AND Status='R'";
                if ($stmt = mysqli_prepare($conn, $query)) {
                    mysqli_stmt_bind_param($stmt, "s", $user_logged);
                    if(!mysqli_stmt_execute($stmt)){
                        return false;
                    }
                    mysqli_stmt_close($stmt);
                }
                $conn->commit();
                return false;
            }
        }
        else{
            return false;
        }
        $query = "UPDATE booking SET Status=? WHERE Username=?";
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