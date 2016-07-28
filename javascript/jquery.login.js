$(function() {
	
	/*  Initialize  */
	var login_submitable = true;
	
	/*  end of Initialize */
	
	$("#sign-in").click(function(e)  /* SIGN IN BUTTON CLICKED */
	{
		e.stopPropagation();
		
		if ($("#login-content").is(":hidden")) {
			showLogin();
			$("[name=user]").focus();
		} else {
			hideLogin();
		}
	});
	$("#sign-out").click(function()  /* SIGN OUT BUTTON CLICKED */
	{
		$.post("/ajax/ajax.logout.php",
		function (data)
		{
			location.reload();
		});
	});
	
	$("#login-content").click(function(e)
	{
		e.stopPropagation();
	});
	
	$(document).click(function ()
	{
		hideLogin();
	});
	
	function showLogin()
	{
		$("#login-content").slideDown({duration: "fast", queue: false});
	}
	function hideLogin()
	{
		$("#login-content").slideUp({
			duration: "fast", 
			queue: false,
			done: function()
				{
					$("#login-action").show();
					$("#login-spinner").hide();
					$('#login-input > [name=user], #login-input > [name=pass]').val("");
				}
			});
	}
	
	$("#login-content > form").submit(function(e)  /* LOGIN FORM SUBMIT */
	{
		e.preventDefault();
		
		if (login_submitable)
		{
			login_submitable = false;
			
			$("#login-action").hide();
			$("#login-spinner").show();
			
			$.post("/ajax/ajax.login.php", {
				user: $('[name=user]').val(),
				pass: $('[name=pass]').val()},
				function (data)
				{
					if (data.success)
					{
						hideLogin();
						location.reload();
					}
					else
					{
						alert("Invalid Username/Password");
						$('#login-input > [name=user], #login-input > [name=pass]').val("");
						$("#login-input > [name=user]").focus();
						login_submitable = true;
						
						$("#login-action").show();
						$("#login-spinner").hide();
					}
				}, 'json');
		}
	});
	
	$("#change_pass_link").fancybox({
		afterShow: function()
			{
				$("#old_pass").focus();
			},
		afterClose: function()
			{
				$("#old_pass").val('');
				$("#new_pass1").val('');
				$("#new_pass2").val('');
				$("#change_pass_spinner").hide();
				$("#change_pass_actions > input").show();
			}
	});
	
	$("#change_pass_form").submit(function(e)
	{
		e.preventDefault();

		var old_pass = $("#old_pass").val();
		var new_pass1 = $("#new_pass1").val();
		var new_pass2 = $("#new_pass2").val();
		
		if (old_pass)
		{
			if (new_pass1 && new_pass2)
			{
				if (new_pass1 == new_pass2)
				{
					$("#change_pass_spinner").show();
					$("#change_pass_actions > input").hide();
					
					$.ajax({
						url: "../ajax/ajax.changepass.php",
						type: "POST",
						data: {old_pass: old_pass, new_pass: new_pass1},
						dataType: "json",
						async: true,
						success: function (data)
						{
							if (data.success)
							{
								alert("Your password has been changed!");
								$.fancybox.close();
							
								$("#old_pass").val('');
								$("#new_pass1").val('');
								$("#new_pass2").val('');
								
								$("#change_pass_spinner").hide();
								$("#change_pass_actions > input").show();
							}
							else
							{
								alert("Error:  Please be sure you entered your current password correctly!");
							}
						},
						error: function(request, status, error)
						{
							alert(status);
						}
					});
				}
				else
				{
					$("#new_pass1").val('');
					$("#new_pass2").val('');
					$("#new_pass1").focus();
					alert("Please ensure that both passwords match.");
				}
			}
			else
			{
				$("#new_pass1").focus();
				alert("Please fill in both password fields.");
			}
		}
		else
		{
			$("#old_pass").focus();
			alert("Please enter your current password.");
		}
	});

});