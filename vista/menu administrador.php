<?php include '../controlador/control.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>NORATOS PARKING</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="vista/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="vista/bootstrap/js/bootstrap.min.js"></script>
</head>
<em><strong>
<body background="imagenes/tecnol.jpg" width="100" height="100"/>
<div class="container">
<header>
<?php include_once 'header.php'; ?>
<div class="col-sm-12 col-md-12"> 
<div class="panel panel-default"> 
<!-- contenedor del titulo--> 
<div class="panel-heading"> 
<h2 align="center">MODIFICACION DE DATOS</h2> 
</div> 
<div class="panel-body"> 
<?php /*include_once 'menu vectores.php'*/?>

<!-- Contenedor ejercicio--> 
<div class="alert alert-success"> 	
<div class="row"> 
<div class="col-sm-12 col-md-12">  


<table class="table">	
<td colspan="2" align="center"><br>
<?php
if ($_SESSION["nivel"]==1)
{
	echo "<button onclick=\"window.location.href='borrarvehiculo.php'\" width=\"500\" style=\"color:#ffffff\" class=\"btn btn-info\">";	
	echo "BORRAR REGISTRO";
}
?>
</td>
<td colspan="2" align="center"><br>
<button onclick="window.location.href='cupos3.php'" width="500" style="color:#ffffff" class="btn btn-info">
ESTACIONAMIENTOS
</td>
<td colspan='2' align='center'><br>
<?php
if ($_SESSION["nivel"]==1)
{
	echo "<button onclick=\"window.location.href='precio.php'\" width=\"500\" style=\"color:#ffffff\" class=\"btn btn-info\">";
	echo "PRECIO";
}
?>
</td>
<td colspan="2" align="center"><br>
<?php
if ($_SESSION["nivel"]==1)
{
	echo "<button onclick=\"window.location.href='registro.php'\" width=\"500\" style=\"color:#ffffff\" class=\"btn btn-info\">";
	echo "REGISTRAR USUARIOS";
}	
?>
</td>
<td colspan= "2" aling= "center"><br>
<button onclick= "window. location. href='cupos2.php'" width="500" style="color:#ffffff" class= "btn btn-info">
ESTACIONAMIENTOS DISPONIBLES

</tr>
</TABLE>
</table> 
</div> 
</div> 
</div> 
</div> 
</div>
</strong></em></header>
</div>
</body>
</html>