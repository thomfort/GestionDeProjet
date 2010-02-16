<?php
class ProjectsController extends AppController {
	var $name = 'Projects';
	//var $helpers = array('Html','Form','Javascript','Ajax');
	var $components = array('Email', 'Cookie','RequestHandler', 'Filter');
	var $uses = array('Project', 'Employe','Client','Contact', 'Facturation', 'Statut');
	
	var $helpers  = array(
              'Html',
              'Session',
              'Paginator',
			  'Javascript'
              );
	var $paginate = array(
				'limit' => 25,
				'order' => array(
					'Project.CreatedDate' => 'DESC'
				)
				//'conditions' => array('Project.Inactive is Null OR Project.Inactive <> 1')
				
              ); 
	
	/*function search($word) {
		$search = $this->Project->find('all', array('order' => array(), 'conditions' => array('Projet.Descr LIKE ' => '%'.$word.'%')));
		$this->set('projectFinds', $search);
	}*/
	
	function index() {
		//if(isset($word)) {
		//$this->Project->recursive = 0;
        $filter = $this->Filter->process($this);
		$this->set('url',$this->Filter->url);
		//$this->paginate = array( );
		$this->set('projects', $this->paginate(null,$filter));
		//}else{
		//	$data = $this->paginate('Project');
		//}
		//debug($data);
		//$this->set('projects', $data);
	}
	
	function updLstContact($idClient) {
		// Liste Contact
		$contacts = $this->Project->Contact->find('superlist', array(
														'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
														'separator'=>' ',
														'order' => array('Contact.nom'),
														'conditions' => array('Contact.actif IS Null')));
	}
	
	function duplicateProject($id) {
				
				
		if (array_key_exists('cancel', $this->params['form'])) {  
			$this->redirect(array('action'=>'index'));
		}
		else {	
			if (empty($this->data)) {
				$this->Project->id = $id;
				$this->data = $this->Project->read();
				
				$this->set('selClient', $this->data['Project']['x2631824_client']);
				$this->set('selContact', $this->data['Project']['x2631824_nomContact']);
				
				// Liste Client
				$clients = $this->Client->find('superlist', array(
														'fields' => array('Client.InCustId','Client.Name','Client.CustId'), 
														'separator'=>' - ',
														'order' => array('Client.Name')));
				$this->set(compact('clients'));
					
				// Liste Charge Projet
				$employes = $this->Project->Employe->find('superlist', array(
																'fields' => array('Employe.InEmplId', 'Employe.LastName', 'Employe.FirstName'),
																'separator'=>' '));
				$this->set(compact('employes'));
				
				// Liste Facturation
				$facturations = $this->Project->Facturation->find('list', array('fields' => array('Facturation.ID', 'Facturation.nom'), 'conditions' => array('Facturation.inactif <> 1 OR Facturation.inactif = NULL')));
				$this->set(compact('facturations'));
				
				// Liste Charge Statut
				$statuts = $this->Project->Statut->find('list', array(
																'fields' => array('Statut.ID', 'Statut.nom'),
																'order' => array('Statut.nom')));
				$this->set(compact('statuts'));
				
				// Liste Contact
				$contacts = $this->Contact->find('superlist', array('conditions' => array('client'=>$this->data['Project']['x2631824_client']),
															'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
															'separator'=>' '));
															
				$this->set(compact('contacts'));
															
				$this->data['Project']['employe_id'] = $this->data['Project']['x2631824_chargeProjet']; 
			} else {
				if($this->data['Project']['BegDate'] == ""){
					$this->data['Project']['BegDate'] = null;
				}
				if($this->data['Project']['EndDate'] == ""){
					$this->data['Project']['EndDate'] = null;
				}
				
				$this->set('selClient', $this->data['Client']['id']);
				$this->set('selContact', $this->data['Contact']['id']);
				
				// Liste Client
				$clients = $this->Client->find('superlist', array(
														'fields' => array('Client.InCustId','Client.Name','Client.CustId'), 
														'separator'=>' - ',
														'order' => array('Client.Name')));
				$this->set(compact('clients'));
				// Liste Charge Projet
				$employes = $this->Project->Employe->find('superlist', array(
																'fields' => array('Employe.InEmplId', 'Employe.LastName', 'Employe.FirstName'),
																'separator'=>' '));
				$this->set(compact('employes'));
				
				// Liste Facturation
				$facturations = $this->Project->Facturation->find('list', array('fields' => array('Facturation.ID', 'Facturation.nom'), 'conditions' => array('Facturation.inactif <> 1 OR Facturation.inactif = NULL')));
				$this->set(compact('facturations'));
				
				// Liste Charge Statut
				$statuts = $this->Project->Statut->find('list', array(
																'fields' => array('Statut.ID', 'Statut.nom'),
																'order' => array('Statut.nom')));
				$this->set(compact('statuts'));
				
				// Liste Contact
				$contacts = $this->Contact->find('superlist', array('conditions' => array('client'=>$this->data['Client']['id']),
															'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
															'separator'=>' '));
															
				$this->set(compact('contacts'));
															
				$this->data['Project']['x2631824_chargeProjet'] = $this->data['Project']['employe_id'];
				$this->data['Project']['x2631824_typeFacturation'] = $this->data['Project']['facturation_id'];
				$this->data['Project']['x2631824_statut'] = $this->data['Project']['statut_id'];
				$this->data['Project']['x2631824_client'] = $this->data['Client']['id'];
				$this->data['Project']['x2631824_nomContact'] = $this->data['Contact']['id'];
				
				
				//$resultat = $this->Project->save( $this->data );	
				$this->Project->create($this->data, true);
				$resultat = $this->Project->save();
				if ($resultat) {
					$msgMail[0] = "Un projet a créé <strong>créé</strong> dans Dynacom!";
					$msgMail[1] = "Ne pas oublier de <strong>créer</strong> le projet dans l'<strong>Intranet</strong> et sur le <strong>réseau</strong>.";
					$msgMail['subject'] = "Nouveau projet";
					$this->send_notification($msgMail,"add");
					$this->Session->setFlash('Le projet vient d\'&ecirc;tre ajout&eacute; et le courriel envoy&eacute;');
					$this->redirect(array('action'=>'index'));
				}
			}
		}
	}
	
	function add() {
		
		
		$this->set('cookclient',$this->Cookie->read('client_id'));
		$this->set('cookempl',$this->Cookie->read('employe_id'));
		
		// Liste Client
		$clients = $this->Client->find('superlist', array(
														'fields' => array('Client.InCustId','Client.Name','Client.CustId'), 
														'separator'=>' - ',
														'order' => array('Client.Name')));
	   	$this->set(compact('clients'));
		
		
	   	
	   	// Liste Contact
		$contacts = $this->Project->Contact->find('superlist', array(
														'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
														'separator'=>' ',
														'order' => array('Contact.nom'),
														'conditions' => array('Contact.actif IS Null')));
	   	$this->set(compact('contacts'));
		//$this->update_select();
		$options = $this->Contact->find('superlist',array('conditions' => array('client' => $this->Cookie->read('client_id')), 'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
														'separator'=>' '));
		$this->set('options',$options);
	   	
   		// Liste Charge Projet
		$employes = $this->Project->Employe->find('superlist', array(
														'fields' => array('Employe.InEmplId', 'Employe.LastName', 'Employe.FirstName'),
														'separator'=>' ',
														'order' => array('Employe.LastName'),
														'conditions' => array('Employe.Inactive IS Null OR Employe.Inactive = 0')));
	   	$this->set(compact('employes'));
		
		// Liste Charge Statut
		$statuts = $this->Project->Statut->find('list', array(
														'fields' => array('Statut.ID', 'Statut.nom'),
														'order' => array('Statut.nom')));
	   	$this->set(compact('statuts'));
	   	
	   	// Liste Facturation
		$facturations = $this->Project->Facturation->find('list', array('fields' => array('Facturation.ID', 'Facturation.nom'),'conditions' => array('Facturation.inactif <> 1 OR Facturation.inactif = NULL')));
	   	$this->set(compact('facturations'));
		
		if (array_key_exists('cancel', $this->params['form'])) {  
			$this->redirect(array('action'=>'index'));  
		}
		else {		
			if (!empty($this->data)) {
				if($this->data['Project']['BegDate'] == ""){
					$this->data['Project']['BegDate'] = null;
				}
				if($this->data['Project']['EndDate'] == ""){
					$this->data['Project']['EndDate'] = null;
				}
				//$this->data['Project']['x2631824_codeBudget'] = $this->data['Project']['x2631824_codeBudget'].'-2600';
				$this->data['Project']['x2631824_chargeProjet'] = $this->data['Project']['employe_id'];
				$this->data['Project']['x2631824_typeFacturation'] = $this->data['Project']['facturation_id'];
				$this->data['Project']['x2631824_statut'] = $this->data['Project']['statut_id'];
				$this->data['Project']['x2631824_client'] = $this->data['Client']['id'];
				$this->data['Project']['x2631824_nomContact'] = $this->data['Contact']['id'];
				
				$resultat = $this->Project->save( $this->data );	
				
				if ($resultat) {
					$this->Cookie->del('client_id');
					$this->Cookie->del('employe_id');
					$this->Cookie->write('client_id',$this->data['Client']['id'],99999999);
					$this->Cookie->write('employe_id',$this->data['Project']['employe_id'],99999999);
					$msgMail[0] = "Un projet a été <strong>créé</strong> dans Dynacom!";
					$msgMail[1] = "Ne pas oublier de <strong>créer</strong> le projet dans l'<strong>Intranet</strong> et sur le <strong>réseau</strong>.";
					$msgMail['subject'] = "Nouveau projet";
					$this->send_notification($msgMail,"add");
					$this->Session->setFlash('Le projet vient d\'&ecirc;tre ajout&eacute; et le courriel envoy&eacute;');
					$this->redirect(array('action'=>'index'));
				}
				else {
					
				}
			} else {
				
			}
			
			
		}
	}
	function edit($id) {
		$this->Project->id = $id;
				
		if (array_key_exists('cancel', $this->params['form'])) {  
			//$this->Session->setFlash('Le projet <i>'.$nomDescr.'</i> a &eacute;t&eacute; modifi&eacute; et le courriel envoy&eacute;');
			$this->redirect(array('action'=>'index'));
		}
		else {	
			if (empty($this->data)) {
				$this->data = $this->Project->read();
				
				$this->set('selClient', $this->data['Project']['x2631824_client']);
				$this->set('selContact', $this->data['Project']['x2631824_nomContact']);
				
				// Liste Client
				$clients = $this->Client->find('superlist', array(
														'fields' => array('Client.InCustId','Client.Name','Client.CustId'), 
														'separator'=>' - ',
														'order' => array('Client.Name')));
				$this->set(compact('clients'));
					
				// Liste Charge Projet
				$employes = $this->Project->Employe->find('superlist', array(
																'fields' => array('Employe.InEmplId', 'Employe.LastName', 'Employe.FirstName'),
																'separator'=>' '));
				$this->set(compact('employes'));
				
				// Liste Facturation
				$facturations = $this->Project->Facturation->find('list', array('fields' => array('Facturation.ID', 'Facturation.nom'),'conditions' => array('Facturation.inactif <> 1 OR Facturation.inactif = NULL')));
				$this->set(compact('facturations'));
				// Liste Charge Statut
				$statuts = $this->Project->Statut->find('list', array(
																'fields' => array('Statut.ID', 'Statut.nom'),
																'order' => array('Statut.nom')));
				$this->set(compact('statuts'));
				
				// Liste Contact
				$contacts = $this->Contact->find('superlist', array('conditions' => array('client'=>$this->data['Project']['x2631824_client']),
															'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
															'separator'=>' '));
															
				$this->set(compact('contacts'));

				$this->data['Project']['client_id'] = $this->data['Project']['x2631824_client']; 
				$this->data['Project']['contact_id'] = $this->data['Project']['x2631824_nomContact']; 
				$this->data['Project']['employe_id'] = $this->data['Project']['x2631824_chargeProjet']; 
				$this->data['Project']['facturation_id'] = $this->data['Project']['x2631824_typeFacturation']; 
				$this->data['Project']['statut_id'] = $this->data['Project']['x2631824_statut'];
				
			} else {
				if($this->data['Project']['BegDate'] == ""){
					$this->data['Project']['BegDate'] = null;
				}
				if($this->data['Project']['EndDate'] == ""){
					$this->data['Project']['EndDate'] = null;
				}
				
				
				//$this->data['Project']['x2631824_codeBudget'] = $this->data['Project']['x2631824_codeBudget'].'-2600';
				$this->data['Project']['x2631824_chargeProjet'] = $this->data['Project']['employe_id'];
				$this->data['Project']['x2631824_typeFacturation'] = $this->data['Project']['facturation_id'];
				$this->data['Project']['x2631824_statut'] = $this->data['Project']['statut_id'];
				$this->data['Project']['x2631824_client'] = $this->data['Client']['id'];
				$this->data['Project']['x2631824_nomContact'] = $this->data['Contact']['id'];
				
				///////////////////////////////////////////////////////////////////////////
				
				// Titre du projet
				$nomDescr = $this->data['Project']['Descr'];
				$ancientitre = $this->Project->find('first', array('field' => 'Descr', 'conditions' => array('Project.InProjId'=>$id)));
				// Code budgetaire
				//$codeBudgetaireOld = $this->Project->find('first', array('field' => 'ProjId', 'conditions' => array('Project.InProjId'=>$id)));
				// Code budgetaire VIA
				$codeBudgetaireVIAOld = $this->Project->find('first', array('field' => 'x2631824_codeBudget', 'conditions' => array('Project.InProjId'=>$id)));
				// Code SMART VIA
				$codeSmartVIAOld = $this->Project->find('first', array('field' => 'x2631824_codeSmart', 'conditions' => array('Project.InProjId'=>$id)));
				// Budget
				$budgetOld = $this->Project->find('first', array('field' => 'EstSales', 'conditions' => array('Project.InProjId'=>$id)));
				// No. bon de commande
				$bonDeCommandeOld = $this->Project->find('first', array('field' => 'x2631824_bonCommande', 'conditions' => array('Project.InProjId'=>$id)));
				// Date de début
				$dateDebutOld = $this->Project->find('first', array('field' => 'BegDate', 'conditions' => array('Project.InProjId'=>$id)));
				// Date de fin
				$dateFinOld = $this->Project->find('first', array('field' => 'EndDate', 'conditions' => array('Project.InProjId'=>$id)));
				// Commentaire
				$commentaireOld = $this->Project->find('first', array('field' => 'Msg', 'conditions' => array('Project.InProjId'=>$id)));
				
				
				
				// Client
				$ancienClientID = $this->Project->find('first', array('field' => 'x2631824_client', 'conditions' => array('Project.InProjId'=>$id)));
				$clientOld = $this->Contact->find('first', array('field' => 'Name', 'conditions' => array('Client.InCustId'=>$ancienClientID['Client']['InCustId'])));
				$clientNew = $this->Contact->find('first', array('field' => 'Name', 'conditions' => array('Client.InCustId'=>$this->data['Client']['id'])));
				// Contact
				$ancienContactID = $this->Project->find('first', array('field' => 'x2631824_nomContact', 'conditions' => array('Project.InProjId'=>$id)));
				$contactOld = $this->Contact->find('first', array('field' => 'nomEtPrenom', 'conditions' => array('Contact.ID'=>$ancienContactID['Contact']['ID'])));
				$contactNew = $this->Contact->find('first', array('field' => 'nomEtPrenom', 'conditions' => array('Contact.ID'=>$this->data['Contact']['id'])));
				// Facturation
				$ancienFacturationID = $this->Project->find('first', array('field' => 'x2631824_typeFacturation', 'conditions' => array('Project.InProjId'=>$id)));
				$facturationOld = $this->Facturation->find('first', array('field' => 'nom', 'conditions' => array('Facturation.ID'=>$ancienContactID['Facturation']['ID'])));
				$facturationNew = $this->Facturation->find('first', array('field' => 'nom', 'conditions' => array('Facturation.ID'=>$this->data['Project']['facturation_id'])));
				// Employe
				$ancienEmployeID = $this->Project->find('first', array('field' => 'x2631824_chargeProjet', 'conditions' => array('Project.InProjId'=>$id)));
				$employeOld = $this->Employe->find('first', array('field' => 'LastName', 'conditions' => array('Employe.InEmplId'=>$ancienContactID['Employe']['InEmplId'])));
				$employeNew = $this->Employe->find('first', array('field' => 'LastName', 'conditions' => array('Employe.InEmplId'=>$this->data['Project']['employe_id'])));
				// Statut
				$ancienStatutID = $this->Project->find('first', array('field' => 'x2631824_statut', 'conditions' => array('Project.InProjId'=>$id)));
				$statutOld = $this->Statut->find('first', array('field' => 'nom', 'conditions' => array('Statut.ID'=>$ancienContactID['Statut']['ID'])));
				$statutNew = $this->Statut->find('first', array('field' => 'nom', 'conditions' => array('Statut.ID'=>$this->data['Project']['statut_id'])));
				
				$resultat = $this->Project->save( $this->data );
				if ($resultat) {
					$msgMail[0] = "Un projet a été <strong>modifié</strong> dans Dynacom !"; 
					$msgMail[1] = "Ne pas oublier de <strong>modifier</strong> le projet dans l'<strong>Intranet</strong> et sur le <strong>réseau</strong>."; 
					$msgMail['subject'] = "Modification de projet";
					
					///////////////////////////////////////////////////////////////////////////
					
					// Titre projet
					if($ancientitre['Project']['Descr'] != $this->data['Project']['Descr']){
						array_push($msgMail, 'Le titre du projet a été modifié '.$ancientitre['Project']['Descr'].' => '.$this->data['Project']['Descr']);
					}
					// Code budgetaire
					/*if($codeBudgetaireOld['Project']['ProjId'] != $this->data['Project']['ProjId']){
						array_push($msgMail, 'Le Code budgetaire a été modifié '.$codeBudgetaireOld['Project']['ProjId'].' => '.$this->data['Project']['ProjId']);
					}*/
					// Code budgetaire VIA
					if($codeBudgetaireVIAOld['Project']['x2631824_codeBudget'] != $this->data['Project']['x2631824_codeBudget']){
						array_push($msgMail, 'Le Code budgetaire VIA a été modifié '.$codeBudgetaireVIAOld['Project']['x2631824_codeBudget'].' => '.$this->data['Project']['x2631824_codeBudget']);
					}
					// code Smart VIA
					if($codeSmartVIAOld['Project']['x2631824_codeSmart'] != $this->data['Project']['x2631824_codeSmart']){
						array_push($msgMail, 'Le code Smart VIA a été modifié '.$codeSmartVIAOld['Project']['x2631824_codeSmart'].' => '.$this->data['Project']['x2631824_codeSmart']);
					}
					// Budget
					if($budgetOld['Project']['EstSales'] != $this->data['Project']['EstSales']){
						array_push($msgMail, 'Le Budget a été modifié '.$budgetOld['Project']['EstSales'].' => '.$this->data['Project']['EstSales']);
					}
					// Bon de commande
					if($bonDeCommandeOld['Project']['x2631824_bonCommande'] != $this->data['Project']['x2631824_bonCommande']){
						array_push($msgMail, 'Le No. bon de commande a été modifié '.$bonDeCommandeOld['Project']['x2631824_bonCommande'].' => '.$this->data['Project']['x2631824_bonCommande']);
					}
					// Date Debut
					if($dateDebutOld['Project']['BegDate'] != $this->data['Project']['BegDate']){
						array_push($msgMail, 'La Date de début a été modifié '.$dateDebutOld['Project']['BegDate'].' => '.$this->data['Project']['BegDate']);
					}
					// Date Fin
					if($dateFinOld['Project']['EndDate'] != $this->data['Project']['EndDate']){
						array_push($msgMail, 'La Date de fin a été modifié '.$dateFinOld['Project']['EndDate'].' => '.$this->data['Project']['EndDate']);
					}
					// Commentaire
					if($commentaireOld['Project']['Msg'] != $this->data['Project']['Msg']){
						array_push($msgMail, 'Le Commentaire a été modifié '.$commentaireOld['Project']['Msg'].' => '.$this->data['Project']['Msg']);
					}
					
					
					// Client
					if($ancienClientID['Client']['InCustId'] != $this->data['Project']['x2631824_client']){
						array_push($msgMail, 'Le Client a été modifié - '.$clientOld['Client']['Name'].' => '.$clientNew['Client']['Name']);
					}
					// Contact
					if($ancienContactID['Contact']['ID'] != $this->data['Project']['x2631824_nomContact']){
						array_push($msgMail, 'Le Contact a été modifié - '.$contactOld['Contact']['nomEtPrenom'].' => '.$contactNew['Contact']['nomEtPrenom']);
					}
					// Facturation
					if($ancienFacturationID['Facturation']['ID'] != $this->data['Project']['x2631824_typeFacturation']){
						array_push($msgMail, 'La Facturation a été modifiée - '.$facturationOld['Facturation']['nom'].' => '.$facturationNew['Facturation']['nom']);
					}
					// Employe
					if($ancienEmployeID['Employe']['InEmplId'] != $this->data['Project']['x2631824_chargeProjet']){
						array_push($msgMail, 'Le Employe a été modifié - '.$employeOld['Employe']['LastName'].' => '.$employeNew['Employe']['LastName']);
					}
					// Statut
					if($ancienStatutID['Statut']['ID'] != $this->data['Project']['x2631824_statut']){
						array_push($msgMail, 'Le Statut a été modifié - '.$statutOld['Statut']['nom'].' => '.$statutNew['Statut']['nom']);
					}
					$this->send_notification($msgMail,"edit");
					//debug($msgMail);
					
					$this->Session->setFlash('Le projet '.$ancientitre['Project']['Descr'].' => <i>'.$nomDescr.'</i> a &eacute;t&eacute; modifi&eacute; et le courriel envoy&eacute;');
					$this->redirect(array('action'=>'index'));
				} else {}
		
				$this->set('selClient', $this->data['Client']['id']);
				$this->set('selContact', $this->data['Contact']['id']);
				
				// Liste Client
				$clients = $this->Client->find('superlist', array(
														'fields' => array('Client.InCustId','Client.Name','Client.CustId'), 
														'separator'=>' - ',
														'order' => array('Client.Name')));
				$this->set(compact('clients'));
					
				// Liste Charge Projet
				$employes = $this->Project->Employe->find('superlist', array(
																'fields' => array('Employe.InEmplId', 'Employe.LastName', 'Employe.FirstName'),
																'separator'=>' '));
				$this->set(compact('employes'));
				
				// Liste Facturation
				$facturations = $this->Project->Facturation->find('list', array('fields' => array('Facturation.ID', 'Facturation.nom'), 'conditions' => array('Facturation.inactif <> 1 OR Facturation.inactif = NULL')));
				$this->set(compact('facturations'));
				
				// Liste Charge Statut
				$statuts = $this->Project->Statut->find('list', array(
																'fields' => array('Statut.ID', 'Statut.nom'),
																'order' => array('Statut.nom')));
				$this->set(compact('statuts'));
				
				// Liste Contact
				$contacts = $this->Contact->find('superlist', array('conditions' => array('client'=>$this->data['Client']['id']),
															'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
															'separator'=>' '));
															
				$this->set(compact('contacts'));
			}
		}
	}
	
	function delete($id) {
		$this->Project->del($id);
		$this->Session->setFlash('Le projet id='.$id.' a &eacute;t&eacute; effac&eacute;.');
		$this->redirect(array('action'=>'index'));
	}
	
	function send_notification($msg,$typ) {
		$this->Email->template = 'email/default';
		$this->data['msgMail'] = $msg;
		
		$contact_to = $this->data['Project']['x2631824_nomContact'];
		$employe_to = $this->data['Project']['x2631824_chargeProjet'];
		$client_to = $this->data['Project']['x2631824_client'];
		$statut_to = $this->data['Project']['x2631824_statut'];
		$facturation_to = $this->data['Project']['x2631824_typeFacturation'];
		
		//$this->data['contact'] = $this->Contact->find('first', array('conditions' => array('Contact.id' => $contact_to)));
		$sql = "SELECT nom, prenom FROM x2631824_Contact WHERE ID = $contact_to";
		$this->Contact->recursive = 0;
		$results = $this->Contact->query($sql);
		$this->data['contactname'] = $results;
		
		$sql = "SELECT LastName, FirstName, Email FROM Empl WHERE InEmplId = $employe_to";
		$this->Employe->recursive = 0;
		$results = $this->Employe->query($sql);
		$this->data['employename'] = $results;
		
		$sql = "SELECT Name FROM Cust WHERE InCustId = $client_to";
		$this->Client->recursive = 0;
		$results = $this->Client->query($sql);
		$this->data['clientname'] = $results;
		
		$sql = "SELECT nom FROM x2631824_Statut WHERE ID = $statut_to";
		$this->Statut->recursive = 0;
		$results = $this->Statut->query($sql);
		$this->data['statutname'] = $results;
		
		$sql = "SELECT nom FROM x2631824_usrTypeDeFacturation WHERE ID = $facturation_to";
		$this->Facturation->recursive = 0;
		$results = $this->Facturation->query($sql);
		$this->data['facturationname'] = $results;
		
		$this->Email->to = "tbouchard@90degres.ca";
		//$this->Email->to = "facturation@90degres.ca";
		$this->Email->subject = $msg['subject'];
		$this->Email->from = $this->data['employename'][0][0]['Email'];
		
		unset($this->data['msgMail']['subject']);
		$this->set('data', $this->data);
		$this->set('typ',$typ);
		
		$result = $this->Email->send();
	}
	
	function update_select() {
		if(!empty($this->data['Client']['id'])) {
			$cli_id = (int)$this->data['Client']['id'];
			
			$options = $this->Contact->find('superlist',array('conditions' => array('client' => $cli_id), 'fields' => array('Contact.ID', 'Contact.nom', 'Contact.prenom'),
														'separator'=>' ', 'order' => array('Contact.nom')));
			$this->set('options',$options);
		}
	}
	
	function close_project($id) {
			$this->Project->id = $id;
			$nameProject = $this->Project->field('Project.Descr');
			$newStatut = 'Archivé et Fermé';
			$this->Project->saveField('x2631824_statut', 2);
			$this->Session->setFlash('Le statut du projet '.$nameProject.' est rendu '.$newStatut.' !');
			// Notify email
			$this->Email->template = 'email/statut';
			$this->Email->to = "facturation@90degres.ca";
			$this->Email->subject = "Le statut du projet $nameProject est rendu $newStatut !";
			$this->set('nameProject',$nameProject);
			$this->set('newStatut',$newStatut);
			$result = $this->Email->send();
			
			$this->redirect(array('action'=>'index'));
	}
	function open_project($id) {
			$this->Project->id = $id;
			$nameProject = $this->Project->field('Project.Descr');
			$newStatut = 'Production';
			$this->Project->saveField('x2631824_statut', 1);
			$this->Session->setFlash('Le statut du projet '.$nameProject.' est rendu '.$newStatut.' !');
			$this->Email->template = 'email/statut';
			$this->Email->to = "facturation@90degres.ca";
			$this->Email->subject = "Le statut du projet $nameProject est rendu $newStatut !";
			$this->set('nameProject',$nameProject);
			$this->set('newStatut',$newStatut);
			$result = $this->Email->send();
			
			$this->redirect(array('action'=>'index'));
	}
	/*
	function open_project($id) {
	
	}*/
	
}
?>