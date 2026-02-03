<?php 
	date_default_timezone_set('America/Argentina/Buenos_Aires'); 
	
	function fechaHora(){
		$mes = array("","enero", 
					  "febrero", 
					  "marzo", 
					  "abril", 
					  "mayo", 
					  "junio", 
					  "julio", 
					  "agosto", 
					  "septiembre", 
					  "octubre", 
					  "noviembre", 
					  "diciembre");
		return date('d')." de ". $mes[date('n')] . " de " . date('Y');
	}


 ?>