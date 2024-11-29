<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Member List</title>	
</head>



<?php
// Start session 
if(!session_id()){ 
    session_start(); 
} 
 
// Load the database configuration file 
include_once 'db.php'; 
 
// Get status message 
if(!empty($_SESSION['response'])){ 
    $status = $_SESSION['response']['status']; 
    $statusMsg = $_SESSION['response']['msg']; 
    unset($_SESSION['response']); 
} 
?>

<!-- Display status message -->
<body>
<div class="container">
<div class="row">
		<div class="col-md-12">
			<div class="card">
			<div class="card-header">
			<?php if(!empty($statusMsg)){ ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
				</div>
			<?php } ?>
                        <h4>
                            Member Listing   
                        </h4>
                    </div>
				<div class="card-body">
				 <!-- Import link -->
    <div class="col-md-12 head">
        <div class="float-end">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
        </div>
    </div>
    <!-- CSV file upload form -->
    <div class="col-md-12 " id="importFrm" style="display: none;">
        <form action="importData.php" method="post" class="row g-2 float-end" enctype="multipart/form-data">
            <div class="col-auto">
                <input type="file" name="file" class="form-control" required/>

                <!-- Link to download sample format -->
                <p class="text-start mb-0 mt-2">
                    <a href="sample-csv-members.csv" class="link-primary" download>Download Sample Format</a>
                </p>
            </div>
            <div class="col-auto">
                <input type="submit" class="btn btn-success mb-3" name="importSubmit" value="Import CSV">
            </div>
        </form>
    </div>

				 <table class="table table-striped table-bordered mt-2">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        // Fetch member records from database 
        $result = $db->query("SELECT * FROM members ORDER BY id DESC"); 
        if($result->num_rows > 0){ 
            while($row = $result->fetch_assoc()){ 
        ?>
            <tr>
                <td><?php echo '#'.$row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['status'] == 1?'Active':'Inactive'; ?></td>
            </tr>
        <?php } }else{ ?>
            <tr><td colspan="5">No member(s) found...</td></tr>
        <?php } ?>
        </tbody>
    </table>
				 
				</div>
				
			</div>
		</div>
	</div>
	</div>
	</body>
	</html>
	
	
	
<!-- Show/hide CSV upload form -->
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
</script>
<!-- jQuery library -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>

<!-- Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>