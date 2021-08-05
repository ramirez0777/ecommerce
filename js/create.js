var flag = false;
if (flag == true)
{
	document.getElementById("submit").disabled = true;
}
function verify()
{
	let reg = /^(?=.*[0-9]+.*)(?=.*[a-zA-Z]+.*)[0-9a-zA-Z]{6,}$/;
	let pass = document.getElementById("pass").value;
	let vpass = document.getElementById("vpass").value;
	
	
	if(pass == vpass)
	{
		if (pass.match(reg))
		{
			document.getElementById("submit").disabled = false;
			document.getElementById("verify").className = 'hide';
		}
		else
		{
			document.getElementById("verify").className = 'show-pass';
			document.getElementById("verify").innerText = 'Password need to have at least 8 characters inluding a number';
			document.getElementById("submit").disabled = true;
		}
	}
	else
	{
		document.getElementById("verify").className = 'show-pass';
		document.getElementById("verify").innerText = 'Passwords don\'t match';

		document.getElementById("submit").disabled = true;
	}
}