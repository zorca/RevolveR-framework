<?php

 /*
  * 
  * Sitemap Route :: Generate sitemap
  *
  * v.1.9.4
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

$sitemap = '<?xml version="1.0" encoding="UTF-8" ?>'. "\n";
$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'. "\n";

foreach( iterator_to_array(

		$model::get( 'nodes', [

			'criterion' => 'id::*',

			'bound'		=> [

				0,   // limit

			],

			'course'	=> 'backward', // backward
			'sort' 		=> 'time',

		]),

	)['model::nodes'] as $node => $n) {

	$sitemap .= ' <url>'. "\n";

		$date = explode('.', explode(' ', $n['time'])[0]);

		$datetime = $date[2] .'-'. $date[1] .'-'. $date[0]; 

		$sitemap .= '<loc>'. site_host . $n['route'] .'</loc>'. "\n";
		$sitemap .= '<lastmod>'. $datetime .'</lastmod>'. "\n";

		$sitemap .= '<changefreq>monthly</changefreq>'. "\n";
		$sitemap .= '<priority>.9</priority>'. "\n";

	$sitemap .= ' </url>'. "\n\n";

}

foreach( iterator_to_array(

		$model::get( 'blog_nodes', [

			'criterion' => 'id::*',

			'bound'		=> [

				0,   // limit

			],

			'course'	=> 'backward', // backward
			'sort' 		=> 'time',

		]),

	)['model::blog_nodes'] as $bnode => $n) {

	$sitemap .= ' <url>'. "\n";

		$date = explode('-', 

					str_replace('.', '-', explode(' ', $n['time'])[0])
		);

		$datetime = $date[2] .'-'. $date[1] .'-'. $date[0]; 

		$sitemap .= '<loc>'. site_host . $n['route'] .'</loc>'. "\n";
		$sitemap .= '<lastmod>'. $datetime .'</lastmod>'. "\n";

		$sitemap .= '<changefreq>monthly</changefreq>'. "\n";
		$sitemap .= '<priority>.9</priority>'. "\n";

	$sitemap .= ' </url>'. "\n\n";

}

foreach( iterator_to_array(

		$model::get( 'wiki_nodes', [

			'criterion' => 'id::*',

			'bound'		=> [

				0,   // limit

			],

			'course'	=> 'backward', // backward
			'sort' 		=> 'time',

		]),

	)['model::wiki_nodes'] as $node => $n) {

	$sitemap .= ' <url>'. "\n";

		$date = explode('.', explode(' ', $n['time'])[0]);

		$datetime = $date[2] .'-'. $date[1] .'-'. $date[0]; 

		$sitemap .= '<loc>'. site_host . $n['route'] .'</loc>'. "\n";
		$sitemap .= '<lastmod>'. $datetime .'</lastmod>'. "\n";

		$sitemap .= '<changefreq>monthly</changefreq>'. "\n";
		$sitemap .= '<priority>.9</priority>'. "\n";

	$sitemap .= ' </url>'. "\n\n";

}

$sitemap .= '</urlset>';

print $sitemap;

define('serviceOutput', [

  'ctype'     => 'application/xml',
  'route'     => '/search/'

]);

?>
