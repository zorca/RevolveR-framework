<?php

 /* 
  * 
  * RevolveR Route Contents Dispatch
  *
  * v.1.9.4
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

if( Auth ) {

  if( in_array( ROLE, ['Admin', 'Writer', 'User'] ) ) {

    $forum_room = null;

    if(!empty(SV['p'])) {

      $token_explode = explode('|', $cipher::crypt('decrypt', SV['c']['usertoken']));

      $files_to_delete = [];

      $action = null;

      $published = 0;

      // Files
      $f_limit = 0;

      while( $f_limit <= 100 ) {

        if( isset(SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]) ) {

          $files_to_delete[ $f_limit ] = SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]['value'];

        }

        $f_limit++;

      }

      if( isset(SV['p']['revolver_node_edit_id']) ) {

        if( (bool)SV['p']['revolver_node_edit_id']['valid'] ) {

          $blog_id = SV['p']['revolver_node_edit_id']['value'];

        }

      }


      if( isset(SV['p']['revolver_node_published']) ) {

        if( (bool)SV['p']['revolver_node_published']['valid'] ) {

          $published = SV['p']['revolver_node_published']['value'];

        }

      }


      if( isset(SV['p']['revolver_node_user']) ) {

        if( (bool)SV['p']['revolver_node_user']['valid'] ) {

          $blog_user = SV['p']['revolver_node_user']['value'];

        }

      }


      if( isset(SV['p']['revolver_node_route']) ) {

        if( (bool)SV['p']['revolver_node_route']['valid'] ) {

          $blog_route = SV['p']['revolver_node_route']['value'];

        }

      }

      if( isset(SV['p']['revolver_blog_edit_title']) ) {

        if( (bool)SV['p']['revolver_blog_edit_title']['valid'] ) {

          $blog_title = strip_tags( SV['p']['revolver_blog_edit_title']['value'] );

        }

      }

      if( isset(SV['p']['revolver_blog_edit_description']) ) {

        if( (bool)SV['p']['revolver_blog_edit_description']['valid'] ) {

          $blog_description = strip_tags( SV['p']['revolver_blog_edit_description']['value'] );

        }

      }

      if( isset(SV['p']['revolver_blog_edit_content']) ) {

        if( (bool)SV['p']['revolver_blog_edit_content']['valid'] ) {

          $blog_content = $markup::Markup(

                  html_entity_decode(

                    htmlspecialchars_decode(

                      SV['p']['revolver_blog_edit_content']['value']

                    )

                  )

                );

        }

      }

      if( isset(SV['p']['revolver_blog_edit_delete']) ) {

        if( (bool)SV['p']['revolver_blog_edit_delete']['valid'] ) {

          $action = 'delete';

        }

      }

      if( isset(SV['p']['revolver_captcha']) ) {

        if( (bool)SV['p']['revolver_captcha']['valid'] ) {

          if( $captcha::verify(SV['p']['revolver_captcha']['value']) ) {

            define('form_pass', 'pass');

          }

        }

      }

    }

  } 
  else {

    header('Location: '. site_host .'/user/auth/?notification=authorization-reazon');

  }

  if( $token_explode[2] === $blog_user || in_array(ROLE, ['Admin', 'Writer'], true)  ) {

    if( $action !== 'delete' ) {

      $model::set('blog_nodes', [

        'id'          => $blog_id,
        'title'       => $blog_title,
        'content'     => $blog_content,
        'route'       => $blog_route,
        'time'        => date('d-m-Y h:i'),
        'description' => $blog_description,
        'published'   => $published,
        'criterion'   => 'id'

      ]);

      // Files
      if( (bool)count($files_to_delete) ) {

        foreach( $files_to_delete as $file_to_delete ) {

          // Delete from database
          $model::erase('blog_files', [

            'criterion' => 'id::'. explode(':', $file_to_delete)[0]

          ]);

          // Delete file from filesystem
          unlink( $_SERVER['DOCUMENT_ROOT'] . '/public/bfiles/' . explode(':', $file_to_delete)[1] );

        }

      }

      if( (bool)count(SV['f']) ) {

        foreach( SV['f'] as $file ) {

          foreach( $file as $f ) {

            $upload_allow = null;

            if( !is_readable($_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name']) ) {

              if( (bool)$f['valid'] ) {

                $upload_allow = true;

              }

            }

            if( $upload_allow ) {

              $model::set('blog_files', [

                'node'     => $blog_route,
                'name'      => $f['name'],
                'criterion' => 'node'

              ]);

              move_uploaded_file( $f['temp'], $_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name'] );

            }

          }

        }

      }

      header( 'Location: '. site_host . $blog_route );

    }
    else if( $action === 'delete' ) {

      $files = iterator_to_array(

        $model::get('blog_files', [

          'criterion' => 'node::'. $blog_route,
          'course'    => 'forward',
          'sort'      => 'id'

        ])

      )['model::blog_files'];

      if( $files ) {

        foreach( $files as $f ) {   

          unlink( $_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name'] );

        }

        // Delete from database
        $model::erase('blog_files', [

          'criterion' => 'node::'. $blog_route

        ]);

      }

      $model::erase('blog_comments', [

        'criterion' => 'node_id::'. $blog_id

      ]);

      $model::erase('blog_nodes', [

        'criterion' => 'id::'. $blog_id

      ]);

      header( 'Location: '. site_host .'/blog/?notification=node-erased^'. $blog_id );

    }

  }

}
else {

  header('Location: '. site_host .'?notification=no-changes');

}

print '<!-- blog dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/blog-d/'

]);

?>
