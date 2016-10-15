$(document).ready(function() {
	$('.datepicker').datepicker();
	loadStoragesIntoSelect();
});

function loadStoragesIntoSelect() {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "loadStoragesForSelect"
		},
		success: function(results) {
			rows = jQuery.parseJSON(results);
			var selectSource = $('#select-source');
			var selectTarget = $('#select-target');
			for(var i in rows) {
				var newOption = document.createElement("option");				
				newOption.innerHTML = rows[i].name;
				newOption.setAttribute("value", rows[i].storage_id);				
				selectSource.append(newOption);
				selectSource.selectpicker('refresh');	
				var newOption2 = document.createElement("option");				
				newOption2.innerHTML = rows[i].name;
				newOption2.setAttribute("value", rows[i].storage_id);		
				selectTarget.append(newOption2);
				selectTarget.selectpicker('refresh');	
			};				
		}
	});
}
