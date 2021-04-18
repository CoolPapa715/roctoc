<?php
require_once(__DIR__ . '/inc/config.php');


if(trim($_POST['ajax_type']) =="get_comment"){
    
    $commet_list = array();

    $ads_id =  $_POST['ads_id'];
 
    
    $sql  = 'SELECT * FROM ads_comment left join users on cmt_maker = users.id  WHERE cmt_ads_id =:ads_id';
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':ads_id', $ads_id);
    $stmt->execute();
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   
        $cmt_id              = !empty($row['cmt_id'         ]) ? $row['cmt_id'         ] : '0';
    	$cmt_ads_id          = !empty($row['cmt_ads_id'     ]) ? $row['cmt_ads_id'     ] : '0';
    	$cmt_message         = !empty($row['cmt_message'    ]) ? $row['cmt_message'    ] : '';
        $first_name          = !empty($row['first_name'     ]) ? $row['first_name'     ] : '';
        $last_name           = !empty($row['last_name'      ]) ? $row['last_name'      ] : '';
    	$cmt_parent_id       = !empty($row['cmt_parent_id'  ]) ? $row['cmt_parent_id'  ] : '0';
        $cmt_date            = !empty($row['cmt_date'       ]) ? $row['cmt_date'       ] : '0';

		$commet_list[] = array(
			'cmt_id'         => $cmt_id,
			'cmt_ads_id'     => $cmt_ads_id,
			'cmt_message'    => $cmt_message,
			'first_name'     => $first_name,
			'last_name'      => $last_name,
			'cmt_parent_id'  => $cmt_parent_id,
			'cmt_date'       => $cmt_date

		);

    }
  
    echo json_encode($commet_list);
    

}


if(trim($_POST['ajax_type']) =="add_comment"){

    $ads_id          = !empty($_POST['ads_id']) ? trim($_POST['ads_id']) : '0';
    $comment_message = !empty($_POST['comment_message']) ? trim($_POST['comment_message']) : '';

//   try {
     
		$conn->beginTransaction();

		/*--------------------------------------------------
		Insert listing
		--------------------------------------------------*/
		$query = "INSERT INTO ads_comment(
			cmt_ads_id,
			cmt_message,
			cmt_maker
		
		)
		VALUES(
			:cmt_ads_id,
			:cmt_message,
			:cmt_maker
		)";
 
	    
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':cmt_ads_id'         , $ads_id);
		$stmt->bindValue(':cmt_message'        , $comment_message);
		$stmt->bindValue(':cmt_maker'          , $userid);
		
		$stmt->execute();


		/*--------------------------------------------------
		Commit
		--------------------------------------------------*/
		$conn->commit();
		$has_sucess = "true";

		echo $has_sucess;
// 	}

// 	catch(PDOException $e) {
// 		$conn->rollBack();
// 		$has_sucess = "false";
	

// 		echo $has_sucess;

// 	}


}
