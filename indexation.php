<?php

include 'traitement.php';
include'functions.php';


// Connexion base de donnée 
$conn = new mysqli('localhost', 'root', '','searchbdd');
if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}


$foldername='files';

if(isset($_POST['upload']))

{

 // echo ($_POST['upload']);
  //var_dump($_FILES['file']);
    
    if(!is_dir($foldername)) mkdir($foldername);
   
    foreach($_FILES['files']['name'] as $i => $name)
    {
     
          if(strlen($_FILES['files']['name'][$i]) > 1)
          {  move_uploaded_file($_FILES['files']['tmp_name'][$i],$foldername."/".$name);
          }
      }
  
 
    $nom = $_FILES['file']['tmp_name'];
    move_uploaded_file($nom, $foldername."/".$_FILES['file']['name']);

  
    
  
 
}

// Insertion BDD
if(isset($_POST["upload"]))
{
		
		readFiles('files');
	
}


// Supprimer tout BDD
if(isset($_POST["delete"]))
{
		
  clear_db($conn);
    clear_dir('files');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Indexation</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
 <link rel="stylesheet" href="css/main.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Signika&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
  
</style>
<body class="min-vh-100">
<h1 class="activee p-3 text-center  mt-3 ">Admin</h1>


<div class="container">
  



<div class="row">
  <div class="col-md-6 offset-md-3">
  <form action="#" method="post" enctype="multipart/form-data"> 
 
<div class="fileinputs pb-3 d-flex">
  <div class="mx-auto">
  <span class="lab_input ">Sélectionner un dossier </span><input class="mt-1 form-control rounded umploadbtn  "  type="file" name="files[]" id="files" multiple directory="" webkitdirectory="" moxdirectory="" /><br/>
 
 <span class="lab_input ">Sélectionner un fichier </span><input class="mt-1 form-control    umploadbtn rounded "  type="file" name="file" id="file" /><br/>

  </div>
</div> <div class="buttons d-flex">
 <div class=" mx-auto">
  <input type="submit"  class="shadow rounded btn  umploadbtn  btn btn-block  text-v"value="Indexer les fichiers" name="upload" />
  

	<input type="submit"  class="shadow rounded btn  umploadbtn  btn btn-block text-v "value="Supprimer l'index" name="delete" />
 
  </div> 
 </div>
</form>
  <h4 class="mt-5 text-center">Traces de l'indexation</h4>
  <div class="traces_index mt-3  p-3 shadow mb-5 bg-body rounded  border-opacity-10">
  <table class="table text-body">
  <thead>
    <tr>
      
      <th scope="col">Fichiers indexés</th>
      
      <th scope="col">Avant l'indexation</th>
      <th scope="col">Aprés l'indexation</th>
    </tr>
  </thead>
  <tbody>
 
      <?php

$conn = new mysqli('localhost', 'root', '', 'searchbdd');
$sql4=" select document, before_index,after_index from document";
$result = mysqli_query($conn, $sql4);

if (mysqli_num_rows($result)>0) {
  //var_dump($result);
 
while ($row = mysqli_fetch_assoc($result)) {
 
  echo '<tr><td>'.$row['document'].'</td>
  <td>'.$row['before_index'].' mots</td>
  <td>'.$row['after_index'].' mots</td> </tr>
  ';

}
}

      ?>
     
    
     
    </tr>
   
   
  </tbody>
</table>  



  

  </div>



  </div>
</div>



<br/>

</div>

</body>
</html>
<br/>
<br/>