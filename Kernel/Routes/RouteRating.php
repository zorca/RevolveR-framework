<?php

 /* 
  * 
  * RevolveR Route Contents Rating Dispatch
  *
  * v.1.9.4.6
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

if(!empty(SV['p'])) {

  $node = $user = $value = $type = null;

  if( isset(SV['p']['revolver_rating_node']) ) {

    if( (bool)SV['p']['revolver_rating_node']['valid'] ) {

      $node = SV['p']['revolver_rating_node']['value'];

    }

  }

  if( isset(SV['p']['revolver_rating_user']) ) {

    if( (bool)SV['p']['revolver_rating_user']['valid'] ) {

      $user = SV['p']['revolver_rating_user']['value'];

    }

  }

  if( isset(SV['p']['revolver_rating_value']) ) {

    if( (bool)SV['p']['revolver_rating_value']['valid'] ) {

      $value = SV['p']['revolver_rating_value']['value'];

    }

  }

  if( isset(SV['p']['revolver_rating_type']) ) {

    if( (bool)SV['p']['revolver_rating_type']['valid'] ) {

      $type = SV['p']['revolver_rating_type']['value'];

    }

  }

  if( $node && $user && $value && $type ) {


    switch( $type ) {

      case 'node':

        $model::set('nodes_ratings', [

          'node_id'     => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'blog':

        $model::set('blog_ratings', [

          'node_id'     => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'store':

        $model::set('goods_ratings', [

          'node_id'     => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'node-comment':

        $model::set('comments_ratings', [

          'comment_id'  => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'blog-comment':

        $model::set('blog_comments_ratings', [

          'comment_id'  => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'store-comment':

        $model::set('store_comments_ratings', [

          'comment_id'  => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;


    }

  }

}

print '<!-- route contents rating dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/rating-d/'

]);

?>
