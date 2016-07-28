<ul>
	<li><span>Hello, <?php echo $login->name; ?>!</span></li>
	<li><a id="change_pass_link" href="#change_pass_dialog">[password]</a>
		<div style="display: none" id="change_pass_dialog">
			<form id="change_pass_form">
				<h2>Change Password</h2>
				<p id="old_pass_inputs">
					<input type="password" name="old_pass" id="old_pass" placeholder="Current Password"/>
				</p>
				<p id="new_pass_inputs">
					<input type="password" name="new_pass1" id="new_pass1" placeholder="New Password"/><br/>
					<input type="password" name="new_pass2" id="new_pass2" placeholder="Confirm Password"/>
				</p>
				<p id="change_pass_actions">
					<input type="submit" value="change"/>
					<div id="change_pass_spinner" style="display:none"></div>
				</p>
			</form>
		</div>
	</li>
	<li><a id="sign-out">[sign out]</a></li>
</ul>