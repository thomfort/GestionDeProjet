<?php
class Client extends AppModel {
	var $useTable = 'Cust';
	var $primaryKey = 'InCustId';
	var $actsAs = array('Containable');
	
	var $hasMany = array(
		'Contact' => array(
			'className' => 'Contact',
			'foreignKey' => 'client',
			)
		); 		
		
	var $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'x2631824_client',
			)
		);
}
?>