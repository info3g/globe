

<form id="subscribeForm" name="advancedForm" >
<div class="form-group">
<label for="purchase_subscription">Purchase subscription:</label>
<select name="purchase_subscription" id="purchase_subscription" class="form-control">
<option value="">--Select--</option>
<option value="weekly">Weekly</option>
<option value="monthly">Monthly</option>
<option value="yearly">Yearly</option>			
</select>
</div>

<div id="weekly" class="subscriptiondata" style="display:none"> 
<select class="weekselect">
<option value="1week">1 Week</option>
<option value="2week">2 Week</option>
<option value="3week">3 Week</option>
<option value="4week">4 Week</option>
</select>
</div>
<div id="monthly" class="subscriptiondata" style="display:none"> 
<select class="monthselect">
<option value="1month">1 month</option>
<option value="3week">3 month</option>
<option value="6week">6 month</option>
</select>
</div>
<div id="yearly" class="subscriptiondata" style="display:none">
<select class="yearselect">
<option value="1year">1 year</option>
</select>
</div>
<input type="submit" value="Save" id="insertsubscription" class="btn btn-default" name="insertsubscription" />

</form>

<div style="display:none;" class="alert alert-success">
<p>Saved successfully.</p>
</div>
<div style="display:none;" class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Unable to save. Please try again.</div>
