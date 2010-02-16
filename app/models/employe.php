<?php 
class Employe extends AppModel {
	var $useTable = 'Empl';
	var $primaryKey = 'InEmplId';
	var $actsAs = array('Containable');
	
	var $hasMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'x2631824_chargeProjet'
			)
		);

}
?>