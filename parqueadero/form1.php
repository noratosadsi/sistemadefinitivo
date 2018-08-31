<?php include 'controlador/control.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>NORATOS PARKING</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php date_default_timezone_set("America/Bogota")?>
<link href="vista/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="vista/bootstrap/js/bootstrap.min.js"></script>

<body background="vista/imagenes/tecnol.jpg">
	<div class="page-container">
		<div class="page-content" >
			<div class="panel-body">
				<div class="page-content-wrap">
					<div class="row edit-inline-form">
						<?php include_once 'vista/header.php';?>
						<div class="panel panel-default">
							<form name="form1" action="" method="post">
								<div class="panel-heading">
									<table border="0" align="center">
										<tr>
											<?php if (isset($_REQUEST['dato1'])) {echo "<td colspan='6' align='center'>
												<div class='alert alert-info'>" . "REGISTRADO CORRECTAMENTE" . "</div>";}
if (isset($_REQUEST['dato'])) {echo "<td colspan='6' align='center'>
												<div class='alert alert-danger'>" . "Número de cédula ya se encuentra registrado" . "</div>";
}?>
										<tr>
		|								<td align="center" style="width:70%" bgcolor="white">
										<h2 align="center">NORATO'S PARKING</h2>
										</td>
										<td align="center" bgcolor="white" valign="center" style="width:10%; font-size:20px">

										<td align="center" bgcolor="white">
										<div id="reloj" style="font-size:20px"><td>FECHA Y HORA</td></div>
										</td>
										</tr>
									</table>
								</div>
							<?php
$cedula = $_POST['cedulaactualizar'];
$lugar = $_POST['lugar'];
include 'modelo/config.php';
//consulta si el vehículo está estacionado por el número de cédula

function consulta($mysql)
{
    if (isset($_POST["lugar"])) {

        $vehiculo = "moto";

    }
    ;

    if (isset($_POST["posicion2"])) {
        $vehiculo = "bicicleta";
    }
    ;

    $cedula = $_POST['cedulaactualizar'];
    $lugar = $_POST['lugar'];

    $consulta = $mysql->query("select * from vistaparqueados where numero is not null and cedula='$cedula' or vehiculo ='$vehiculo' and numero='$lugar';")
    or die($mysql->error . "sadfasdhfhasdhfh 123");

    /*   $tipo="tipo.tipo";

    $consulta=$mysql->query("select * from estacionamiento inner join factura
    on estacionamiento.id=factura.estacionamiento_id inner join vehiculo
    on factura.vehiculo_cliente_cedula=vehiculo.cliente_cedula
    inner join tipo on vehiculo.cliente_cedula=tipo.vehiculo_cliente_cedula
    inner join detallefactura on factura.idFactura=detallefactura.factura_idFactura
    inner join cliente on vehiculo.cliente_cedula=cliente.cedula
    where vehiculo.cliente_cedula='$cedula' and estacionamiento.vehiculo='$tipo'")
    or die ($mysql->error."sadfasdhfhasdhfh 123 $cedula $lugar");
     */
    return $consulta;
};
$reg = consulta($mysql)->fetch_array();
/*

$consulta=$mysql->query("select * from parqueados
where cedulaclie='$cedula' or estacionamiento='$lugar'")
or die ($mysql->error);
$reg=$consulta->fetch_array();

$consulta2=$mysql->query("select * from historicofacturado
where cedulaclie='$cedula'")
or die ($mysql->error);
$reg1=$consulta2->fetch_array();*/

//consulta el costo seleccionado
$costo = $mysql->query("select * from costo where vehiculo='$reg[tipo]'")
or die($mysql->error);
$cos = $costo->fetch_array();
//comprueba si el vehículo está estacionado para registrar su salida

if ($reg > 0) {

    /*    $consulta=$mysql->query("select * from estacionamiento
    inner join factura
    on estacionamiento.id=factura.estacionamiento_id
    inner join vehiculo
    on factura.vehiculo_cliente_cedula=vehiculo.cliente_cedula
    inner join tipo
    on vehiculo.cliente_cedula=tipo.vehiculo_cliente_cedula
    where factura.vehiculo_cliente_cedula=$cedula or estacionamiento.numero=$lugar")
    or die ($mysql->error." aqui esta el error: Fallo al accesar al sistema  ".$mysql->errno);

    $consulta=$mysql->query("select * from parqueados where cedulaclie='$cedula' or estacionamiento='$lugar'")
    or die ($mysql->error);

    $con=$consulta->fetch_array();*/

    //guarda la consulta en la variable $con para realizar la salida del vehículo

    $con = consulta($mysql)->fetch_array();

    $cedula = $reg["vehiculo_cliente_cedula"];
    $horaing = $con['horaingreso'];
    $horaingreso = date("d/m/Y H:i:s", strtotime($horaing));
    $horasalida = date('d/m/Y H:i:s');

    //deshabilita si el vehiculo se encuentra parqueado
    $readonly = "readonly";

    //cambia el estilo de la caja de caja de texto a gris

    $estilo = "style='background-color: #E9E9E9'";

    //required para que pida el efectivo

    $required = "required";

    //calcular el precio dependiendo si es por minutos, dias, horas o mes

    //calcular por minutos
    if ($con["precio"] == $cos["pmin"]) {

        $precio = $cos['pmin'] / 60;

    }
    ;
    //calcular por horas
    if ($con["precio"] == $cos["phoras"]) {

        $hora = $cos['phoras'] / 60;

        $precio = $hora / 60;
    }
    ;
    //calcular por dias
    if ($con["precio"] == $cos["pdias"]) {

        $dia = $cos['pdias'] / 1440;
        $precio = $dia / 60;

    }
    ;
    //calcular po mes
    if ($con["precio"] == $cos["pmensual"]) {

        $mes = $cos['pmensual'] / 43800;
        $precio = $mes / 60;

    }
    ;
    //actualiza los datos registrando la salida del vehículo
    /*

    $mysql->query("update parqueados
    set horasalida=now(),
    duracion=timediff(horasalida,horaingreso),
    total=time_to_sec(duracion)*$precio,
    iva=total*0.19
    where cedulaclie=$cedula;");
    if ($mysql->error)
    die (header ("Location: index.php?error=no se pudo actualizar la tabla parqueados"));

     */
    $mysql->query("update detallefactura inner join factura on detallefactura.factura_idFactura=factura.idFactura set fechafactura=now(), horasalida=now(), duracion=timediff(horasalida,horaingreso), total=time_to_sec(duracion)*$precio, iva=total*0.19 where idFactura=$con[idFactura];");

    if ($mysql->error) {
        die("errorrrrr  " . $con["precio"] . "| no aparece el precio");
    }

    //die (header ("Location: index.php?error=no se pudo actualizar la tabla parqueados"));

    /*
    $actualizar=$mysql->query("select * from parqueados where cedulaclie='$cedula'")
    or die ($mysql->error);
    $act=$actualizar->fetch_array();*/

    //suma el precio con iva

    //$total=$act["total"]+$act["iva"];

    /*  $actualizar=$mysql->query("select * from estacionamiento inner join factura
    on estacionamiento.id=factura.estacionamiento_id inner join vehiculo
    on factura.vehiculo_cliente_cedula=vehiculo.cliente_cedula
    inner join tipo on vehiculo.cliente_cedula=tipo.vehiculo_cliente_cedula
    inner join detallefactura on factura.idFactura=detallefactura.factura_idFactura
    where vehiculo.cliente_cedula=$cedula")
    or die ($mysql->error."sadfasdhfhasdhfh 12345 $cedula $lugar");  */

    consulta($mysql);
    $act = consulta($mysql)->fetch_array();
    $total = $act["total"] + $act["iva"];

    //deshabilita el botón de ingresar vehiculo al parqueadero
    $deshabilitar = "disabled";

    // $act=$actualizar->fetch_array();

    //$total=$act["total"];

    /*echo $horasalida,"esta";*/
} else {

    /* $consulta2=$mysql->query("select * from historicofacturado
    where cedulaclie='$cedula'")
    or die ($mysql->error); */

    /*     if (isset($reg1["cedulaclie"])){
    $cedula=$reg1["cedulaclie"];
    }
    else {
    $cedula=$_POST["cedulaactualizar"];
    echo '<script language="javascript">alert("Usuario NO existe");</script>';
    };
     */

    $consulta2 = $mysql->query("select * from vistaparqueados where cedula='$cedula' and numero is null;")
    or die($mysql->error);
    $reg1 = $consulta2->fetch_array();

    if (isset($reg1["cedula"])) {
        $cedula = $reg1["cedula"];
    } else {
        $cedula = $_POST["cedulaactualizar"];

    }
    ;

    //deshabilita la opcion de ingresar efectivo antes de ingresar el vehiculo
    $disabled = "disabled";

    /* $consulta1=$mysql->query("select * from historicofacturado where cedulaclie='$cedula'")
    or die ($mysql->error);
    $con=$consulta1->fetch_array();
    $horaingreso =date('d/m/Y H:i:s'); */
    /*$horasalida ="NO PARQUEADO";*/

    $consulta1 = $mysql->query("select * from vistaparqueados where cedula='$cedula' and numero is null;")
    or die($mysql->error);
    $con = $consulta1->fetch_array();
    $horaingreso = date('d/m/Y H:i:s');

}
//cupos
include "modelo/cupos.php";
?>
							<table border="0" width="95%" class="alert alert-primary" align="center" Style="font-family: Arial; font-size: 10pt;">
								<form class="form-inline  col-xs-12 col-md-12">
									<div class="form-group col-xs-2 col-md-2">
										<label for="formGroupExampleInput">Cedula</label>
										<input type="text" name="cedulacliente" class="form-control" value="<?php echo $cedula; ?>"  id="formGroupExampleInput" placeholder="Cedula">
									</div>

									<div class="form-group col-xs-2 col-md-2">
										<label for="formGroupExampleInput">Nombres</label>
										<input type="text" name="nombre" value="<?php echo $con['nombre']; ?>" <?php echo $estilo, $readonly; ?>>
									</div>

								<div class="form-group col-xs-2 col-md-2">
										<label>Apellidos</label>
										<input type="text" name="apellido" value="<?php echo $con['apellido']; ?>" <?php echo $estilo, $readonly; ?>>
								</div

								<div class="form-group col-xs-2 col-md-2">
									<label>Telefono 1</label>
									<input type="text" name="telefono1" value="<?php echo $con['telefono1']; ?>" <?php echo $estilo, $readonly; ?>>
								</div>

								<div class="form-group col-xs-2 col-md-2">
									<label>Telefono 2</label>
									<input type="text" name="telefono2" value="<?php echo $con['telefono2']; ?>" <?php echo $estilo, $readonly; ?>>
								</div>


									<!-- <button onclick="window.location.href='index.php'" type="button" class="btn btn-primary btn-sm" disabled> -->
									<!-- <span class="glyphicon glyphicon-refresh"></span>&nbsp;Validar</button></td> -->
									

									<div class="form-group col-xs-2 col-md-2">
										<label>Fecha y hora de Ingreso</label>
										<label> "<?php echo $horaingreso; ?>"</label>
									</div>
								</form>


							<form class="form-inline col-xs-12 col-md-12">



							<div class="form-group col-xs-2 col-md-2">
										<label>Placa o Matricula</label>
										<input type="text" name="matricula" value="<?php echo $con['matricula']; ?>" <?php echo $estilo, $readonly; ?>>
									</div>

	<div class="form-group col-xs-2 col-md-2">
										<label>Marca</label>
											<input type="text" name="marca" value="<?php echo $con['marca']; ?>" <?php echo $estilo, $readonly; ?>>
									</div>



								<div class="form-group col-xs-2 col-md-2">
										<label for="formGroupExampleInput">Nombres</label>
										<input type="text" name="nombre" value="<?php echo $con['nombre']; ?>" <?php echo $estilo, $readonly; ?>>
									</div>
									<!-- <button onclick="window.location.href='index.php'" type="button" class="btn btn-primary btn-sm" disabled> -->
									<!-- <span class="glyphicon glyphicon-refresh"></span>&nbsp;Validar</button></td> -->
									<div class="form-group col-xs-2 col-md-2">
										<label>Marca</label>
											<input type="text" name="marca" value="<?php echo $con['marca']; ?>" <?php echo $estilo, $readonly; ?>>
									</div>

									<div class="form-group col-xs-2 col-md-2">
										<label>Fecha y hora de salida</label>
										<label type="datetime" value="<?php echo $horasalida; ?>" name="fecha_hora" readonly></label>
									</div>
								</form>

							<form class="form-inline col-xs-12 col-md-12">
								<div class="form-group col-xs-2 col-md-2">
										<label>Apellidos</label>
										<input type="text" name="apellido" value="<?php echo $con['apellido']; ?>" <?php echo $estilo, $readonly; ?>>
								</div

								<div class="form-group col-xs-2 col-md-2">
									<label>Modelo</label>
									<input type="text" name="modelo" value="<?php echo $con['modelo']; ?>" <?php echo $estilo, $readonly; ?>>
								</div>





								<div class="form-inline col-xs-2 col-md-2">
									<label>Tiempo de permanencia</label>
									<label><?php echo $act["duracion"]; ?>></label>
								</div>
								</form>



								<form class="form-inline col-xs-2 col-md-2">




								<div class="form-group col-xs-2 col-md-2">
									<label class="my-1 mr-2">Tipo</label>
									<input type="hidden" id="veh" name="vehiculo1">
									<!--CAMPO TIPO VEHICULO SELECCIONADO-->
									<select class="custom-select my-1 mr-sm-2"onchange="myFunction()"  id="vehiculo" name="tipo">
									<?php

//muestra ambos vehículos para seleccionar en caso de que el vehículo ingrese al parqueadero

if (!isset($con["numero"])) {
    echo "<option selected value='moto'>motititio</option>";
    echo "<option selected value=\"motos\">moto</option>";
    echo "<option selected value=\"bicicleta\">bicicleta</option>";
}
//muestra el vehículo parqueado sin opción de seleccionar otro vehículo

if (isset($con["numero"])) {
    if ($con['vehiculo'] == "moto") {
        echo "<option selected value=\"moto\">moto</option>";
    }
    if ($con['vehiculo'] == "bicicleta") {
        echo "<option selected value=\"bicicleta\">bicicleta</option>";
    }
}
?>
									</select>
								</div>


								<div class="form-group col-xs-2 col-md-2">
									<label>Precio 1</label>
									<label> "<?php echo "$" . $act["total"]; ?>"</label>
								</div>
									<!-- <td>Precio</td>
									<input type="text" name="precio" value="<?php echo "$" . $act["total"]; ?>" readonly> -->
							</form>



								

								<div class="form-group col-xs-2 col-md-2">
									<label>Descripcion / Observacion</label>
									<input type="text" name="descripcion" value="<?php echo $con['descripcion']; ?>" <?php echo $estilo, $readonly; ?>>
								</div>


								<div class="form-group col-xs-2 col-md-2">
									<label>IVA</label>
									<label> <?php echo "$" . $act["iva"]; ?>" </label>
								</div>
								</form>







							<table border="0" align="center" class="alert alert-default">
								<tr>
									<td colspan="10" align="center" class="panel panel-default">ESTACIONAMIENTOS</td>
								</tr>
								<tr>
									<td colspan="5" align="center" class="panel panel-default">MOTOS</td>
									&nbsp;&nbsp;
									<td colspan="5" align="center" class="panel panel-default">BICICLETAS</td>
								</tr>
								<tr>
									<td colspan="5">
									<?php

//consulta si está estacionado

/*  $estacionado=$mysql->query("select * from parqueados where cedulaclie=$cedula")
or die ($mysql->error); */

$estacionado = consulta($mysql);

$m = $cupm["cantidad"]; //Cantidad de parqueaderos disponibles para motos

$x = 0;
echo "<table border='0' Style='font-family: Arial; font-size: 9pt; color:black'>";
echo "<tr align='center'>";

for ($i = 1; $i <= $m; $i++) {
    $x = $x + 1;

    if ($verificar = $estacionado->num_rows) {
        $usar = "";
    } else {
        $usar = "onclick='sub($i);change(this);'";
    }

    if (in_array($i, $estmoto)) {
        echo "<td align='center' style='width:30px' bgcolor='gray' name='posicion'>";
        echo "$i </td>";
    } else {
        echo "<td align='center' style='width:30px'>";
        echo "<input type='button' name='posicion' $usar style='width:30px' value='$i' $desmoto></td>";
    }

    if ($x == 15) {
        echo "</tr>";
        echo "<tr align='center'>";
        $x = 0;
    }

}
echo "</table>";
?>
										</td>

									&nbsp;&nbsp;
									<td>
									<?php
$b = $cupb["cantidad"]; //Cantidad de parqueaderos disponibles para bicicletas

$x = 0;
echo "<table border='0'  Style='font-family: Arial; font-size: 9pt; color:black'>";
echo "<tr align='center'>";

for ($i = 1; $i <= $b; $i++) {
    $x = $x + 1;

    if ($verificar = $estacionado->num_rows) {
        $usar = "";
    } else {
        $usar = "onclick='subb($i);change(this);'";
    }

    if (in_array($i, $estbicicleta)) {
        echo "<td style='width:30px' bgcolor='gray' name='posicionb'>";
        echo "$i</td>";
    } else {
        echo "<td style='width:30px'>";
        echo "<input type='button' name='posicionb' $usar style='width:30px' value='$i'><!--$i--></td>";
    }

    if ($x == 15) {
        echo "</tr>";
        echo "<tr align='center'>";
        $x = 0;
    }

}
echo "</table";
?>
								</td>
							</tr>
							<tr>
								<td colspan="12" align="center" class="alert alert-info">
								ESTACIONAMIENTO ASIGNADO
								<input type='text' name='lugar' class='inputcentrado' size='5' value="<?php if (isset($con['numero'])) {echo ($con['numero']);} else {echo "no estacionado";}?>" id="id" onkeypress="return false;" readonly>

								</td>
							</tr>

						</table>
						<table border="0" width="90%" class="alert alert-primary" align="center" Style="font-family: Arial; font-size: 10pt;">
							<tr>
								<td colspan="2" align="center">TARIFAS</td>
								<td></td>
								<td align="center"><strong>TIPO</strong></td>
								<td align="center"><strong>MOTOS</strong></td>
								<td align="center"><strong>BICICLETAS</strong></td>
							</tr>
							<tr>

							<!--CAMPO TARIFA SELECCIONADA-->
								<input type="hidden" id="tar" name="tarifa1" >
								<td>Minutos</td>
								<td>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
									<label class="custom-control-label" for="customRadioInline1">Toggle this custom radio</label>
								</div>






								<div class="custom-control custom-radio">
									<input  type="radio" id="r1" onclick="myFunction()" name="tarifa" value="minutos" <?php if (isset($con["precio"])) {if ($con["precio"] == $cos["pmin"]) {echo "checked";} else {echo $deshabilitar;}}?>></td><!--checked="checked"-->
								</div>
								<td></td>
								<td>Capacidad</td>
								<td><input type="text" style="text-align:center" name="capacidadmotos" size="10" value="<?php echo $cupm['cantidad']; ?>" disabled></td>
								<td><input type="text" style="text-align:center" name="capacidadbici"size="10" value="<?php echo $cupb['cantidad']; ?>" disabled></td>


								<td>Costo segun tarifario</td>
								<td><input type="text" name="costotarif" value="<?php if (isset($act["precio"])) {echo $act["precio"];}?>" style="text-align:center;background-color: #E9E9E9" readonly></td>
								<!--<td><input type="text" name="costotarif" value="<?/*php echo $costo*/?>" style="text-align:center" readonly></td>-->
							</tr>
							<tr>
								<td>Horas</td>
								<td><input type="radio" id="r2" onclick="myFunction(this)" name="tarifa" value="horas" <?php if (isset($con["precio"])) {if ($con["precio"] == $cos["phoras"]) {echo "checked";} else {echo $deshabilitar;}}?>></td>
								<td></td>
								<td>Ocupados</td>
								<td><input type="text" name="ocupadosmotos"size="10" value="<?php echo $mot["motos"] ?>" style="text-align:center" disabled></td>
								<td><input type="text" name="ocupadosbici"size="10" value="<?php echo $bic["bicicletas"] ?>" style="text-align:center" disabled></td>
								<td>Valor a pagar</td>
								<td><input type="text" name="apagar" value="<?php echo $total ?>" readonly style="text-align:center;background-color: #E9E9E9" /></td>
							</tr>
							<tr>
								<td>Dias</td>
								<td><input type="radio" id="r3" onclick="myFunction()" name="tarifa" value="dias" <?php if (isset($con["precio"])) {if ($con["precio"] == $cos["pdias"]) {echo "checked";} else {echo $deshabilitar;}}?>></td>
								<td></td>
								<td>Disponible</td>
								<td><input type="text" name="disponiblemotos"size="10" disabled value="<?php echo $mdisp ?>" style="text-align:center"></td>
								<td><input type="text" name="disponiblebici"size="10" disabled value="<?php echo $bdisp ?>" style="text-align:center"></td>
								<td>Efectivo</td>
								<td><input type="text" name="efectivo" <?php echo $required, $disabled; ?>></td>
							</tr>
							<tr>
								<td>Mensualidad</td>
								<td><input type="radio" id="r4" onclick="myFunction()" name="tarifa" value="mes" <?php if (isset($con["precio"])) {if ($con["precio"] == $cos["pmensual"]) {echo "checked";} else {echo $deshabilitar;}}?>></td>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<td colspan="3"></td>
								<td>Cambio</td>
								<td><input type="text" name="cambio" style="text-align:center;background-color: #E9E9E9" readonly></td>
							</tr>
						</table>
							<table border="0" width="60%" align="center">
								<tr>
								<td align="center">

								<input type="submit" class="btn btn-info btn-md" value="INGRESAR" name="ingresar" onclick="this.form.action='modelo/bd.php'" <?php echo $deshabilitar; ?>>
								</td>

								<td align="center">
								<input type="submit" class="btn btn-info btn-md" value="REGISTRAR SALIDA" onclick="this.form.action='vista/salida.php'" <?php echo $disabled; ?>>
								</td>



								<td align="center">
								<a type="button" href="vista/listado.php" target="_blank" class="btn btn-info btn-md">PARQUEADOS</a>
								</td>

								<td align="center">
								<a type="button" href="vista/listadocobrados.php" target="_blank" button class="btn btn-info btn-md">COBRADOS</a>
								</td>
								</tr>
								<br>
							</table>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
	function startTime(){
	today=new Date();
	h=today.getHours();
	m=today.getMinutes();
	s=today.getSeconds();
	m=checkTime(m);
	s=checkTime(s);
	document.getElementById('reloj').innerHTML=h+":"+m+":"+s+"<br>"+fecha;
	t=setTimeout('startTime()',500);}
	function checkTime(i)
	{if (i<10) {i="0" + i;}return i;}
	window.onload=function(){startTime();}

	//<![CDATA[
	var date = new Date();
	var d  = date.getDate();
	var day = (d < 10) ? '0' + d : d;
	var m = date.getMonth() + 1;
	var month = (m < 10) ? '0' + m : m;
	var yy = date.getYear();
	var year = (yy < 1000) ? yy + 1900 : yy;
	var fecha=(day + "/" + month + "/" + year);
	//]]>

	//comprueba si se ha seleccionado moto o bicicleta

	function imprimirValor(){
	var select = document.getElementById("vehiculo");
	//var options=document.getElementsByTagName("option");
	alert(select.value);
	//alert(options[select.value-1].innerHTML);

	if (select.value=="moto")
	{

		var mensaje="<?php $mensaje = "hoja amigou motou";?>";
		alert("<?php echo $mensaje; ?>");
	}

	if (select.value=="bicicleta")
	{
		var mensaje="<?php $mensaje = "hoja como estas bicicleta";?>";
		alert("<?php echo $mensaje; ?>");
	}
	}


	window.onload = function() {
	myFunction();
	};

	function myFunction() {

	//verifica si se ha seleccionado moto o bicicleta
	var select = document.getElementById("vehiculo");
	//var options=document.getElementsByTagName("option");
	// alert(select.value);
	//alert(options[select.value-1].innerHTML);

	if (select.value=="moto")
	{
		var mensaje="<?php $mensaje = "hoja amigou motou";?>";
		var deshabilitar="<?php echo $desmoto = "disabled"; ?>";

		//alert("<?php echo $desmoto; ?>");
	}
	else
	{
		var deshabilitar="<?php $desmoto = "";?>";
		//alert("<?php echo $desmoto . " no hay nada"; ?>");
	}

	if (select.value=="bicicleta")
	{
		var mensaje="<?php $mensaje = "hoja como estas bicicleta";?>";
		//alert("<?php echo $mensaje; ?>");
	}

	//verifica el vehiculo y la tarifa
	if((
	(document.getElementById("r1").checked == true)
	|  (document.getElementById("r2").checked == true)
	|  (document.getElementById("r3").checked == true)
	| (document.getElementById("r4").checked == true)
	) &
	(document.form1.tipo.value != "")
	)
	{



	//Valida radio checkeado
		o = document.forms[0].tarifa;
		for(i=0;i<o.length;i++){
			if(o[i].checked){
				var sel =(o[i].value);
				//alert("Ha seleccionado "+sel);
				//alert("Ha seleccionado "+o[i].value);
	document.getElementsByName("tarifa1")[0].value = sel;
				break;
			}
		}

	//Valida opcion seleccionada
		var x = document.getElementById("vehiculo").selectedIndex;
		//alert(document.getElementsByTagName("option")[x].value);
		vehiculo = (document.getElementsByTagName("option")[x].value);
		//alert (vehiculo);
		document.getElementsByName("vehiculo1")[0].value = vehiculo;

	// Calcula costo
	var veh = document.getElementById('veh').value;
	var tar = document.getElementById('tar').value;

	//Inicio

	//MOTOS
	if ((veh=='moto')&& (tar=='minutos')) {
	var z = "<?php echo $cosminm; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}

	if ((veh=='moto')&& (tar=='horas')) {
	var z = "<?php echo $coshorasm; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}

	if ((veh=='moto')&& (tar=='dias')) {
	var z = "<?php echo $cosdiasm; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}

	if ((veh=='moto')&& (tar=='mes')) {
	var z = "<?php echo $cosmensualm; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}

	//BICICLETAS
	if ((veh=='bicicleta')&& (tar=='minutos')) {
	var z = "<?php echo $cosminb; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}

	if ((veh=='bicicleta')&& (tar=='horas')) {
	var z = "<?php echo $coshorasb; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}

	if ((veh=='bicicleta')&& (tar=='dias')) {
	var z = "<?php echo $cosdiasb; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}

	if ((veh=='bicicleta')&& (tar=='mes')) {
	var z = "<?php echo $cosmensualb; ?>";
	document.getElementsByName("costotarif")[0].value = z;
	}
	//fin
	}

	else{
			if
			((document.getElementById("r1").checked == true)
			|  (document.getElementById("r2").checked == true)
			|  (document.getElementById("r3").checked == true)
			| (document.getElementById("r4").checked == true)
			){
			alert ("Debe seleccionar un tipo de Vehiculo");
			document.getElementById('vehiculo').focus();
			}
			else{
				alert ("Debe seleccionar una Tarifa");
				document.getElementById('r1').focus();
				}
	}

	}
		var color1="";
			var color2="";
			function change (elemento) {
				if(color1=="yellow")
				{
					elemento.style.backgroundColor=color1="#fff";
				}else{
					elemento.style.backgroundColor=color1="gray";
				}
			}
			function change2 (elemento) {
				if(color2=="yellow")
				{
					elemento.style.backgroundColor=color2="#fff";
				}else{
					elemento.style.backgroundColor=color2="yellow";
				}
			}

	//muestra los numeros de estacionamiento de las motos
	function sub(a){
	a=a-1;
	//obtiene el numero de estacionamiento para las motos
	seleccionmoto = document.getElementsByName("posicion")[a].value;
	//muestra el numero de estacionamiento seleccionado para motos
	document.getElementsByName("lugar")[0].value = seleccionmoto;

	/*  alert(+seleccion);*/

	};

	//muestra los numeros de estacionamiento de las bicicletas
	function subb(b){

	b=b-1;

	//obtiene el numero de estacionamiento apra las bicicletas
	seleccionbici = document.getElementsByName("posicionb")[b].value;
	//muestra el numero de estacionamiento seleccionado para bicicletas
	document.getElementsByName("lugar")[0].value = seleccionbici;

	/*  alert(+seleccion);*/

	};
</script>
</body>
</html>