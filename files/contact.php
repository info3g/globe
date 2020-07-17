<form id="contactUs" name="contactUs">
	<div class="form-group">
		<label for="pixelEditor"><span class="require">*</span>First Name:</label>
		<input type="text" class="form-control" name="firstname" id="firstname" required />
	</div>
	<div class="form-group">
		<label for="pixelEditor"><span class="require">*</span>last Name:</label>
		<input type="text" class="form-control" name="lastname" id="lastname" required />
	</div>
	<div class="form-group">
		<label for="pixelEditor"><span class="require">*</span>Email:</label>
		<input type="email" class="form-control" name="email" id="email" required />
	</div>		<div class="form-group" style="display:none;">		<label for="pixelEditor"><span class="require">*</span>Store Url:</label>		<input type="text" class="form-control" name="store_url" id="store_url" required />	</div>
	<div class="form-group">
		<label for="pixelEditor"><span class="require">*</span>Message/Query:</label>
		<textarea class="form-control" name="query" id="query" required></textarea>
	</div>
	<input type="submit" value="Send" id="sendEmail" class="btn btn-default" name="sendEmail" />
</form>
<div style="display:none;" class="alert alert-success">
	<p>Your mail has been sent successfully.</p>
</div>
<div style="display:none;" class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Unable to send email. Please try again.</div>