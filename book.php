<?php
    include 'lib/utils.php';
    include 'lib/check.php';
    session_start();

    $conn = connectDB();

    if(isset($_SESSION['265444_time'])){
        $time = $_SESSION['265444_time'];
        if($time < time() - $timeout){
            logout();
            echo "logout";
            exit();
        }
        else{
            $_SESSION['265444_time'] = time();
        }
    }

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
        $user_logged = $_SESSION['265444_user'];
        $id = $_REQUEST['cell'];
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "SELECT Status, Username FROM booking WHERE SeatId=? FOR UPDATE";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $id);
            if(!mysqli_stmt_execute($stmt)){
                $conn->rollback();
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
            $conn->rollback();
            return false;
        }
        return true;
    }

    function reserve(){
        global $conn;
        $id = $_REQUEST['cell'];
        $user_logged = $_SESSION['265444_user'];
        $_SESSION[$id] = 1;
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "INSERT INTO booking(SeatId, Status, Username) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE Username=?";
        $status_new = 'R';
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ssss", $id, $status_new, $user_logged, $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                $conn->rollback();
                return false;
            }
            mysqli_stmt_close($stmt);
            $conn->commit();
        }
        else{
            $conn->rollback();
            return false;
        }
        return true;
    }

    function removeReservation(){
        global $conn;
        $id = $_REQUEST['cell'];
        $user_logged = $_SESSION['265444_user'];
        $_SESSION[$id] = 0;
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "DELETE FROM booking WHERE SeatId=? AND Username=?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ss", $id, $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                $conn->rollback();
                return false;
            }
            mysqli_stmt_close($stmt);
            $conn->commit();
        }
        else{
            $conn->rollback();
            return false;
        }
        return true;
    }

    function purchaseSeats(){
        global $conn;
        global $rows, $columns;
        $n_cells = $_REQUEST['ncells'];
        $user_logged = $_SESSION['265444_user'];
        $continue = 0;
        $reserved_view_seats = array();
        $conn->autocommit(FALSE);
        $conn->begin_transaction();
        $query = "SELECT COUNT(*) FROM booking WHERE Username=? AND Status='R'";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                $conn->rollback();
                return false;
            }
            mysqli_stmt_bind_result($stmt, $n_reserved);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            if($n_reserved != $n_cells){
                //echo $n_reserved;
                for($i = 0; $i < $rows; $i++){
                    for($j = 0; $j < $columns; $j++){
                        $car = chr(65+$j);
                        $stringId = $car.($i+1);
                        if(isset($_SESSION[$stringId])){
                            if($_SESSION[$stringId] == 1){
                                 //inserire qui query per vedere se questo 
                                 //posto è presente nel database oppure se è libero   
                                $query = "SELECT COUNT(*), Username FROM booking WHERE SeatId = ? FOR UPDATE";
                                if($stmt = mysqli_prepare($conn, $query)){
                                    mysqli_stmt_bind_param($stmt, "s", $stringId);
                                    if(!mysqli_stmt_execute($stmt)){
                                        $conn->rollback();
                                        return false;
                                    }
                                    mysqli_stmt_bind_result($stmt, $is_there, $is_whose);
                                    mysqli_stmt_fetch($stmt);
                                    mysqli_stmt_close($stmt);
                                    if($is_there != 0 && $is_whose != $user_logged){
                                        $continue = 1;
                                    }
                                    else if($is_there == 0){
                                        $query = "INSERT INTO booking(SeatId, Status, Username) VALUES (?, ?, ?)";
                                        $status_new = 'R';
                                        if ($stmt = mysqli_prepare($conn, $query)) {
                                            mysqli_stmt_bind_param($stmt, "sss", $stringId, $status_new, $user_logged);
                                            if(!mysqli_stmt_execute($stmt)){
                                                $conn->rollback();
                                                return false;
                                            }
                                            mysqli_stmt_close($stmt);
                                        }
                                        else{
                                            $conn->rollback();
                                            return false;
                                        }
                                    }
                                }
                                else{
                                    $conn->rollback();
                                    return false;
                                }
                            }
                        }
                    }
                }
                if($continue == 1){
                    $query = "DELETE FROM booking WHERE Username=? AND Status='R'";
                    if ($stmt = mysqli_prepare($conn, $query)) {
                        mysqli_stmt_bind_param($stmt, "s", $user_logged);
                        if(!mysqli_stmt_execute($stmt)){
                            $conn->rollback();
                            return false;
                        }
                        mysqli_stmt_close($stmt);
                    }
                    $conn->commit();
                    return false;
                }
            }
        }
        else{
            $conn->rollback();
            return false;
        }
        $query = "UPDATE booking SET Status=? WHERE Username=?";
        $status_new = 'P';
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ss", $status_new, $user_logged);
            if(!mysqli_stmt_execute($stmt)){
                $conn->rollback();
                return false;
            }
            mysqli_stmt_close($stmt);
            $conn->commit();
        }
        else{
            $conn->rollback();
            return false;
        }
        return true;
    }

?>