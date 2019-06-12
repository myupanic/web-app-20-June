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