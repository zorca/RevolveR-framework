<?php

$render_node .= '<section class="revolver__advanced-contents">';

$render_node .= '<h2>'. TRANSLATIONS[ $ipl ]['Comments'] .' &hellip;</h2>';

if( ACCESS === 'none' ) {

	$render_node .= '<div class="revolver__status-notifications revolver__inactive">';

	$render_node .= '<div class="revolver__statuses-heading">... Please register<i>+</i></div>';

	$render_node .= TRANSLATIONS[ $ipl ]['You can write here as guest with moderation'] .' '. TRANSLATIONS[ $ipl ]['Please'];

	$render_node .= ' <a href="/user/auth/">'. TRANSLATIONS[ $ipl ]['confirm your person'] .'</a> ';

	$render_node .= TRANSLATIONS[ $ipl ]['if you have an account or'];

	$render_node .= ' <a href="/user/register/">'. TRANSLATIONS[ $ipl ]['register'] .'</a>';

	$render_node .= '</div>';

	$render_node .= '</p>';

}

// Render comments
if( is_array( $node_comments ) ) {

	foreach( $node_comments as $c ) {

		if( (bool)$c['comment_published'] ) {

			$class = 'published';

		}
		else {

			$class = 'unpublished';

			if( isset( ACCESS['role'] ) ) {

				if( in_array( ACCESS['role'], ['none', 'User'], true) ) {

					continue;

				}

			}

			if( ACCESS === 'none' ) {

				continue;

			}

		}

		$dateData_0 = explode(' ',  $c['comment_time']);

		$dateData_1 = explode('.', $dateData_0[0]); ///YYYY-MM-DDThh:mmTZD

		$datetime = $dateData_1[2] .'-'. $dateData_1[1] .'-'. $dateData_1[0];

		$render_node .= '<article itemprop="comment" itemscope itemtype="https://schema.org/Comment" id="comment-'. $c['comment_id'] .'" class="revolver__comments comments-'. $c['comment_id'] .' '. $class .'">';

		$render_node .= '<header class="revolver__comments-header">'; 

		$render_node .= '<h2><a itemprop="url" href="'. $n['route'] .'#comment-'. $c['comment_id'] .'">&#8226;'. $c['comment_id'] .'</a> '. TRANSLATIONS[ $ipl ]['by'] .' <span>'. $c['comment_user_name'] .'</span></h2>';

		$render_node .= '<time itemprop="dateCreated" datetime="'. $datetime .'">'. $c['comment_time'] .'</time>';

		$render_node .= '</header>';

		$render_node .= '<figure itemprop="creator" itemscope itemtype="https://schema.org/Person" class="revolver__comments-avatar">';

		if( $c['comment_user_avatar'] === 'default') {

			$src = '/public/avatars/default.png';

		}
		else {

			$src = $c['comment_user_avatar'];

		}

		$render_node .= '<img itemprop="image" src="'. $src .'" alt="'. $c['comment_user_name'] .'" />';

		$render_node .= '<figcaption itemprop="name">'. $c['comment_user_name'] .'</figcaption>';

		$render_node .= '</figure>';

		$render_node .= '<div class="revolver__comments-contents">'. $markup::Markup( 

				htmlspecialchars_decode( 

					html_entity_decode( 

						$c['comment_contents']

					)

				), [ 'lazy' => 1 ] ) .'</div>';


		$render_node .= '<footer class="revolver__comments-footer">';


		if( isset($n['rating']) ) {

			$tpe = PASS[ 1 ] === 'blog' ? 'blog-comment' : 'node-comment';

			$render_node .= '<div itemscope itemtype="https://schema.org/AggregateRating" class="revolver-rating">';
			$render_node .= '<ul class="rated-'. $c['rating'] .'" data-node="'. $c['comment_id']  .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';

				$render_node .= '<li data-rated="1">1</li>';
				$render_node .= '<li data-rated="2">2</li>';
				$render_node .= '<li data-rated="3">3</li>';
				$render_node .= '<li data-rated="4">4</li>';
				$render_node .= '<li data-rated="5">5</li>';

			$render_node .= '</ul>';

			$render_node .= '<span itemprop="ratingValue">'. $c['rating'] .'</span> / <span itemprop="bestRating">5</span> #<span class="closest" itemprop="ratingCount">'. $c['rates'] .'</span>';
			$render_node .= '<meta itemprop="worstRating" content="1" />';
			$render_node .= '</div>';

		}

		if( $n['editor'] ) {

			$render_node .= '<nav><ul>';

			$render_node .= '<li><a title="'. $c['comment_id'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="/comment/'. $c['comment_id'] .'/edit/">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

			$render_node .= '</ul></nav>';

		}

		$render_node .= '</footer>';

		$render_node .= '</article>';

	}

}

$render_node .= '</section>';

?>
