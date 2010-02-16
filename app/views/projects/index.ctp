<?php 
//debug($projects);
?>

<table border="1" class="contenulist">
<?php



// Header List
echo $html->tableHeaders(
    array(
      $paginator->sort('Date', 'CreatedDate', array('url'=>array($url))),
      $paginator->sort('Code budgetaire.', 'ProjId', array('url'=>array($url))),
      $paginator->sort('Titre du projet','Descr', array('url'=>array($url))),
	  $paginator->sort('Chargé de projet','Employe.LastName', array('url'=>array($url))),
      $paginator->sort('Client','Client.Name', array('url'=>array($url))),
	  $paginator->sort('Contact','Contact.nom', array('url'=>array($url))),
	  $paginator->sort('Statut','x2631824_statut', array('url'=>array($url))),
	  'Actions' 
    )
  );
  
  
	
// Project List
foreach($projects as $project)
{
	// 2 = archivé et fermé
	// 1 = En production
	//
	$status = '';
	if($project['Project']['x2631824_statut'] == 1){
		$status = $html->link( 
					$html->image("greenbullet.png", array('class' => 'statut_img', 'title'=>'En production')), 
					array('action' => 'close_project', 
					$project['Project']['InProjId']), 
					array('escape' => false), 
					'Vous êtes sur le point de changer de statut pour Archivé / Fermé.\nVoulez-vous continuer?' );
		//$status = "Production ".$html->link('--', array('action' => 'close_project', $project['Project']['InProjId']), array('escape' => false), 'Vous êtes sur le point de changer de statut pour Archivé / Fermé.\nVoulez-vous continuer?' );
	}
	if($project['Project']['x2631824_statut'] == 2){ 
		$status = $html->link( 
					$html->image("redbullet.png", array('class' => 'statut_img', 'title'=>'Archivé et fermé')), 
					array('action' => 'open_project', 
					$project['Project']['InProjId']), 
					array('escape' => false), 
					'Vous êtes sur le point de changer le statut pour En Production.\nVoulez-vous continuer?' );
		//$status = "Archivé et Fermé ".$html->link('++', array('action' => 'open_project', $project['Project']['InProjId']), array('escape' => false), 'Vous êtes sur le point de changer le statut pour En Production.\nVoulez-vous continuer?' );
	}
	echo $html->tableCells(
	array(
        array(
			$project['Project']['CreatedDate'],
			$project['Project']['ProjId'],
			$project['Project']['Descr'],
			$project['Employe']['LastName'].' '.$project['Employe']['FirstName'],
			$project['Client']['Name'],
			$project['Contact']['nom'].' '.$project['Contact']['prenom'],
			$status,
			'<div align=center>'.
			$html->link($html->image("edit2.png", array('class' => 'edit_img', 'title'=>'Éditer')), 
									array (	'action' => 'edit', 
											$project['Project']['InProjId']),
									array(	'escape' => false) ). ' ' .
			$html->link( 
					$html->image("duplicate.png", array('class' => 'duplicate_img', 'title'=>'Dupliquer')), 
					array('action' => 'duplicateProject', 
					$project['Project']['InProjId']), 
					array('escape' => false), 
					'Vous êtes sur le point de créer un projet à partir de celui-ci, voulez-vous continuer ?' ).
			'</div>'
        )
	)
    );
}
?>

</table>
<?php
// Pagination
echo $html->div(
  null,
  $paginator->prev(
    '<< Previous ',
    array(
      'class' => 'PrevPg','url'=>array($url)
    ),
    null,
    array(
      'class' => 'PrevPg DisabledPgLk'
    )
  ).
  $paginator->numbers(array('url'=>array($url))).
  $paginator->next(
    ' Next >>',
    array(
      'class' => 'NextPg','url'=>array($url)
    ),
    null,
    array(
      'class' => 'NextPg DisabledPgLk'
    )
  ),
  array(
    'style' => 'width: 100%;margin:8px 0;'
  )
);  

?>