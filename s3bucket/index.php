<?php 
// Include the AWS SDK autoloader 
require 'vendor/autoload.php'; 
use Aws\S3\S3Client; 
 
// Amazon S3 API credentials 
$region = 'eu-central-1'; 
$version = 'latest'; 
$access_key_id = 'XIJJ4ANWBFETEJNHTY0U'; 
$secret_access_key = 'foiCf3eRXlGnuBnWjxLBG4j8niZubt7ZLk8BfSFX'; 
$bucket = 'crm-test'; 
 
 
$statusMsg = ''; 
$status = 'danger'; 
 
// If file upload form is submitted 
if(isset($_POST["submit"])){ 
    // Check whether user inputs are empty 
    if(!empty($_FILES["userfile"]["name"])) { 
        // File info 
        $file_name = basename($_FILES["userfile"]["name"]); 
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('pdf','doc','docx','xls','xlsx','jpg','png','jpeg','gif'); 
        if(in_array($file_type, $allowTypes)){ 
            // File temp source 
            $file_temp_src = $_FILES["userfile"]["tmp_name"]; 
             
            if(is_uploaded_file($file_temp_src)){ 
                // Instantiate an Amazon S3 client 
                $s3 = new S3Client([ 
                    'version' => $version, 
                    'region'  => $region, 
                    'credentials' => [ 
                        'key'    => $access_key_id, 
                        'secret' => $secret_access_key, 
                    ] 
                ]); 
 
                // Upload file to S3 bucket 
                try { 
                    $result = $s3->putObject([ 
                        'Bucket' => $bucket, 
                        'Key'    => $file_name, 
                        'ACL'    => 'public-read', 
                        'SourceFile' => $file_temp_src 
                    ]); 
                    $result_arr = $result->toArray(); 
                     
                    if(!empty($result_arr['ObjectURL'])) { 
                        $s3_file_link = $result_arr['ObjectURL']; 
                    } else { 
                        $api_error = 'Upload Failed! S3 Object URL not found.'; 
                    } 
                } catch (Aws\S3\Exception\S3Exception $e) { 
                    $api_error = $e->getMessage(); 
                } 
                 
                if(empty($api_error)){ 
                    $status = 'success'; 
                    $statusMsg = "File was uploaded to the S3 bucket successfully!"; 
                }else{ 
                    $statusMsg = $api_error; 
                } 
            }else{ 
                $statusMsg = "File upload failed!"; 
            } 
        }else{ 
            $statusMsg = 'Sorry, only Word/Excel/Image files are allowed to upload.'; 
        } 
    }else{ 
        $statusMsg = 'Please select a file to upload.'; 
    } 
} 
?>
<form method="post" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label><b>Select File:</b></label>
        <input type="file" name="userfile" class="form-control" required>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="submit" value="Upload">
    </div>
</form>