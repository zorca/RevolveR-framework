<?php

 /* 
  * 
  * RevolveR Route Comment Dispatch
  *
  * v.1.9.2
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

					SV['p']['revolver_comment_content']['value'], [ 'xhash' => 0 ]

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
			$model::erase('blog_comments', [

				'criterion' => 'id::'. $comment_id

			]);

			header('Location: '. site_host . $node_route .'?notification=comment-erased^'. $comment_id);

		}
		else {

			if( (bool)strlen( $contents ) ) {

				$model::set('blog_comments', [

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

		$model::set('blog_comments', [

			'node_id'	=> $node_id,
			'user_id'	=> $user_id,
			'content'	=> $contents,
			'time'		=> date('d.m.Y h:m'),
			'published'	=> 1

		]);

		header( 'Location: '. site_host . $node_route .'?notification=comment-added^' . 'not-subscribed');

	}

}

print '<!-- Blog comments dispatch -->';

define('serviceOutput', [

	'ctype'	=> 'text/html',
	'route'	=> '/blog-comments-d/'

]);

?>
