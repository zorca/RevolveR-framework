<?php

 /*
  * 
  * RevolveR Node Categories 
  *
  * v.1.9.4.9
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
  * Developer: Maltsev Dmitry
  *
  * License: Apache 2.0
  *
  */

//$dbx::query('c', 'revolver__store_categories', $STRUCT_CATEGORIES);

//$dbx::query('c', 'revolver__store_goods', $STRUCT_GOODS);

//$dbx::query('c', 'revolver__store_goods_files', $STRUCT_GOODS_FILES);

//$dbx::query('c', 'revolver__store_comments', $STRUCT_BLOG_COMMENTS);

//$dbx::query('c', 'revolver__goods_ratings', $STRUCT_NODES_RATINGS);

//$dbx::query('c', 'revolver__store_comments_ratings', $STRUCT_COMMENTS_RATINGS);

$title = TRANSLATIONS[ $ipl ]['Store'];

$contents = '';

if( ROLE !== 'none' ) {

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		if( !empty(SV['p']) ) {

			if( isset(SV['p']['revolver_category_title']) ) {

				if( (bool)SV['p']['revolver_category_title']['valid'] ) {

					$title = SV['p']['revolver_category_title']['value'];

				}

			}

			if( isset(SV['p']['revolver_category_description']) ) {

				if( (bool)SV['p']['revolver_category_description']['valid'] ) {

					$description = SV['p']['revolver_category_description']['value'];

				}

			}

			if( isset(SV['p']['revolver_captcha']) ) {

				if( (bool)SV['p']['revolver_captcha']['valid'] ) {

					if( $captcha::verify(SV['p']['revolver_captcha']['value']) ) {

						define('form_pass', 'pass');

					}

				}

			}

			if( defined('form_pass') ) {

				if( form_pass === 'pass' && (bool)SV['p']['identity']['validity'] ) {

					$model::set('store_categories', [

						'title'			=> $title,
						'description'	=> $description

					]);

					$notify::set('status', 'Category created');

				}

			}

		}

		$form_parameters = [

			// main parameters
			'id'		=> 'categories-add-form',
			'class'		=> 'revolver__categories-add-form revolver__new-fetch',
			'method'	=> 'post',
			'action'	=> RQST,
			'encrypt'	=> true,
			'captcha'	=> true,
			'submit'	=> 'Submit',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_1' => [

					'title' => 'Add store category',
					
					// wrap fields into label
					'labels' => [

						'label_1' => [

							'title'  => 'Category title',
							'access' => 'categories',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_category_title',
									'placeholder'	=> 'Type category name',
									'required'		=> true

								],

							],

						],

						'label_2' => [

							'title'	 => 'Category description',
							'access' => 'categories',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_category_description',
									'placeholder'	=> 'Type category description',
									'required'		=> true

								],

							],

						],

					],

				],

			],

		];


		$contents .= '<h2 class="revolver__collapse-form-legend revolver__collapse-form-legend-form-free">'. TRANSLATIONS[ $ipl ]['Add store category'] .'</h2>';

		$contents .= '<output class="revolver__collapse-form-contents" style="overflow: hidden; width: 0px; height: 0px; line-height: 0px; display: inline-block; min-height: 0px; opacity: 0; transform: scaleX(1) scaleY(1) scaleZ(1);">';
		$contents .= $form::build( $form_parameters );
		$contents .= '</output>';

	}

}

$contents .= '<dl class="revolver__categories revolver__store">';

foreach( iterator_to_array(

		$model::get('store_categories', [

			'criterion' => 'id::*',
			'course'	=> 'forward',
			'sort'		=> 'id'

		])

	)['model::store_categories'] as $category ) {

	if( ROLE !== 'none' ) {

		if( ROLE === 'Admin') {

			$contents .= '<dt>&#8226; '. $category['title'] .' &#8226; <span style="float:right">[ <a href="/store/category/'. $category['id'] .'/edit/">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a> ]</span></dt>';

		}

	}
	else {

		$contents .= '<dt>&#8226; '. $category['title'] .'</dt>';

	}

	$contents .= '<dd>';
	$contents .= '<p>'. $category['description'] .'</p>';

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		$contents .= '<dfn style="display: block; text-align:center">[<a href="/store/goods/add/" title="'. TRANSLATIONS[ $ipl ]['Add goods'] .'"> ...'. TRANSLATIONS[ $ipl ]['Add goods'] .'... </a>]</dfn>';

	}

	$contents .= '<ul>';

	$language_segments = [];

	foreach( iterator_to_array(

		$model::get('store_goods', [

			'criterion' => 'id::*',
			'course'	=> 'forward',
			'sort'		=> 'id'

		])

	)['model::store_goods'] as $node ) {

		$language = $lang::getLanguageData( $node['country'] );

		if( $node['category'] === $category['id'] && $language['cipher'] === $node['country'] ) {

			if( (bool)$node['published'] ) {

				$layout = '<li>';
				$layout .= '<a href="/store/goods/'. $node['id'] .'/" title="'. $node['description'] .'">'. $node['title'] .'</a>';

				if( ROLE !== 'none' ) {

					if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

						$layout .= '<span style="float:right">[ <a title="'. TRANSLATIONS[ $ipl ]['Edit node'] .' '. $node['title'] .'" href="/store/goods/'. $node['id'] .'/edit/">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a> ]</span>'; 

					}

				}

				$layout .= '<div>'. $node['description'] .'</div>';

				$layout .= '<div style="text-align:right">';

				if( (int)$node['rebate'] > 0 ) {

					$layout .= '<dfn style="color:#b00000"><s>'. ((int)$node['price'] + (((int)$node['price'] / 100) * (int)$node['tax'] ) ) .' <b>'. $language['currency_symb'] .'</b></s></dfn>';
					$layout .= '<dfn> <span>'. ((int)$node['price'] - ( ( (int)$node['price'] / 100 ) * (int)$node['rebate'] ) + (((int)$node['price'] / 100) * (int)$node['tax'] )) .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

				}
				else {

					$layout .= '<dfn> <span>'.  (int)$node['price'] + (((int)$node['price'] / 100) * (int)$node['tax'] ) .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

				}

				$layout .= '</div>';

				$layout .='</li>';

				$language_segments[ $language['cipher'] ][ $language['name'] .'|'. $language['code_length_3'] .'|'. $language['hreflang'] .'|'. $language['code_length_2'] .'|'. $language['currency_code'] .'|'. $language['currency_symb'] ][] = [

					'layout' => $layout,

				];

			}
			else {

				if( in_array(ROLE, ['none', 'User'], true) || ROLE === 'none' ) {

					continue;

				}

				$layout = '<li>';
				$layout .= $node['title'];

				$layout .= in_array(ROLE, ['Admin', 'Writer'], true) ? '<span style="float:right">[ <a title="'. TRANSLATIONS[ $ipl ]['Edit node'] .' '. $node['title'] .'" href="/store/goods/'. $node['id'] .'/edit/">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a> ]</span>' : '';

				$layout .= '<div>'. $node['description'] .'</div>';

				$layout .= '<div style="text-align:right">';

				if( (int)$node['rebate'] > 0 ) {

					$layout .= '<dfn style="color:#b00000"><s>'. ((int)$node['price'] + (((int)$node['price'] / 100) * (int)$node['tax'] ) ) .' <b>'. $language['currency_symb'] .'</b></s></dfn>';
					$layout .= '<dfn> <span>'. ((int)$node['price'] - ( ( (int)$node['price'] / 100 ) * (int)$node['rebate'] ) + (((int)$node['price'] / 100) * (int)$node['tax'] )) .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

				}
				else {

					$layout .= '<dfn> <span>'.  (int)$node['price'] + (((int)$node['price'] / 100) * (int)$node['tax'] ) .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

				}

				$layout .= '</div>';

				$layout .='</li>';

				$language_segments[ $language['cipher'] ][ $language['name'] .'|'. $language['code_length_3'] .'|'. $language['hreflang'] .'|'. $language['code_length_2'] .'|'. $language['currency_code'] .'|'. $language['currency_symb'] ][] = [

					'layout' => $layout,

				];

			}

		}

	}

	foreach( $language_segments as $lng => $l ) {

		$desc = explode('|', key($l));

		$contents .= '<li>';
		$contents .= '<dl class="revolver__categories-by-country">';
		$contents .= '<dt>'. TRANSLATIONS[ $ipl ]['exchange currency'] .' &#8226; <span class="state-attribution revolver__sa-iso-'. strtolower( $desc[3] ) .'"></span><span class="revolver__stats-system">[ '. $desc[0] .' :: '. $desc[4] .' :: '. $desc[5] .' ]</span></dt>';

		$contents .= '<dd><ul>';

		foreach( $l as $p ) {

			foreach( $p as $xp ) {

				$contents .= $xp['layout'];

			}

		}

		$contents .= '</ul></dd></dl></li>';

	}

	$contents .= '</ul></dd>';

}

$contents .= '</dl>'; 

$node_data[] = [

	'title'		=> $title,
	'id'		=> 'store',
	'route'		=> '/store/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
