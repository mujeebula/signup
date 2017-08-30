$(document).ready(function(){
      var date_input=$('#dateOfBirth');
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'yyyy-mm-dd',
        container: container,
        endDate: '+0d',
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
});
console.log(JSON.parse(localStorage.getItem('signupDetails')));

$("#pwd").focusout(function(){
  testSignupPassword($(this).val());
});

$("#repeat-pwd").focusout(function(){
  testSignupRepeatPassword($(this).val());
});

/*
  This function validates form for password constraints.
  On success it stores the data in Local Storage.
*/
function validateSignupForm() {
  if(testSignupPassword($("#signup-pwd").val())){
    if(testSignupRepeatPassword($("#signup-repeat-pwd").val())){
      if($("#signup-pwd").val() == $("#signup-repeat-pwd").val()){
        console.log("correct pwd");
        storeUserData();
        return true;
      }else{
          $("#err-repeat-pwd").text("Password does not match.").show();
          return false;
      }
    }else{
      return false;
    }
  }else{
    return false;
  }
}

function validateLoginForm(){
  if(testLoginPassword($("#login-pwd").val())){
    return true;
  }else{
    return false;
  }
}

function testLoginPassword(password) {
  if(!(password.length >= 8)){
    $("#login-err-pwd").text("Password length should be greater than 7").show();
    return false;
  }
  if(!(/[A-Z]+/).test(password)){
    $("#login-err-pwd").text("Password must have atleast one upper case letter").show();
    return false;
  }
  if(!(/[a-z]+/).test(password)){
    $("#login-err-pwd").text("Password must have atleast one lower case letter").show();
    return false;
  }
  if(!(/[0-9]+/).test(password)){
    $("#login-err-pwd").text("Password must have atleast one digit").show();
    return false;
  }
  if(!(/[^a-zA-Z0-9]+/).test(password)){
    $("#login-err-pwd").text("Password must have atleast one special character").show();
    return false;
  }
  $("#login-err-pwd").hide();
  return true;
}


/*
  It verifies if the password follows all constraints.
  Else it shows the first un-sattisfied constraint.
*/
function testSignupPassword(password) {
  if(!(password.length >= 8)){
    $("#signup-err-pwd").text("Password length should be greater than 7").show();
    return false;
  }
  if(!(/[A-Z]+/).test(password)){
    $("#signup-err-pwd").text("Password must have atleast one upper case letter").show();
    return false;
  }
  if(!(/[a-z]+/).test(password)){
    $("#signup-err-pwd").text("Password must have atleast one lower case letter").show();
    return false;
  }
  if(!(/[0-9]+/).test(password)){
    $("#signup-err-pwd").text("Password must have atleast one digit").show();
    return false;
  }
  if(!(/[^a-zA-Z0-9]+/).test(password)){
    $("#signup-err-pwd").text("Password must have atleast one special character").show();
    return false;
  }
  $("#signup-err-pwd").hide();
  return true;
}

/*
  It verifies if the password follows all constraints.
  Else it shows the first un-sattisfied constraint.
*/
function testSignupRepeatPassword(password) {
  if(!(password.length >= 8)){
    $("#signup-err-repeat-pwd").text("Password length should be greater than 7").show();
    return false;
  }
  if(!(/[A-Z]+/).test(password)){
    $("#signup-err-repeat-pwd").text("Password must have atleast one upper case letter").show();
    return false;
  }
  if(!(/[a-z]+/).test(password)){
    $("#signup-err-repeat-pwd").text("Password must have atleast one lower case letter").show();
    return false;
  }
  if(!(/[0-9]+/).test(password)){
    $("#signup-err-repeat-pwd").text("Password must have atleast one digit").show();
    return false;
  }
  if(!(/[^a-zA-Z0-9]+/).test(password)){
    $("#signup-err-repeat-pwd").text("Password must have atleast one special character").show();
    return false;
  }
  if($("#pwd").val() == $("#repeat-pwd").val()){
    console.log("correct pwd");
  }else{
    $("#signup-err-repeat-pwd").text("Password does not match.").show();
    return false;
  }  
  $("#signup-err-repeat-pwd").hide();
  return true;
}

/*
  This function stores the user details using Local Storage API
*/
function storeUserData(){
  var firstName = $("#firstName").val();
  var middleName = $("#middleName").val();
  var lastName = $("#lastName").val();
  var email = $("#email").val();
  var password = $("#pwd").val();
  var gender = $('input[name=gender]:checked', '#signupForm').val()
  var dateOfBirth = $("#dateOfBirth").val();

  var signupDetails = {};
  signupDetails.firstName = firstName;
  signupDetails.middleName = middleName;
  signupDetails.lastName = lastName;
  signupDetails.email = email;
  signupDetails.password = password;
  signupDetails.gender = gender;
  signupDetails.dateOfBirth = dateOfBirth;

  localStorage.setItem('signupDetails', JSON.stringify(signupDetails));
}

/*
  This function retrieves the user details using Local Storage API
*/
function getUserData(){
  var signupDetails = JSON.parse(localStorage.getItem('signupDetails'));
}