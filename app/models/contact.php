<?php 
class Contact extends AppModel {
	var $useTable = 'x2631824_Contact';
	var $primaryKey = 'ID';
	var $actsAs = array('Containable');
	
	var $hasMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'x2631824_nomContact'
			)
		);
		
	var $belongsTo = array(
		'Client' => array(
			'className' => 'Client',
			'foreignKey' => 'client'
			)
		);

}
?>