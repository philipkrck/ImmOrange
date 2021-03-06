<?php

    ### PHP Preparation

        // start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        require_once('../includes/functions/pdo.php');
        include('../includes/functions/manage_favorites.php');


    ### Functions

        function getOffer($offer_id) {
            $offer_statement = pdo()->prepare("SELECT offer_id, offer_name, price, qm, number_of_rooms, is_for_rent, is_apartment, construction_year, has_basement, has_garden, has_bathtub, has_elevator, has_balcony, city, p.creation_date, company_name, acc_id FROM property_offer p LEFT OUTER JOIN realtor r ON p.realtor_id = r.realtor_id LEFT OUTER JOIN account a ON r.realtor_id = a.realtor_id WHERE offer_id = :offer_id;");
            $offer_statement->bindParam(':offer_id', $offer_id);
            $offer_statement->execute();
            return $offer_statement->fetch();
        }


    ### Busines Logic
    
        if (empty($_GET['offer_id'])) {
            die('Keine Immobilie ausgewählt. <a href="/index.php">Zurück zur Homepage</a>');
        }

        // getting offer based on GET-Parameter
        $offer = getOffer($_GET['offer_id']);

        // checks if offer exists
        if (empty($offer)) {
            die('Diese Immobilie existiert nicht. <a href="/index.php">Zurück zur Homepage</a>');
        }
        
        // toggle favorite if get param is set
        $favorite_id = isset($_GET['favorite']) ? $_GET['offer_id'] : null;
        if ($favorite_id) {
            toggleFavorite($favorite_id);
        }

        // checking the number of extras in offer
        $extras_count = 0;
        if ($offer["has_basement"]) {
            $extras_count++;
        }
        if ($offer["has_garden"]) {
            $extras_count++;
        }
        if ($offer["has_bathtub"]) {
            $extras_count++;
        }
        if ($offer["has_elevator"]) {
            $extras_count++;
        }
        if ($offer["has_balcony"]) {
            $extras_count++;
        }

        // converting creation_date
        $creation_date_without_time = substr($offer["creation_date"],0,10);
        $creation_date_splitted = explode('-', $creation_date_without_time);
        $date = $creation_date_splitted[2];
        $month = $creation_date_splitted[1];
        $year = $creation_date_splitted[0];
        $creation_date = $date.'.'.$month.'.'.$year;

        // check if offer is for rent
        if ($offer["is_for_rent"]) {
            $purchasing_type = "zu vermieten";
        } else {
            $purchasing_type = "zu verkaufen";
        }

        // check if offer is appartment
        if ($offer["is_apartment"]) {
            $offer_type = "Wohnung";
        } else {
            $offer_type = "Haus";
        }

?>

<!DOCTYPE html>
<html>

    <!-- HEAD-AREA -->
    <head>
    
        <!-- Homepage-Title -->
        <title><?php echo  $offer["offer_name"] ?>  ∙  ImmOrange GmbH</title>

        <!-- Styles -->
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="../css/results.css">
        <link rel="stylesheet" href="../css/pages/offer.css">
        <link rel="stylesheet" type="text/css" href="/css/fonts/OpenSans.css"> 

        <!-- Scripts -->
        <?php
            include('../includes/functions/toggle_favorite.php');
        ?>

    </head>

    <!-- BODY-AREA -->
    <body>

        <!-- HEADER-AREA -->
        <header>
            <?php 
                include('../includes/header.php');
            ?>
        </header>

        <!-- MAIN-AREA -->
        <main>

            <!-- CONTENT-AREA -->
            <div class="content offer">
            
                <!-- offer-summary -->
                <?php
                    $offer_id = $offer["offer_id"];
                    echo '<div class="result-container">
                    
                    <!-- offer-image -->
                    <div class="result-image-container">
                        <a href="/pages/offer.php?offer_id='.$offer_id.'"><img src="/includes/functions/image_source.php?offer_id='.$offer_id.'"></a>
                    </div>

                    <!-- offer-title -->
                    <h3 class="result-title">'.$offer["offer_name"].'</h3>

                    <!-- favorite-icon -->
                    ';        
                    $image_source = "../img/icons/heart_white.png";   
                    $favorites = (isset($_COOKIE['favorites']) && !empty($_COOKIE['favorites'])) ? json_decode($_COOKIE['favorites'], true) : array();
                    if (in_array($offer_id, $favorites)) {
                        $image_source = "../img/icons/heart_orange.png";
                    }
                    echo '<a href="offer.php?offer_id='.$offer_id.'&favorite" class="heart-icon" id="heart-icon"><img src="'.$image_source.'" onclick="toggleFavorite()"></a>';

                    echo '
                    <!-- offer-price -->
                    <span class="result-price">'.$offer["price"].' €</span>

                    <!-- offer-qm -->
                    <span class="result-space">'.$offer["qm"].' qm</span>

                    <!-- offer-rooms -->
                    <div class="result-rooms-container">
                        <img src="../img/icons/rooms.png" class="rooms-img"><span class="result-rooms">'.$offer["number_of_rooms"].' Zimmer</span>
                    </div>

                    <!-- offer-location -->
                    <div class="result-location-container">
                        <img src="../img/icons/location.png" class="location-img"><span class="result-location">in '.$offer["city"].'</span>
                    </div>'; 
                            
                        echo '  
                    </div>';

                    

                ?> 

                <!-- offer-additional-info -->
                <?php echo '
                    <div class="additional-info">
                        <span><b>'.$offer_type.'</b></span>                        
                        <span><b>'.$purchasing_type.'</b></span>
                        <span>Bauhjahr: <b>'.$offer["construction_year"].'</b></span>
                        <span class="online-since">Anzeige online seit: <b>'.$creation_date.'</b></span>
                    </div>';
                ?>
                
                <!-- offer-options -->
                <?php

                    if ($extras_count > 0) {
                        echo '                        
                        
                        <div class="extras-container">
                               
                            <h3>Extras:</h3>
        
                            <div class="extras">';       
                                          
                                if ($offer["has_basement"]) {
                                    echo '<img src="../img/icons/basement.png" title="besitzt einen Keller">';
                                }
                                if ($offer["has_garden"]) {
                                    echo '<img src="../img/icons/botanical.png" title="besitzt einen Garten">';
                                }
                                if ($offer["has_balcony"]) {
                                    echo '<img src="../img/icons/balcony.png" title="besitzt einen Balkon">';
                                }
                                if ($offer["has_bathtub"]) {
                                    echo '<img src="../img/icons/bathtub.png" title="besitzt eine Badewanne">';
                                }
                                if ($offer["has_elevator"]) {
                                    echo '<img src="../img/icons/lift.png" title="besitzt einen Fahrstuhl">';
                                }                                
        
                        echo '</div></div>';
                    }

                ?>

                <!-- realtor-info -->
                <div class="realtor-info">
                    <h3>Interesse?</h3>
                    <h4>Besuchen Sie das Profil des Maklers!</h4>
                    <?php
                        echo '<a href="account.php?acc_id='.$offer["acc_id"].'">';
                    ?>
                    <img src="../img/icons/Benutzer.png">                    
                        <span>
                            <?php
                                echo $offer['company_name'];
                            ?>
                        </span>
                    </a>
                </div>
            </div>
        </main>

        <!-- FOOTER-AREA -->
        <footer>
            <?php
                include('../includes/footer.php');
            ?>
        </footer>
    
    </body>  

</html> 