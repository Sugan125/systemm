<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<h2>Employee List</h2>

<table>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Country</th>
	<th>Action</th>
  </tr>
  <?php if(count($users)>0) { 
	  	foreach($users as $user ){
	  ?>
  <tr>
    <td><?=$user->name?></td>
    <td><?=$user->email?></td>
    <td><?=$user->address?></td>
	<td><a href="<?=base_url('index.php/pdf/download/'.$user->id)?>">Download PDF</a></td>
  </tr>
  <?php } 
  }
  ?>

</table>

</body>
</html>
