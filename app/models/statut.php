<?php 
class Statut extends AppModel {
	var $useTable = 'x2631824_Statut';
	var $primaryKey = 'ID';
	var $actsAs = array('Containable');
	
	var $hasMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'x2631824_statut'
			)
		);

}
?>