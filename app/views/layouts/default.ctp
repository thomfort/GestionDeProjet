<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<title><?php echo $title_for_layout?></title>
<!-- Inclue les fichiers et scripts externes ici (Voir le HTML Helper pour plus d'informations) -->
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/reset/reset-min.css"> 
<?php echo $html->css('style'); ?>
<?php echo $scripts_for_layout ?>
<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.min.js"></script>
<?php echo $html->css('jquery/jquery-ui.css'); ?>
<script type="text/javascript" src="js/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous/src/scriptaculous.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/application.js"></script>

</head>
<body>
<div id="container">
<!-- Si vous voulez qu'une sorte de menu s'affiche sur toutes vos vues, incluez le ici -->
<div id="header">
    <div id="menu">
		<?php echo $html->link('Bon de commande', 'http://'.$_SERVER["SERVER_NAME"].'/commande90degres/'); ?> |
		<?php echo $html->link('Liste projet', array('controller' => 'projects', 'action' => 'index')); ?> |
		<?php echo $html->link('Ajouter un contact', array('controller' => 'contacts', 'action' => 'add')); ?> |
		<?php echo $html->link('Ajouter un projet', array('controller' => 'projects', 'action' => 'add')); ?>
	</div>
	<div style="float:right;margin-top:20px;width: 275px;"> 
		<?php echo $form->create('Project', array('url'=>array('action'=>'index'), 'class'=>'formSearch')) ?>  
		<?php echo $form->input('Descr', array('label' => '', 'value' => '')) ?>  
		<?php echo $form->end('Rechercher'); ?>  
	</div>
    <h2><?php echo "Syst&egrave;me d'ajout de projet"; ?></h2>
</div>

<!-- C'est ici que je veux que mes vues soient affichées -->
<div id="body">
	<?php $session->flash(); ?>
	<?php echo $content_for_layout ?>
</div>

<!-- On ajoute un pied de page à chaque page affichée -->
<div id="footer"></div>
</div>
</body>
</html>