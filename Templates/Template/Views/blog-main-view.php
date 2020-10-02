<?php

$render_node .= '<section class="revolver__advanced-contents">';

$blog_id = iterator_to_array(

	$model::get( 'blog_nodes', [

		'criterion' => 'route::'. RQST,

		'bound' 	=> [

			1

		],

		'course'	=> 'backward',
		'sort' 		=> 'id'

	])

)['model::blog_nodes'][0]['id'];

$comments = iterator_to_array(

	$model::get( 'blog_comments', [

		'criterion' => 'node_id::'. $blog_id,
		'course'	=> 'forward',
		'sort' 		=> 'id'

	])

)['model::blog_comments'];

// Render comments
if( $comments ) {

	foreach( $comments as $c ) {

		$comment_user = iterator_to_array(

				$model::get('users', [

					'criterion' => 'id::'. $c['user_id'],
					'course'	=> 'forward',
					'sort'		=> 'id'

				])

			)['model::users'][0];

		if( (bool)$c['published'] ) {

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

		$dateData_0 = explode(' ',  $c['time']);

		$dateData_1 = explode('.', $dateData_0[0]); ///YYYY-MM-DDThh:mmTZD

		$datetime = $dateData_1[2] .'-'. $dateData_1[1] .'-'. $dateData_1[0];

		$render_node .= '<article id="comment-'. $c['id'] .'" class="revolver__comments comments-'. $c['id'] .' '. $class .'">';

		$render_node .= '<header class="revolver__comments-header">'; 

		$render_node .= '<h2><a href="'. RQST .'#comment-'. $c['id'] .'">&#8226;'. $c['id'] .'</a> '. TRANSLATIONS[ $ipl ]['by'] .' <span>'. $comment_user['nickname'] .'</span></h2>';

		$render_node .= '<time datetime="'. $datetime .'">'. $c['time'] .'</time>';

		$render_node .= '</header>';

		$render_node .= '<figure class="revolver__comments-avatar">';

		if( $comment_user['avatar'] === 'default') {

			$src = '/public/avatars/default.png';

		}
		else {

			$src = $comment_user['avatar'];

		}

		$render_node .= '<img src="'. $src .'" alt="'. $comment_user['nickname'] .'" />';

		$render_node .= '</figure>';

		$render_node .= '<div class="revolver__comments-contents">'. $markup::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$c['content']

						), 

					), [ 'lazy' => 1 ] ) .'</div>';


		if( $n['editor'] ) {

			$render_node .= '<footer class="revolver__comments-footer"><nav><ul>';

			if( $comment_user['id'] === USER['id'] || in_array(ROLE, ['Admin', 'Writer']) ) {

				$render_node .= '<li><a title="'. $c['id'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="'. RQST .'comment/'.  $c['id'] .'/edit/">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

			}

			$render_node .= '</ul></nav></footer>';

		}

		$render_node .= '</article>';

	}

}

$render_node .= '</section>';

?>
