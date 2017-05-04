<?php
require '../pages/connection.php';
require '../pages/generatecode.php';

$strPassCode = fnGenerateCode();

if(empty($_POST['member_id']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['first_name'])){
	//error page here.
	echo "fill up all";

} else{
	$strMemberID= $_POST['member_id'];
	$strLastName= $_POST['last_name'];
	$strMiddleName= $_POST['middle_name'];
	$strFirstName= $_POST['first_name'];
	$strEmail= $_POST['email'];
}

$conn->beginTransaction();
try{
	$stmt2 = $conn -> prepare("INSERT INTO tblMember(strMemberID,strMemFname,strMemMname,
								strMemLname,strMemEmail,strMemPasscode) 
								VALUES(:memberID,:firstname,:middlename,:lastname,:email,:passcode)");
	$stmt2->bindParam(':memberID',$strMemberID,PDO::PARAM_STR);
	$stmt2->bindParam(':firstname',$strFirstName,PDO::PARAM_STR);
	$stmt2->bindParam(':middlename',$strMiddleName,PDO::PARAM_STR);
	$stmt2->bindParam(':lastname',$strLastName,PDO::PARAM_STR);
	$stmt2->bindParam(':email',$strEmail,PDO::PARAM_STR);
	$stmt2->bindParam(':passcode',$strPassCode,PDO::PARAM_STR);
	$stmt2->execute();

	$stmt3 = $conn -> prepare("INSERT INTO tblMemberDetail(strMemDeMemId,strMemDeFieldName,
								strMemDeFieldData) VALUES(:memberID,:fieldName,:fieldData)");

	foreach ($_POST as $key => $value) {
		if($key == "member_id" || $key == "last_name" || $key == "first_name" || $key == "email" || $key =="middle_name"){
		} else {
			$strFieldName = $key;
			$strFieldData = $value;
			$stmt3->bindParam(':memberID',$strMemberID,PDO::PARAM_STR);
			$stmt3->bindParam(':fieldName',$strFieldName,PDO::PARAM_STR);
			$stmt3->bindParam(':fieldData',$strFieldData,PDO::PARAM_STR);
			$stmt3->execute();
		}
	}
	$conn->commit();
}catch(PDOException $e){
	$conn->rollback();
	echo "<h1>".$e->getMessage()."</h1>";
	$strError = 1;
}
if(isset($strError)){
	header("location:../pages/member.php?mes=1");
} else{
	header("location:../pages/member.php?mes=2");
}
