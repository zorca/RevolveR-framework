
<?php 

    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

    header('Status: 404 Not Found');


    if( N ) {

        // Title for 404
        $title = 'This URL not found on this host :: 404';

        // Brand for 404
        $brand = '!_found ';

        // Website description
        $descr = 'Requested URL is not accesible on this host for now.';    

    }


?>

<!-- RevolveR :: head -->
<?php include('Head.php'); ?>

<?php // Scalable design class

    $main_class = 'revolver__scalable-main';
    $auth_class = $authFlag ? 'revolver__authorized' : 'revolver__not-authorized';

?>

    <body>

        <div class="preloader" style="position: fixed; width: 100%; height: 100%; background: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #b7754594 .25vw), linear-gradient(to bottom, #eeeeee5c, #bfbfbf1a), transparent url('/Interface/preloader.svg') 50% 50% no-repeat; z-index: 100000"></div>


        <!-- RevolveR :: root -->
        <main id="RevolverRoot" class="<?php print $main_class; ?>">

            <?php include('Header.php'); ?>

            <!-- RevolveR :: main -->
            <section class="revolver__main-contents <?php print $main_class; ?> <?php print $auth_class; ?>">

            <?php 

                if( CONTENTS_FLAG && RQST !== '/' ) {

                    $render_node .= '<article class="revolver__article article-id-404">';
                    $render_node .= '<header class="revolver__article-header">';
                    $render_node .= '<h1>'. TRANSLATIONS[ $ipl ]['Route not found'] .'</h1>';
                    $render_node .= '</header>';

                    $render_node .= '<div class="revolver__article-contents">';
                    $render_node .= '<p>'. TRANSLATIONS[ $ipl ]['Route'];
                    $render_node .= ' <b>'. RQST .'</b> '. TRANSLATIONS[ $ipl ]['was not found on this host'] .'!</p>';
                    $render_node .= '<p><a href="/">'. TRANSLATIONS[ $ipl ]['Begin at homepage'] .'!</a>';
                    $render_node .= '</p></div>';

                    $render_node .= '<footer class="revolver__article-footer">';
                    $render_node .= '<nav><ul><li><a title="'. TRANSLATIONS[ $ipl ]['Homepage'] .'" href="/">'. TRANSLATIONS[ $ipl ]['Homepage'] .'</a></li></ul></nav>';
                    $render_node .= '</footer></article>';

                }

                print $render_node;

            ?>

            </section>

            <?php include('Footer.php'); ?>

        </main>

<?="\n\n";

foreach( $scripts as $s ) {

    print $s ."\n"; 

}

?>
