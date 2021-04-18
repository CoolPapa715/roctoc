<?php
// prevent direct access
if (!isset($version)) {
	http_response_code(403);
	exit;
}

/*--------------------------------------------------
check v.3.12 update
--------------------------------------------------*/

// if 'rel_favorites' table doesn't exist, update to v.3.12
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'rel_favorites')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.12_update.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
check v.3.13 update
--------------------------------------------------*/

// if 'plan_types' table doesn't exist, update to v.3.13
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'plan_types')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.13_update.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

/*--------------------------------------------------
check v.3.21 update
--------------------------------------------------*/

// if 'language' table doesn't exist, update to v.3.21
$query = "SELECT count(*) AS c FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$db_name') AND (TABLE_NAME = 'language')";
$stmt  = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if($row['c'] < 1) {
	$sql = file_get_contents('sql/directoryplus_3.21_update.sql');

	$sql = explode(";\n", $sql);

	try {

		// begin transaction
		$conn->beginTransaction();

		foreach($sql as $k => $v) {
			try {
				$v = trim($v);

				if(!empty($v)) {
					$stmt = $conn->prepare($v);
					$stmt->execute();
				}
			}

			catch (PDOException $e) {
				echo $e->getMessage();
				die();
			}
		}

		// commit
		$conn->commit();
	}

	catch(PDOException $e) {
		$conn->rollBack();
		$result_message =  $e->getMessage();

		echo $result_message;
	}
}

