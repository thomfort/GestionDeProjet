<?php 
class Project extends AppModel {
	var $useTable = 'ProjInfo';
	var $primaryKey = 'InProjId';
	var $actsAs = array('Containable');
	var $validate = array(
					'Descr' => array(
						'rule' => array('between', 5, 50),
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Ce champ est requis et doit contenir entre 5 et 50 caractères!'
						),
					'EstSales' => array(
						'rule' => 'money',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Ce champ est requis et doit contenir que des caracteres numeric!'
						),
					'contact_id' => array(
						'rule' => 'notEmpty',
						
						'message' => 'Vous devez choisir un contact!'
						)
					);
	
	var $belongsTo = array(
		'Client' => array(
			'className' => 'Client',
			'foreignKey' => 'x2631824_client'
			),
		'Contact' => array(
			'className' => 'Contact',
			'foreignKey' => 'x2631824_nomContact'
			),
		'Employe' => array(
			'className' => 'Employe',
			'foreignKey' => 'x2631824_chargeProjet'
			),
		'Facturation' => array(
			'className' => 'Facturation',
			'foreignKey' => 'x2631824_typeFacturation'
			),
		'Statut' => array(
			'className' => 'Statut',
			'foreignKey' => 'x2631824_statut'
			)	
		);
		
		
	var $paginate = array('Comment'=>array('limit'=>10));
}
?>