<head>

	<meta charset="utf-8" />
	<base href="<?php print site_host .'/' ?>" target="_self" />

	<meta name="description" content="<?php print $descr .' | '. $brand ?>" />
	<meta name="host" content="<?php print $host ?>" />

	<title><?php print $title ?></title>		

	<?php if( INSTALLED ): ?>

	<link rel="alternate" type="application/atom+xml" href="<?php print site_host ?>/aggregator/" />

	<?php endif; ?>

	<?php $f = 0; foreach( $istyles as $css ) {

		if( $f++ > 0 ) {

			$css = '	'. $css;			

		}

		print $css ."\n";

	}

	?>

	<link rel="icon mask-icon shortcut apple-touch-icon" type="image/png" sizes="any" href="/favicon.ico" />

	<meta name="generator" content="RevolveR CMF v.<?php print rr_version ?>" />

</head>
