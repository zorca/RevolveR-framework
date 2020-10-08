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

		$render_node .= '<time itemprop="datePublished" datetime="'. $datetime[2] .'-'. $datetime[1] .'-'. $datetime[0] .'">'. $n['time'] .'</time>';

	}

	$render_node .= '</header>';

	if( RQST === '/blog/' ) {

		$render_node .= '<div class="revolver__article-contents">'. $n['contents'] .'</div>';

	}

	if( $n['footer'] ) {

		$render_node .= '<footer class="revolver__article-footer"><nav>';

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
