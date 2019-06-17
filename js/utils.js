
function enableDisableSubmit() {
    var regexMail = /\S+@\S+\.\S+/;
    var usernameMail = document.getElementById("username").value;
    if(usernameMail.match(regexMail) != null){
        document.getElementById("submit").disabled = false;
    }
    else{
        document.getElementById("submit").disabled = true;
    }
}

function signUpCheckPswConstraints(){
    var regexMail = /\S+@\S+\.\S+/;
    var usernameMail = document.getElementById("username").value;
    var regexPsw = /.*[a-z].*[A-Z0-9].*|.*[A-Z0-9].*[a-z].*/;
    var passwordOne = document.getElementById("psw1").value;
    var passwordTwo = document.getElementById("psw2").value;
    if(passwordOne.match(regexPsw) != null && passwordOne == passwordTwo && usernameMail.match(regexMail) != null){
        document.getElementById("submit").disabled = false;
        document.getElementById("message").style.display = "none";
    }
    else{
        document.getElementById("message").style.display = "block";
        document.getElementById("submit").disabled = true;
    }
}

function checkSeat(cell){
    AjaxReq = new XMLHttpRequest();
    AjaxReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText == "logout"){
                window.open("login.php?msg=logout", "_parent");
            }
            else{
                if(this.responseText == "yellow"){
                    window.cellsToBook++;
                    printMessage("reservesucc");
                }
                else{
                    window.cellsToBook--;
                    if(this.responseText != "lightgreen"){
                        printMessage("reserveerr");
                    }
                    else{
                        printMessage("removed");
                    }
                }
                changeColor(cell, this.responseText);
            }
        }
    };
    AjaxReq.open("GET", "book.php?cell=" + cell.id, true);
    AjaxReq.send();
}

function changeColor(cell, color){
    var priority = null;
    if(color == "red"){
        cell.style.cursor = "not-allowed";
        priority = "important";
    }
    cell.style.setProperty("background-color", color, priority);
}

function buy(){
    AjaxReq = new XMLHttpRequest();
    AjaxReq.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(window.cellsToBook != 0){
                window.cellsToBook = 0;
                if(this.responseText == "error"){
                    window.open("home.php?msg=buyerr", "_parent");
                }
                else{
                    window.open("home.php?msg=buysucc", "_parent");
                }
            }
        }
    };
    AjaxReq.open("POST", "book.php?buy=1" + "&ncells=" + window.cellsToBook, true);
    AjaxReq.send();
}

function reload(){
    window.open("home.php", "_parent");
}

function errMessageVisitor(){
    $(document).ready(function(){
        $("#must-log").css("margin-top", "50px");
        $("#must-log").css("display", "inline-block");
    });
}

function testCookies(){
    document.cookie = 'cookietest=1';
    var cookiesEnabled = document.cookie.indexOf('cookietest=') !== -1;
    document.cookie = 'cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT';
    if(!cookiesEnabled){
        $(document).ready(function(){
            $(".main").css("display", "none");
            $("#cookies-dis").css("display", "inline-block");
        });
    }
    else{
        $(document).ready(function(){
            $(".main").css("display", "block");
        });
    }
}

function printMessage(msg){
    var strMess = "";
    $(document).ready(function(){
        if(msg.id == undefined){
            strMess = msg;
        }
        else{
            strMess = msg.id;
        }
        if(strMess.includes("err")){
            bgColor = "red";
        }
        else{
            bgColor = "green";
        }
        $(".msg").css("display", "none");
        $("#" + strMess).css("background-color", bgColor);
        $("#" + strMess).css("display", "inline-block");
    });
}
