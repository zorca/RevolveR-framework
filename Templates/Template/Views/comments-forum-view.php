<?php

$render_node .= '<section class="revolver__advanced-contents">';

$comments = iterator_to_array(

				$model::get('froom_comments', [

					'criterion' => 'froom_id::'. (int)PASS[ 3 ],
					'course'	=> 'forward',
					'sort'		=> 'id'

				])

			)['model::froom_comments'];

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

		$render_node .= '<h2><a href="/forum/'. PASS[ 2 ] .'/'. PASS[ 3 ] .'/#comment-'. $c['id'] .'">&#8226;'. $c['id'] .'</a> '. TRANSLATIONS[ $ipl ]['by'] .' <span>'. $comment_user['nickname'] .'</span></h2>';

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

		if( in_array(ROLE, ['Admin', 'Writer']) || USER['name'] === $comment_user['nickname'] ) {

				$quick_edit_attr = ' contenteditable="false"';
				$quick_edit_data = ' data-node="'. $c['id'] .'" data-type="forum-comment" data-user="'. $comment_user['nickname'] .'"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}

		$render_node .= '<div class="revolver__comments-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $markup::Markup( 

				htmlspecialchars_decode( 

					html_entity_decode( 

						$c['content'] 

					)

				), [ 'lazy' => 1 ] ) .'</div>';


		if( $comment_user['id'] === USER['id'] || in_array(ROLE, ['Admin', 'Writer']) ) {

			$render_node .= '<footer class="revolver__comments-footer"><nav><ul>';
			$render_node .= '<li class="revolver__quick-edit-handler" title="'. TRANSLATIONS[ $ipl ]['qedit'] .'">[ '. TRANSLATIONS[ $ipl ]['QEdit'] .' ]</li>';
			$render_node .= '<li><a title="'. $c['id'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="/forum/'. PASS[ 2 ] .'/'. PASS[ 3 ] .'/comment/'.  $c['id'] .'/edit/">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';
			$render_node .= '</ul></nav></footer>';

		}

		$render_node .= '</article>';

	}

}

$render_node .= '</section>';

?>
