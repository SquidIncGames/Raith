function renderPoint(label, points) {
	$(label).html(points + " points restants");
	if (points < 0)
		$(label).addClass("errors");
	else
		$(label).removeClass("errors");
}

//Mise à jour d'un compteur
function updatePoint(champ, label) {
	var maxPoints = $('#max-'+label.slice(1)).text();
	var points = maxPoints;
	$(champ).each(function () {
		points -= $(this).val();
	});
	renderPoint(label, points);
	return maxPoints - points;
}

//Fonction de mise à jour des compteurs de points
function updatePoints() {
	var points = $('#max-pts-maitrises').text() - updatePoint(".champ-maitrises-arme", "#pts-maitrises-armes") - updatePoint(".champ-maitrises-metier", "#pts-maitrises-metiers");
	renderPoint("#pts-maitrises", points);
};

function showInput(selector, item){
	//On fait apparaître le champ de niveau
	$("#" + item).parent().show();
	//On supprime l'option
	$(selector + " > option[value='" + item + "']").remove();
};

$(document).ready(function() {

	//Crée le label d'affichage dynamique des points de maitrises
	points = $('#max-pts-maitrises').text();
	$("#maitrises > h3").append("<label id='pts-maitrises'>" + points + " points restants</label>");
	//Crée le label d'affichage dynamique des points d'armes
	points = $('#max-pts-maitrises-armes').text();
	$("#maitrises-armes > h4").append("<label id='pts-maitrises-armes'>" + points + " points restants</label>");
	//Crée le label d'affichage dynamique des points de métiers
	points = $('#max-pts-maitrises-metiers').text();
	$("#maitrises-metiers > h4").append("<label id='pts-maitrises-metiers'>" + points + " points restants</label>");

	//Traitements relatifs aux sélections d'armes
	$("#select-maitrises-arme").on('change', function() { //FIXME: can't display last one
		var item = $(this).val();
		showInput("#select-maitrises-arme", item);
	}).show();
	//Traitements relatifs aux sélections de métiers
	$("#select-maitrises-metier").on('change', function () { //FIXME: can't display last one
		var item = $(this).val();
		showInput("#select-maitrises-metier", item);
	}).show();

	//Actualisation du compteur de points de maitrises restants
	$(".champ-maitrises-arme").on('change', function () {
		updatePoints();
	}).each(function () {
		if ($(this).val() == 0)
			$(this).parent().hide();
		else
			$("#select-maitrises-arme > option[value='" + this.id + "']").remove();
	});
	//Actualisation du compteur de points de métiers restants
	$(".champ-maitrises-metier").on('change', function () {
		updatePoints();
	}).each(function () {
		if ($(this).val() == 0)
			$(this).parent().hide();
		else
			$("#select-maitrises-metier > option[value='" + this.id + "']").remove();
	});
	
	updatePoints();

	//Désactive la validation s'il y a des erreurs
	$("form").on('submit', function(){
		if ($("#maitrises .errors").length > 0)
			return false;
	});

});
