<?php



function readFiles ($folder){

    if ($toc=opendir($folder)) {

    while (($file= readdir($toc) )!== false) {

       

        if ($file == '.' || $file == '..') {
           continue;
        }
       // echo "filename : ".$file.'<br>';


        if (is_dir($psource=($folder.'/'.$file))) {
           readFiles(($psource));
         // echo "sub folder :".$sub_folder;
        }
        else
        {

//C'est un fichier html ou pas

				
//Si c'est un .txt

if(stripos($psource, '.txt'))
{
  //echo $psource, '<br>';
  traitement_txt($psource);
}
//Si c'est un .htm (ou .html)

if( stripos($psource, '.htm') )
{
  //echo $psource, '<br>';
  traitement_html($psource);
}
//echo "--DEBUT indexation : $path_source ", "<br>";


  
           
        }
       
    }
       
    }


}


function traitement_txt($source){
  $text=file_get_contents($source);

  $title=substr($text,0,20);
  //echo $title.'<br>';
  $description=substr($text,0,50);  
  //echo $description;
 
  // segmentation du texte en mots
  $chaine = eliminate_separators($text);
  $before_indexation= count($chaine);
 
  //eliminier les doublons dans le head
  $ch=array_count_values($chaine);
  $after_indexation= count($ch);
 
   //Insertion en base de données
  insert_in_db($ch,$source,$title,$description,$before_indexation,$after_indexation);
 
  
 
 }








function traitement_html($source){
 $text=file_get_contents($source);
 //echo $text;

 //$text=utf8_encode($text);
//HEAD

 //récuperer le titre 
 $title=getTitle($text);
 

 // récupérer de decscription
 $description=get_description($source);
 


 // récupérer des keywords
 $keywords=get_keywords($source);
 

 // constitution du head
 $texte_head=$title." ".$description." ".$keywords;
 $texte_head = strtolower($texte_head);

 //echo"<br>"." title head de chaque doc :".$texte_head;

 // segmentation du texte en mots
 $head = eliminate_separators($texte_head);
 $before_indexation_head= count($head);


 //eliminier les doublons dans le head
 $head=array_count_values($head);
 $after_indexation_head= count($head);
 $head=poids($head,1.5);

 //BODY

//recupération du body du corps du document

$chaine_body_html = get_body($text);

//suppression du javascript du body
$body_html = strip_javascript ($chaine_body_html);

//suppression de balises html du body
$chaine_body_texte = strip_tags($body_html);
$chaine_body_texte = strtolower($chaine_body_texte);
$body=eliminate_separators($chaine_body_texte);
$before_indexation_body= count($body);
$body_wd=array_count_values($body);
$after_indexation_body= count($body_wd);



//fusionner head+body
$tab = fusionner ($head, $body_wd );
//print_r($tab);
$before_indexation= $before_indexation_head+$before_indexation_body;
$after_indexation= $after_indexation_head+$after_indexation_body;

 //Insertion en base de données
 insert_in_db($tab,$source,$title,$description,$before_indexation,$after_indexation);

 

}



  




?>