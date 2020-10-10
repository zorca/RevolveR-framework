<?php

	$nodeLoaded = true;

	if( !isset($n['editor']) ) {

		$n['editor'] = null;

	}

	if( !isset( $n['time'] ) ) {

		$n['time'] = null;

	}

	$render_node .= '<article itemscope itemtype="https://schema.org/BlogPosting" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$render_node .= '<header class="revolver__article-header">'; 

	if( empty( PASS[ 2 ] ) ) {

		$render_node .= '<h2 itemprop="headline"><a itemprop="url" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$render_node .= '<h2 itemprop="headline">'. $n['title'] .'</h2>';

	}

	if( $n['time'] ) {

		$datetime = explode( '-', str_replace('.', '-', explode(' ', $n['time'])[0]) );

		$render_node .= '<time itemprop="datePublished dateModified" datetime="'. $datetime[2] .'-'. $datetime[1] .'-'. $datetime[0] .'">'. $n['time'] .'</time>';

	}

	$render_node .= '</header>';

	if( RQST === '/blog/' ) {

		$render_node .= '<div class="revolver__article-contents" itemprop="articleBody mainEntityOfPage">'. $n['contents'] .'</div>';

	}

	if( $n['footer'] ) {

		$render_node .= '<footer class="revolver__article-footer">';

		if( isset($n['rating']) ) {

			$render_node .= '<div itemscope itemtype="https://schema.org/AggregateRating" class="revolver-rating">';
			$render_node .= '<ul class="rated-'. $n['rating'] .'" data-node="'. $n['id'] .'" data-user="'. USER['id'] .'" data-type="blog">';
				$render_node .= '<li data-rated="1">1</li>';
				$render_node .= '<li data-rated="2">2</li>';
				$render_node .= '<li data-rated="3">3</li>';
				$render_node .= '<li data-rated="4">4</li>';
				$render_node .= '<li data-rated="5">5</li>';
			$render_node .= '</ul>';

			$render_node .= '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
			$render_node .= '<meta itemprop="height" content="435">';
			$render_node .= '<meta itemprop="width" content="432">';
			$render_node .= '<meta itemprop="url" content="'. site_host .'/Interface/ArticlePostImage.png">';
			$render_node .= '</div>';

			$render_node .= '<div class="meta" itemprop="author publisher" itemscope itemtype="http://schema.org/Organization">';

			$render_node .= '<div itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">';
			$render_node .= '<meta itemprop="url" content="'. site_host .'/Interface/ArticlePostImage.png" />';
			$render_node .= '</div>';
			$render_node .= '<span itemprop="name">'. $n['author'] .'</span>';

			$render_node .= '</div>';

		}

		$render_node .= '<div itemprop="author publisher" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">'. $n['author'] .'</span></div>';

		$render_node .= '<nav>';

		$render_node .= '<ul>';

		if( $n['editor'] ) {

			$render_node .= '<li><a title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="'. $n['route'] .'edit/' .'">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

		}
		else {


			$render_node .= '<li><a title="'. $n['title'] .'" href="'. $n['route'] .'">'. TRANSLATIONS[ $ipl ]['Read More'] .' &rArr;</a></li>';

		}

		$render_node .= '</ul></nav></footer>';

	}

	$render_node .= '</article>';


?>
