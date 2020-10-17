

<?php if( RQST !== '/' ): ?>

<div class="revolver__breadcrumb">

	<h6 class="revolver__meta-header">Breadcrumb</h6>

	<?php require('Breadcrumb.php') ?>

</div>

<?php endif;?>


<!-- RevolveR :: footer -->
<footer class="revolver__footer <?php print $auth_class; ?>">

	<p><?php print $title; ?> | <span><?php print date('Y'); ?></span> | <a href="https://revolvercmf.ru" title="RevolveR CMF by RevolveR Labs powered" target="_blank">RevolveR CMF</a></p>

	<?php 

	if( INSTALLED ):

		include('Menu.php'); 

	endif; ?>

</footer>
