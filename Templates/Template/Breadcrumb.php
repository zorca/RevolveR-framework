
<!-- RevolveR :: breadcrumb -->
<nav class="revolver__main-breadcrumb">

	<ul itemscope itemtype="https://schema.org/BreadcrumbList">

		<?php if( !defined(ROUTE) && isset(PASS[ 2 ]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemprop="item" href="/" title="<?php print TRANSLATIONS[ $ipl ]['Home']; ?>">
				<b itemprop="name"><?php print TRANSLATIONS[ $ipl ]['Home']; ?></b>
			</a>

			<meta itemprop="position" content="1" />

		</li>

		<li>
			<span>›</span>
		</li>

		<?php if( PASS[ 1 ] === 'wiki' && isset(PASS[4]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print site_host .'/wiki/'; ?>" href="/wiki/" title="<?php print TRANSLATIONS[ $ipl ]['Wiki']; ?>" itemscope>
				<b itemprop="name"><?php print TRANSLATIONS[ $ipl ]['Wiki']; ?></b>
			</a>

			<meta itemprop="position" content="2" />

		</li>

		<li>
			<span>›</span>
		</li>

		<?php endif;?>

		<?php if( PASS[ 1 ] === 'blog' && isset(PASS[3]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print site_host .'/blog/'; ?>" href="/blog/" title="<?php print TRANSLATIONS[ $ipl ]['Blog']; ?>" itemscope>
				<b itemprop="name"><?php print TRANSLATIONS[ $ipl ]['Blog']; ?></b>
			</a>

			<meta itemprop="position" content="2" />

		</li>

		<li>
			<span>›</span>
		</li>

		<?php endif;?>

		<?php if( PASS[ 1 ] === 'forum' && isset(PASS[3]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print site_host .'/forum/'; ?>" href="/forum/" title="<?php print TRANSLATIONS[ $ipl ]['Forum']; ?>" itemscope>
				<b itemprop="name"><?php print TRANSLATIONS[ $ipl ]['Forum']; ?></b>
			</a>

			<meta itemprop="position" content="2" />

		</li>

		<li>
			<span>›</span>
		</li>

			<?php if( isset(PASS[4]) ): ?>

			<?php

				$forum_breadcrumb = iterator_to_array(

					$model::get('forums', [

						'criterion' => 'id::'. (int)PASS[ 2 ],
						'course'	=> 'forward',
						'sort'		=> 'id'

					])

				)['model::forums'];

				$b_title = 'undefined';

				if( $forum_breadcrumb ) {

					$b_title = $forum_breadcrumb[0]['title'];

				}

			?>

			<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

				<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print site_host .'/forum/' . PASS[2] .'/'; ?>" href="/forum/<?php print PASS[2] .'/'; ?>" title="<?php print $b_title; ?>" itemscope>
					<b itemprop="name"><?php print $b_title; ?></b>
				</a>

				<meta itemprop="position" content="3" />

			</li>

			<li>
				<span>›</span>
			</li>

			<?php endif;?>

		<?php endif;?>
		
		<li>
			<em><?php print $title; ?></em>	
		</li>

		<?php endif;?>

	</ul>
		
</nav>
