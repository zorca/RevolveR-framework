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

		/* Comments rating */
		$crating = iterator_to_array(

					$model::get( 'blog_comments_ratings', [

						'criterion'	=> 'comment_id::'. $c['id'],
						'course'	=> 'backward',
						'sort'		=> 'id'

					])

				)['model::blog_comments_ratings'];

		$crate = 0;

		if( $crating ) {

			foreach( $crating as $r => $rv ) {

				$crate += $rv['rate'];

			}

			$crate /= count( $crating ); 

		}
		else {

			$crating = [];

		}

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

		$render_node .= '<article itemprop="comment" itemscope itemtype="https://schema.org/Comment" id="comment-'. $c['id'] .'" class="revolver__comments comments-'. $c['id'] .' '. $class .'">';

		$render_node .= '<header class="revolver__comments-header">'; 

		$render_node .= '<h2><a itemprop="url" href="'. RQST .'#comment-'. $c['id'] .'">&#8226;'. $c['id'] .'</a> '. TRANSLATIONS[ $ipl ]['by'] .' <span>'. $comment_user['nickname'] .'</span></h2>';

		$render_node .= '<time itemprop="dateCreated" datetime="'. $datetime .'">'. $c['time'] .'</time>';

		$render_node .= '</header>';

		$render_node .= '<figure itemprop="creator" itemscope itemtype="https://schema.org/Person" class="revolver__comments-avatar">';

		if( $comment_user['avatar'] === 'default') {

			$src = '/public/avatars/default.png';

		}
		else {

			$src = $comment_user['avatar'];

		}

		$render_node .= '<img itemprop="image" src="'. $src .'" alt="'. $comment_user['nickname'] .'" />';

		$render_node .= '<figcaption itemprop="name">'. $comment_user['nickname'] .'</figcaption>';

		$render_node .= '</figure>';

		if( in_array(ROLE, ['Admin', 'Writer']) || USER['name'] === $comment_user['nickname'] ) {

				$quick_edit_attr = ' contenteditable="false"';
				$quick_edit_data = ' data-node="'. $c['id'] .'" data-type="blog-comment" data-user="'. $comment_user['nickname'] .'"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}

		$render_node .= '<div class="revolver__comments-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $markup::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$c['content']

						), 

					), [ 'lazy' => 1 ] ) .'</div>';


		$render_node .= '<footer class="revolver__comments-footer">';

		$tpe = 'blog-comment';

		$render_node .= '<div class="revolver-rating">';
		$render_node .= '<ul class="rated-'. floor($crate) .'" data-node="'. $c['id'] .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';

			$render_node .= '<li data-rated="1">1</li>';
			$render_node .= '<li data-rated="2">2</li>';
			$render_node .= '<li data-rated="3">3</li>';
			$render_node .= '<li data-rated="4">4</li>';
			$render_node .= '<li data-rated="5">5</li>';

		$render_node .= '</ul>';

		$render_node .= '<span>'. floor($crate) .'</span> / <span>5</span> #<span class="closest">'. count($crating) .'</span>';
		$render_node .= '</div>';

		if( $comment_user['id'] === USER['id'] || in_array(ROLE, ['Admin', 'Writer']) ) {

			$render_node .= '<nav><ul>';

			$render_node .= '<li class="revolver__quick-edit-handler" title="'. TRANSLATIONS[ $ipl ]['qedit'] .'">[ '. TRANSLATIONS[ $ipl ]['QEdit'] .' ]</li>';

			$render_node .= '<li><a title="'. $c['id'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="'. RQST .'comment/'.  $c['id'] .'/edit/">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

			$render_node .= '</ul></nav>';

		}

		$render_node .= '</footer>';

		$render_node .= '</article>';

	}

}

$render_node .= '</section>';

?>
