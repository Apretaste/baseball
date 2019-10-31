function formatDate(dateStr) {
  var date = new Date(dateStr);
  var year = date.getFullYear();
  var month = (1 + date.getMonth()).toString().padStart(2, '0');
  var day = date.getDate().toString().padStart(2, '0');
  return day + '/' + month + '/' + year;
}

function tomorrowDate(){
	var date = new Date(Date.now());
	date.setDate(date.getDate()+1);
	return date;
}


$(function(){
	/*$(".datatable").DataTable({
		scrollY:        200,
		deferRender:    true,
		scroller:       true
	});*/
});
