
<!-- RevolveR :: header -->
<header class="revolver__header <?php print $auth_class; ?>">

	<h1 class="revolver__logo">

		<a title="<?php print DESCRIPTION; ?>" href="<?php print site_host ?>"><?php print $brand; ?></a>

	</h1>

	<div class="revolver__search-box">

		<form action="/search/" method="GET">

			<input type="search" name="query" placeholder="<?php print TRANSLATIONS[ $ipl ]['Type keywords here'] ?>" required <?php if(!INSTALLED): ?>disabled="disabled"<?php endif; ?> />
			<input type="submit" name="revolver-search-submit" value="<?php print TRANSLATIONS[ $ipl ]['Search'] ?>" />

		</form>

	</div>

</header>

<section class="revolver__nav-bar">

	<!-- main menu -->
	<?php include('Menu.php'); ?>

	<!-- RevolveR :: site description -->
	<h2 class="revolver__site-description"><?php print DESCRIPTION; ?></h2>

</section>