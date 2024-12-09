<?php
	//error_reporting(0);
	require 'SOAPService.php';
	require './WSDLDocument/WSDLDocument.php';
	$wsdl = new WSDLDocument('SOAPService', "https://www.haragaakos.hu/services/SOAPService.php","https://www.haragaakos.hu/services/");
	$wsdl->formatOutput = true;
	$wsdlfile = $wsdl->saveXML();
	echo $wsdlfile;
	file_put_contents ("service.wsdl" , $wsdlfile);
?>
