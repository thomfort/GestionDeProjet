<?php 
class Facturation extends AppModel {
	var $useTable = 'x2631824_usrTypeDeFacturation';
	var $primaryKey = 'ID';
	var $actsAs = array('Containable');
	
	var $hasMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'x2631824_typeFacturation'
			)
		);
}
?>