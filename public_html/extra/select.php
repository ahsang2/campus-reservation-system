<?php  
require_once('header.php');
 echo "hello world";
 if(isset($_POST["ResID"]))  
 {  
     
     echo "hello world";
     /*
      $output = '';  
      $query = "SELECT * FROM employee WHERE id = '".$_POST["employee_id"]."'";  
      $result = mysqli_query($connect, $query);  
      $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td width="30%"><label>Name</label></td>  
                     <td width="70%">'.$row["name"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Address</label></td>  
                     <td width="70%">'.$row["address"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Gender</label></td>  
                     <td width="70%">'.$row["gender"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Designation</label></td>  
                     <td width="70%">'.$row["designation"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Age</label></td>  
                     <td width="70%">'.$row["age"].' Year</td>  
                </tr>  
                ';  */
     // }  
     // $output .= "</table></div>";  
      //echo $output;  
 }  
 ?>
 <?

close();
?>