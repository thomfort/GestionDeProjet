jQuery(document).ready(function() {
	onSelectChangeClient();
	jQuery("form#ProjectAddForm #ProjectDescr").focus();
	jQuery("#ContactNom").focus();
	// Liste
	jQuery("tr:odd").css({'background-color':'#ebebeb'});
	jQuery("div.input:odd").css({'background-color':'#ebebeb'});
	
	jQuery('img.statut_img').wrap('<div align=center></div>');
	jQuery('img.statut_img').wrap('<div align=center></div>');
	
	// Formulaire
	//$("div.input:odd").css("background-color", "#ebebeb");
	jQuery("table.contenulist tbody tr").hover(function() {
		jQuery(this).addClass("mouseon");
	},
	function() {
   	 	jQuery(this).removeClass("mouseon");
	});
	
	// Calendar
	jQuery("#ProjectBegDate").datepicker({
		dateFormat: 'yy-mm-dd',
		defaultDate: 'null'
	});
	jQuery("#ProjectEndDate").datepicker({
		dateFormat: 'yy-mm-dd',
		defaultDate: 'null'
	});
	jQuery(function($){
		//VIA
		$("input#ProjectX2631824CodeBudget").mask("9999-MK9999");
		//SMART
		$("input#ProjectX2631824CodeSmart").mask("9999?-9999");
	});
	
	// Autogenerate code budg MS/VIA
	var client = jQuery("#clients").change(onSelectChangeClient);	
	
});

function onSelectChangeClient() {
		var selected = jQuery("#clients option:selected").text();
		
		var i = selected.lastIndexOf("-")+2;
		var timeunique = new Date().getTime();
		
		timeunique = timeunique.toString();
		timeunique = timeunique.substr(5,9);
		
		selected = selected.substr(i,6);
		selected = selected.toUpperCase();
		
		var codebudgetms = selected+'-'+timeunique;
		//alert(selected+timeunique);
		// VIA & MS
		jQuery("#ProjectProjId").val(codebudgetms);
		// VIA only
		//jQuery("#ProjectX2631824CodeBudget").val(codebudgetms);

	}