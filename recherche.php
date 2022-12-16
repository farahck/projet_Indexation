<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Signika&display=swap" rel="stylesheet">
</head>

<body>

</body>

<body>
    <style>
        body {
            background:#F6F5FD;
            font-family: 'Signika', sans-serif;
        }

        .btnbg {
            background: #AA9EF2;
        }

        .sizeicon {
            font-size: 23px;
            font-weight: bold;
        }

        form {
            border: 1px solid #AA9EF2;

        }

        .mr-0 {
            margin-right: 0 !important;
        }

        .titre {
            color:#C568E6;
        }
        .doc{
            
            color: #AA9EF2;
        }

        .desc {
            font-family: 'Signika', sans-serif;
           

        }

        .float-right {
            float: right;
        }

        .btn_submit {

     margin: 0 10px;

        }
        .btn_submit:hover,  .btn_submit:focus{

background-color:#AA9EF2 ;

}
        .input {
          
            width: 90%;
        }

        .stylish-input-group {
            display: flex;
            justify-content: space-between;


        }

        .lh0 {
            line-height: 0px;
        }

        .bx-cloud-download {
            color: #4a00e0;
        }
        .accordion-button::after{
            background-image: url('cloud.png')!important;
        }
        .w35{
            min-width: 45%;
        }
        .text_rech{
  color:#7C6FCB;
}
.acc{
background-color: white;
border:1px solid #AA9EF2;
}
.oneresult{
    padding-right: 10px;
}
.accordion-button:not(.collapsed) {
    background-color:white;
    border-bottom:none !important;
  
}
.accordion{
    border: none !important;
}
.cloud{
    background-color:#FAFAFA;
    border:1px solid #AA9EF2;
    border-radius: 5px;
}
    </style>



    <div class="container">
        <div class="row ">
            <div class="col-md-6 offset-md-3 min-vh-100 ">
                <div class="tocccc ">
                    <h1 class="text-center text_rech mt-5">Recherche</h1>
                    <div id="imaginary_container" class="mmm ">


                        <form method="POST" action="" class="  rounded ">
                            <div class="input-group stylish-input-group d-flex justify-content-between">

                                <input class="rounded-start border-0 input form-control mb-0" type="text" name="mot_cle" placeholder="Rechercher" value="<?php if (isset($_POST['mot_cle'])) {
                                                                                                                                                echo htmlentities($_POST['mot_cle']);
                                                                                                                                            } ?>" />
                                <button class=" border-0 btn_submit btn btnbg mr-0" type="submit"><i class='bx bx-search-alt-2 sizeicon text-light'></i></button>

                            </div>
                        </form>


                    </div>


                    <?php
                    include 'traitement.php';
                    include 'functions.php';

                    $conn = new mysqli('localhost', 'root', '', 'searchbdd');



                    if (isset($_POST['mot_cle'])) {
                        //echo'hskdjksj';
                        $sqll = "select document.id as id,document.document as doc,document.titre as titre,
document.description as description,

mot_document.poids as poid
from mot_document join document
on mot_document.id_document = document.id
join  mot on
mot_document.id_mot = mot.id  where mot.mot like'$_POST[mot_cle]' ";
                        $result = mysqli_query($conn, $sqll);






                    ?>


                    <?php
                             
                        echo '<div class =" resultat  my-3">';
                        if (mysqli_num_rows($result) > 0) {
                            echo ' Résultats de recherche pour <span style="font-weight:bold;">' . $_POST['mot_cle'] . '</span> <br><br>
                            <div class="rounded shadow p-3 acc">';
                            //var_dump($result);
                            $i=0;
                            while ($row = mysqli_fetch_assoc($result)) {

                                $id = $row['id'];
                                $titre = $row['titre'];
                                $description = $row['description'];


                             echo"<div class=\"accordion accordion-flush\" id=\"accordionFlushExample\">
                                <div class=\"accordion-item\">
                                    <h5 class=\"accordion-header\" id=\"flush-heading_$i\">
                                        <button class=\"accordion-button collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#flush-collapse_$i\" aria-expanded=\"false\" aria-controls=\"flush-collapse_$i\">
                                        <div class=\" oneresult\"> <div class=\"d-flex\"><a class=\"text-decoration-none titre d-flex \" href=" . $row["doc"] . "> <h5 class=\"m-auto\">" . $row["titre"] .  "</h5>
                                         </div> 
                                        <a class=\"text-decoration-none doc \"   href=" . $row["doc"] . "> <p>" . $row["doc"] . "\n(" . $row["poid"] . ") </p></a>
                                        <a class=\"text-decoration-none text-dark desc\" href=" . $row["doc"] . ">
                                         <p>" .substr($row["description"],0,150). "... </p></a></div> <br>
                                    </button>
                                    </h5>
                                    <div id=\"flush-collapse_$i\" class=\"accordion-collapse collapse\" aria-labelledby=\"flush-heading_$i\" data-bs-parent=\"#accordionFlushExample\">
                                    <div class=\" cloud p-4\">";
                                    $tab_tag = selectForTag();
                                   
                                    $colors = array("#F0F8FF", "#F0FFFF", "#FFFAFA", "#40E0D0", "#98FB98","#F87400","#F8E100");
				
                                   // Taille maximal - Taille minimal

        $max_size = 150; // max font size en %

        $min_size = 70; // min font size en %

        $max_qty = max(array_values($tab_tag));

        $min_qty = min(array_values($tab_tag));



        $spread = $max_qty - $min_qty;

        if (0 == $spread) { 

            $spread = 1;

        }

        $step = ($max_size - $min_size)/($spread);

        foreach ($tab_tag as $key => $value) {
            $tab_colors =array("#0084F8", "#0C732D","#F87400","#F8E100","#7E43CF","#A10937");
				
            $color = rand ( 0, count ( $tab_colors ) - 1 );

            $size = $min_size + (($value - $min_qty) * $step);




			echo '<a class="text-decoration-none px-1" href="" style="font-size: '.$size.'%; color:' . $tab_colors [$color] . '"';

            echo ' title="'.$value.' recherche(s) sur le mot '.$key.'"';

            echo '>'.$key.'</a> ';
        }





    
                
                                    echo"</div>
                                </div>
                                </div>

        
        
                            </div>";
                            $i++;
                            }
                        } else echo ' Aucun document ne correspond aux termes de recherche spécifiés <span style="font-weight:bold;">' . $_POST['mot_cle'] . '</span>';
                    }
                    echo '</div> </div>'
                    ?>









                 









                </div>

            </div>


        </div>
    </div>

 

</body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</html>