<div class="row">
      <h3>Log-in here</h3>
</div>

<div class="row">
      <%loginfailed%>
</div>


<div class="row">
	<form class="form-horizontal" action="content.php" method="post" name="login">
		<input type="hidden" name="action" value="signin" />
		<input type="hidden" name="url" value="<%url%>" />
		<input type="hidden" name="autologin" value="false" />
		<input type="hidden" name="viewonline" vale="false" />
		<input type="hidden" name="sid" value="<%phpsid%>" />

			
		<div class="form-group" >
				<div class="col-sm-2" ></div>
				<label for="loginid" class="col-sm-3 control-label" >Login ID:</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="loginid" tabindex="1"  name="username"/>
				</div>
		</div>


		<div class="form-group" >
				<div class="col-sm-2" ></div>
				<label for="password" class="col-sm-3 control-label">Password:</label>
				<div class="col-sm-3">
					<input type="password" class="form-control" id="password" name="password" tabindex="2" />
				</div>
		</div>

		
		<div class="form-group" >
				<div class="col-sm-2" ></div>
				<div class="col-sm-3 control-label" ></div>
				<div class="col-sm-3">
					<input class="form-control" type="submit" name="submit" id="submit" value="Submit" class="button" tabindex="3" />		
				</div>
		</div>
			
      </form>
</div>
	  
<div class="row">
	  <p><br/><%forgottext%><br/></p>
</div> <!-- row -->

