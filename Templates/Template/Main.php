
<!-- RevolveR :: main -->
<section class="revolver__main-contents <?php print $main_class; ?> <?php print $auth_class; ?>">

<?php

$mainWrap = true;

/* Edit Templates */
if( defined('ROUTE') ) {

	if( isset( ROUTE['edit'] ) ) {

		// Categories edit
		if( ROUTE['node'] === '#categories' && ROUTE['edit'] ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/categories-edit.php');

			$mainWrap = null;

		}

		// Profile edit
		if( ROUTE['node'] === '#user' && ROUTE['edit'] ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/profile-edit.php');

			$mainWrap = null;

		}

		// Forums edit
		if( ROUTE['node'] === '#forum' && (bool)ROUTE['edit'] && PASS[ 3 ] === 'edit' && empty( PASS[ 4 ] ) ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/forum-edit.php');

			$mainWrap = null;

		}

		if( ROUTE['node'] === '#forum' && (bool)ROUTE['edit'] && is_numeric(PASS[ 2 ]) && is_numeric(PASS[ 3 ]) && is_numeric(PASS[ 5 ]) ) {

			//var_dump('TESTING');

			require_once('./Templates/'. TEMPLATE .'/Forms/comments-forum-edit.php');

			$mainWrap = null;

		}

	}

}

if( !defined('ROUTE') ) {

	if( isset( PASS[ 3 ] ) ) {

		if( PASS[ 1 ] === 'comment' && PASS[ 3 ] === 'edit' ) {

			// Comments edit
			require_once('./Templates/'. TEMPLATE .'/Forms/comments-edit.php');

			$mainWrap = null;

		}

	}

}

?>

<?php if( $mainWrap ): 

	$render_node = '';

	$counter = 0;

	$shift = 0;

	if( is_array( $node_data ) ) {

		foreach( $node_data as $n ) {

			if( !isset($n['editor_mode']) ) {

				$n['editor_mode'] = null;

			}

			/* Pagination :: makes a render nodes only in pagination range */
			$pages_count = ceil( count($node_data) / $nodes_per_page );

			if( (bool)pagination['allow'] ) {

				if( (bool)pagination['offset'] ) {

					if( (bool)( pagination['offset'] - $nodes_per_page ) ) {

						if( $counter + $nodes_per_page >= pagination['offset'] ) {

							break;

						}

						if( $shift++ < ( pagination['offset'] - $nodes_per_page ) || $shift > ( pagination['offset'] ) ) {

							continue;

						}

					}
					else {

						if( $counter >= pagination['offset'] ) {

							break;

						}

					}

				}
				else {

					if( $counter >= $nodes_per_page ) {

						break;

					}

				}

			}

			// Node presets
			if( (bool)$n['published'] ) {

				$class = 'published';

			}
			else {

				$class = 'unpublished';

				if( in_array( ACCESS['role'], ['none', 'User'], true ) || ACCESS === 'none' ) {

					continue;

				}

			}

			if( !$n['editor_mode'] ) {

				if( RQST === $n['route'] ) {

					$allowView = true;

					if( defined('ROUTE') ) {

						if( !$resolve::isAllowed( ROUTE['route'] ) ) {

							$allowView = null;

						}

					}

					if( $allowView ) {

						/* Node views */
						empty($n['route']) ?: include('./Templates/'. TEMPLATE .'/Views/node-view.php');

						if( !$resolve::isAllowed( RQST ) && !(bool)pagination['offset'] ) {

							/* Comments views */
							include('./Templates/'. TEMPLATE .'/Views/comments-view.php');

							/* Comments add */
							if( PASS[ 1 ] !== 'forum' ) {

								require_once('./Templates/'. TEMPLATE .'/Forms/comments-add.php');

							}
							else {

								/* Comments views */
								include('./Templates/'. TEMPLATE .'/Views/comments-forum-view.php');

								if( Auth ) {

									require_once('./Templates/'. TEMPLATE .'/Forms/comments-forum-add.php');

								}

							}

						}

					}

				}
				else if( RQST === '/' ) {

					include('./Templates/'. TEMPLATE .'/Views/node-view.php');

				}

				/* Forum */

				if( defined('ROUTE') ) {

					if( ROUTE['node'] === '#forum' && is_numeric( PASS[ 2 ] ) || is_numeric( PASS[ 3 ] ) ) {

						if( PASS[ 3 ] !== 'edit' && empty( PASS[ 3 ] ) ) {

							include('./Templates/'. TEMPLATE .'/Views/forum-view.php');

						}

					}

				}

			}
			else {

				
				if( PASS[ 4 ] === 'edit' && RQST === $n['route'] . 'edit/' ) {

					/* Forum topic edit */
					require_once('./Templates/'. TEMPLATE .'/Forms/forum-room-edit.php');

				}
				else if( PASS[ 4 ] !== 'edit' && RQST === $n['route'] . 'edit/' ) {

					/* Node edit */
					require_once('./Templates/'. TEMPLATE .'/Forms/node-edit.php');

				}


			}

			$counter++;

		} /* end foreach */ 

	}

endif;

?>

<?php if( $pages_count > 1 && pagination['allow'] ) {

	require_once('./Templates/'. TEMPLATE .'/Pagination.php');

} ?>

<?php 

	// Preload contents
	define('PreloadList', $markup::$lazyList);

?>

<?php print $notify::Conclude(); ?>

<?php print $render_node; ?>

<!-- related -->
<?php require_once('Related.php'); ?>

</section>
