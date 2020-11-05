<?php

 /*
  * 
  * Search Route
  *
  * v.1.9.4.9
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

$output = '<p>'. TRANSLATIONS[ $ipl ]['Welcome Pick networks'] .'!</p>';

$query = null;

if( !empty(SV['p']) ) {

  if( isset(SV['p']['revolver_pick_query']) ) {

    if( (bool)SV['p']['revolver_pick_query']['valid'] ) {

      $query = SV['p']['revolver_pick_query']['value'];

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

$form_parameters = [

  // main para
  'id'      => 'pick-query-box',
  'class'   => 'revolver__pick-query-box revolver__new-fetch',
  'method'  => 'post',
  'action'  => RQST,
  'encrypt' => true,
  'captcha' => true,
  'submit'  => 'Pick it',

  // included fieldsets
  'fieldsets' => [

    // fieldset contents parameters
    'fieldset_1' => [

      'title' => 'Pick query box',
      
      // wrap fields into label
      'labels' => [

        'label_1' => [

          'title'  => 'Query phrase',
          'access' => 'comment',
          'auth'   => 'all',

          'fields' => [

            0 => [

              'type'        => 'input:text',
              'name'        => 'revolver_pick_query',
              'placeholder' => 'Query phrase',
              'required'    => true

            ],

          ],

        ],

      ],

    ],

  ],

];

// Picks query box
$output .= $form::build( $form_parameters );

if( defined('form_pass') ) {

  if( form_pass === 'pass' ) {

    if( $query ) {

      $qs = $query;

      $output  .= '<div class="revolver__search_results">';

      $output .= '<p>'. TRANSLATIONS[ $ipl ]['Search for'] .' <b>'. $qs .'</b>.</p>';

      $output .= '<ol>';

      function search( string $qs, iterable $v ): string {

        $ptitle = htmlspecialchars_decode($v['title']);
        $pdescr = htmlspecialchars_decode($v['description']);

        if( $pdescr === 'null' ) { // use short snippet of content as description

          $pdescr = preg_replace("#[^а-яА-ЯA-Za-z:;._,? -]+#u", '', substr(

            html_entity_decode(

                $v['content']

            ), 0, 100)

          ) .'...';

        } 
        else {

          $pdescr = preg_replace("#[^а-яА-ЯA-Za-z:;._,? -]+#u", '', substr(

            html_entity_decode(

                $pdescr

            ), 0, 100) .'...'

          );

        }

        $output  = '<li>';

        $output .= '<a target="_blank" href="'. $v['uri'] .'" title="'. $pdescr .'">';
        $output .= str_ireplace( $qs, '<mark>'. $qs .'</mark>', $ptitle) .'</a>';

        $output .= '<em>'. (isset($v['time']) ? $v['time'] : date('d-m-Y h:i')) .'</em>';

        $output .= '<span>'. str_ireplace( $qs, '<mark>'. $qs .'</mark>', $pdescr ) .'</span>';

        $replace = trim(

          preg_replace(

            ['/ +/', '/~\w*~/', '/<[^>]*>/' ],

            [' ', ' ', ''],

            str_replace(

              [ '&nbsp;', "\n", "\r" ], 

              '',

              html_entity_decode(

                $v['content'], ENT_QUOTES, 'UTF-8'

              )

            )

          )

        );

        $snippet = preg_split('/'. $qs .'/i', $replace);

        $c = 1;

        foreach( $snippet as $snip ) {

          $length  = strlen( $snip ) * .3;

          $xlength = strlen( explode( $qs, $snip )[0] ); 


          if( $c % 2 !== 0 ) {

            $highlight_1 = substr( $snip, $xlength * .3, $xlength );

          }
          else {

            $highlight_2 = substr( $snip, 0, $length );

          }

          $c++;

        }

        $output .= '<dfn class="revolver__search-snippet">... '. preg_replace("#[^а-яА-ЯA-Za-z:;._,? -]+#u", '', $highlight_1) . '<mark>'. $qs .'</mark>'. preg_replace("#[^а-яА-ЯA-Za-z:.;_,? -]+#u", '', $highlight_2) .' ...</dfn></li>';

        return $output;

      }

      // Picking results
      $results = [];

      // Index picking
      foreach( iterator_to_array(

        $model::get( 'index', [

          'criterion' => 'content::'. $qs,

          'bound'   => [

            5000,   // limit

          ],

          'course'  => 'backward', // backward
          'expert'  => true,
          'sort'    => 'id'

        ])

      )['model::index'] as $k => $v ) {

        if( preg_match('/'. $qs .'/i', $v['content']) ) {

          $results[] = search( $qs, $v );

        }

      }

      // Shuffle results
      shuffle($results);

      foreach( $results as $r ) {

        $output .= $r;

      }

      $output .= '</ol>';

      $output .= '<p>'. TRANSLATIONS[ $ipl ]['Search for'] .' <b>'. $qs .'</b>.</p>';

      $output .= '</div>';

    }

  }

}

$node_data[] = [

  'title'     => TRANSLATIONS[ $ipl ]['Pick'],
  'id'        => 'pick',
  'route'     => '/pick/',
  'contents'  => $output,
  'teaser'    => null,
  'footer'    => null,
  'published' => 1

];

?>
