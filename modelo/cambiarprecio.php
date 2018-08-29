 <?php
include "../controlador/control.php";
include "config.php";

//cambiar el costo de la moto por minuto
if (@$_POST["motosmin"])
{
	$mysql->query("update costo set pmin=$_POST[motosmin] where vehiculo='moto'");
	if($mysql->error)
	//die (header ("Location: ../vista/precio.php?error=$error"));
	die (header ("Location: ../vista/precio.php?error=<script>alert('error actualizando costo por minuto en motos')</script>"));
	$error="<script>alert('error actualizando costo por minuto en motos')</script>";

}
//cambiar el costo de la bicicleta por minuto
if (@$_POST["bicicletasmin"])
{
	$mysql->query("update costo set pmin=$_POST[bicicletasmin] where vehiculo='bicicleta'");
	if($mysql->error)
	$error=" error actualizando costo por minuto en bicicletas";
	header ("Location: ../vista/precio.php?error=$error");
}
//cambiar el costo de la moto por hora
if (@$_POST["motoshoras"])
{
	$mysql->query("update costo set phoras=$_POST[motoshoras] where vehiculo='moto'");
	if($mysql->error)
	$error=" error actualizando costo por horas en motos";
	header ("Location: ../vista/precio.php?error=$error");
}
//cambiar el costo de la bicicleta por hora
if (@$_POST["bicicletashoras"])
{
	$mysql->query("update costo set phoras=$_POST[bicicletashoras] where vehiculo='bicicleta'");
	if($mysql->error)
	$error="error actualizando costo por horas en bicicleta";
	header ("Location: ../vista/precio.php?error=$error");

}
//cambiar el costo de la moto por dias
if (@$_POST["motosdias"])
{
	$mysql->query("update costo set pdias=$_POST[motosdias] where vehiculo='moto'");
	if($mysql->error)
	$error=" error actualizando costo por dias en motos";
	header ("Location: ../vista/precio.php?error=$error");
}
//cambiar el costo de la bicicleta por dias
if (@$_POST["bicicletasdias"])
{
	$mysql->query("update costo set pdias=$_POST[bicicletasdias] where vehiculo='bicicleta'");
	if($mysql->error)
	$error="error actualizando costo por dias en bicicleta";
	header ("Location: ../vista/precio.php?error=$error");
}
//cambiar el costo de la moto por mes
if (@$_POST["motosmes"])
{
	$mysql->query("update costo set pmensual=$_POST[motosmes] where vehiculo='moto'");
	if($mysql->error)
	$error="error actualizando costo por mes en motos";
	header ("Location: ../vista/precio.php?error=$error");
}
//cambiar el costo de la bicicleta por mes
if (@$_POST["bicicletasmes"])
{
	$mysql->query("update costo set pmensual=$_POST[bicicletasmes] where vehiculo='bicicleta'");
	if($mysql->error)
	$error="error actualizando costo por mes en bicicletas";
	header ("Location: ../vista/precio.php?error=$error");
}

//redirige a la pagina precio.php
header ("Location: ../vista/precio.php?actualizado");
?>