<?php

 /*
  * 
  * Search Route
  *
  * v.1.9.3
  *
  *
  *
  *
  *               ^
  *              | |
  *            @#####@
  *          (###   ###)-.
  *        .(###     ###) \
  *       /  (###   ###)   )
  *      (=-  .@#####@|_--"
  *      /\    \_|l|_/ (\
  *     (=-\     |l|    /
  *      \  \.___|l|___/
  *      /\      |_|   /
  *     (=-\._________/\
  *      \             /
  *        \._________/
  *          #  ----  #
  *          #   __   #
  *          \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( isset(SV['g']['query']) ) {

  $qs = SV['g']['query']['value'];

  $output  = '<div class="revolver__search_results">';

  $output .= '<p>'. TRANSLATIONS[ $ipl ]['Search for'] .' <b>'. $qs .'</b>.</p>';

  $output .= '<ul>';

  function search( string $qs, iterable $v, string $m ): string {

    if( $m === 'topic' ) {

      $url = '/forum/'. $v['forum_id'] .'/'. $v['id'] .'/';

    } 
    else {

      $url = $v['route'];

    }

      $output = '<li>'. TRANSLATIONS[ 'EN' ][ $m ] .':: <a href="'. $url .'" title="'. $v['description'] .'">'.  str_ireplace( $qs, '<mark>'. $qs .'</mark>', $v['title'] ) .'</a><span>'. str_ireplace( $qs, '<mark>'. $qs .'</mark>', $v['description'] ) .'</span>';

      $replace = trim(

        preg_replace(

          '/ +/', ' ', 

          preg_replace(

            '/~\w*~/', ' ',

            strip_tags(

              html_entity_decode(

                strip_tags(

                  preg_replace(

                    '/<[^>]*>/', '',

                    str_replace(

                      [ '&nbsp;', "\n", "\r" ], 

                      '',

                      html_entity_decode(

                          $v['content'], ENT_QUOTES, 'UTF-8'

                      )

                    )

                  )

                )

              )

            )

          )

        )

      );


      $snippet = preg_split('/'. $qs .'/i', $replace);

      $c = 1;

      foreach( $snippet as $snip ) {

        $length = strlen( $snip ) * .3;

        if( $c % 2 !== 0 ) {

          $highlight_1 = substr( $snip, $length, 0 );    

        }
        else {

          $highlight_2 = substr( $snip, 0, $length );

        }

        $c++;

      }

      return $output . '<dfn>... '. $highlight_1 . '<mark>'. $qs .'</mark>'. preg_replace('/[\x{10000}-\x{10FFFF}]/u', '\xEF\xBF\xBD', $highlight_2) .' :: <span>'. $v['time'] .'</span> ...</dfn></li>';

  }


  // Nodes search
  foreach( $all_nodes as $k => $v ) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'node' );

    }

  }


  // Blog search
  foreach( iterator_to_array(

    $model::get( 'blog_nodes', [

      'criterion' => 'id::*',

      'bound'   => [

        20,   // limit

      ],

      'course'  => 'backward', // backward
      'sort'    => 'time',

    ])

  )['model::blog_nodes'] as $k => $v) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'blog' );

    }

  }

  // Wiki search
  foreach( iterator_to_array(

    $model::get( 'wiki_nodes', [

      'criterion' => 'id::*',

      'bound'   => [

        20,   // limit

      ],

      'course'  => 'backward', // backward
      'sort'    => 'time',

    ])

  )['model::wiki_nodes'] as $k => $v) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'Wiki' );

    }

  }

  // Forum search
  foreach( iterator_to_array(

    $model::get( 'forum_rooms', [

      'criterion' => 'id::*',

      'bound'   => [

        20,   // limit

      ],

      'course'  => 'backward', // backward
      'sort'    => 'time',

    ])

  )['model::forum_rooms'] as $k => $v) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'topic' );

    }

  }

  $output .= '</ul>';

  $output .= '<p>'. TRANSLATIONS[ $ipl ]['Search for'] .' <b>'. $qs .'</b>.</p>';

  $output .= '</div>';

}

print $output;

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/search/'

]);

?>
