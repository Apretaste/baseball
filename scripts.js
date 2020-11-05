//
// list of provinces
//
var provinces = {
	PRI: 'Pinar del Río',
	ART: 'Artemisa',
	IND: 'Industriales',
	IJV: 'Isla de la Juventud',
	MAY: 'Mayabeque',
	MTZ: 'Matanzas',
	CFG: 'Cienfuegos',
	VCL: 'Villa Clara',
	SSP: 'Sancti Spíritus',
	CAV: 'Ciego de Avila',
	CMG: 'Camaguey',
	LTU: 'Las Tunas',
	HOL: 'Holguín',
	GRA: 'Granma',
	SCU: 'Santiago de Cuba',
	GTM: 'Guantánamo'};

$(document).ready(function() {
	//
	// start basic components
	//
	$('.tabs').tabs();
	$('.modal').modal();

	//
	// start check component
	//

	// checks/uncheck components
	$('.checks .check').click(function() {
		$('.checks .check').removeClass('active');
		$(this).addClass('active');
	})

	// get values of active "checks" components
	$.fn.value = function() {
		var values = [];
		$(this).find('.check').each(function(i, e){
			if($(e).hasClass('active')) {
				values.push($(e).attr('value'));
			}
		})

		return values;
	}
});

//
// search for a tag
//
function searchByTag() {
	// get tag selected
	var value = $('.checks').value();

	// send request
	apretaste.send({
		command: 'BASEBALL JUEGOS',
		data: {filter: value[0]}
	})
}
