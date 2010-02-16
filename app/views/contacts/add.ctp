<?php echo $form->create('Contact'); ?>
<?php echo $form->input('ID'); ?>
<?php echo $form->input('nom', array('label' => 'Nom <span class=\'oblig\'>*</span>', 'size' => 50)); ?>
<?php echo $form->input('prenom', array('label' => 'Prenom <span class=\'oblig\'>*</span>', 'size' => 50)); ?>
<?php echo $form->input('client_id'); ?>	


<?php echo $form->end('Ajouter'); ?> 