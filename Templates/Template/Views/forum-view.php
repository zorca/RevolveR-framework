<?php

	if( !isset($n['editor']) ) {

		$n['editor'] = null;

	}

	if( !isset($n['language']['hreflang']) ) {

		$n['language']['hreflang'] = $primary_language; 

	}

	$render_node .= '<article lang="'. $n['language']['hreflang'] .'" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$render_node .= '<header class="revolver__article-header">'; 

	if( $n['teaser'] ) {

		$render_node .= '<h2><a hreflang="'. $n['language']['hreflang'] .'" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$render_node .= '<h2>'. $n['title'] .'</h2>';

	}

	$render_node .= '</header>';

	if( RQST === '/' ) {

		$render_node .= '<div class="revolver__article-contents">'. $markup::Markup( $n['contents'], [ 'length' => 2000, 'xhash' => 0, 'lazy' => 1 ] ) .'</div>';

	}
	else {

		if( $flag_main_node ) {

			$render_node .= '<div class="revolver__article-contents">'. $n['contents'] .'</div>';	

		}
		else {

			$render_node .= '<div class="revolver__article-contents">'. $markup::Markup( $n['contents'], ['lazy' => 1] ) .'</div>';

		}

	}

	if( $n['footer'] ) {

		$render_node .= '<footer class="revolver__article-footer"><nav>';

		$render_node .= '<ul>';

		if( $n['editor'] ) {

			$render_node .= '<li><a title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="'. $n['route'] .'edit/' .'">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

		}
		else {

			$render_node .= '<li><a hreflang="'. $n['language']['hreflang'] .'" title="'. $n['title'] .'" href="'. $n['route'] .'">'. TRANSLATIONS[ $ipl ]['Read More'] .' &rArr;</a></li>';

		}

		$render_node .= '</ul></nav></footer>';

	}

	$render_node .= '</article>';

?>
