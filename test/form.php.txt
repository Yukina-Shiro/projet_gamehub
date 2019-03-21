<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
?>
<form id="form-1" method="post" data-ajax="false">
<label for="name">Name : <span>*</span></label>
<input type="text" name="name" id="name" placeholder="Name">
<label for="email">Email: <span>*</span></label>
<input type="email" id="email" name="email" placeholder="Email"/>
<fieldset data-role="controlgroup">
<legend>Gender:</legend>
<label for="male">Male</label>
<input type="radio" name="gender" id="male" value="male">
<label for="female">Female</label>
<input type="radio" name="gender" id="female" value="female">
</fieldset>
<fieldset data-role="controlgroup">
<legend>Qualification:</legend>
<label for="graduation">Graduation</label>
<input type="checkbox" id="graduation" value="graduation">
<label for="postgraduation">Post Graduation</label>
<input type="checkbox" id="postgraduation" value="postgraduation">
<label for="other">Other</label>
<input type="checkbox" id="other" value="other">
</fieldset>
<label for="info">Message:</label>
<textarea name="addinfo" id="info" placeholder="Message"></textarea>
<input type="submit" data-inline="true" value="Submit" data-theme="b">
</form>
<?php
var_dump( $_POST);
?>
