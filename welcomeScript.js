$(document).ready(function(){
	var signupDetails = JSON.parse(localStorage.getItem('signupDetails'));
	var fullName = [signupDetails.firstName, signupDetails.middleName, signupDetails.lastName];
	fullName = fullName.join(" ");
	$("#name").text(fullName);
});