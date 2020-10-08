
<!-- RevolveR :: header -->
<header itemscope itemtype="http://schema.org/Organization" class="revolver__header <?php print $auth_class; ?>">

	<h1 class="revolver__logo" rel="banner">

		<a itemprop="url" title="<?php print DESCRIPTION; ?>" href="<?php print site_host ?>">

			<span itemprop="name"><?php print $brand; ?></span>

		</a>

		<link itemprop="logo" href="/Interface/RCMF.svg" />

	</h1>

	<div class="revolver__search-box">

		<form action="/search/" method="GET">

			<input type="search" name="query" placeholder="<?php print TRANSLATIONS[ $ipl ]['Type keywords here'] ?>" required <?php if(!INSTALLED): ?>disabled="disabled"<?php endif; ?> />
			<input type="submit" name="revolver-search-submit" value="<?php print TRANSLATIONS[ $ipl ]['Search'] ?>" />

		</form>

	</div>

	<!-- RevolveR :: site description -->
	<h2 itemprop="description" class="revolver__site-description"><?php print $descr; ?></h2>

</header>

<section class="revolver__nav-bar">

	<?php require('Menu.php'); ?>

</section>

<section class="revolver__breadcrumb">

	<?php require('Breadcrumb.php') ?>

</section>
