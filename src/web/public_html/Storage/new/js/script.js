$(document).ready(function() {	 
  	$('#button-submit-storage').button();
  	$('#button-submit-storage').click(function() {
  		submitStorage();
  	});

  	loadTemplatesIntoSelect();
});

function submitStorage() {
	var storageName = $('#input-name').val().trim();
	var storageLocation = $('#input-location').val().trim();
	var storageNotes = $('#input-notes').val().trim();
	var storageTemplateId = $('#select-templates').val();
	var inputsOk = true;

	if(storageName == null || storageName == '') {
		$('#input-name').addClass("danger");
		inputsOk = false;
	} else {
		$('#input-name').removeClass("danger");
	}	
	if(storageLocation == null || storageLocation == '') {
		$('#input-location').addClass("danger");
		inputsOk = false;
	} else {
		$('#input-location').removeClass("danger");
	}
	
	if(!inputsOk) {
		alert("Both Name and Location of the the Storage have to be provided.");
		return;
	}
	
	var storageData = {};
	storageData.name = storageName;
	storageData.location = storageLocation;	
	storageData.notes = storageNotes;
	storageData.templateId = storageTemplateId;

	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "insertStorage",
			data: storageData
		},
		success: function(result) {
			console.log(result);
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to submit Storage: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			} 
		}
	});
}

function loadTemplatesIntoSelect() {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "loadTemplatesForSelect"
		},
		success: function(results) {
			rows = jQuery.parseJSON(results);
			templatesSelect = $('.select-templates');
			for(var i in rows) {
				newOption = document.createElement("option");				
				newOption.innerHTML = rows[i].name;
				newOption.setAttribute("value", rows[i].template_id);				
				templatesSelect.append(newOption);
				templatesSelect.selectpicker('refresh');	
			}					
		}
	});
}