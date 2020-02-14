<?php

$action = getopt(null, ["action:", "id:", "name:"]);

switch ($action["action"]) {
	case 'add':
		echo 'Execute add';
		actionAdd();
		break;
	case 'edit':
		actionEdit(rtrim($action["id"]));
		break;
	case 'delete':
		actionDelete(rtrim($action["id"]));
		break;
	case 'search':
		actionSearch(rtrim($action["name"]));
		break;

	default:
		echo 'Execute not there';
}

function actionAdd()
{
	echo "Enter your Student ID: ";
	$stdID = rtrim(fgets(fopen("php://stdin", "r")));

	echo "Enter your Name: ";
	$name = rtrim(fgets(fopen("php://stdin", "r")));

	echo "Enter your Surname: ";
	$surname = rtrim(fgets(fopen("php://stdin", "r")));

	echo "Enter your Age: ";
	$Age = rtrim(fgets(fopen("php://stdin", "r")));

	echo "Enter your Curriculum: ";
	$Curriculum = rtrim(fgets(fopen("php://stdin", "r")));

	createJSONDoc($stdID, $name, $surname, $Age, $Curriculum);
	addStudent($stdID, $name, $surname, $Age, $Curriculum);
}
function actionEdit($num1)
{

	$stdID = rtrim($num1);

	echo "Enter your Name: ";
	$name = rtrim(fgets(fopen("php://stdin", "r")));

	echo "Enter your Surname: ";
	$surname = rtrim(fgets(fopen("php://stdin", "r")));

	echo "Enter your Age: ";
	$Age = rtrim(fgets(fopen("php://stdin", "r")));

	echo "Enter your Curriculum: ";
	$Curriculum = rtrim(fgets(fopen("php://stdin", "r")));

	dbEdit($stdID, $name, $surname, $Age, $Curriculum);
}
function actionDelete($numID)
{
	$sql = "DELETE FROM studenttable WHERE StudentID = '" . $numID . "';";

	if (dbcon()->query($sql)) {
		echo "The User has been successfully removed from the database!!";
	} else {
		echo "The User has not yet been removed!";
	}
}
function actionSearch($name)
{
	$sqlDs = "SELECT * FROM studenttable where FirstName ='" . $name . "'";
	$sqlAll = "SELECT * FROM studenttable";
	$result = "";

	if (isset($name)) {
		$result = dbcon()->query($sqlDs);
	} else {
		$result = dbcon()->query($sqlAll);
	}

	if ($result->num_rows > 0) {
		// output data of each row
		while ($row = $result->fetch_assoc()) {
			echo "Name: " . $row["FirstName"] . " - Surname: " . $row["LastName"] . " - Age: " . $row["Age"] . " - Curriculum: " . $row["Curriculum"] . "\n";
		}
	} else {
		echo "0 results";
	}
}

function dbcon()
{
	$mysqli = new mysqli("localhost", "root", "", "BidVest");

	return $mysqli;
}
function addStudent($IDa, $stdNamea, $stdSurnamea, $stdAgea, $stdcuria)
{
	if (dbcon()->query("INSERT INTO studenttable
					(
					StudentID,
					LastName,
					FirstName,
					Age,
					Curriculum)
					VALUES
					(
					'" . $IDa . "',
					'" . $stdNamea . "',
					'" . $stdSurnamea . "',
					'" . $stdAgea . "',
					'" . $stdcuria . "');") == TRUE) {
		echo "The User has been added successfully!!";
	} else {

		echo "The User has not been inserted!";
	}
}
function createJSONDoc($ID, $stdName, $stdSurname, $stdAge, $stdcuri)
{

	$stdJsonArray = array('ID' => $ID, 'Name' => $stdName, 'Surname' => $stdSurname, 'Age' => $stdAge, 'Curriculum' => $stdcuri);

	$url = "project_dir\\students\\" . $ID . ".json";

	$fp = fopen($url, 'c');
	fwrite($fp, json_encode($stdJsonArray));
	fclose($fp);
}
function dbEdit($ID, $stdName, $stdSurname, $stdAge, $stdcuri)
{

	$sqli = "UPDATE studenttable
		SET
		LastName = '" . $stdSurname . "',
		FirstName = '" . $stdName . "',
		Age = '" . $stdAge . "',
		Curriculum = '" . $stdcuri . "'
		WHERE StudentID = '" . $ID . "'";

	if (dbcon()->query($sqli) == TRUE) {
		echo "UPDATED SUCESS";
	} else {
		echo "Has not updated!";
	}
}
