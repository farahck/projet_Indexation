<?php

function eliminate_separators($text){
    $separators= " \n1234567890/[]|_@-$%&;§-<>,«.»#=:\'\"()!?{}";
   
   
    $tab_mots_vides=charger_tab_mots_vides("mots_vides.txt");
    $no_sep=strtok($text,$separators);
    $tab=[];
    while ($no_sep !== false)
  {
  //echo $no_sep." dddd<br>";
  $no_sep = strtok($separators);
  
  if ((strlen ( $no_sep ) > 2) && ! (in_array ( $no_sep, $tab_mots_vides ))) {
              
    $tab [] = $no_sep;
    
  }
  
  
  }
  
  
  return $tab;
  }
  
  function charger_tab_mots_vides($fichier) {
      // $tableau_mots_vides = file($fichier) ;
      $fileop = fopen ( $fichier, "r" );
      while ( ! feof ( $fileop ) ) {
          $tableau_mots_vides [] = trim ( fgets ( $fileop ) );
      }
      fclose ( $fileop );
      
      return $tableau_mots_vides;
  }
  
  function insert_in_db($words,$file,$title,$description,$before_index,$after_index){
   // var_dump($words);
   //echo $file;
    $title=remove_accents($title);
    $description=remove_accents($description);
  //echo $file;
    $conn = new mysqli('localhost', 'root', '','searchbdd');
    if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
   }
   //echo 'succes';
  
   //$sql = "INSERT INTO document (document,titre,description) VALUES('$file','$title','$description')";
   $sql = "INSERT INTO document (document, titre, description,before_index,after_index) VALUES ('$file', '$title' , '$description','$before_index',$after_index)";
  
          mysqli_query($conn, $sql);
         //echo $res;	
      
          $id_document = mysqli_insert_id($conn);
         // echo $id_document;
  
  foreach ($words as $word=>$occ ) {
 
  // on teste si le mot existe dans la table : Select 
  $sql2 = "SELECT * FROM mot WHERE mot ='$word'";
  $result = mysqli_query($conn, $sql2);
  //var_dump($result);
  // si existe on recupere id
  if (mysqli_num_rows($result)==1) {
    $ligne = mysqli_fetch_row($result);
          $id_mot = $ligne[0];
   
  }
  else{ 
    
    $word=remove_accents($word);  
                      // insertion mot dans la table mot	
                      $sql3 = "INSERT INTO mot (mot) VALUES ('$word')";
                      mysqli_query($conn, $sql3);
            $id_mot = mysqli_insert_id($conn);
                  
  
          
  }
   //mise relation ID_mot avec id_document et le poids
  $sql4 = "INSERT INTO mot_document (id_mot,id_document,poids) VALUES ($id_mot,$id_document,$occ)";
  mysqli_query($conn, $sql4);
  
  
  
  
  }
  
  $conn->close();
  
  
  
  }
  
  function getTitle($ch){
    $modele_title="/<title>(.*)<\/title>/i";
    preg_match($modele_title,$ch,$res);
    //verifier si le resultat != false
    if ($res) {
      //echo "hhhh";
      //print_r($res[1]);
          return $res[1];
      
    }
   
    else return "";
  
  
  
  }
  
  
  function get_description($source){
      
    $table_metas = get_meta_tags($source);
    $desc = isset( $table_metas['description']);
    if($desc )
       return $table_metas['description'];
    else return  "";
  
  }
  
  
  //-------------------------------------------
  function get_keywords($source){
  
    $table_metas = get_meta_tags($source);
    $keyw=isset($table_metas['keywords']);
  
    if($keyw)
  
    
       return $table_metas['keywords'];
    
  
    else return "";
  
  }
  
  function poids($occs, $coef){
      
    $tab_poids = array();
    foreach ( $occs as $mot => $occ)
  {
        $poid = $occ * $coef;
  
  $tab_poids[$mot] = $poid;
  
  }
  return $tab_poids;
  }
  
  
  function get_body($chaine_html){
    $modele = "/<body[^>]*>(.*)<\/body>/is";
  
    preg_match($modele,$chaine_html,$tableau_res);
  
    if($tableau_res)
   
       return $tableau_res[1];
    else return "";
                
  }
  
  
  
  
  
  // suppression du javascript dans l'html
  function  strip_javascript ($chaine_html ) {
      $modele_balises_scripts = '/<[^>]*?script[^>]*?>.*?<\/script>/is' ;
      
   
      $html_sans_script = preg_replace ( $modele_balises_scripts, '', $chaine_html );
      
      return $html_sans_script;
  }
  
  
  function  fusionner ( $tab_head , $tab_body ) {
      if (count ( $tab_head ) > count ( $tab_body )) {
          // element du head superieurà elements dubody 
          $tab_grand = $tab_head;
          $tab_petit = $tab_body ;
      } else {
           // element du body superieurà elements du head 
          $tab_petit = $tab_head ;
          $tab_grand = $tab_body ;
      }
      
      foreach ( $tab_petit  as  $mot => $valeur ) {
          if (array_key_exists ( $mot , $tab_grand )) {
           // echo 'yes';
              // si le mot est dans le 2ème
              // additionne les valeurs
              $tab_grand [ $mot ] =$tab_grand [ $mot ]+ $valeur;
          } else {
           // echo 'no';
              // rajouter l'élément au 2ème tableau
              $tab_grand [ $mot ]= $valeur ;
          }
      }
      return  $tab_grand ;
     
  }
  function remove_accents($chaine) {
    $transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'a' => 'a', 'A' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'a' => 'a', 'A' => 'A', 'a' => 'a', 'A' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', '?' => 'b', '?' => 'B', 'c' => 'c', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'ç' => 'c', 'Ç' => 'C', 'd' => 'd', 'D' => 'D', '?' => 'd', '?' => 'D', 'd' => 'd', 'Ð' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'e' => 'e', 'E' => 'E', 'ê' => 'e', 'Ê' => 'E', 'e' => 'e', 'E' => 'E', 'ë' => 'e', 'Ë' => 'E', 'e' => 'e', 'E' => 'E', 'e' => 'e', 'E' => 'E', 'e' => 'e', 'E' => 'E', '?' => 'f', '?' => 'F', 'ƒ' => 'f', 'ƒ' => 'F', 'g' => 'g', 'G' => 'G', 'g' => 'g', 'G' => 'G', 'g' => 'g', 'G' => 'G', 'g' => 'g', 'G' => 'G', 'h' => 'h', 'H' => 'H', 'h' => 'h', 'H' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'i' => 'i', 'I' => 'I', 'i' => 'i', 'I' => 'I', 'i' => 'i', 'I' => 'I', 'j' => 'j', 'J' => 'J', 'k' => 'k', 'K' => 'K', 'l' => 'l', 'L' => 'L', 'l' => 'l', 'L' => 'L', 'l' => 'l', 'L' => 'L', 'l' => 'l', 'L' => 'L', '?' => 'm', '?' => 'M', 'n' => 'n', 'N' => 'N', 'n' => 'n', 'N' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'n' => 'n', 'N' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'o' => 'o', 'O' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'o' => 'o', 'O' => 'O', 'o' => 'o', 'O' => 'O', 'ö' => 'oe', 'Ö' => 'OE', '?' => 'p', '?' => 'P', 'r' => 'r', 'R' => 'R', 'r' => 'r', 'R' => 'R', 'r' => 'r', 'R' => 'R', 's' => 's', 'S' => 'S', 's' => 's', 'S' => 'S', 'š' => 's', 'Š' => 'S', '?' => 's', '?' => 'S', 's' => 's', 'S' => 'S', '?' => 's', '?' => 'S', 'ß' => 'SS', 't' => 't', 'T' => 'T', '?' => 't', '?' => 'T', 't' => 't', 'T' => 'T', '?' => 't', '?' => 'T', 't' => 't', 'T' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'u' => 'u', 'U' => 'U', 'û' => 'u', 'Û' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'ü' => 'ue', 'Ü' => 'UE', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', 'w' => 'w', 'W' => 'W', '?' => 'w', '?' => 'W', 'ý' => 'y', 'Ý' => 'Y', '?' => 'y', '?' => 'Y', 'y' => 'y', 'Y' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'z' => 'z', 'Z' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'z' => 'z', 'Z' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', '?' => 'a', '?' => 'a', '?' => 'b', '?' => 'b', '?' => 'v', '?' => 'v', '?' => 'g', '?' => 'g', '?' => 'd', '?' => 'd', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'zh', '?' => 'zh', '?' => 'z', '?' => 'z', '?' => 'i', '?' => 'i', '?' => 'j', '?' => 'j', '?' => 'k', '?' => 'k', '?' => 'l', '?' => 'l', '?' => 'm', '?' => 'm', '?' => 'n', '?' => 'n', '?' => 'o', '?' => 'o', '?' => 'p', '?' => 'p', '?' => 'r', '?' => 'r', '?' => 's', '?' => 's', '?' => 't', '?' => 't', '?' => 'u', '?' => 'u', '?' => 'f', '?' => 'f', '?' => 'h', '?' => 'h', '?' => 'c', '?' => 'c', '?' => 'ch', '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => 'sch', '?' => 'sch', '?' => '', '?' => '', '?' => 'y', '?' => 'y', '?' => '', '?' => '', '?' => 'e', '?' => 'e', '?' => 'ju', '?' => 'ju', '?' => 'ja', '?' => 'ja');
    return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $chaine);
}
function clear_db($conn){
  $sql1 = "DELETE FROM `mot`";
		mysqli_query($conn, $sql1);
		$sql2 = "DELETE FROM `document`";
		mysqli_query($conn, $sql2);
		$sql3 = "DELETE FROM `mot_document`";
		mysqli_query($conn, $sql3);
}
function clear_dir($dir){
  $files = glob($dir . '/*');
  foreach($files as $file){
    // verifier si c'est un file et pas un repertoire
    if(is_file($file)){
    //supprimer les files avec unlink.
    unlink($file);
    }
    }

}

function selectForTag(){

        $conn = new mysqli('localhost', 'root', '','searchbdd');
       
  
  $tab = array();
  $sql ="SELECT mot.mot, mot_document.poids
      FROM mot
        INNER JOIN mot_document
        ON mot.id = mot_document.id_mot
        WHERE mot_document.poids > 1 ORDER BY RAND()
        DESC LIMIT 80";
  
  $resultat =   mysqli_query($conn, $sql);	
  if (mysqli_num_rows($resultat) > 0) {
  
  while ( $row = mysqli_fetch_assoc( $resultat) ) {
    $tab [] = $row ['mot'];
  }
    }

  $tab = array_flip ( $tab );
  
  
  return $tab;
  }





?>
