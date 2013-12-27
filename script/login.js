function do_login()
{
	if(document.getElementById('user').value.length < 1)
	{
		alert(nousername);
		document.getElementById('user').focus();
		return false;
	}
	if(document.getElementById('password').value.length < 1)
	{
		alert(nouserpass);
		document.getElementById('password').focus();
		return false;
	}
	if(document.getElementById('randcode').value.length < 1)
	{
		alert(norandcode);
		document.getElementById('randcode').focus();
		return false;
	}
}