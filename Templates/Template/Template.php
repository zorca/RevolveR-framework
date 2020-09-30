<?php include('Head.php'); ?>

<?php // Scalable design class

    $main_class = 'revolver__scalable-main';
    $auth_class = $authFlag ? 'revolver__authorized' : 'revolver__not-authorized';

?>

<body>

<div class="preloader" style="position: fixed; width: 100%; height: 100%; background: rgba(60, 60, 60, .8) url('/Interface/preloader.svg') 50% 50% no-repeat; z-index: 100000"></div>

<main id="RevolverRoot" class="<?php print $main_class; ?>">

<?php

    include('Header.php');

    include('Main.php');

    include('Footer.php');

?>

</main>

<?="\n\n";

foreach( $scripts as $s ) {

    print $s ."\n"; 

}

?>
