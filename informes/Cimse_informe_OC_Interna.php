<?php
//============================================================+
	   //Creamos la conexión
	include_once '../includes/sp_connect.php';
	include_once '../includes/db_connect.php';
	$conexion_sp=mysqli_connect(HOSTSP,USERSP,PASSWORDSP,DATABASESP) or
		die("Problemas con la conexión");
		mysqli_query($conexion_sp,"set names 'utf8'");
	$conexion_db=mysqli_connect(HOST,USER,PASSWORD,DATABASE) or
    die("Problemas con la conexión");
	mysqli_query($conexion_db,"set names 'utf8'");
	//echo"<ul class='nav navbar-nav'>";
	//echo"<li>  Detalle:  </li>";
	//echo"</ul>";
	//echo"<br>";
	//generamos la consulta para el encabezado
	   if(!$resultComprobante = mysqli_query($conexion_sp, "select IdComprobante, NumeroComprobante, FechaComprobante, NonmbreEmpresa, ApellidoContacto, CondicionesPago, Notas, NumeroComprobante01, PlazoEntrega, Confecciono, MantiniemtoOferta, Transporte, Solicito from comprobantes where TipoComprobante=9 and NumeroComprobante='".$_REQUEST['idppto']."' limit 1")) die("Problemas con la consulta1");
		$reg = mysqli_fetch_array($resultComprobante); 

		//Agrego un intermedio con el cambio de la tabla contactos a contactos2
		if(!$resultContEmp = mysqli_query($conexion_sp, "select idOrganizacion from contactos2 where IdContacto='".$reg['NonmbreEmpresa']."' limit 1")) die("Problemas con la consulta2");
		$regContEmp = mysqli_fetch_array($resultContEmp);		
	
		if(!$resultEmp = mysqli_query($conexion_sp, "select Organizacion, CUIT, CondicionIVA from organizaciones where id='".$regContEmp['idOrganizacion']."' limit 1")) die("Problemas con la consulta2");
		$regEmp = mysqli_fetch_array($resultEmp);
		
		if(!$resultEmpDir = mysqli_query($conexion_sp, "select Direccion, Ciudad, Provoestado from direcciones where CUIT='".$regContEmp['idOrganizacion']."' and Direccion Not Like '%@%' order by id asc limit 1")) die("Problemas con la consulta2");
		$regEmpDir = mysqli_fetch_array($resultEmpDir);		
		
		if(!$resultEmpTel = mysqli_query($conexion_sp, "select Telefono, Telefonomovil from telefonos where IdContacto='".$reg['NonmbreEmpresa']."' limit 1")) die("Problemas con la consulta2");
		$regEmpTel = mysqli_fetch_array($resultEmpTel);		

		if(!$resultEmpMail = mysqli_query($conexion_sp, "select Direccion from direcciones where CUIT='".$reg['NonmbreEmpresa']."' and Direccion Like '%@%' limit 1")) die("Problemas con la consulta2");
		$regEmpMail = mysqli_fetch_array($resultEmpMail);
	
	//generamos la consulta para el detalle	
		
		if(!$resultDetalle = mysqli_query($conexion_sp, "select * from detallecomprobante where IdComprobante='".$reg['IdComprobante']."' order by Orden")) die("Problemas con la consulta2");
//============================================================+

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public $miCuit;
	public $miIIBBCM;
	public $miFechaInicioAct;
	public $tipoComprobante;
	public $codTipoComprobante;
	public $miPuntoVenta;
	public $numeroComprobante;
	public $fechaComprobante;
	public $decrTipoComprobante;
	public $CAE;
	public $vtoCAE;
	public $ImpresionRemito;
	public $DelRemito;
	public $AlRemito;
	public function Header() {
//============================================================+
//TEXTOS DEL HEADER
$this->SetY(8);
$this->SetX(55);
$this->SetFont('helvetica', 'B', 26);
$txt='CIMSe';
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$this->MultiCell(49, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(18);
$this->SetX(55);
$this->SetFont('helvetica', 'B', 13);
$txt='PATAGONIA S.R.L.';
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$this->MultiCell(49, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(23);
$this->SetX(45);
$this->SetFont('helvetica', 'B', 6);
$txt='CENTRO DE INSTRUMENTACION, METROLOGÍA Y SERVICIOS';
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$this->MultiCell(69, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(26);
$this->SetX(59);
$this->SetFont('helvetica', 'B', 6);
$txt='CALIBRACIÓN Y VENTA DE INSTRUMENTOS DE MEDICIÓN';
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$this->MultiCell(39, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(34);
$this->SetX(38);
$this->SetFont('helvetica', 'B', 7);
$txt='Lote 8, Manzana “C”, Bº San Cristobal, Valentina Sur-Nqn';
$this->MultiCell(83, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(37);
$this->SetX(30);
$this->SetFont('helvetica', 'B', 7);
$txt='Cel.: Admin. (299) 156066112; Lab. (299) 155-179547';
$this->MultiCell(93, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(40);
$this->SetX(46);
$this->SetFont('helvetica', 'B', 7);
$txt='E-mail: administracion@cimsesrl.com.ar';
$this->MultiCell(63, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(43);
$this->SetX(46);
$this->SetFont('helvetica', '', 6);
$txt='IVA RESPONSABLE INSCRIPTO';
$this->MultiCell(63, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(14);
$this->SetX(170);
$this->SetFont('helvetica', 'B', 16);
$this->Cell(1, 1, 'AVISO DE COMPRA', 0, false, 'C', 0, '', 0, false, 'M', 'M');

$this->SetY(23);
$this->SetX(170);
$this->SetFont('helvetica', 'B', 16);
$this->Cell(1, 1, 'Nº '.$this->numeroComprobante, 0, false, 'C', 0, '', 0, false, 'M', 'M');

$this->SetY(32);
$this->SetX(170);
$this->SetFont('helvetica', 'B', 12);
$this->Cell(1, 1, 'FECHA  '.$this->fechaComprobante, 0, false, 'C', 0, '', 0, false, 'M', 'M');

$this->SetY(37);
$this->SetX(140);
$this->SetFont('helvetica', '', 7);
$txt='CUIT:  '.$this->miCuit;
$this->MultiCell(63, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(40);
$this->SetX(140);
$this->SetFont('helvetica', '', 7);
$txt='Ingresos brutos CM:  '.$this->miIIBBCM;
$this->MultiCell(63, 4, $txt, 0, 'C', 0, 0, '', '', true);

$this->SetY(43);
$this->SetX(140);
$this->SetFont('helvetica', '', 7);
$txt='Inicio de actividades:  '.$this->miFechaInicioAct;
$this->MultiCell(63, 4, $txt, 0, 'C', 0, 0, '', '', true);

//============================================================+
// LOGOS DEL HEADER
$image_file = K_PATH_IMAGES.'Presupuesto/Cimse.png';
// Image method signature:
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
$this->Image($image_file, 10, 10, 35, 35, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//============================================================+

// LINEAS DEL HEADER
$this->Line(8, 9, 8, 47, array('width' => 0.75));
$this->Line(8, 9, 205, 9, 6);
$this->Line(8, 47, 205, 47, 6);	
$this->Line(205, 9, 205, 47, 6);	
//$this->Line(136, 9, 136, 27, 6);	
//$this->Line(119, 9, 119, 27, 6);
$this->Line(128, 9, 128, 47, 6);	
//$this->Line(119, 27, 136, 27, 6);	
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->SetY(-15);
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 		//echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
		$this->Cell(0, 10, $dias[date('w')].', '.date("d").' de '.$meses[date('n')-1].' de '.date("Y").' - Pagina '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('OC INTERNA');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT-5, PDF_MARGIN_TOP+2, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

$pdf->numeroComprobante=$reg['NumeroComprobante'];
$pdf->fechaComprobante=substr($reg['FechaComprobante'],8,2)."/".substr($reg['FechaComprobante'],5,2)."/".substr($reg['FechaComprobante'],0,4);
//BUSCO miCuit EN CONTROLPANEL.
if(!$resultDatosAux = mysqli_query($conexion_sp, "select ContenidoValor from controlpanel where Descripcion = 'miCuit' and padre = '1' limit 1")){die("Problemas con la consulta de CONTROLPANEL");}
$rowresultDatosAux = mysqli_fetch_array($resultDatosAux);
$pdf->miCuit = $rowresultDatosAux['ContenidoValor'];
//BUSCO miIIBBCM EN CONTROLPANEL.
if(!$resultDatosAux = mysqli_query($conexion_sp, "select ContenidoValor from controlpanel where Descripcion = 'miIIBBCM' and padre = '1' limit 1")){die("Problemas con la consulta de CONTROLPANEL");}
$rowresultDatosAux = mysqli_fetch_array($resultDatosAux);
$pdf->miIIBBCM = $rowresultDatosAux['ContenidoValor'];
//BUSCO miInicioActividad EN CONTROLPANEL.
if(!$resultDatosAux = mysqli_query($conexion_sp, "select ContenidoValor from controlpanel where Descripcion = 'miInicioActividad' and padre = '1' limit 1")){die("Problemas con la consulta de CONTROLPANEL");}
$rowresultDatosAux = mysqli_fetch_array($resultDatosAux);	
$pdf->miFechaInicioAct = $rowresultDatosAux['ContenidoValor'];

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 11);

// add a page
$pdf->AddPage('P', 'A4');

// set some text for example
$txt = "\nEmpresa: ".substr($regEmp['Organizacion'],0,90)."\nDomicilio: ".substr($regEmpDir['Direccion'],0,70)." - ".substr($regEmpDir['Ciudad'],0,37)."\nCUIT: ".$regEmp['CUIT']."                                           Mail: ".substr($regEmpMail['Direccion'],0,67)."\n";
$pdf->MultiCell(193, 23, $txt, 1, 'L', 0, 1, '', '', true, 0, false, true, 40, 'T');
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

// set some text for example
$txt = "Num. de cotización: ".substr($reg['NumeroComprobante01'],0,90);
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->MultiCell(193, 6, $txt, 1, 'L', 0, 1, '', '', true, 0, false, true, 60, 'T');

//////////////////////////////

$pdf->Ln(1);

$pdf->MultiCell(15, 5, 'Notas:', 0, 'J', 0, 0, '', '', true, 0, false, true, 40, 'T');
$txt = $reg['Notas'];
$pdf->MultiCell(178, 5, $txt, 1, 'L', 0, 1, '', '', true, 0, false, true, 40, 'T');


$pdf->Ln(4);

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
	$html = '
<table border="0" width="105%"{border-collapse: collapse;}>
	<tr>
	
		<th width="5%"><b>Item</b></th>
		<th align="left" width="10%"><b>Código</b></th>
		<th align="left" width="41%"><b>Descripción</b></th>
		<th align="right" width="5%"><b>Cant.</b></th>
		<th align="right" width="6%"><b>Unid.</b></th>';
			$html = $html .'
			<th align="right" width="13%"><b>Unitario</b></th>
			<th align="right" width="15%"><b>SubTotal s/IVA</b></th>
		<th align="right" width="5%"><b>IVA</b></th>';
	$html = $html .'			
	</tr>
	<hr width="105%">';		

//$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------

$totalPresup=0;
while ($rowDetalle = mysqli_fetch_array($resultDetalle)){   
	if(!$resultArticulo = mysqli_query($conexion_sp, "select descricpcion, MonedaOrigen, ValorVenta, IVA, UnidadMedida, ComposicionyDescirpcion, CodigoProveedor from productos where IdProducto='".$rowDetalle['IdProducto']."' limit 1")) die("Problemas con la consulta2");
	$rowProd = mysqli_fetch_array($resultArticulo);
	if(!$monedaArticulo = mysqli_query($conexion_sp, "select Simbolo from monedaorigen where IdRegistroCambio='".$rowProd['MonedaOrigen']."' limit 1")) die("Problemas con la consulta2");
	$rowMonedaArt = mysqli_fetch_array($monedaArticulo);
	if(!$monedaDetalle = mysqli_query($conexion_sp, "select Simbolo from monedaorigen where IdRegistroCambio='".$rowDetalle['Moneda']."' limit 1")) die("Problemas con la consulta2");
	$rowMonedaDet = mysqli_fetch_array($monedaDetalle);
	if(!$iva = mysqli_query($conexion_sp, "select Texto, Valor from z_ivas where IdRegistro='".$rowProd['IVA']."' limit 1")) die("Problemas con la consulta2");
	$rowIVA = mysqli_fetch_array($iva);
	if(!$confecc = mysqli_query($conexion_db, "select Nombre, Apellido from members where id='".$reg['Confecciono']."' limit 1")) die("Problemas con la consulta2");
	$rowConfecc = mysqli_fetch_array($confecc);
	if(!$solicit = mysqli_query($conexion_db, "select Nombre, Apellido from members where id='".$reg['Solicito']."' limit 1")) die("Problemas con la consulta2");
	$rowSolicit = mysqli_fetch_array($solicit);
    $html = $html .'
	<tr>
		<td style="font-size:0.95em; font-weight:normal">'.$rowDetalle['Orden'].'</td>
		<td style="font-size:0.95em; font-weight:normal">'.$rowProd['CodigoProveedor'].'</td>
		<td style="font-size:0.95em; font-weight:normal">'.$rowProd['descricpcion'].'</td>
		<td style="font-size:0.95em; font-weight:normal; text-align: right">'.$rowDetalle['Cantidad'].'</td>
		<td style="font-size:0.95em; font-weight:normal; text-align: center">'.$rowProd['UnidadMedida'].'</td>';
		//MONEDA
			//moneda de origen
			$simboloMoneda=$rowMonedaDet['Simbolo'];
			// Con Precios
			$html = $html .'
				<td style="font-size:0.95em; font-weight:normal; text-align: right">'.$simboloMoneda.' '.number_format($rowDetalle['CostoUnitario'],2,',','.').'</td>
				<td style="font-size:0.95em; font-weight:normal; text-align: right">'.$simboloMoneda.' '.number_format($rowDetalle['SubTotal'],2,',','.').'</td>
				<td style="font-size:0.9em; font-weight:normal; text-align: right"><small>+'.$rowIVA['Texto'].'</small></td>
		</tr>';
	
	//DETALLE DEL ARTICULO
	IF ($_REQUEST['descr']=='1'){
	$html = $html .'<tr>
		<td style="font-size:0.95em; font-weight:normal">  </td>
		<td colspan="3" style="font-size:0.66em; font-weight:normal">'.$rowProd['ComposicionyDescirpcion'].'</td>
		<td style="font-size:0.95em; font-weight:normal">  </td>
	</tr>';}
	
//DESTINO, OBSERVACIONES Y NUMEROS DE SERIE	
	$html = $html .'<tr>
		<td colspan="3" style="font-size:0.75em; font-weight:normal">Destino: '.$rowDetalle['Destino'].'</td>
		<td colspan="3" style="font-size:0.75em; font-weight:normal">Obs: '.$rowDetalle['Observaciones'].'</td>
		<td colspan="3" style="font-size:0.75em; font-weight:normal">Nº serie: ';

		//NUMERO DE SERIE DEL ARTICULO
		//Diciembre 2018. También los números de serie de la tabla

			//Si el cliente no los quiere mostrar ($_REQUEST['serie']=='0') ni me gasto
			//Tengo que buscar todos los numeros de serie de este idDetalleComprobante en la tabla NumerosSerie
			if(!$resultNumerosSerie = mysqli_query($conexion_sp, "select idNumeroSerie,numeroSerie from numerosserie where IdDetalleComprobante='".$rowDetalle['IdDetalleComprobante']."' order by numeroSerie")) die("Problemas con la consulta numerosserie");
			//Si hay números de serie para mostrar sigo, sino no
			if ((mysqli_num_rows($resultNumerosSerie)>0) or ($rowDetalle['NumeroSerie']!=NULL)){

				//Si hay números del método nuevo
				if (mysqli_num_rows($resultNumerosSerie)>0){
					while ($regNumerosSerie = mysqli_fetch_array($resultNumerosSerie)){
						$html = $html.$regNumerosSerie['numeroSerie']. " - ";	
					}
				}
				//Si hay numeros del metodo viejo
				if ($rowDetalle['NumeroSerie']!=NULL){$html = $html.$rowDetalle['NumeroSerie'];}
			}
				$html = $html .'</td>
				
			</tr>';
			
		
		


		$html = $html .'	<hr width="105%">'; 
			$totalPresup= $totalPresup + $rowDetalle['SubTotal'];


}  
    $html = $html ."</table>";
$pdf->writeHTML($html, true, false, true, false, ''); 
// ---------------------------------------------------------

	
	$html ='
<table border="0" {border-collapse: collapse;}>
	<tr>
		<td> </td>';
			$html =$html .'<td style="font-size:1.3em; font-weight:bold; text-align: right" colspan="5">SUBTOTAL s/IVA:</td>';

		$html = $html .'
		<td style="font-size:1.3em; font-weight:bold; text-align: center" colspan="2">'.$simboloMoneda.' '.number_format($totalPresup,2,',','.').'</td>
	</tr>
	<hr width="105%">
</table>';

	$pdf->writeHTML($html, true, false, true, false, ''); 

if(!$plazoEnt = mysqli_query($conexion_sp, "select ContenidoValor from controlpanel where Descripcion='".$reg['PlazoEntrega']."' and padre='51' limit 1")) die("Problemas con la consulta plazoentrega en controlpanel");

if ($rowPlazoEnt = mysqli_fetch_array($plazoEnt)){$elPlazoEnt=$rowPlazoEnt['ContenidoValor'];}
else {$elPlazoEnt='';}


	$html ='
</br>
<table border="0" {border-collapse: collapse;}>
	<tr>
		<td width="15%" style="font-size:0.9em; font-weight:bold; text-align: right"><b>Transporte: </b></td>
		<td width="1%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
		<td width="75%" style="font-size:1.2em; font-weight:normal; text-align: left"><b>'.$reg['Transporte'].'</b></td>
		<td width="11%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
	</tr>
	<tr>
		<td width="15%" style="font-size:0.9em; font-weight:bold; text-align: right"><b>Plazo de Entrega: </b></td>
		<td width="1%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
		<td width="75%" style="font-size:0.9em; font-weight:normal; text-align: left">'.$elPlazoEnt.'</td>
		<td width="11%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
	</tr>
</table>
<table border="0" {border-collapse: collapse;}>
	<tr>				
		<td width="75%" style="font-size:0.8em; text-align: left">CF: '.$rowConfecc['Nombre'].' '.$rowConfecc['Apellido'].'/'.$rowSolicit['Nombre'].' '.$rowSolicit['Apellido'].'</td>
	</tr>
</table>
</br>';

$pdf->writeHTML($html, true, false, true, false, '');

//-------------------

// ---------------------------------------------------------
// Recuadro de OC recibida por el proveedor
	
	$html ='
</br>
<table border="0" {border-collapse: collapse;}>
	<tr>
		<td width="15%" style="font-size:0.9em; font-weight:bold; text-align: right"></td>
		<td width="1%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
		<td colspan="6" border="1" width="75%" style="font-size:1.2em; font-weight:normal; text-align: left">
			<table border="0" {border-collapse: collapse;}>
				<tr>
					<td width="95%" style="font-size:0.9em; font-weight:bold; text-align: center"> Recibido por '.substr($regEmp['Organizacion'],0,60).'</td>
				</tr>
				<tr>
					<td width="95%" style="font-size:0.9em; font-weight:bold; text-align: center"> </td>
				</tr>
				<tr>
					<td width="15%" style="font-size:0.9em; font-weight:bold; text-align: right">Vía Mail</td>
					<td width="1%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
					<td width="5%" style="font-size:0.9em; font-weight:bold; text-align: right">
						<table border="1" {border-collapse: collapse;}>
							<tr>
								<td width="95%" style="font-size:0.9em; font-weight:bold; text-align: center"> </td>
							</tr>						
						</table>
					</td>
					<td width="1%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
					<td width="15%" style="font-size:0.9em; font-weight:bold; text-align: right">Vía Tel</td>
					<td width="1%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
					<td width="5%" style="font-size:0.9em; font-weight:bold; text-align: right">
						<table border="1" {border-collapse: collapse;}>
							<tr>
								<td width="95%" style="font-size:0.9em; font-weight:bold; text-align: center"> </td>
							</tr>						
						</table>
					</td>
					<td width="1%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>		
					<td width="15%" style="font-size:0.9em; font-weight:bold; text-align: right">Fecha:</td>
					<td width="15%" style="font-size:0.9em; font-weight:bold; text-align: right">    / </td>
					<td width="11%" style="font-size:0.9em; font-weight:bold; text-align: right">    /  </td>
				</tr>
				<tr>
					<td width="95%" style="font-size:0.9em; font-weight:bold; text-align: center"> </td>
				</tr>
				<tr>
					<td width="95%" style="font-size:0.9em; font-weight:bold; text-align: left">  Contacto:</td>				
				</tr>
			</table>
		</td>
		<td width="11%" style="font-size:0.9em; font-weight:bold; text-align: right"> </td>
	</tr>
</table>
</br>';

$pdf->writeHTML($html, true, false, true, false, '');

//-------------------

//imagen firma
// Image example with resizing
//$image_file = K_PATH_IMAGES.'Presupuesto/Firma.jpg';
// Image method signature:
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
//$pdf->Image($image_file, 15, 140, 30, 18, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

//- - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// set alpha to semi-transparency
$pdf->SetAlpha(0.15);

// draw jpeg image
$pdf->Image('copiainterna.jpg', 20, 100, 160, 50, '', '', '', true, 72);

// restore full opacity
$pdf->SetAlpha(1);

- - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
	
//Close and output PDF document
		$pdf->Output('Orden de compra - copia interna.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+