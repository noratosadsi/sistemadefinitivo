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

<body class="bg-light">
	<main role="main" class="container">
		<div class="my-3 p-3 bg-white rounded shadow-sm ">
			<div class="py-5 text-center">
					<?php if (isset($_REQUEST['dato1'])) {echo "<td colspan='6' align='center'>
					<?php include_once 'vista/header.php';?>
					<div class='alert alert-info'>" . "REGISTRADO CORRECTAMENTE" . "</div>";}
					if (isset($_REQUEST['dato'])) {echo "<td colspan='6' align='center'>
					<div class='alert alert-danger'>" . "Número de cédula ya se encuentra registrado" . "</div>";
					}?>
					<?php
						$cedula = $_POST['cedulaactualizar'];
						$lugar = $_POST['lugar'];
						include 'modelo/config.php';
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
							return $consulta;
						};
						$reg = consulta($mysql)->fetch_array();
						$costo = $mysql->query("select * from costo where vehiculo='$reg[tipo]'")
						or die($mysql->error);
						$cos = $costo->fetch_array();
						if ($reg > 0) {
							$con = consulta($mysql)->fetch_array();

							$cedula = $reg["vehiculo_cliente_cedula"];
							$horaing = $con['horaingreso'];
							$horaingreso = date("d/m/Y H:i:s", strtotime($horaing));
							$horasalida = date('d/m/Y H:i:s');

							$readonly = "readonly";

							$estilo = "style='background-color: #E9E9E9'";

							$required = "required";
						
							if ($con["precio"] == $cos["pmin"]) {

								$precio = $cos['pmin'] / 60;

							}
							;
							if ($con["precio"] == $cos["phoras"]) {

								$hora = $cos['phoras'] / 60;

								$precio = $hora / 60;
							}
							;
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
							$mysql->query("update detallefactura inner join factura on detallefactura.factura_idFactura=factura.idFactura set fechafactura=now(), horasalida=now(), duracion=timediff(horasalida,horaingreso), total=time_to_sec(duracion)*$precio, iva=total*0.19 where idFactura=$con[idFactura];");

							if ($mysql->error) {
								die("errorrrrr  " . $con["precio"] . "| no aparece el precio");
							}
							consulta($mysql);
							$act = consulta($mysql)->fetch_array();
							$total = $act["total"] + $act["iva"];
							$deshabilitar = "disabled";
							/*echo $horasalida,"esta";*/
						} else {
							$consulta2 = $mysql->query("select * from vistaparqueados where cedula='$cedula' and numero is null;")
							or die($mysql->error);
							$reg1 = $consulta2->fetch_array();

							if (isset($reg1["cedula"])) {
								$cedula = $reg1["cedula"];
							} else {
								$cedula = $_POST["cedulaactualizar"];

							}
							;
							$disabled = "disabled";
							$consulta1 = $mysql->query("select * from vistaparqueados where cedula='$cedula' and numero is null;")
							or die($mysql->error);
							$con = $consulta1->fetch_array();
							$horaingreso = date('d/m/Y H:i:s');
						}
						//cupos
						include "modelo/cupos.php";
						?>
									<h1 class="display-4"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">NORATO'S PARKING</font></font></h1>
									<p class="lead text-right"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">FECHA Y HORA</font></font></p>
								</div>

								<div class="my-3 p-3 bg-white rounded shadow-sm">
									<div class="my-3 p-3 bg-white rounded shadow-sm">
										<form class="needs-validation" novalidate>
											<div class="row">
												<div class="col-md-4 mb-4">
													<label for="formGroupExampleInput">Cedula</label>
													<div class="col-md-7 mb-6">
														<input type="text" name="cedulacliente" class="form-control" value="<?php echo $cedula; ?>"  id="formGroupExampleInput" placeholder="Cedula">
													</div>
												</div>
												<div  class="col-md-4 mb-4">
													<label>Placa o Matricula</label>
													<div class="col-md-6 mb-5">
														<input type="text" name="matricula" class="form-control"  placeholder="Placa o Matricula" value="<?php echo $con['matricula']; ?>" <?php echo $estilo, $readonly; ?>>
													</div>
												</div>
												<div class="col-md-4 mb-4">
													<h4 class="d-flex justify-content-between align-items-center mb-3">
													<span class="text-muted">Fecha y hora de Ingreso</span>
													<div class="col-md-8 mb-8">
														<span class="badge badge-secondary badge-pill"><label> "<?php echo $horaingreso; ?>"</label></span>
													</div>
													</h4>
												</div>

												<div  class="col-md-4 mb-4">
													<label for="formGroupExampleInput">Nombres</label>
													<div class="col-md-7 mb-7">
														<input type="text" name="nombre" class="form-control"  placeholder="Nombres" value="<?php echo $con['nombre']; ?>" <?php echo $estilo, $readonly; ?>>
													</div>
												</div>
												<div class="col-md-4 mb-4">
													<label>Marca</label>
													<div class="col-md-6 mb-6">
														<input type="text" name="marca"  class="form-control"  placeholder="Marca" value="<?php echo $con['marca']; ?>" <?php echo $estilo, $readonly; ?>>
													</div>
												</div>
												<div class="col-md-4 mb-4">
													<h4 class="d-flex justify-content-between align-items-center mb-3">
														<span class="text-muted">Fecha y hora de salida</span>
													<div class="col-md-8 mb-8">
														<span class="badge badge-secondary badge-pill"><label  value="<?php echo $horasalida; ?>" ></label></span>
													</div>
													</h4>
												</div>

												<div  class="col-md-4 mb-4">
													<label>Apellidos</label>
													<div class="col-md-7 mb-6">
														<input type="text" name="apellido"  class="form-control"  placeholder="Apellidos" value="<?php echo $con['apellido']; ?>" <?php echo $estilo, $readonly; ?>>
													</div>
												</div>
												<div class="col-md-4 mb-4">
													<label>Modelo</label>
													<div class="col-md-6 mb-6">
														<input type="text" name="modelo" class="form-control"  placeholder="Modelo" value="<?php echo $con['modelo']; ?>" <?php echo $estilo, $readonly; ?>>
													</div>
												</div>
												<div class="col-md-4 mb-4">
													<h4 class="d-flex justify-content-between align-items-center mb-3">
													<span class="text-muted">Tiempo de permanencia</span>
													<div class="col-md-8 mb-8">
														<span class="badge badge-secondary badge-pill"><label value=" <?php echo $act["duracion"]; ?>"></label></span>
													</div>
													</h4>
												</div>

												<div  class="col-md-4 mb-4">
													<label>Telefono 1</label>
													<div class="col-md-7 mb-6">
														<input type="text" name="telefono1" class="form-control"  placeholder="Telefono 1" value="<?php echo $con['telefono1']; ?>" <?php echo $estilo, $readonly; ?>>
													</div>
												</div>
												<div class="col-md-4 mb-4">
													<label>Tipo</label>
													<div class="col-md-7 mb-6">
													<!--CAMPO TIPO VEHICULO SELECCIONADO-->
														<select class="custom-select d-block w-100" onchange="myFunction()"  id="vehiculo" name="tipo">
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
								echo "<option  value=\"moto\">moto</option>";
							}
							if ($con['vehiculo'] == "bicicleta") {
								echo "<option  value=\"bicicleta\">bicicleta</option>";
							}
						}
						?>
									</select>
								</div>
							</div>
							<div class="col-md-4 mb-4">
								<h4 class="d-flex justify-content-between align-items-center mb-3">
								<span class="text-muted">Precio</span>
								<div class="col-md-12 mb-12">
									<span class="badge badge-secondary badge-pill"><label> "<?php echo "$" . $act["total"]; ?>"</label></label></span>
								</div>
								</h4>
							</div>

							<div  class="col-md-4 mb-4">
								<label>Telefono 2</label>
								<div class="col-md-7 mb-6">
									<input type="text" name="telefono2"  class="form-control"  placeholder="Telefono 2" value="<?php echo $con['telefono2']; ?>" <?php echo $estilo, $readonly; ?>>
								</div>
							</div>
							<div class="col-md-4 mb-4">
								<label>Descripcion / Observacion</label>
								<div class="col-md-8 mb-8">
									<input type="text" name="descripcion" class="form-control"  placeholder="Descripcion / Observacion" value="<?php echo $con['descripcion']; ?>" <?php echo $estilo, $readonly; ?>>
								</div>
							</div>
							<div class="col-md-4 mb-4">
								<h4 class="d-flex justify-content-between align-items-center mb-3">
								<span class="text-muted">IVA</span>
								<div class="col-md-12 mb-12">
									<span class="badge badge-secondary badge-pill">	<label> <?php echo "$" . $act["iva"]; ?> </label></span>
								</div>
								</h4>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="my-3 p-3 bg-white rounded shadow-sm">
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<h2 align="center">ESTACIONAMIENTOS</h2>
					</tr>
					<tr class="col-md-12 mb-12">
						<h2 class="col-md-6 mb-6"  align="center">MOTOS</h2>
						<h2 class="col-md-6 mb-6" align="center">BICICLETAS</h2>
					</tr>
				</thead>
				<tbody>
					<td colspan="5">
						<?php
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

												<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
						echo "</table>";
						?>
					</td>
				<tbody>
			</table >
			<section class="jumbotron text-center">
				<div class="container">
					<h6 class="jumbotron-heading">ESTACIONAMIENTO ASIGNADO</h6>
					<p>
					<span class="badge badge-secondary badge-pill align-items-center mb-3"><label type='text' name='lugar' class='inputcentrado' size='5' value="<?php if (isset($con['numero'])) {echo ($con['numero']);} else {echo "no estacionado";}?>" id="id" onkeypress="return false;" readonly></label></span>
					</p>
				</div>
			</section>
		</div>
		
		<div class="my-3 p-3 bg-white rounded shadow-sm">				
			<h3 class="my-0">TARIFAS</h3>
			<small class="text-muted">Valor por tiempo prestado</small>
			<div class="d-block my-3 col-md-2 mb-2">
				<div class="custom-control custom-radio">
					<input type="radio" id="r1" onclick="myFunction()" name="tarifa" value="minutos" <?php if (isset($con["precio"])){if($con["precio"]==$cos["pmin"]){ echo "checked";}else  {echo $deshabilitar;}} ?>>
					<label>Minutos</label>
				</div>
				<div class="custom-control custom-radio">
					<input type="radio" id="r3" onclick="myFunction(this)" name="tarifa" value="horas" <?php if (isset($con["precio"])) {if ($con["precio"] == $cos["phoras"]) {echo "checked";} else {echo $deshabilitar;}}?>/>
					<label>Horas</label>
				</div>
				<div class="custom-control custom-radio">
					<input type="radio" id="r3" onclick="myFunction()" name="tarifa" value="dias" <?php if (isset($con["precio"])) {if ($con["precio"] == $cos["pdias"]) {echo "checked";} else {echo $deshabilitar;}}?>/>
					<label>Dias</label>
				</div>
				<div class="custom-control custom-radio">
					<input type="radio" id="r4" onclick="myFunction()" name="tarifa" value="mes" <?php if (isset($con["precio"])) {if ($con["precio"] == $cos["pmensual"]) {echo "checked";} else {echo $deshabilitar;}}?>/>
					<label>Mensualidad</label>
				</div>
			</div>

			<div class="row col-md-6 mb-6">
				<div class="col-md-12 mb-12 ">
					<div class="col-md-4 mb-4 ">
						<label for="cc-expiration">TIPO</label>
					</div>
					<div class="col-md-4 mb-4 ">
						MOTOS
					</div>
					<div class=" col-md-4 mb-4">
						BICICLETAS
					</div>
				</div>
				<div class="col-md-12 mb-12 ">
					<div class="col-md-4 mb-4 ">
						<label for="cc-cvv">Capacidad</label>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" style="text-align:center" name="capacidadmotos" size="10" value="<?php echo $cupm['cantidad']; ?>" disabled>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" style="text-align:center" name="capacidadbici"size="10" value="<?php echo $cupb['cantidad']; ?>" disabled>
					</div>
				</div>
				<div class="col-md-12 mb-12 ">
					<div class="col-md-4 mb-4 ">
						<label for="cc-cvv">Disponibilidad</label>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="disponiblemotos"size="10" disabled value="<?php echo $mdisp ?>" style="text-align:center"/>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="disponiblebici"size="10" disabled value="<?php echo $bdisp ?>" style="text-align:center"/>
					</div>
				</div>
				<div class="col-md-12 mb-12 ">
					<div class="col-md-4 mb-4 ">
						<label for="cc-cvv">Ocupados</label>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="ocupadosmotos"size="10" value="<?php echo $mot["motos"] ?>" style="text-align:center" disabled>|
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="ocupadosbici"size="10" value="<?php echo $bic["bicicletas"] ?>" style="text-align:center" disabled>
					</div>
				</div>
			</div>

			<div class="row col-md-4 mb-4">
				<div class="col-md-12 mb-12 ">
					<div class="col-md-8 mb-8 ">
						<h5>Costo segun tarifa</h5>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="costotarif" value="<?php if (isset($act["precio"])) {echo $act["precio"];}?>" style="text-align:center;background-color: #E9E9E9" readonly>
					</div>
				</div>
				<div class="col-md-12 mb-12 ">
					<div class="col-md-8 mb-8 ">
						<h5>Valor a pagar</h5>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="apagar" value="<?php echo $total ?>" readonly style="text-align:center;background-color: #E9E9E9" />
					</div>
				</div>
				<div class="col-md-12 mb-12 ">
					<div class="col-md-8 mb-8 ">
						<h5>Efectivo</h5>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="efectivo" <?php echo $required, $disabled; ?>/>
					</div>
				</div>
				<div class="col-md-12 mb-12 ">
					<div class="col-md-8 mb-8 ">
						<h5>Cambio</h5>
					</div>
					<div class="col-md-4 mb-4 ">
						<input type="text" name="cambio" style="text-align:center;background-color: #E9E9E9" readonly>
					</div>
				</div>
			</div>
		</div>	
			
		<div class="my-3 p-3 bg-white rounded shadow-sm">		
				<button type="submit" class="btn btn-success col-md-3 mb-3  " value="INGRESAR" name="ingresar" onclick="this.form.action='modelo/bd.php'" <?php echo $deshabilitar; ?>>Ingresar</button>
				<button type="submit" class="btn btn-success col-md-3 mb-3  btn-md" value="REGISTRAR SALIDA" onclick="this.form.action='vista/salida.php'" <?php echo $disabled; ?>>REGISTRAR SALIDA</button>
				<button type="submit" class="btn btn-success col-md-3 mb-3  " href="vista/listado.php"   <?php echo $deshabilitar; ?>>Pasrqueaderos</button>
				<button type="submit" class="btn btn-success col-md-3 mb-3  " href="vista/listadocobrados.php">Cobrados</button>
		</div>	
	</main>
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