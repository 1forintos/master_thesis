<?php
	require_once "db_init.php";
	require_once "auth.php";

	authenticate();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "loadTable") {
			loadTable($_POST['tableName']);
		} else if($_POST['method'] == "loadStoragesForSelect") {			
			loadStoragesForSelect();
		} else if($_POST['method'] == "loadItemTypesForSelect") {			
			loadItemTypesForSelect();
		} else if($_POST['method'] == "loadTemplatesForSelect") {			
			loadTemplatesForSelect();
		} else if($_POST['method'] == "insertStorageTemplate") {
			insertStorageTemplate($_POST['data']);
		} else if($_POST['method'] == "insertItemType") {
			insertItemType($_POST['data']);
		} else if($_POST['method'] == "insertStorage") {
			insertStorage($_POST['data']);
		} else if($_POST['method'] == "insertStoredItem") {
			insertStoredItem($_POST['data']);
		} else if($_POST['method'] == "loadTableData") {
			loadTableData($_POST['data']);
		} else if($_POST['method'] == "updateItemType") {
			updateItemType($_POST['data']);
		} else if($_POST['method'] == "updateStoredItem") {
			updateStoredItem($_POST['data']);
		} else if($_POST['method'] == "updateStorageTemplateItem") {
			updateStorageTemplateItem($_POST['data']);
		} else if($_POST['method'] == "updateStorageTemplate") {
			updateStorageTemplate($_POST['data']);
		} else if($_POST['method'] == "deleteItemType") {
			deleteItemType($_POST['data']);
		} else if($_POST['method'] == "deleteStoredItem") {
			deleteStoredItem($_POST['data']);
		} else if($_POST['method'] == "deleteTemplateItem") {
			deleteTemplateItem($_POST['data']);
		} else if($_POST['method'] == "deleteTemplate") {
			deleteTemplate($_POST['data']);
		}
	}

	function loadTableData($tableInfo) {
		$query = null;
		if($tableInfo == "item_types") {
			$query = "
				SELECT *
				FROM ItemTypes
			";
		} elseif($tableInfo == "stored_items") {
			$query = "
				SELECT 
					SI.id AS id,
					Storages.name AS storage_name,
					IT.name AS item_name,
					SI.quantity AS quantity,
					IT.quantity_unit AS quantity_unit,
					SI.timestamp AS timestamp
				FROM 
					ItemTypes AS IT
					INNER JOIN StoredItems AS SI
						ON IT.item_type_id = SI.item_type_id
					INNER JOIN Storages
						ON SI.storage_id = Storages.storage_id
			";
		} elseif($tableInfo == "templates"){
			$query = "
				SELECT *
				FROM StorageTemplates
			";
		} elseif($tableInfo == 'template-items') {
			$query = "
				SELECT 
					STI.id AS id,
					StorageTemplates.name AS template_name,
					IT.name AS item_name,
					STI.quantity AS quantity,
					IT.quantity_unit AS quantity_unit,
					STI.timestamp AS timestamp
				FROM 
					ItemTypes AS IT
					INNER JOIN StorageTemplateItems AS STI
						ON IT.item_type_id = STI.item_type_id
					INNER JOIN StorageTemplates
						ON STI.template_id = StorageTemplates.template_id
			";
		}


		if(!$query) {
			throwError("Table not found.");
		}

		$ps = $GLOBALS['pdo']->prepare($query);
		
		$ps->execute();
		$ps->setFetchMode(PDO::FETCH_OBJ);
		$rows = $ps->fetchAll();
		$tableData = array();
		foreach($rows as $object) {
			$tableData[] = array();
			foreach($object as $key => $value) {
				$tableData[count($tableData) - 1][$key] = utf8_encode($value);
			}
		}
		$result = array(
			"status" => "success",
			"data" => $tableData
		);
		echo json_encode($result);
	}

	function deleteItemType($itemTypeId) {
		if(itemTypeUsed($itemTypeId)) {
			throwError("Item type is used in Storage(s).");
		}	

		$ps = $GLOBALS['pdo']->prepare("
			DELETE FROM ItemTypes			
			WHERE item_type_id=?			
		");	

		$success = $ps->execute(array($itemTypeId));
		if(!$success) {
			throwError("Failed to delete item type.");
		}
		echo "success";
	}
	
	function deleteTemplate($templateId) {
		$ps = $GLOBALS['pdo']->prepare("
			UPDATE Storages
			SET template_id = null
			WHERE template_id = ?
		");
		$ps->execute(array($templateId));

		$ps = $GLOBALS['pdo']->prepare("
			DELETE FROM StorageTemplateItems		
			WHERE template_id = ?			
		");			
		$ps->execute(array($templateId));

		$ps = $GLOBALS['pdo']->prepare("
			DELETE FROM StorageTemplates		
			WHERE template_id = ?			
		");			
		$ps->execute(array($templateId));

		if(!$success) {
			throwError("Failed to delete item type.");
		}
		echo "success";
	}
	
	function deleteTemplateItem($templateItemId) {
		$ps = $GLOBALS['pdo']->prepare("
			DELETE FROM StorageTemplateItems			
			WHERE id = ?			
		");	

		$success = $ps->execute(array($templateItemId));
		if(!$success) {
			throwError("Failed to delete item.");
		}
		echo "success";
	}

	function deleteStoredItem($storedItemId) {
		$ps = $GLOBALS['pdo']->prepare("
			DELETE FROM StoredItems			
			WHERE id = ?			
		");	

		$success = $ps->execute(array($storedItemId));
		if(!$success) {
			throwError("Failed to delete item.");
		}
		echo "success";
	}

	function updateStorageTemplate($templateData) {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT COUNT(*) AS template_exists 
			FROM StorageTemplates
			WHERE template_id != ?
				AND name = ?				
		");	
		$ps->execute(array($templateData['templateId'], $templateData['name']));
		$result = $ps->fetch();
		if($result['template_exists']) {
			throwError("Another template with the name [" . $templateData['name'] . "] already exists.");
		}		

		$ps = $GLOBALS['pdo']->prepare("
			UPDATE StorageTemplates
			SET name = ?, notes = ?, timestamp=CURRENT_TIMESTAMP
			WHERE template_id = ?			
		");	

		$success = $ps->execute(array($templateData['name'], $templateData['notes'], $templateData['templateId']));
		if(!$success) {
			throwError("Failed to insert item type.");
		}
		echo "success";
	}
	
	function updateStorageTemplateItem($itemData) {
		$ps = $GLOBALS['pdo']->prepare("
			UPDATE StorageTemplateItems
			SET quantity = ?, timestamp=CURRENT_TIMESTAMP
			WHERE id = ?			
		");

		$quantity = $itemData['quantity'] == null || $itemData['quantity'] == '' ? null : $itemData['quantity'];

		$success = $ps->execute(array($quantity, $itemData['templateItemId']));
		if(!$success) {
			throwError("Failed to insert item type.");
		}
		echo "success";
	}

	function updateStoredItem($itemData) {
		$ps = $GLOBALS['pdo']->prepare("
			UPDATE StoredItems
			SET quantity = ?, timestamp=CURRENT_TIMESTAMP
			WHERE id = ?			
		");

		$quantity = $itemData['quantity'] == null || $itemData['quantity'] == '' ? null : $itemData['quantity'];

		$success = $ps->execute(array($quantity, $itemData['storedItemId']));
		if(!$success) {
			throwError("Failed to insert item type.");
		}
		echo "success";
	}

	function updateItemType($itemData) {
		$ps = $GLOBALS['pdo']->prepare("
			UPDATE ItemTypes
			SET quantity_unit=?,notes=?,timestamp=CURRENT_TIMESTAMP
			WHERE item_type_id=?			
		");

		$quantityUnit = $itemData['quantityUnit'] == null || $itemData['quantityUnit'] == '' ? null : $itemData['quantityUnit'];

		$success = $ps->execute(array($quantityUnit, $itemData['notes'], $itemData['itemTypeId']));
		if(!$success) {
			throwError("Failed to insert item type.");
		}
		echo "success";
	}

	function insertStoredItem($itemData) {
		if(itemExists($itemData)) {
			throwError("This Item is already stored in this Storage.");
		}

		$ps = $GLOBALS['pdo']->prepare("
			INSERT INTO StoredItems(storage_id, item_type_id, quantity, owner_id) 
			VALUES (?, ?, ?, ?)			
		");

		$quantity = $itemData['quantity'] == null || $itemData['quantity'] == '' ? 0 : $itemData['quantity'];

		$success = $ps->execute(array($itemData['storageId'], $itemData['itemTypeId'], $quantity, $_SESSION['user_id']));
		if(!$success) {
			throwError("Failed to insert item type.");
		}
		echo "success";
	}

	function insertItemType($itemData) {
		if(itemTypeExists($itemData['name'])) {
			throwError("Item [" . $itemData['name'] . "] already exists.");
		}

		$ps = $GLOBALS['pdo']->prepare("
			INSERT INTO ItemTypes(name, notes, quantity_unit) 
			VALUES (?, ?, ?)			
		");

		$quantityUnit = $itemData['quantityUnit'] == null || $itemData['quantityUnit'] == '' ? 0 : $itemData['quantityUnit'];

		$success = $ps->execute(array($itemData['name'], $itemData['notes'], $quantityUnit));
		if(!$success) {
			throwError("Failed to insert item type.");
		}
		echo "success";
	}

	function insertStorage($storageData) {
		if(storageExists($storageData['name'])) {
			throwError("Storage [" . $storageData['name'] . "] already exists.");
		}

		$ps = $GLOBALS['pdo']->prepare("
			INSERT INTO Storages(name, location, notes, owner_id, template_id) 
			VALUES (?, ?, ?, ?, ?)			
		");

		$templateId = $storageData['templateId'] == null || $storageData['templateId'] == -1 ? null : $storageData['templateId'];

		$success = $ps->execute(array(
			$storageData['name'], 
			$storageData['location'], 
			$storageData['notes'], 
			$_SESSION['user_id'],
			$templateId
		));

		if(!$success) {
			throwError("Failed to insert storage.");
		}

		// if template set add items of the template
		if($templateId) {
			$ps = $GLOBALS['pdo']->prepare("
				SELECT storage_id
				FROM Storages
				WHERE name = ?
			");
			$ps->execute(array($storageData['name']));
			$result = $ps->fetch();
			$newStorageId = $result['storage_id'];

			$ps = $GLOBALS['pdo']->prepare("
				SELECT item_type_id, quantity
				FROM StorageTemplateItems
				WHERE template_id = ?
			");
			$ps->execute(array($templateId));
			$rows = $ps->fetchAll();
			foreach($rows as $row) {
				$ps = $GLOBALS['pdo']->prepare("
					INSERT INTO StoredItems(storage_id, item_type_id, owner_id, quantity)
					VALUES (?, ?, ?, ?)					
				");
				$ps->execute(array($newStorageId, $row['item_type_id'], $_SESSION['user_id'], $row['quantity']));
			}
		}

		echo "success";
	}

	function loadTemplatesForSelect() {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT 
				template_id, 
				name
			FROM StorageTemplates
		");

		$ps->execute();
		$result = $ps->fetchAll();
		$results = array();		
		$first = true;
		foreach($result as $row) {	
			$results[] = array();
			$i = count($results) - 1;
			$results[$i]['template_id'] = $row['template_id'];
			$results[$i]['name'] = utf8_encode($row['name']);			
		}		
		echo json_encode($results);		
	}

	function loadStoragesForSelect() {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT 
				storage_id, 
				name
			FROM Storages
		");

		$ps->execute();
		$result = $ps->fetchAll();
		$results = array();		
		$first = true;
		foreach($result as $row) {	
			$results[] = array();
			$i = count($results) - 1;
			$results[$i]['storage_id'] = $row['storage_id'];
			$results[$i]['name'] = utf8_encode($row['name']);			
		}				
		echo json_encode($results);
	}

	function loadItemTypesForSelect() {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT 
				item_type_id, 
				name, 
				quantity_unit
			FROM ItemTypes
		");

		$ps->execute();
		$result = $ps->fetchAll();
		$results = array();		
		$first = true;
		foreach($result as $row) {	
			$results[] = array();
			$i = count($results) - 1;
			$results[$i]['item_type_id'] = $row['item_type_id'];
			$results[$i]['name'] = utf8_encode($row['name']);
			$results[$i]['quantity_unit'] = utf8_encode($row['quantity_unit']);			
		}				
		echo json_encode($results);
	}

	function insertStorageTemplate($templateData) {
		if(templateExists($templateData['name'])) {
			throwError("Template [" . $templateData['name'] .  "] already exists.");
		} elseif(!uniqueItemTypes($templateData['defaultItems'])) {
			throwError("Item type selections have to be unique.");
		}

		$ps = $GLOBALS['pdo']->prepare("
			INSERT INTO StorageTemplates(name, notes)
			VALUES (?, ?)
		");

		$success = $ps->execute(array($templateData['name'], $templateData['notes']));
		if(!$success) {
			throwError("Failed to insert new template.");
		} 

		$ps = $GLOBALS['pdo']->prepare("
			SELECT template_id
			FROM StorageTemplates
			WHERE name = ?
		");
		$ps->execute(array($templateData['name']));
		$newTemplateId = $ps->fetch();
		$newTemplateId = $newTemplateId['template_id'];
		
		// insert items for template
		foreach($templateData['defaultItems'] as $item) {
			$ps = $GLOBALS['pdo']->prepare("
				INSERT INTO StorageTemplateItems(template_id, item_type_id, quantity)
				VALUES (?, ?, ?)
			");

			$quantity = $item['quantity'] == null || $item['quantity'] == '' ? 0 : $item['quantity'];
			$success = $ps->execute(array($newTemplateId, $item['itemTypeId'], $quantity));

			// rollback if failed
			if(!$success) {
				deleteTemplateInfoFromDatabase($newTemplateId);				
			}
		}	

		echo "success";	
	}

	function templateExists($templateName) {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT COUNT(*) > 0 AS table_exists
			FROM StorageTemplates
			WHERE name = ?
		");

		$ps->execute(array($templateName));
		$result = $ps->fetch();
		if($result['table_exists']) {
			return true;
		}
		return false;
	}

	function storageExists($storageName) {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT COUNT(*) > 0 AS storage_exists
			FROM Storages
			WHERE name = ?
		");

		$ps->execute(array($storageName));
		$result = $ps->fetch();
		if($result['storage_exists']) {
			return true;
		}
		return false;
	}

	function itemExists($itemData) {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT COUNT(*) > 0 AS item_exists
			FROM StoredItems
			WHERE storage_id = ?
				AND item_type_id = ?
		");
		
		$ps->execute(array($itemData['storageId'], $itemData['itemTypeId']));
		$result = $ps->fetch();
		if($result['item_exists']) {
			return true;
		}
		return false;
	}

	function itemTypeExists($itemName) {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT COUNT(*) > 0 AS item_exists
			FROM ItemTypes
			WHERE name = ?
		");

		$ps->execute(array($itemName));
		$result = $ps->fetch();
		if($result['item_exists']) {
			return true;
		}
		return false;
	}

	function itemTypeUsed($itemTypeId) {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT COUNT(*) > 0 AS item_type_used
			FROM StorageTemplateItems
			WHERE item_type_id = ?
		");

		$ps->execute(array($itemTypeId));
		$result = $ps->fetch();
		if($result['item_type_used']) {
			return true;
		}
		return false;
	}

	function throwError($msg) {
		$errorData = array(
			"error" => $msg
		);
		echo json_encode($errorData);
		exit;
	}

	function uniqueItemTypes($items) {
		$itemTypeIds = array();
		foreach($items as $item) {
			$itemTypeIds[] = $item['itemTypeId'];
		}		
	
		return count(array_unique($itemTypeIds)) == count($items);
	}

	function deleteTemplateInfoFromDatabase($templateId) {
		$ps = $GLOBALS['pdo']->prepare("
			DELETE FROM StorageTemplateItems
			WHERE template_id = ?
		");			

		$success = $ps->execute(array($templateId));
		if(!$success) {
			throwError("Failed to rollback insertion of template data with id: " . $templateId);
		}
		$ps = $GLOBALS['pdo']->prepare("
			DELETE FROM StorageTemplates
			WHERE template_id = ?
		");			

		$success = $ps->execute(array($templateId));

		if(!$success) {
			throwError("Failed to rollback insertion of template data with id: " . $templateId);
		}
	}
?>