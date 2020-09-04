<?php

 /* 
  * 
  * RevolveR Route Contents Dispatch
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

if( Auth ) {

  if( in_array( ROLE, ['Admin', 'Writer', 'User'] ) ) {

    $forum_room = null;

    if(!empty(SV['p'])) {

      $token_explode = explode('|', $cipher::crypt('decrypt', SV['c']['usertoken']));

      $files_to_delete = [];

      $action = null;

      // Files
      $f_limit = 0;

      while( $f_limit <= 100 ) {

        if( isset(SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]) ) {

          $files_to_delete[ $f_limit ] = SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]['value'];

        }

        $f_limit++;

      }

      if( isset(SV['p']['revolver_froom_edit_id']) ) {

        if( (bool)SV['p']['revolver_froom_edit_id']['valid'] ) {

          $froom_id = SV['p']['revolver_froom_edit_id']['value'];

        }

      }

      if( isset(SV['p']['revolver_froom_route']) ) {

        if( (bool)SV['p']['revolver_froom_route']['valid'] ) {

          $froom_route = SV['p']['revolver_froom_route']['value'];

        }

      }

      if( isset(SV['p']['revolver_froom_edit_title']) ) {

        if( (bool)SV['p']['revolver_froom_edit_title']['valid'] ) {

          $froom_title = strip_tags( SV['p']['revolver_froom_edit_title']['value'] );

        }

      }

      if( isset(SV['p']['revolver_froom_edit_description']) ) {

        if( (bool)SV['p']['revolver_froom_edit_description']['valid'] ) {

          $froom_description = strip_tags( SV['p']['revolver_froom_edit_description']['value'] );

        }

      }

      if( isset(SV['p']['revolver_froom_edit_content']) ) {

        if( (bool)SV['p']['revolver_froom_edit_content']['valid'] ) {

          $froom_content = $markup::Markup(

            SV['p']['revolver_froom_edit_content']['value'], [ 'xhash' => 0 ]

          );

        }

      }

      if( isset(SV['p']['revolver_froom_edit_delete']) ) {

        if( (bool)SV['p']['revolver_froom_edit_delete']['valid'] ) {

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

      $forum_room = iterator_to_array(

        $model::get('forum_rooms', [

          'criterion' => 'id::'. (int)$froom_id,
          'course'  => 'backward',
          'sort'    => 'id'

        ])

      )['model::forum_rooms'][0];

    }

  } 
  else {

    header('Location: '. site_host .'/user/auth/?notification=authorization-reazon');

  }

  if( $forum_room ) {

    if( $token_explode[2] === $forum_room['user'] || in_array(ROLE, ['Admin', 'Writer', 'User'], true)  ) {

      if( $action !== 'delete' ) {

        $model::set('forum_rooms', [

          'id'          => $froom_id['id'],
          'title'       => $froom_title,
          'content'     => $froom_content,
          'description' => $froom_description,
          'criterion'   => 'id'

        ]);

        // Files
        if( (bool)count($files_to_delete) ) {

          foreach( $files_to_delete as $file_to_delete ) {

            // Delete from database
            $model::erase('froom_files', [

              'criterion' => 'id::'. explode(':', $file_to_delete)[0]

            ]);

            // Delete file from filesystem
            unlink( $_SERVER['DOCUMENT_ROOT'] . '/public/tfiles/' . explode(':', $file_to_delete)[1] );

          }

        }

        if( (bool)count(SV['f']) ) {

          foreach( SV['f'] as $file ) {

            foreach( $file as $f ) {

              $upload_allow = null;

              if( !is_readable($_SERVER['DOCUMENT_ROOT'] .'/public/tfiles/'. $f['name']) ) {

                if( (bool)$f['valid'] ) {

                  $upload_allow = true;

                }

              }

              if( $upload_allow ) {

                $model::set('froom_files', [

                  'froom'     => $froom_id,
                  'name'      => $f['name'],
                  'criterion' => 'node'

                ]);

                move_uploaded_file( $f['temp'], $_SERVER['DOCUMENT_ROOT'] .'/public/tfiles/'. $f['name'] );

              }

            }

          }

        }

        header( 'Location: '. site_host . $froom_route );

      }
      else if( $action === 'delete' && form_pass === 'pass' ) {

        $files = iterator_to_array(

          $model::get('froom_files', [

            'criterion' => 'froom::'. $froom_id,
            'course'    => 'forward',
            'sort'      => 'id'

          ])

        )['model::files'];

        if( $files ) {

          foreach( $files as $f ) {   

            unlink( $_SERVER['DOCUMENT_ROOT'] .'/public/tfiles/'. $f['name'] );

          }

          // Delete from database
          $model::erase('froom_files', [

            'criterion' => 'froom_id::'. $froom_id

          ]);

        }

        // Delete from database
        $model::erase('forum_rooms', [

          'criterion' => 'id::'. $froom_id

        ]);

        header( 'Location: '. site_host .'?notification=node-erased^'. $froom_id );

      }

    }

  } 
  else {

    header('Location: '. site_host .'?notification=no-changes');

  }

}
else {

  header('Location: '. site_host .'?notification=no-changes');

}

print '<!-- forum topic dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/contents-d/'

]);

?>
