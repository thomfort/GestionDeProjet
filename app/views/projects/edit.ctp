<?php echo $form->create('Project'); ?>
<?php echo $form->input('InProjId'); ?>

<?php 
echo "<div class='input select'>";
	echo $form->label('Client', null, array('for' => 'clients')); 
	echo $form->select('Client.id', $clients, $selClient, array('id' => 'clients','selected' => '33'));
echo "</div>";

echo "<div class='input select'>";
	echo $form->label('Contact', null, array('for' => 'clients'));
	echo $form->select('Contact.id', $contacts, $selContact, array('id' => 'contacts')); 
echo "</div>";
// Ajax Part 
$options = array('url' => 'update_select', 'update' => 'contacts');
echo $ajax->observeField('clients',$options);
?>

<?php /* echo $form->input('client_id');*/ ?>	
<?php /* echo $form->input('contact_id', array('label' => 'Contact<span class=\'oblig\'>*</span>', 'empty' => '(choose Contact)'));*/?>	
<?php
		echo $html->link("Ajouter contact", 
							array (	'controller' => 'contacts', 'action' => 'add' ),
							array(	'escape' => false) );
?>

<?php echo $form->input('UpdatedDate', array('type' => 'hidden', 'value'=>date("Y-m-d H:i:s"))); ?>
<?php echo $form->input('Descr', array('label' => 'Titre du projet <span class=\'oblig\'>*</span>', 'size' => 50)); ?>
<?php echo $form->input('ProjId', array('label' => 'Code budgetaire <span class=\'oblig\'></span>', 'type'=>'hidden')); ?>
<?php echo $form->input('x2631824_codeBudget', array('label' => 'Code budgetaire VIA <span class=\'oblig\'>*</span>', 'after' => '-2600')); ?> 
<?php echo $form->input('x2631824_codeSmart', array('label' => 'Code smart VIA')); ?>

<?php echo $form->input('facturation_id', array('empty' => '(choose Facturation)')); ?>
<?php echo $form->input('EstSales', array('type' => 'text', 'label' => 'Budget <span class=\'oblig\'>*</span>', 'size' => '10')); ?>
<?php echo $form->input('x2631824_bonCommande', array('label' => 'No. bon de commande (PO)')); ?>
<?php echo $form->input('BegDate', array('type' => 'text', 'label' => 'Date de début')); ?>
<?php echo $form->input('EndDate', array('type' => 'text', 'label' => 'Date de fin')); ?>
<?php echo $form->input('Msg', array('label' => 'Commentaire', 'cols' => 50)); ?>

<?php echo $form->input('employe_id', array('empty' => '(choose Employe)')); ?>	
<?php echo $form->input('statut_id', array('empty' => '(choose Statut)')); ?>		

<?php echo $form->submit('Sauvegarder', array('div'=>false, 'name'=>'submit')); ?>
<?php echo $form->submit('Annuler', array('div'=>false, 'name'=>'cancel')); ?>
<?php echo $form->end(); ?>
