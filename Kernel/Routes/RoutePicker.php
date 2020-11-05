<?php

 /* 
  * 
  * RevolveR Route Picker
  *
  * v.1.9.4.9
  *
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
  */

if( isset(SV['g']['host']) && in_array(ROLE, ['Admin', 'Writer']) ) {

  $url = filter_var('https://'. SV['g']['host']['value'], FILTER_VALIDATE_URL);

  function getUri(string $url): iterable {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_DNS_SHUFFLE_ADDRESSES, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FILETIME, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $data = curl_exec($ch);

    if( !curl_errno($ch) ) {

      $i = curl_getinfo($ch);

      $ssl_pass = (int)$i['ssl_verify_result']; 

      if( !(bool)$ssl_pass ) {

        $ok = true;

      }

      switch( $i['http_code'] ) {

        case 200:
        case 301:
        case 302:

          $ok = true;

          break;
        
        default:

          $ok = null;

          break;
      }

      switch( explode(';', $i['content_type'])[0] ) {

        case 'application/xhtml+xml':
        case 'text/html':

          $ok = true;

          break;
        
        default:

          $ok = null;

          break;

      }

      if( $data && $ok ) {

        curl_close($ch);

        return [ $data,  $i['fileatime'] ];

      } 
      else {

        curl_close($ch);

        return [ null, null ];

      }

    } 
    else {

      curl_close($ch);

      return [ null, null ];

    }

  }

  function getMetaTags(string $str): iterable {

    $pattern = '~<\s*meta\s

    # using lookahead to capture type to $1
      (?=[^>]*?
      \b(?:name|property|http-equiv)\s*=\s*
      (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
      ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
    )

    # capture content to $2
    [^>]*?\bcontent\s*=\s*
      (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
      ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
    [^>]*>

    ~ix';
   
    if( preg_match_all($pattern, $str, $out) ) {

      return array_combine( $out[1], $out[2] );

    }

    return [];

  }

  function getHost(string $uri, string $url): ?string {

    $segments = parse_url(

      str_ireplace('.www', '', $uri)

    );

    $r = null;

    if( isset($segments['host']) ) {

      $r = $segments['host'];

    } 
    else {

      $r = parse_url(

        str_ireplace('.www', '', $url)

      );

      $r = $r['host'];

    }

    return $r;

  }

  function parse(string $html, string $url): ?iterable {

    $host_links = [];

    // Perform title
    preg_match_all('#<title>(.+?)</title>#su', $html, $meta_title);

    // Perform body
    preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $meta_body);

    // Perform links only for host
    preg_match_all('/<a.*?href=["\'](.*?)["\'].*?>/i', $html, $meta_links);

    foreach( $meta_links[1] as $l ) {

      $flnk = getHost($l, $url);

      if( getHost($url, $url) === $flnk ) {

        $lnk  = parse_url($l);

        $xlnk = parse_url($url)['scheme'] .'://'. getHost($url, $url);

        if( isset($lnk['path']) ) {

          $xlnk .= $lnk['path'];

        }

        if( isset($lnk['query']) ) {

          $xlnk .= '?'. $lnk['path'];

        }

        $host_links[] = $xlnk;

      }

    }

    return [

      'title' => $meta_title[1][0],
      'meta'  => getMetaTags($html),
      'href'  => array_unique($host_links),
      'text'  => trim(

                  html_entity_decode(

                    preg_replace([

                        '/<script\b[^>]*>(.*?)<\/script>/si',
                        '/<style\b[^>]*>(.*?)<\/style>/si',
                        '/<.+?>/mi', 
                        '/<a[^>]+\>/i', 
                        '/\s*$^\s*/m', 
                        '/[\r\n]+/', 
                        '/\s+/'

                      ], 
                      
                      [
                        '',
                        '',
                        '',
                        '',
                        "\n",
                        "\n",
                        ' '

                      ], $meta_body)[0]

                  )

      ),

      'body'  =>  $meta_body

    ];

  }

  $xdata = getUri($url)[0]; // todo :: test file-a-time [1]

  if( $xdata ) {

    $meta_data = parse(

      $xdata, $url

    );

    foreach( $meta_data['href'] as $uri ) {

      $testIndex = iterator_to_array(

        $model::get('index', [

          'criterion' => 'uri::'. $uri,
          'course'    => 'backward',
          'sort'      => 'id'

        ])

      )['model::index'];

      if( $testIndex ) {

        $testIndex = $testIndex[0];

        if( date('d-m-Y') !== $testIndex['date'] ) {

          $udata = getUri($uri)[0]; // todo :: test file-a-time [1]

          if( $udata ) {

            $xmeta_data = parse(

              $udata, $uri

            );

            // Intelligent update when uri exist and expired
            $model::set('index', [

              'uri'         => $uri,
              'host'        => getHost($url, $url),
              'date'        => date('d-m-Y'),
              'title'       => $xmeta_data['title'],
              'description' => (isset( $xmeta_data['meta']['og:description'] ) ? $xmeta_data['meta']['og:description'] : (isset($xmeta_data['meta']['description']) ? $xmeta_data['meta']['description'] : 'null')),
              'content'     => $xmeta_data['text'],
              'criterion'   => 'uri'

            ]);

          }

        }

      } 
      else {

        $udata = getUri($uri)[0]; // todo :: test file-a-time [1]

        if( $udata ) {

          $xmeta_data = parse(

            $udata, $uri

          );

          // Intelligent insert when uri not indexed
          $model::set('index', [

            'id'          => 0,
            'uri'         => $uri,
            'host'        => getHost($url, $url),
            'date'        => date('d-m-Y'),
            'title'       => $xmeta_data['title'],
            'description' => (isset( $xmeta_data['meta']['og:description'] ) ? $xmeta_data['meta']['og:description'] : (isset($xmeta_data['meta']['description']) ? $xmeta_data['meta']['description'] : 'null')),
            'content'     => $xmeta_data['text'],

          ]);

        }

      }

      sleep(3);

    }

  }

}

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/picker/'

]);

?>
