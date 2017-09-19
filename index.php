<?php  

//Aqui configuran su base de datos
define("HOST","localhost");
define("USER","clubdelc_android");
define("PASS","12345678");
define("DB","clubdelc_inventario");
//-----------------------------------------------------------------
$carpeta_destino 	= "imagenes/";
$key 				= "";
$tipo_operacion 	= "";
$id 				= "";
$nombre 			= "";
$codigo 			= "";
$nuevo_codigo		= "";
$categoria 			= "";
$descripcion 		= "";
$precio 			= "";
$stock 				= "";
$imagen 			= "";
$where 				= "";
if(isset($_REQUEST['operacion']) && isset($_REQUEST['key']))
{
	$key 			= $_REQUEST['key'];
	$tipo_operacion	= $_REQUEST['operacion'];
	if($key && $key == "c000ccf225950aac2a082a59ac5e57ff")
	{
		if($tipo_operacion)
		{
			$conexion = mysqli_connect(HOST,USER,PASS,DB);
			if($conexion)
			{
				switch ($tipo_operacion) 
				{
					case 'ver':
						if(isset($_REQUEST['where']))
						{
							$where = $_REQUEST['where'];
						}
						$cadenaSQL 	= "SELECT * FROM productos";
						if($where)
						{
							$cadenaSQL .= " WHERE ".$where;
						}	
						$consulta 	= mysqli_query($conexion,$cadenaSQL);
						if($consulta->num_rows > 0)
						{
							while($registro=mysqli_fetch_array($consulta,MYSQLI_ASSOC))
							{
								$rows[]=$registro;
							}
							echo json_encode($rows);
						}
						else
						{
							echo "¡ALERTA!, 0 registros encontrados.";
						}
						break;
					case 'insertar':
						if(isset($_FILES['archivo']))
						{
							if($_FILES['archivo']['error'])
							{
								switch ($_FILES['archivo']['error']) {
									case 1:		echo "¡ERROR!, El tamaño de la imagen supera el limite permitido 2Mb.";break;
									case 2:		echo "¡ERROR!, El tamaño de la imagen supera el limite permitido 2Mb.";break;
									case 3:		echo "¡ERROR!, La transferencia de la imagen se ha interrumpido.";break;
									case 4:		echo "¡ERROR!, El tamaño de la imagen enviado es nulo.";break;
								}
							}
							else
							{
								if(isset($_FILES['archivo']['name']))
								{
									$imagen = generarNombreImagen($_FILES['archivo']['type']);
									if($imagen)
									{
										eliminarImagen($carpeta_destino,$imagen);
										if(move_uploaded_file($_FILES['archivo']['tmp_name'], $carpeta_destino.$imagen))
										{
											if($_REQUEST['nombre'] && $_REQUEST['codigo'] && $_REQUEST['categoria'] && $_REQUEST['descripcion'] && $_REQUEST['precio'] && $_REQUEST['stock'])
											{
												$nombre 		= $_REQUEST['nombre'];
												$codigo 		= $_REQUEST['codigo'];
												$categoria 		= $_REQUEST['categoria'];
												$descripcion 	= $_REQUEST['descripcion'];
												$precio 		= $_REQUEST['precio'];
												$stock 			= $_REQUEST['stock'];
												$cadenaSQL		= "INSERT INTO productos (`nombre`,`codigo`,`categoria`,`descripcion`,`precio`,`stock`,`imagen`) VALUES ('".$nombre."','".$codigo."','".$categoria."','".$descripcion."',".$precio.",".$stock.",'".$imagen."')";
												$consulta = mysqli_query($conexion,$cadenaSQL);
												if($consulta)
												{
													echo "Los datos se grabaron correctamente.";									
												}
												else
												{
													eliminarImagen($carpeta_destino,$imagen);
													echo "¡ERROR!, no se grabaron los datos en la base.";
												}
											}
											else
											{
												eliminarImagen($carpeta_destino,$imagen);
												echo "¡ERROR!, no se puede ingresar datos en blanco a la base.";
											}
										}
										else{echo "¡ERROR!, no se pudo guardar la imagen.";}
									}	
									else{echo "¡ERROR!, nombre de la imagen vacio.";}	
								}
								else{echo "¡ERROR!, la imagen no tiene nombre.";}
							}						
						}
						else{echo "¡ERROR!, ninguna imagen recibida.";}					
						break;
					case 'modificar':
						if(isset($_REQUEST['codigo']))
						{
							$codigo = $_REQUEST['codigo'];
							if(isset($_FILES['archivo']))
							{
								if($_FILES['archivo']['error'])
								{
									switch ($_FILES['archivo']['error']) {
										case 1:		echo "Error, El tamaño de la imagen supera el limite permitido 2Mb.";break;
										case 2:		echo "Error, El tamaño de la imagen supera el limite permitido 2Mb";break;
										case 3:		echo "Error, La transferencia de la imagen se ha interrumpido";break;
										case 4:		echo "Error, El tamaño de la imagen enviado es nulo";break;
									}
								}
								else
								{
									if(isset($_FILES['archivo']['name']))
									{
										if($_FILES['archivo']['size'] <= 1048576)
										{
											$tamaño = getimagesize($_FILES['archivo']['tmp_name']);
											if($tamaño[0] <= 400 && $tamaño[1] <= 400)
											{												
												$cadenaNuevaImagen = "SELECT * FROM productos WHERE codigo='".$codigo."'";
												$consulta = mysqli_query($conexion,$cadenaNuevaImagen);
												if ($consulta->num_rows > 0)
												{
													while($rows = mysqli_fetch_array($consulta,MYSQLI_ASSOC))
													{
														$row[] = $rows;
													}
													$imagen 	= $row[0]["imagen"];
													$id 		= $row[0]["id"];
													if($imagen)
													{
														eliminarImagen($carpeta_destino,$imagen);
														$imagen = generarNombreImagen($_FILES['archivo']['type']);
														if($imagen)
														{
															if(move_uploaded_file($_FILES['archivo']['tmp_name'], $carpeta_destino.$imagen))
															{
																if($_REQUEST['nombre'] && $_REQUEST['codigo'] && $_REQUEST['categoria'] && $_REQUEST['descripcion'] && $_REQUEST['precio'] && $_REQUEST['stock'])
																{
																	$nombre 		= $_REQUEST['nombre'];
																	$categoria 		= $_REQUEST['categoria'];
																	$descripcion 	= $_REQUEST['descripcion'];
																	$precio 		= $_REQUEST['precio'];
																	$stock 			= $_REQUEST['stock'];
																	$nuevo_codigo	= $_REQUEST['nuevo_codigo'];
																	$cadenaSQL = "UPDATE productos SET nombre='".$nombre."', codigo='".$nuevo_codigo."', categoria='".$categoria."', descripcion='".$descripcion."', precio=".$precio.", stock=".$stock.", imagen='".$imagen."' WHERE id=".$id;
																	$consulta = mysqli_query($conexion,$cadenaSQL);
																	if($consulta)
																	{
																		echo "Los datos se modificaron correctamente.";									
																	}
																	else
																	{
																		eliminarImagen($carpeta_destino,$imagen);
																		echo "¡ERROR!, no se modificaron los datos en la base.";
																	}
																}
																else
																{
																	eliminarImagen($carpeta_destino,$imagen);
																	echo "¡ERROR!, no se puede ingresar datos en blanco a la base.";
																}
															}
															else{echo "¡ERROR!, no se pudo guardar la imagen.";}
														}	
														else{echo "¡ERROR!, nombre de la imagen vacio.";}	
													}	
													else{echo "¡ERROR!, cadena vacia en campo imagen.";}
												}
												else{echo "¡ERROR!, 0 registros encontrados.";}									
											}
											else{echo "¡ERROR!, no se grabo la imagen, dimesiones max. 400px X 400px.";}
										}
										else{echo "¡ERROR!, no se grabo la imagen, supera el tamaño permitido 1Mb.";}	
									}
									else{echo "¡ERROR!, La imagen no tiene nombre.";}
								}
							}
							else{echo "¡ERROR!, ninguna imagen seleccionada.";}	
						}
						else{echo "¡ERROR!, codigo vacio.";}										
						break;
					case 'eliminar':
						if(isset($_REQUEST['codigo']))
						{
							$codigo = $_REQUEST['codigo'];
							$cadenaSQL = "SELECT * FROM productos WHERE codigo='".$codigo."'";
							$consulta = mysqli_query($conexion,$cadenaSQL);
							if($consulta->num_rows > 0)
							{
								while($rows = mysqli_fetch_array($consulta,MYSQLI_ASSOC))
								{
									$row[] = $rows;
								}
								$imagen 	= $row[0]["imagen"];
								$id 		= $row[0]["id"];
								$cadenaDeleteSQL = "DELETE FROM productos WHERE id='".$id."'";
								$consulta = mysqli_query($conexion,$cadenaDeleteSQL);
								if($consulta){
									if(eliminarImagen($carpeta_destino,$imagen))
									{
										echo "El registro se elimino satisfactoriamente.";
									}
									else
									{
										echo "¡ERROR!, no se pudo eliminar la imagen vinculada.";
									}
									
								}
								else{echo "¡ERROR!, no se pudo eliminar el registro de la base de datos.";}							
							}
							else{echo "¡ERROR!, 0 registros encontrados.";}
						}
						else{echo "¡ERROR!, codigo vacio.";}
						break;
					default:
						echo "¡ERROR!, operacion desconocida.";
						break;
				}
			}
			else{echo "¡ERROR!, sin conexion a la base de datos.";}	
			mysqli_close($conexion);
		}
		else{echo "¡ERROR!, tipo de operacion sin definir.";}	
	}
	else
	{
		echo "Error, faltan Datos";
	}
}
else
{
	echo "¡ERROR!, llave y/o operacion no definido(s).";
}
function generarNombreImagen($type)
{
	$nombreImagen = microtime();
	$nombreImagen .= rand(1,10000);
	$nombreImagen = md5($nombreImagen);
	switch ($type) {		
		case 'imagen/png':
			$extension = "png";
		default:
			$extension = "jpg";
			break;
	}
	$nombreImagen .= ".".$extension;
	return $nombreImagen;
}
function eliminarImagen($carpeta_destino,$imagen)
{
	if(file_exists($carpeta_destino.$imagen))
	{
		return unlink($carpeta_destino.$imagen);		
	}
}
?>