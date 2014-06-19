function check(input) 
{
	var pass = document.getElementById('password').value;
	var cpass = document.getElementById('password_confirmation').value;

	if (pass != cpass) {
		input.setCustomValidity('The two passwords must match.');
	} 
	else {
		// input is valid -- reset the error message
		 input.setCustomValidity('');
	}
}
