<?php

	if( isset($node_data[0]) ) {

		$nodeLoaded = true;

		$n = $node_data[0];

		if( !isset($n['editor']) ) {

			$n['editor'] = null;

		}

		if( !isset($n['language']['hreflang']) ) {

			$n['language']['hreflang'] = $primary_language; 

		}

		if( !isset( $n['time'] ) ) {

			$n['time'] = null;

		}

		$render_node .= '<article itemscope itemtype="http://schema.org/Article" lang="'. $n['language']['hreflang'] .'" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

		$render_node .= '<header class="revolver__article-header">'; 

		if( $n['teaser'] ) {

			$render_node .= '<h2 itemprop="name"><a itemprop="url" hreflang="'. $n['language']['hreflang'] .'" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

		}
		else {

			$render_node .= '<h2 itemprop="name">'. $n['title'] .'</h2>';

		}

		if( $n['time'] ) {

			$datetime = explode( '-', explode(' ', $n['time'])[0] );

			$render_node .= '<time itemprop="datePublished" datetime="'. $datetime[2] .'-'. $datetime[1] .'-'. $datetime[0] .'">'. $n['time'] .'</time>';

		}

		$render_node .= '</header>';

		$render_node .= '<div itemprop="articleBody" class="revolver__article-contents">'. $markup::Markup( $n['contents'], [ 'lazy' => 1 ] ) .'</div>';


		if( $n['footer'] ) {

			$render_node .= '<footer class="revolver__article-footer"><nav>';

			$render_node .= '<ul>';

			if( $n['editor'] ) {

				$render_node .= '<li><a title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="'. $n['route'] .'edit/' .'">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

			}

			$render_node .= '</ul></nav></footer>';

		}

		$render_node .= '</article>';

	}

?>
