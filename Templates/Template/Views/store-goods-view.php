<?php

	$nodeLoaded = true;

	$goods = iterator_to_array(

			$model::get( 'store_goods', [

				'criterion' => 'id::'. (int)PASS[ 3 ],

				'bound'		=> [

					1

				],

				'course'	=> 'backward',
				'sort' 		=> 'id'

			])

		)['model::store_goods'][0];

	$language = $lang::getLanguageData( $goods['country'] );

	$render_node .= '<div itemtype="http://schema.org/Product" itemscope>';

	$render_node .= '<article class="revolver__article article-id-'. $goods['id'] .' revolver__store-goods">';

	$render_node .= '<header class="revolver__article-header">';

	$render_node .= '<h2 itemprop="name">'. $goods['title'] .'</h2>';

	$render_node .= '<div itemprop="offers" style="display:block; float:right;" itemtype="http://schema.org/Offer" itemscope>';

	$render_node .= '<link itemprop="url" href="'. site_host .'/store/goods/'. PASS[ 3 ] .'/" />';

	$render_node .= '<meta itemprop="availability" content="https://schema.org/InStock" />'; // make iterable for items count

	$render_node .= '<meta itemprop="itemCondition" content="https://schema.org/UsedCondition" />';

	$render_node .= '<meta itemprop="priceCurrency" content="'. $language['currency_code'] .'" />';

	$render_node .= '<meta itemprop="priceValidUntil" content="'. date('Y-m-d') .'" />';

	if( (int)$goods['rebate'] > 0 ) {

		$render_node .= '<dfn> <span itemprop="price">'. ((int)$goods['price'] - ( ( (int)$goods['price'] / 100 ) * (int)$goods['rebate'] ) + (((int)$goods['price'] / 100) * (int)$goods['tax'] )) .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

		$render_node .= '<dfn> <s>'. ((int)$goods['price'] + (((int)$goods['price'] / 100) * (int)$goods['tax'] ) ) .' <b>'. $language['currency_symb'] .'</b></s></dfn>';

	}
	else {

		$render_node .= '<dfn> <span itemprop="price">'. (int)$goods['price'] + (((int)$goods['price'] / 100) * (int)$goods['tax'] ) .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

	}

	$render_node .= '</div>';

	$render_node .= '</header>';

	$render_node .= '<div itemprop="description" class="revolver__article-contents">';

	$render_node .= '<div class="revolver__store-goods-cover">';

    $files = iterator_to_array(

        $model::get('store_goods_files', [

          'criterion' => 'node::'. $goods['id'],
          'course'    => 'forward',
          'sort'      => 'id'

        ])

      )['model::store_goods_files'];

	if( $files ) {

		$render_node .= '<figure>';

		foreach( $files as $f ) {   

			$render_node .= '<img itemprop="image" src="/public/sfiles/'. $f['name'] .'" />';

		}

		$render_node .= '</figure>';

	} 
	else {

		$render_node .= '<figure>';

		$render_node .= '<img src="/Interface/store-default.png" alt="Goods have no cover" />';

		$render_node .= '</figure>';

	}

	if( (int)$goods['rebate'] > 0 ) {

		$render_node .= '<span class="revolver__store_rebate-span">'.  TRANSLATIONS[ $ipl ]['rebate'] .'</span>';
		$render_node .= '<b class="revolver__store_rebate">'. $goods['rebate'] .'%</b>';

	}

	$render_node .= '</div>';

	$render_node .= '<div class="revolver__store-good-description">';

	$render_node .= '<h2 itemprop="brand" itemtype="http://schema.org/Brand" itemscope>'. TRANSLATIONS[ $ipl ]['Goods vendor'] .': <span itemprop="name">'. $goods['vendor'] .'</span></h2>';

	$render_node .= $markup::Markup(

						htmlspecialchars_decode(

							html_entity_decode(

								$goods['content']

							)

						), ['lazy' => 1]);

	$render_node .= '<ul>';


	if((bool)$goods['service']) {

		$render_node .= '<li>'. TRANSLATIONS[ $ipl ]['Service'] .'</li>';

	} 
	else {

		$render_node .= '<li><s>'. TRANSLATIONS[ $ipl ]['Service'] .'</s></li>';

	}

	if((bool)$goods['delivery']) {

		$render_node .= '<li>'. TRANSLATIONS[ $ipl ]['Delivery'] .'</li>';

	} 
	else {

		$render_node .= '<li><s>'. TRANSLATIONS[ $ipl ]['Delivery'] .'</s></li>';

	}

	if((bool)$goods['pickup']) {

		$render_node .= '<li>'. TRANSLATIONS[ $ipl ]['Pickup'] .'</li>';

	} 
	else {

		$render_node .= '<li><s>'. TRANSLATIONS[ $ipl ]['Pickup'] .'</s></li>';

	}

	$render_node .= '</ul>';

	$render_node .= '<h4>'. TRANSLATIONS[ $ipl ]['Tax'] .': <span>'. $goods['tax'] .'%</span></h4>';

	$render_node .= '<h4>'. TRANSLATIONS[ $ipl ]['Quantity'] .': <span>'. $goods['quantity'] .'</span></h4>';

	$render_node .= '</div>';

	$render_node .= '</div>';

	$render_node .= '<footer class="revolver__article-footer">';

	$goods_rating = iterator_to_array(

			$model::get( 'goods_ratings', [

				'criterion' => 'node_id::'. (int)PASS[ 3 ],
				'course'	=> 'backward',
				'sort' 		=> 'id'

			])

		)['model::goods_ratings'];


	$grate = 0;

	if( $goods_rating ) {

		foreach( $goods_rating as $r => $rv ) {

			$grate += $rv['rate'];

		}

		$grate /= count( $goods_rating ); 

	}
	else {

		$goods_rating = [];

	}

	$tpe = 'store';

	$render_node .= '<div class="revolver-rating" itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>';
	$render_node .= '<ul class="rated-'. floor($grate) .'" data-node="'. $goods['id'] .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';
		$render_node .= '<li data-rated="1">1</li>';
		$render_node .= '<li data-rated="2">2</li>';
		$render_node .= '<li data-rated="3">3</li>';
		$render_node .= '<li data-rated="4">4</li>';
		$render_node .= '<li data-rated="5">5</li>';
	$render_node .= '</ul>';

	$render_node .= '<span itemprop="ratingValue">'. floor( $grate ) .'</span> / <span>5</span> #<span class="closest" itemprop="reviewCount">'. count( $goods_rating ) .'</span>';
	$render_node .= '</div>';

	$render_node .= '<nav>';

	$render_node .= '<ul>';

	$render_node .= '<li class="revolver__in-basket-handler" data-goods="'. $goods['id'] .'">[ <span>'. TRANSLATIONS[ $ipl ]['In basket'] .'</span> ]</li>';

	if( Auth ) {

		if( in_array(ROLE, ['Admin', 'Writer']) ) {
	
			$render_node .= '<li><a title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="/store/goods/'. (int)PASS[ 3 ] .'/edit/' .'">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';
		
		}


	}

	$render_node .= '</ul>';

	$render_node .= '</nav>';

	$render_node .= '</footer>';

	$render_node .= '</article>';


?>
