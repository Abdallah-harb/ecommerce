<?php

	// for arabic lang translater
	function lang($phrase){

		static $lang = array(

			"ADMIN"   => 'admin with arab lang',
			"MESSAGE" =>'marhba'
		);

		return $lang[$phrase];
	}

?>