<?php //echo $paginator->numbers(); ?>
<?php //echo $javascript->link('/jquery.min', false); ?>
<table border="1">
	<tr>
		<th>ID</th>
		<th>Nom</th>
		<th>Prenom</th>

	</tr>

	<?php foreach($contacts as $contact): ?>

		<tr class="contenulist">
			<td><?php echo $contact['Contact']['ID']; ?></td>
			<td><?php echo $contact['Contact']['nom']; ?></td>
			<td><?php echo $contact['Contact']['prenom']; ?></td>
			<td>
				<?php
					echo $html->link($html->image('edit.png'), 
									array (	'action' => 'edit', 
											$contact['Contact']['ID']),
									array(	'escape' => false) );
				?>
				<?php
					echo $html->link($html->image('delete.gif'),
									array(	'action' => 'delete',
											$contact['Contact']['ID']),
									array(	'escape' => false),
									"Etes-vous sûr de vouloir supprimer ce contact?"	);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>

<?php echo $html->link('Ajouter un projet', array('controller' => 'Contacts', 'action' => 'add')) ?>