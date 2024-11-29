<?php 
 
// Start session 
if(!session_id()){ 
    session_start(); 
} 
 
// Load the database configuration file 
include_once 'db.php'; 
 
$res_status = $res_msg = ''; 
if(isset($_POST['importSubmit'])){
    // Allowed mime types 
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'); 
     
    // Validate whether selected file is a CSV file 
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){  
         
        // If the file is uploaded 
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
             
            // Open uploaded CSV file with read-only mode 
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r'); 
             
            // Skip the first line 
            fgetcsv($csvFile); 
             
            // Parse data from CSV file line by line 
            while(($line = fgetcsv($csvFile)) !== FALSE){ 
                $line_arr = !empty($line)?array_filter($line):''; 
                if(!empty($line_arr)){ 
                    // Get row data 
                    $name   = trim($line_arr[0]); 
                    $email  = trim($line_arr[1]); 
                    $phone  = trim($line_arr[2]); 
                    $status = trim($line_arr[3]); 
                     
                    // Check whether member already exists in the database with the same email 
                    $prevQuery = "SELECT id FROM members WHERE email = '".$email."'"; 
                    $prevResult = $db->query($prevQuery); 
                     
                    if($prevResult->num_rows > 0){ 
                        // Update member data in the database 
                        $db->query("UPDATE members SET name = '".$name."', phone = '".$phone."', status = '".$status."', modified = NOW() WHERE email = '".$email."'"); 
                    }else{
                        // Insert member data in the database 
                        $db->query("INSERT INTO members (name, email, phone, created, modified, status) VALUES ('".$name."', '".$email."', '".$phone."', NOW(), NOW(), '".$status."')"); 
                    } 
                } 
            } 
             
            // Close opened CSV file 
            fclose($csvFile); 
             
            $res_status = 'success'; 
            $res_msg = 'Members data has been imported successfully.'; 
        }else{ 
            $res_status = 'danger'; 
            $res_msg = 'Something went wrong, please try again.'; 
        } 
    }else{ 
        $res_status = 'danger'; 
        $res_msg = 'Please select a valid CSV file.'; 
    } 
 
    // Store status in SESSION 
    $_SESSION['response'] = array( 
        'status' => $res_status, 
        'msg' => $res_msg 
    ); 
} 
 
// Redirect to the listing page 
header("Location: index.php"); 
exit(); 
 
?>