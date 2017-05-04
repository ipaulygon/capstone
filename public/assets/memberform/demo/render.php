<?php

require 'readform.php';
require '../pages/connection.php';

session_start();
$blStatus = 1;

$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : FALSE;
unset($_SESSION['form_data']);

if( !$form_data ) {
	header( 'Location: /' ) ;
}
$loader = new formLoader($form_data, 'demo/submit.php');

$stmt = $conn -> query("SELECT strDynFieldName FROM tblDynamicField");
$qrDynFieldsRows = $stmt -> fetchAll();

$arrFieldNames = $loader->render_form();
$form_title = $loader->form_title();
$form_title = trim($form_title);
$blBlankField = false;
$arrFieldTemp = $arrFieldNames;

foreach ($arrFieldNames as $arrFieldName) {
	$arrFieldName = str_replace('_', ' ', strtolower($arrFieldName));
	$arrFieldName = trim($arrFieldName);

	//checking if there is any same field names
	$intCounter = 0;
	foreach ($arrFieldTemp as $strFieldTemp) {
		if($arrFieldName == $strFieldTemp){
			$intCounter++;
		}
	}

	if($intCounter > 1){
		$blBlankField = true;
		break;
	}

	if(empty($arrFieldName)){
		$blBlankField = true;
		break;
	}

	$strFieldTemp = $arrFieldName;
}

if($blBlankField || empty($form_title)){
	header("location:../pages/memberform.php?mes=1");
	$strError = 1;
} else{
	$conn->beginTransaction();
	try{
		$stmt1 = $conn -> prepare("INSERT INTO tblMemberForm(strMemFormTitle,strMemForm) 
										VALUES(:formtitle,:form)");
		$stmt1->bindParam(':formtitle',$form_title,PDO::PARAM_STR);
		$stmt1->bindParam(':form',$form_data,PDO::PARAM_STR);
		$stmt1->execute();

		$stmt2 = $conn -> prepare("UPDATE tblDynamicField SET blDynStatus = 0");
		$stmt2->execute();

		$stmt3 = $conn -> prepare("INSERT INTO tblDynamicField(strDynFieldName, blDynStatus) 
										VALUES(:fieldname, :blStatus)");

		foreach ($arrFieldNames as $strFieldname) {
			if($strFieldname == "first_name" || $strFieldname == "last_name" || $strFieldname == "email" || $strFieldname == "middle_name" || $strFieldname == "member_id"){
			} else {
				$blFieldExist = false;
				foreach ($qrDynFieldsRows as $qrDynFieldsRow) {
					if($strFieldname == $qrDynFieldsRow[0]){
						$blFieldExist = true;
						break;
					}
				}
				if($blFieldExist){
					$stmt4 = $conn -> prepare("UPDATE tblDynamicField SET blDynStatus = 1 WHERE strDynFieldname = :fieldname");
					$stmt4->bindParam(':fieldname',$strFieldname,PDO::PARAM_STR);
					$stmt4->execute();
				} else{
					$stmt3->bindParam(':fieldname',$strFieldname,PDO::PARAM_STR);
					$stmt3->bindParam(':blStatus',$blStatus,PDO::PARAM_STR);
					$stmt3->execute();
				}
			}
		}
		$conn->commit();
	}catch(PDOException $e){
		$conn->rollback();
		// echo "<h1>".$e->getMessage()."</h1>";
		$strError = 1;
	}
}

if(isset($strError)){
	header("location:../pages/memberform.php?mes=1");
	// echo "<h1>".$e->getMessage()."</h1>";
} else{
	$file = fopen('../pages/src/json/example.json', 'w+');
	fwrite($file, $form_data);
	fclose($file);
	header("location:../pages/addmember.php");
}
?>
