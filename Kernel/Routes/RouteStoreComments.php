<?php

 /* 
  * 
  * RevolveR Route Comment Dispatch
  *
  * v.1.9.4.9
  *
  *
  *
  *
  *
  *			          ^
  *			         | |
  *			       @#####@
  *			     (###   ###)-.
  *			   .(###     ###) \
  *			  /  (###   ###)   )
  *			 (=-  .@#####@|_--"
  *			 /\    \_|l|_/ (\
  *			(=-\     |l|    /
  *			 \  \.___|l|___/
  *			 /\      |_|   /
  *			(=-\._________/\
  *			 \             /
  *			   \._________/
  *			     #  ----  #
  *			     #   __   #
  *			     \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( in_array(ROLE, ['Admin', 'Writer', 'User'], true) ) {

	if( !empty(SV['p']) ) {

		$advanced_action = 'update';

		$action = null;

		$subscribe = null;

		$published = 0;

		$contents = '';

		if( in_array(ROLE, ['Admin', 'Writer', 'User'], true) ) {

			if( isset(SV['p']['revolver_comment_id']) ) {

				if( (bool)SV['p']['revolver_comment_id']['valid'] ) {

					$comment_id = SV['p']['revolver_comment_id']['value'];

				}

			}

			if( isset(SV['p']['revolver_comments_action_edit']) ) {

				if( (bool)SV['p']['revolver_comments_action_edit']['valid'] ) {

					$action = 'edit';

				}

			}

			if( isset(SV['p']['revolver_comments_action_delete']) ) {

				if( (bool)SV['p']['revolver_comments_action_delete']['valid'] ) {

					$advanced_action = 'delete';

				}

			}

			if( isset(SV['p']['revolver_comments_published']) ) {

				if( (bool)SV['p']['revolver_comments_published']['valid'] ) {

					$published = 1;

				}

			}

		}

		if( isset(SV['p']['revolver_comment_content']) ) {

			if( (bool)SV['p']['revolver_comment_content']['valid'] ) {

				$contents = $markup::Markup(

									html_entity_decode(

										htmlspecialchars_decode(

											SV['p']['revolver_comment_content']['value']

										)

									)

								);

			}

		}

		if( isset(SV['p']['revolver_comment_time']) ) {

			if( (bool)SV['p']['revolver_comment_time']['valid'] ) {

				$time = SV['p']['revolver_comment_time']['value'];

			}

		}

		if( isset(SV['p']['revolver_comment_user_id']) ) {

			if( (bool)SV['p']['revolver_comment_user_id']['valid'] ) {

				$user_id = SV['p']['revolver_comment_user_id']['value'];

			}

		}

		if( isset(SV['p']['revolver_node_id']) ) {

			if( (bool)SV['p']['revolver_node_id']['valid'] ) {

				$node_id = SV['p']['revolver_node_id']['value'];

			}

		}

		if( isset(SV['p']['revolver_node_route']) ) {

			if( (bool)SV['p']['revolver_node_route']['valid'] ) {

				$node_route = SV['p']['revolver_node_route']['value'];

			}

		}

	}

	if( $action === 'edit' ) {

		if( $advanced_action === 'delete' ) {

			// Delete comment
			$model::erase('store_comments', [

				'criterion' => 'id::'. $comment_id

			]);

			header('Location: '. site_host . $node_route .'?notification=comment-erased^'. $comment_id);

		}
		else {

			if( (bool)strlen( $contents ) ) {

				$model::set('store_comments', [

					'id'		=> $comment_id,
					'user_id'	=> $user_id,
					'node_id'	=> $node_id,
					'content'	=> $contents,
					'time'		=> date('d.m.Y h:m'),
					'published'	=> $published,

					'criterion'	=> 'id'

				]);

				header('Location: '. site_host . $node_route .'?notification=comment-updated^'. $comment_id .'#comment-'. $comment_id );

			}
			else {

				header('Location: '. site_host . '/blog/?notification=no-changes' );

			}

		}

	}
	else {

		$model::set('store_comments', [

			'node_id'	=> $node_id,
			'user_id'	=> $user_id,
			'content'	=> $contents,
			'time'		=> date('d.m.Y h:m'),
			'published'	=> 1

		]);

		$email_users = iterator_to_array(

			$model::get( 'users', [

				'criterion' => 'id::'. $user_id,

				'bound'		=> [

					1

				],

				'course'	=> 'forward',
				'sort'		=> 'id'

			])

		)['model::users'];

		if( $email_users ) {

			$user_id_to = $email_users[0]['id'];
			$user_email = $email_users[0]['email'];
			$user_name  = $email_users[0]['nickname'];

			$email  = '<p>'. TRANSLATIONS[ $ipl ]['Posted'];

			$email .= ' <a title="'. TRANSLATIONS[ $ipl ]['new comment'] .'" href="'. site_host . $node_route .'">';

			$email .= TRANSLATIONS[ $ipl ]['new comment'];

			$email .= '</a>!</p>';

			$mail::send( 

				$email_users[0]['email'], TRANSLATIONS[ $ipl ]['New comment for you contents'], $email

			);

		}

		$model::set('messages', [

			'user_id'	=> $user_id,
			'to'		=> $user_name,
			'from'		=> 'Administrator',

			'message'	=> $markup::Markup(

				htmlspecialchars_decode( 

					html_entity_decode( 

						'<p>'. TRANSLATIONS[ $ipl ]['Hello'] .', '. $user_name .'! '. TRANSLATIONS[ $ipl ]['Posted'] .' <a title="'. TRANSLATIONS[ $ipl ]['new comment'] .'" href="'. $node_route .'">'. TRANSLATIONS[ $ipl ]['new comment'] .'</a>!</p>'

					)

				)
				
			),

			'time' => date('d.m.Y h:m')

		]);

		header( 'Location: '. site_host . $node_route .'?notification=comment-added^' . ($subscribed ? 'subscribed' : 'not-subscribed'));

	}

}

print '<!-- Blog comments dispatch -->';

define('serviceOutput', [

	'ctype'	=> 'text/html',
	'route'	=> '/blog-comments-d/'

]);

?>