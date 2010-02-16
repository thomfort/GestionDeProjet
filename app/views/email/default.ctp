<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<title><?php echo $title_for_layout?></title>
<!-- Inclue les fichiers et scripts externes ici (Voir le HTML Helper pour plus d'informations) -->
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/reset/reset-min.css"> 
<link rel="stylesheet" type="text/css" href="/addProject/css/style.css" />
<?php echo $scripts_for_layout ?>
<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>
<?php 
if(isset($javascript)):
    echo $javascript->link('prototype.js');
    echo $javascript->link('jquery.min.js');
endif;          
?> 
</head>
<body>
<div id="container">
<!-- Si vous voulez qu'une sorte de menu s'affiche sur toutes vos vues, incluez le ici -->
<div id="header">
    <div id="menu">
		<?php echo $html->link('Bon de commande', 'http://127.0.0.1/commande90degres/'); ?> |
		<?php echo $html->link('Liste projet', array('controller' => 'projects', 'action' => 'index')); ?> |
		<?php echo $html->link('Ajouter un projet', array('controller' => 'projects', 'action' => 'add')); ?>
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