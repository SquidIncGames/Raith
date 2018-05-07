
//Fonction de mise à jour des compteurs de points
function updatePoints(selector, points){
	var champ = false;

	if (points == 30)
		champ = "#ptsMaitrises";
	else if (points == 15)
		champ = "#ptsMetiers";
	else
		console.log("Erreur : usage invalide de la fonction updatePoints");

	if (champ != false){
		$(selector).each(function(){ points -= $(this).val(); });
		$(champ).html(points + " points restants");

		//Avertissement visuel en cas d'excès de points
		if (points < 0)
			$(champ).addClass("errors");
		else
			$(champ).removeClass("errors");
	}
};

function showInput(selector, item){
	//On fait apparaître le champ de niveau
	$("#" + item).parent().show();
	//On supprime l'option
	$(selector + " > option[value='" + item + "']").remove();
};

$(document).ready(function() {

	//Crée le label d'affichage dynamique des points de maitrises
	points = 30;
	$("#maitrises > h3").append("<label id='ptsMaitrises'>" + points + " points restants</label>");
	//Crée le label d'affichage dynamique des points de métiers
	points = 15;
	$("#metiers > h3").append("<label id='ptsMetiers'>" + points + " points restants</label>");

	//Traitements relatifs aux sélections de maîtrises
	$("#select-maitrise").on('change', function() {
		var item = $(this).val();
		console.log(item);
		showInput("#select-maitrise", item);
	}).show();

	//Traitements relatifs aux sélections de métiers
	$("#select-metier").on('change', function() {
		var item = $(this).val();
		showInput("#select-metier", item);
	}).show();

	//Actualisation du compteur de points de maitrises restants
	$(".champ-maitrise").on('change', function () {
		updatePoints(".champ-maitrise", 30);
	}).parent().hide();
	//Actualisation du compteur de points de métiers restants
	$(".champ-metier").on('change', function () {
		updatePoints(".champ-metier", 15);
	}).parent().hide();

	//Désactive la validation s'il y a des erreurs
	$("input, select, textarea").on('change', function(){

		if ($(".warning").length > 0)
			$("#submitCharForm").prop("disabled", true);
		else
			$("#submitCharForm").prop("disabled", false);

	});

});
