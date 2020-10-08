<?php include('Head.php'); ?>

<?php // Scalable design class

    $main_class = 'revolver__scalable-main';
    $auth_class = $authFlag ? 'revolver__authorized' : 'revolver__not-authorized';

	if( (PASS[ 1 ] === 'blog' && !empty( PASS[ 2 ] )) || (PASS[ 1 ] === 'wiki' && !empty( PASS[ 3 ] )) ) {

		$descr = $node_data[0]['description'];

	}

?>

<body>

<div class="preloader" style="position: fixed; width: 100%; height: 100%; background: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #b7754594 .25vw), linear-gradient(to bottom, #eeeeee5c, #bfbfbf1a), transparent url('/Interface/preloader.svg') 50% 50% no-repeat; z-index: 100000"></div>

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
