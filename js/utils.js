var cellsToBook = 0;

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
            //console.log(this.responseText);
            if(this.responseText == "yellow"){
                cellsToBook++;
            }
            else if(this.responseText == "lightgreen"){
                cellsToBook--;
            }
            if(cellsToBook == 0){
                document.getElementById("buy").disabled = true;
            }
            else{
                document.getElementById("buy").disabled = false;
            }
            changeColor(cell, this.responseText);
        }
    };
    AjaxReq.open("GET", "book.php?cell=" + cell.id, true);
    AjaxReq.send();
}

function changeColor(cell, color){
    priority = null;
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
            //cellsToBook = 0;
            if(this.responseText == "error"){
                window.open("home.php?msg=error", "_parent");
            }
            reload();
        }
    };
    AjaxReq.open("GET", "book.php?buy=1" + "&ncells=" + cellsToBook, true);
    AjaxReq.send();
}

function reload(){
    window.open("home.php", "_parent");
}
