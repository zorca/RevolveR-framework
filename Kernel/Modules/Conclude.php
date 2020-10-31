<?php
 
 /* 
  * 
  * Conclude caches and templates
  *
  * make exit and frees resources
  *
  * v.1.9.4.9
  *
  *                   ^
  *                  | |
  *                @#####@
  *              (###   ###)-.
  *            .(###     ###) \
  *           /  (###   ###)   )
  *          (=-  .@#####@|_--"
  *          /\    \_|l|_/ (\
  *         (=-\     |l|    /
  *          \  \.___|l|___/
  *          /\      |_|   /
  *         (=-\._________/\
  *          \             /
  *            \._________/
  *              #  ----  #
  *              #   __   #
  *              \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

final class Conclude {

	/* Get template cache for defined URI */
	public static function getCacheFile( ?iterable $f ): string {

		$c = '0';

		if( !empty( SV[ 'p' ] ) ) {

			return $c;

		}

		if( defined('ROUTE') ) {

			if( ROUTE['route'] === '/logout/' ) {

				return $c;

			}

		}

		$prm = !isset( $f[ 1 ] ) ? '' : '-'. str_replace('=', '-', $f[ 1 ] );

		$uri = defined('ROUTE') ? ltrim( ROUTE['node'], '#' ) . $prm : 'contents_'. $prm;

		// Files
		$files = [

			'preload'	=> TCache . $uri . rtrim( str_replace( '/', '_', RQST ), '_' ) .'-'. md5( $uri ) .'.tpreload',
			'cache'		=> TCache . $uri . rtrim( str_replace( '/', '_', RQST ), '_' ) .'-'. md5( $uri ) .'.tcache'

		];

		foreach( $files as $f => $p ) {

			switch( $f ) {

				case 'preload':

					if( is_readable($p) ) {

						$preload_parts = file_get_contents( $p );

						if( defined('PrefetchesList') ) {

							$preload_parts = 'Link: '. PrefetchesList . ltrim( rtrim($preload_parts, ', '), 'Link: ');

						}

						header( $preload_parts );

					}

					break;

			}

			if( $f === 'cache') {

				if( is_readable($p) ) {

					$c = file_get_contents( $p );

				}

			}

		}

		return $c;

	}

	/* Public resources cache refresh */
	public static function publicResourcesCacheRefresh( string $m, iterable $res ): iterable {

		$stack = [];

		foreach( $res as $s ) {

			if( in_array( $s['part'], ['kernel', 'module'], true ) ) {

				$license = base64_decode(

					'CiAvKgogICogUmV2b2x2ZVIgRnJvbnQtZW5kIGtlcm5lbCBwYXJ0cwogICoKICAqIAogICoJ'.
					'CQkgICAgICAgICAgXgogICoJCQkgICAgICAgICB8IHwKICAqCQkJICAgICAgIEAjIyMjI0AK'.
					'ICAqCQkJICAgICAoIyMjICAgIyMjKS0uCiAgKgkJCSAgIC4oIyMjICAgICAjIyMpIFwKICAq'.
					'CQkJICAvICAoIyMjICAgIyMjKSAgICkKICAqCQkJICg9LSAgLkAjIyMjI0B8Xy0tJwogICoJ'.
					'CQkgL1wgICAgXF98bHxfLyAoXAogICoJCQkoPS1cICAgICB8bHwgICAgLwogICoJCQkgXCAg'.
					'XC5fX198bHxfX18vCiAgKgkJCSAvXCAgICAgIHxffCAgIC8KICAqCQkJKD0tXC5fX19fX19f'.
					'X18vXAogICoJCQkgXCAgICAgICAgICAgICAvCiAgKgkJCSAgIFwuX19fX19fX19fLwogICoJ'.
					'CQkgICAgICMgIC0tLS0gICMKICAqCQkJICAgICAjICAgX18gICAjCiAgKgkJCSAgICAgXCMj'.
					'IyMjIyMjLwogICoKICAqCiAgKgogICogRGV2ZWxvcGVyOiBEbWl0cnkgTWFsdHNldgogICog'.
					'TGljZW5zZTogQXBhY2hlIDIuMAogICoKICAqLwog'

				);

			}
			else {

				$license = '';

			}

			$rsc = file_get_contents( site_host . $s['path'] );

			$src = $license ."\n". ((bool)$s['min'] ? ( new Minifier() )::minify( $rsc ) : preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '', $rsc));

			$root = $_SERVER['DOCUMENT_ROOT'];

			switch( $m ) {

				case 'script':

					$ext = '.es7';

					$dir = '/public/cache/scripts/';

					break;

				case 'style':

					$ext = '.css';

					$dir = '/public/cache/styles/';

					break;

			}

			if( (bool)strlen($src) ) {

				$Name = explode('/', $s['path']); 

				$File = str_replace('=', '', base64_encode( hash('md2', $src))) . ((bool)$s['min'] ? '.min'. $ext : $ext);

				$resFile = str_ireplace( ['.js', '.css'], ['', ''], $Name[ count($Name) - 1 ] ); 

						if( !is_readable( $root . $dir . $resFile .'-'. $File) ) {

							// Clean static interface files cache
							foreach( scandir( $root . $dir ) as $cf) {

								if( !in_array($cf, ['.', '..']) && !is_dir( $root . $dir . $cf ) ) {

								if( (bool)preg_match('/'. $resFile .'/i', $cf) ) {

									unlink($root . $dir . $cf);

								}

							}

							// Clean templates cache when static interface files changed 
							foreach( array_diff(

										scandir('./cache/tplcache/', 1), [ '..', '.' ]

									) as $file ) {

									unlink( './cache/tplcache/'. $file );

							}

						}

						$CacheFile = fopen( $root . $dir . $resFile .'-'. $File, 'w' );

						fwrite( $CacheFile, $src );

						fclose( $CacheFile );

					}

				}

				$Hash = shell_exec('openssl dgst -sha'. $s['alg'] .' -binary '. $root . $dir . $resFile .'-'. $File .' | openssl base64 -A');

				switch( $m ) {

					case 'script':

						$Tag = '<script data-part="'. $s['name'] .'" src="'. site_host . $dir . $resFile .'-'. $File .'" integrity="sha'. $s['alg'] .'-'. $Hash .'" crossorigin="use-credentials"';

					switch( $s['part'] ) {

						case 'kernel':

							$Tag .= '></script>';

							break;

						case 'module':

							$Tag .= ' defer="defer"></script>';

							break;

					}

					break;

					case 'style':

						$attr = 'rel="stylesheet" ';

						if( (bool)$s['defer'] ) {

							$attr = 'rel="preload" as="style"';

						}

						$Tag = '<link data-part="'. $s['name'] .'" media="all" '. $attr .' href="'. site_host . $dir . $resFile .'-'. $File .'" integrity="sha'. $s['alg'] .'-'. $Hash .'" crossorigin="use-credentials" />';

					break;

				}

			$stack[] = $Tag;

		}

		return $stack;

	}

	/* Template futures */
	public static function Template( string $type = 'text/html' ): string {

		#header('Content-Type: '. $type .'; charset=utf-8');

		$R = 'Null'; $dir = './Templates/'. TEMPLATE .'/';

		if( N && RQST !== '/' ) {

			if( PASS[ count( PASS ) - 2 ] === 'edit' ) {

				$R = 'Template';

			}

			if( PASS[ 1 ] === 'forum' ) {

				$R = 'Template';

			}

			if( PASS[ 1 ] === 'blog' ) {

				$R = 'Template';

			}

		}
		else {

			$R = 'Template';

		}

		return $dir . $R .'.php';

	}

	/* Decorate log lines */
	public static function stringDecorator( iterable $s, string $symbol = '.', int $length = 57 ): string {

		$r = '';

		$length -= $symbol === '#' ? 2 : 0;

		if( isset( $s[ 1 ] ) ) {

			$s[ 0 ] = '[ '. $s[ 0 ];

			$s[ 1 ] = $s[ 1 ] .' ]';

		}

			$l = $length - strlen( $s[ 0 ] );

		if( isset( $s[ 1 ] ) ) {

			$l = $length - ( strlen($s[ 1 ]) + strlen($s[ 0 ]) );

		}

		// Call Spaceship
		if( (bool)( $l ) <=> 0 ) {

			$r = '.. '. $s[ 0 ] . ( $symbol !== '#' ? ' ' : $symbol ) . str_repeat( 

				$symbol, $l 

			);

			if( !isset( $s[ 1 ] ) ) {

				$r = rtrim($r, ' ');

			}

		}

		return $r . ( $symbol !== '#' ?( !isset( $s[ 1 ] ) ? '.' : ' ' ) : $symbol ) . ( isset( $s[ 1 ] ) ? $s[ 1 ] : '' ) .' ..';

	}

	/* Performance */
	public static function Performance( int $state ): string {

	$datum = [];

	if( INSTALLED ) {

			$datum[] = '';
			$datum[] = '';
			$datum[] = '<!--';
			$datum[] = '';
			$datum[] = self::stringDecorator([ '' ], '#' );
			$datum[] = '';

			$datum[] =  self::stringDecorator([ '⇒ RevolveR Contents Management Framework', 'v.'. rr_version ], '.' );
			$datum[] = '';

			if( defined('Status_DBX') ) {

				$datum[] = self::stringDecorator([ !(bool)Status_DBX[ 3 ] ? 'ℜ' : '∝ Data Base connections', !(bool)Status_DBX[ 3 ] ? 'Flowless ease' : Status_DBX[ 3 ] ], '.');
				$datum[] = self::stringDecorator([ '★ Data Base X cache queries', Status_DBX[ 1 ] ], '.');

				if( (bool)Status_DBX[ 3 ] ) {

					$datum[] = self::stringDecorator([ '☆ Data Base X queries', Status_DBX[ 0 ] ], '.');

				}

				$datum[] = self::stringDecorator([ '⊶ Data Base X hash queries', Status_DBX[ 2 ] ], '.');

			}

			$datum[] = self::stringDecorator([ '◬ Used Memory (Megabytes)', round( ( memory_get_usage() - MemoryStart ) / 1024 / 1024, 2 ) ], '.'); 
			$datum[] = self::stringDecorator([ '⊗ Accomplishment Time (Seconds)', round( ( microtime(true) - StartTime ), 3 ) ], '.');
			$datum[] = '';

			switch ( $state ) {

				case 1:

					$datum[] = self::stringDecorator([ '⊛ Status', 'Full Kernel run' ], '.');

					break;

				case 2:

					$datum[] = self::stringDecorator([ '⊙ Status', 'Partial Kernel run with cache' ], '.');

					break;

				case 3:

					$datum[] = self::stringDecorator([ '⊚ Status', 'no cache' ], '.');

					break;

			}

			$datum[] = '';
			$datum[] = self::stringDecorator([ '' ], '#' );
			$datum[] = '';
			$datum[] = '-->';
			$datum[] = '';

		}

		$output = '<meter value="0">' . "\n". implode( "\n", $datum ) ."\n" . '</meter>';

		if( defined('ROUTE') ) {

			if( in_array(ROUTE['route'], ['/sitemap/', '/aggregator/']) ) {

				$output = "\n". implode( "\n", $datum ) ."\n";

			}

		}

		return $output;

	}

	/* Save cache file */
	public static function saveCacheFile( string $output, string $uri, ?string $type = null ): ?bool {

		if( PASS[ count( PASS ) - 2 ] === 'edit' ) {

			return null;

		}

		// Exclude caches for page not found
		if( N ) {

			if( !defined('ROUTE') ) {

				return null;

			}

		}

		// Exclude URI's from caches
		if( defined('ROUTE') ) {

			if( in_array( ltrim( ROUTE['node'], '#' ), [ 

				'search', 
				'secure',
				'basket',
				'setup',
				'user', 
				'user-d',
				'rating-d', 
				'category-d', 
				'contents-d', 
				'comments-d', 
				'forum-d', 
				'forum-room-d',
				'forum-comments-d', 
				'blog-d', 
				'blog-comments-d',
				'wiki-d',
				'wiki-node-d', 
				'store-goods-d',
				'store-goods-edit-d',
				'terminal' 

			] ) ) {

				return null;

				if( in_array( ROUTE['route'], [ '/logout/', '/user/messages/' ] ) ) {

					return null;

				}

			}

		}

		if( $type === 'preload' ) {

			$ext = '.tpreload';

		}
		else {

			$ext = '.tcache';

		}

		$cache = fopen(TCache . $uri . rtrim( str_replace( '/', '_', RQST ), '_' ) .'-'. md5( $uri ) . $ext, 'w');

		fwrite( $cache, $output );

		fclose( $cache );

		return true;

	}

	/* Conclude Kernel with template and store cache */
	public static function Сonclude( string $type = 'text/html', ?iterable $uri = null ): string {

		header('Content-Type: '. $type .'; charset=utf-8');

		// Store buffer contents and close
		$b = trim( 

			ob_get_clean() 

		);

		$header  = '<!DOCTYPE html>'. "\n";
		$header .= '<html lang="'. main_language .'" xmlns="http://www.w3.org/1999/xhtml" prefix="og: https://ogp.me/ns#">'. "\n";

		$footer  = "\n". '<span class="revolver__privacy-key" data-xprivacy="'. base64_encode( '{ "xkey": "_s::'. session_id() .'"}' ) .'"></span>'. "\n\n";
		$footer .= "\n". '</body>';
		$footer .= "\n". '</html>' ."\n";

		// Filename prepare
		$prm = !isset( $uri[ 1 ] ) ? '' : '-'. str_replace( '=', '-', $uri[ 1 ] );

		$uri = defined('ROUTE') ? ltrim( ROUTE['node'], '#' ) . $prm : 'contents_'. $prm;

		// Preload list
		if( defined('PreloadList') ) {

			if( (bool)count(PreloadList) ) {

				$preload = 'Link: ';

				foreach( PreloadList as $preload_path ) {

					$preload .= '<'. $preload_path .'>; rel=preload; as=image, ';

				}

				self::saveCacheFile( rtrim($preload, ', '), $uri, 'preload' );

				if( defined('PrefetchesList') ) {

					header( 'Link: '. PrefetchesList . ltrim(rtrim($preload, ', '), 'Link: ') );

				} 
				else {

					header( rtrim($preload, ', ') );

				}

			}

		} 
		else {

			if( defined('PrefetchesList') ) {

				header('Link: '. PrefetchesList);

			}

		}

		$isExtension = null;

		$isInstalled = null;

		$allow_cache = null;

		if( defined('ROUTE') ) {

			if( (bool)ROUTE['ext'] ) {

				$isExtension = true;

			}

			foreach( EXTENSIONS_SETTINGS as $e ) {

				if( $e['name'] === ltrim( ROUTE['node'], '#' ) ) {

					if( (bool)$e['installed'] ) {

						$isInstalled = true;

						if( (bool)$e['cache'] ) {

							$allow_cache = true;

						}

					}

					break;

				}

			}

		}

		// Compress exceptions
		if( $type === 'text/html' && !in_array( PASS[ 1 ], [ 

			'search',
			'user-d', 
			'category-d', 
			'contents-d', 
			'comments-d', 
			'forum-d', 
			'forum-room-d',
			'forum-comments-d',
			'blog-d', 
			'blog-comments-d',
			'wiki-d',
			'wiki-node-d',
			'store-goods-d',
			'store-goods-edit-d',
			'rating-d'

		] ) ) {

			if( !Auth ) {

				if( (!$isExtension && !$isInstalled) || ($isExtension && $allow_cache && $isInstalled) ) {

					self::saveCacheFile( $b, $uri );

				}

			}

			header('Content-Encoding: deflate');

			// Add performace to output
			$output = deflate_add(

				deflate_init( ZLIB_ENCODING_DEFLATE, [ 'level' => 9 ] ), 

				$header . $b ."\n". self::Performance( 1 ) ."\n". $footer, 

				ZLIB_NO_FLUSH | ZLIB_FINISH

			);

		}
		else {

			if( !Auth && !in_array( PASS[ 1 ], [ 'aggregator', 'sitemap' ] ) ) {

				if( (!$isExtension && !$isInstalled) || ($isExtension && $allow_cache && $isInstalled) ) {

					self::saveCacheFile( $b, $uri );

				}

			}

			if( defined('ROUTE') ) {

				if( ROUTE['node'] === '#secure' || ROUTE['node'] === '#terminal-s' ) {

					header('Content-Encoding: deflate');

					$output = deflate_add(

						deflate_init( ZLIB_ENCODING_DEFLATE, [ 'level' => 9 ] ),

						$b,

						ZLIB_NO_FLUSH | ZLIB_FINISH

					);

				}
				else {

					$output = $b ."\n". str_replace( ['<samp>', '</samp>'], ['', ''], self::Performance( 1 ) ) ."\n";

				}

			}
			else {

				$output = $b ."\n". self::Performance( 1 ) ."\n";

			}

		}

		#######################################################
		# Finalize output, free resources and stop the Kernel #
		#######################################################

		session_write_close();

		if( defined('NF') ) {

			if( NF ) {

				header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

				header('Status: 404 Not Found');

			}

		}

		exit( $output );

	}

	/* Conclude Kernel with cache */
	public static function ConcludeCache( string $type = 'text/html', string $cache ): void {

		header('Content-Type: '. $type .'; charset=utf-8');

		header('Content-Encoding: deflate');

		header('Connection: close');

		$header  = '<!DOCTYPE html>'. "\n";
		$header .= '<html lang="'. main_language .'" xmlns="http://www.w3.org/1999/xhtml" prefix="og: https://ogp.me/ns#">'. "\n";

		$footer  = "\n". '<br class="revolver__privacy-key" data-xprivacy="'. base64_encode( '{ "xkey": "_s::'. session_id() .'"}' ) .'" />'. "\n\n";
		$footer .= "\n". '</body>';
		$footer .= "\n". '</html>' ."\n";

		print deflate_add(

			deflate_init( ZLIB_ENCODING_DEFLATE, [ 'level' => 9 ] ),

			$header . $cache ."\n". self::Performance( 2 ) ."\n". $footer,

			ZLIB_NO_FLUSH | ZLIB_FINISH

		);

		session_write_close();

		exit(

			ob_get_clean()

		);

	}

	/* Is conclude of route allowed */
	public static function isAllowed( string $route ): ?bool {

		$a = null;

		foreach( main_nodes as $k => $v ) {

			if( $v['route'] === $route ) {

				$a = true;

			}

		}

		return $a;

	}

}

?>
