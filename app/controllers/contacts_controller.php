<?php
class ContactsController extends AppController {
var $uses = array('Project', 'Contact', 'Client');
	var $helpers = array('Html','Form','Javascript','Ajax');
	var $components = array('Email', 'Cookie','RequestHandler');


	function index() {
		$contact_list = $this->Contact->find('list', 
											array(
											'condition'=>array('Contact.actif' => 'Null', 'Contact.actif ' => False),
											'order' => array('Contact.nom ASC')
											)
										);
		$this->set('contacts', $contact_list);
		//$this->set('contacts', $this->Contact->Client->generateList());
	}
	
	function add() {
		$clients = $this->Contact->Client->find('list', array(
														'fields' => array('Client.InCustId','Client.Name'), 
														'order' => array('Client.Name')));
	   	$this->set(compact('clients'));
		if (!empty($this->data)) {
			$this->data['Contact']['client'] = $this->data['Contact']['client_id'];
			$this->data['Contact']['nomEtPrenom'] = $this->data['Contact']['nom']." ".$this->data['Contact']['prenom'];
			$resultat = $this->Contact->saveAll( $this->data );	
			if ($resultat) {
				$this->Session->setFlash('Le contact vient d\'&ecirc;tre ajout&eacute;');
				$this->redirect(array('controller' => 'projects', 'action'=>'add'));
			}
		}
	}
	
	

	
	function edit($id) {
		$clients = $this->Contact->Client->find('list', array(
														'fields' => array('Client.InCustId','Client.Name'), 
														'order' => array('Client.Name')));
	   	$this->set(compact('clients'));
		if (empty($this->data)) {
			$this->Contact->id = $id;
			$this->data = $this->Contact->read();
			$this->data['Contact']['client_id'] = $this->data['Contact']['client']; 
		} else {
			$this->data['Contact']['client'] = $this->data['Contact']['client_id'];
			$this->data['Contact']['nomEtPrenom'] = $this->data['Contact']['nom']." ".$this->data['Contact']['prenom'];
			$nomDescr = $this->data['Contact']['nom'];
			$this->Contact->saveAll( $this->data );
			$this->Session->setFlash('Le contact <i>'.$nomDescr.'</i> a &eacute;t&eacute; modifi&eacute;');
			$this->redirect(array('action'=>'index'));
		}
	}

	
	function delete($id) {
		$this->Contact->del($id);
		$this->Session->setFlash('Le contact id='.$id.' a &eacute;t&eacute; effac&eacute;.');
		$this->redirect(array('action'=>'index'));
	}
	
}
?>