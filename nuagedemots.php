<!DOCTYPE html>
<html>
	<head>
		<title > Nuage des mots</title>
		<link rel="stylesheet" href="css/style.css" type="text/css"	media="screen">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link
		<script src="js/tagcloud.jquery.min.js"></script>
		<script type="text/javascript" src="js/tagcloud.jquery.js"></script>
		
	</head>
	<body  class="min-vh-100" style="background:   linear-gradient(to top, #8e2de2, #4a00e0);">
		
		
		
		<?php
			function selectForTag(){
			//connexion et selection du nuage de mots
			//error_reporting(E_ALL ^ E_DEPRECATED);
            $conn = new mysqli('localhost', 'root', '','searchbdd');
           
			
			$tab = array();
			$sql ="SELECT mot.mot, mot_document.poids
					FROM mot
						INNER JOIN mot_document
						ON mot.id = mot_document.id_mot
						WHERE mot_document.poids > 1 ORDER BY RAND()
						DESC LIMIT 100";
			
			$resultat =   mysqli_query($conn, $sql);	
			if (mysqli_num_rows($resultat) > 0) {
			
			while ( $row = mysqli_fetch_assoc( $resultat) ) {
				$tab [] = $row ['mot'];
			}
        }
			$tab = array_flip ( $tab );
			
			return $tab;
			}
			$tab_tag = selectForTag();
			
		?>
		
      <div class="nuage">
		<h1 class=" text-center text-light">Nuage de mots</h1>
		
      
      <div class="row">
      <div class=" col-md-8 offset-md-2">
		
        <div id ="_tag"  class ="m-auto bg-light p-4"  >
        <?php //$tab = genererNuage($tab_tag);
				$colors = array("#F0F8FF", "#F0FFFF", "#FFFAFA", "#40E0D0", "#98FB98");
				
					$tab_tag = array_flip($tab_tag);
					foreach ($tab_tag as $tag){
						$tab_colors = array ("#45a247", "#ad5389", "#2ebf91", "#f12711", "#f953c6", "#4A00E0");
						$color = rand ( 0, count ( $tab_colors ) - 1 );
						echo '<a class="text-decoration-none" style=" color:' . $tab_colors [$color] . '; " title="Rechercher le tag ' . $tag . ' " href="index.php ">'.$tag.' </a>';
						}

				
				?>
                                                                                                       
	
            </div>  
        </div>

      </div>
	  </div>
        </body>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</html>