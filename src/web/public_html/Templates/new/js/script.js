$(document).ready(function() {	
	newItemsCounter = 0;

  	$('#button-add-item_type').button();
  	$('#button-add-item_type').click(function() {
  		addNewItemType();
  	});

  	$('.button-remove').button();  	

  	$('#button-submit-template').button();
  	$('#button-submit-template').click(function() {
  		submitTemplate();
  	});

	loadItemTypesIntoSelect();
});

function submitTemplate() {
	var templateName = $('#input-name').val().trim();
	if(templateName == null || templateName == '') {
		$('#input-name').addClass("danger");
		alert("Template name is empty.");
	} else {
		$('#input-name').removeClass("danger");
		var templateData = {};
		templateData.name = templateName;
		templateData.notes = $('#input-notes').val().trim();
		templateData.defaultItems = [];
		var numbersOk = true;
		$.each($(".input-container-item_type"), function(i, value) {
			if($(this).is(":visible")) {
				var itemTypeId = $(this).find('select').val();
				var quantity = $(this).find('.input-quantity').val().trim();				
				if(!(quantity >= 0) && quantity != null && quantity != '') {
					numbersOk = false;
					$(this).find('.input-quantity').addClass("danger");					
				} else {
					$(this).find('.input-quantity').removeClass("danger");			
					templateData.defaultItems.push({itemTypeId: itemTypeId, quantity: quantity});
				}
			}			
		});
		if(!numbersOk) {
			alert("Each quantity has to be 0 or a positive number.");
			return;
		}

		$.ajax({
			type: "POST",
			url: "/sm/db/db_methods.php",
			data: {
				method: "insertStorageTemplate",
				data: templateData
			},
			success: function(result) {
				if(result.indexOf("success") > -1) {
					alert("Success!");
					location.reload();
				} else {
					var resultObj = $.parseJSON(result);
					if('error' in resultObj) {
						alert("Failed to submit template: " + resultObj.error);
					} else {
						console.log("What the heck happened??");
					}
				} 
			}
		});
	}
}

function addNewItemType() {
	newItemsCounter++;
	var clone = $('.input-container-item_type').last().clone();
	clone.appendTo($('#itemTypes-input-container'));
	clone.attr("id", "input-item_type-" + newItemsCounter);
	clone.find('.bootstrap-select').remove();
	clone.find('select').selectpicker();
	clone.selectpicker({
	    style: 'btn-default',
	    size: "4"
  	});

	clone.change(function () {
	    $(this).val($(this).val());
		$(this).selectpicker('refresh')
		var initialUnit = $('option:selected', this).attr('quantity_unit');
		var correspondingUnitLabel = $(this).find('#input-quantity_unit');		
		correspondingUnitLabel.attr("data-toggle", "tooltip");
		correspondingUnitLabel.attr("title", initialUnit);
		if(initialUnit.length > 5) {			
			initialUnit = initialUnit.substr(0, 5) + "...";			
		}
		correspondingUnitLabel.text(initialUnit);
	});
	clone.find('.button-remove').click(function() {
  		$(this).parent().parent().remove();
  	});
	clone.show();
}

function loadItemTypesIntoSelect() {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "loadItemTypesForSelect"
		},
		success: function(results) {
			rows = jQuery.parseJSON(results);
			itemTypesSelect = $('.select-item_type');
			for(var i in rows) {
				newOption = document.createElement("option");				
				newOption.innerHTML = rows[i].name;
				newOption.setAttribute("value", rows[i].item_type_id);
				newOption.setAttribute("quantity_unit", rows[i].quantity_unit);
				itemTypesSelect.append(newOption);
				itemTypesSelect.selectpicker('refresh');	
			}		
			if(rows.length > 1) {
				var initialUnit = rows[0]['quantity_unit'];
				var initialUnitContainer = $('.input-container-item_type #input-quantity_unit');
				initialUnitContainer.attr("data-toggle", "tooltip");
				initialUnitContainer.attr("title", initialUnit);
				if(initialUnit.length > 5) {
					initialUnit = initialUnit.substr(0, 5) + "...";
				}
				$('.input-container-item_type #input-quantity_unit').text(initialUnit);
			}
		}
	});
}