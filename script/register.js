// JavaScript Document
function check()
{
	var letters = "abcdefghijklmnopqrstuvwxyz_0123456789" +
				 "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
	var letter =  "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
	var allword = letters + "~!@#$%^&*()+|/?;:'[{]}`"
		  
	if(!$('#yzm').val())
	{
		alert(allnotblank)
		$('#yzm').focus()
		return false
	}
	if(!$('#user').val())
	{
		alert(allnotblank)
		$('#user').focus()
		return false
	}
	if(!$('#password').val())
	{
		alert(allnotblank)
		$('#password').focus()
		return false
	}
	if(!$('#re_password').val())
	{
		alert(allnotblank)
		$('#re_password').focus()
		return false
	}
	if(!$('#email').val())
	{
		alert(allnotblank)
		$('#email').focus()
		return false
	}
	
	if($('#password').val().length < 4)
	{
		alert(passlength)
		$('#password').focus()
		return false
	}
	if($('#password').val().length > 20)
	{
		alert(passistooleng)
		$('#password').focus()
		return false
	}
	if($('#password').val() != $('#re_password').val())
	{
		alert(passnotsame)
		$('#re_password').focus()
		return false
	}
}
function show_yzm()
{
	$('#yzm_pic').html("<img onclick='get_randfunc(this);' src='includes/rand_func.php'/>");
}
function check_email()
{
	if($('#email').val().indexOf("@") == -1)
	{
		$('#tishi_email').html(emailerror);
		return false;
	}
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&check_email=1&email='+$('#email').val(); 
	$.post(url, pars,function (originalRequest)
		{
			if(originalRequest==1)
				$('#tishi_email').html("<img src=image/default/icon_right_19x19.gif>");
			if(originalRequest==2)
				$('#tishi_email').html(changeemil);
		}
	 )	
}

function show_yzwt()
{
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&wtyz=1'; 
	$.post(url, pars,function (originalRequest)
		{
			if(originalRequest)
				$('#yzwt').html(originalRequest);
			$('#tishi8').html("");
		}
	)	
}

function check_yzwt()
{
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&ckyzwt='+$('#ckyzwt').val(); 
	$.post(url, pars,function (originalRequest)
	{
		if(originalRequest=='true')
		{	
			$('#tishi8').html("<img src=image/default/icon_right_19x19.gif>");
		}
		else
		{
			$('#tishi8').html(randcodeerror);
			$('#ckyzwt').val('');	
			$('#ckyzwt').focus()
		}
	});
}
function check_yzm()
{
	if(!$('#yzm').val())
	{
		$('#tishi2').html(randcodeisemp);
		$('#yzm').focus()
		return false
	}
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&yzm='+$('#yzm').val(); 
	$.get(url, pars,function (originalRequest)
	{
		if(originalRequest>0)
		{	
			$('#tishi2').html(rcodeiserror);
			$('#yzm').val('');	
			$('#yzm').focus()
		}
		else
		{
			$('#tishi2').html("<img src=image/default/icon_right_19x19.gif>");
		}
	});
}

function check_user()
{
	  var letters = "abcdefghijklmnopqrstuvwxyz_0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"
	  var letter =  "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
	  var allword = letters + "~!@#$%^&*()+|/?;:'[{]}`"
	  
	  if($('#user').val().length < 1)
	  {
		$('#tishi').html(enterusername);
		return false
	  }
	  else if($('#user').val().length < 4)
	  {
		$('#tishi').html(unameisfour);
		return false
	  }
	  else if($('#user').val().indexOf(" ")!=-1)
	  {
		$('#tishi').html(have_blank);
		return false
	  }
	  else
	  {
		  $('#tishi').html("");
		 　var l=0;
		   var v=$('#user').val();
		   for(i=0;i<v.length;i++)  
		   {
				   var t=v.charCodeAt(i);
				   if(t>255)
				   {
						$('#tishi').html(unameisen);
						$('#user').val('');
						return false 
				   }
		   }
		   	
			var url = 'ajax_back_end.php';
			var sj = new Date();
			var pars = 'shuiji=' + sj+'&user='+$('#user').val(); 
			$.get(url, pars,function (originalRequest){
					if(originalRequest!='')
					{	
						if(originalRequest>0)
						{
							$('#tishi').html(usernameprotect);
							$('#user').val();
						}
						else
						{
							$('#tishi').html("<img src=image/default/icon_right_19x19.gif>");
						}
					}
				}						  
			); 
	  }

}