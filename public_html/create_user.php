<?php 
require_once('header.php');
setTabTitle("Create New User");
?>

<div style="margin:20px">

<h4>Create New User</h4>
<hr>

<form action="user_added.php" method="post">
  <div class="form-group">
    <label for="NetID">NetID</label>
    <input name="NetID" type="text" class="form-control" id="NetID" placeholder="Enter NetID">
  </div>
  
    <div class="form-group">
    <label for="Password">Password</label>
    <input name="Password" type="password" class="form-control" id="password" placeholder="Password">
  </div>
  
  <div class="form-group">
    <label for="Password1">Confirm Password</label>
    <input name="Password1" type="password" class="form-control" id="password1" placeholder="Password">
  </div>

  <div class="form-group">
    <label for="FirstName">First Name</label>
    <input type="text" class="form-control" name="FirstName" id="FirstName" placeholder="John">
  </div>
  
  
    <div class="form-group">
    <label for="LastName">Last Name</label>
    <input type="text" class="form-control" name="LastName" id="LastName" placeholder="Doe">
  </div>
  
    <div class="form-group">
    <label for="Dorm">Dorm</label>
    <select id="Dorm" class="form-control" name="Dorm">
        <option value="Allen">Allen Hall</option>
        <option value="Busey-Evans">Busey-Evans</option>
        <option value="ISR">ISR (Townsend and Wardall)</option>
        <option value="LAR">LAR (Leonard and Shelden)</option>
        
        <option value="FAR">FAR (Oglesby and Trelease)</option>
        <option value="PAR">PAR (Babcock, Blaisdell, Carr, and Saunders)</option>
        
        <option value="BL">Barton and Lundgren </option>
        <option value="Hopkins">Hopkins</option>
        <option value="Nugent">Nugent</option>
        <option value="Wassaja">Wassaja</option>
        <option value="Weston">Weston</option>
        
        <option value="Bousfield">Bousfield</option>
        <option value="Scott">Scott</option>
        <option value="Snyder">Snyder</option>
        <option value="TVD">Taft–Van Doren</option>
        
        <option value="Other">Other</option>
    </select>
  </div>
  
  <button type="submit" class="btn btn-primary">Create Account</button>
</form>
</div>
<?php
close();
?>