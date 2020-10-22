
 /* 
  * RevolveR Front-end :: main interface
  *
  * v.1.9.4.7
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
  */

if( !self.run ) {

	const R = new R_CMF_i[ 0 ]( 'R' );

	self.run = true;

	R.menuPosition  = 0;

	R.night = null;

}

self.searchAction = null;

/* Night */
R.nightMode = () => {

	R.new('style', 'head', 'in', {

		html: ':root { --body-bg: transparent repeating-linear-gradient(-45deg, transparent, transparent .01vw, #cccccca3 .01vw, #00000075 .5vw); \
					   --article-bg-color: rgba(0, 0, 0, .6); \
					   --article-text-shadow-color: 0 0 .1vw #000; \
					   --article-text-color: #c7c7c7; \
					   --article-header-bg: linear-gradient(to right, rgba(254, 255, 232, 0) 0%, rgb(62 57 57) 100%); \
					   --article-footer-bg: linear-gradient(to left, rgba(254, 255, 232, 0) 0%, rgb(64 66 57) 100%); \
					   --comments-article-header: linear-gradient(to right, rgba(254, 255, 232, 0), rgb(0 0 0)); \
					   --comments-article-footer: linear-gradient(to left, rgba(254, 255, 232, 0), rgb(45 45 45) 99.58%); \
					   --article-header-text-shadow: #000; \
					   --article-header-text-color: #fdfdfd; \
					   --article-header-hover-text-shadow-color: #000; \
					   --simple-shadow: #000; \
					   --forum-topic-list-bg: #000; \
					   --article-list-item-color: #c7c7c7; \
					   --related-list-box-shadow: inset .2vw .2vw .2vw #000; \
					   --pagination-list-bg: linear-gradient(to bottom, rgba(255, 255, 255, .3) 0, rgb(0 0 0 / 50%) 100%); \
					   --pagination-list-text-color: #fff; \
					   --parallax-bg-color: #949494e3; \
					   --parallax-1-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #6b6b6b .25vw), linear-gradient(to bottom, #eeeeee5c, #bfbfbf1a); \
					   --parallax-2-bg: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #ffffff45 .1vw, #94949494 .25vw), linear-gradient(to top, #eeeeee5c, #bfbfbf1a); \
					   --tabs-list-items-bg: #5f5b5b; \
					   --tabs-list-items-text-shadow: #666; \
					   --tabs-body-bg: #272727; \
					   --tabs-list-items-hover-bg: linear-gradient(to bottom, rgb(0, 0, 0) 0%, rgb(47, 47, 47) 100%); \
					   --form-fieldset-bg: linear-gradient(45deg, rgba(35, 35, 35, .7) 0, rgba(78, 78, 77, .4) 60%, rgba(252, 255, 244, .2) 100%); \
					   --form-legend-bg: linear-gradient(45deg, rgba(45, 45, 45, .8) 0%, rgba(93, 95, 91, .6) 60%, rgba(252, 255, 244, .4) 100%); \
					   --form-input-bg: linear-gradient(to bottom, #7d7e7d 0%,#0e0e0e 100%); \
					   --form-input-text-color: #c5c1c1f2; \
					   --form-editor-buttons-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #15171594 .25vw), linear-gradient(to bottom, #eeeeee5c, #2727271a); \
					   --form-editor-buttons-text-color: #fff; \
					   --form-submit-bg: linear-gradient(45deg, rgb(23 23 23 / 70%) 0%, rgb(45 45 44 / 40%) 60%, rgba(252, 255, 244, .2) 100%); \
					   --form-input-select-main-opened: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #000 .1vw, #ffffff91 .25vw), linear-gradient(to top, #eeeeee9e, #00000042) !important; \
					   --form-placeholder: #fff; \
					   --form-select-main: linear-gradient(to bottom, #7d7e7d 0%, #0e0e0e 100%); \
					   --form-select-list-active: linear-gradient(to bottom, #3c3c3ca8 0%, #848484 100%); \
					   --article-user-list-header: linear-gradient(to left, rgba(228, 228, 228, 0.24) 0%, rgb(255 255 255 / 74%) 100%); \
					   --terminal-text-color: #fff; \
					   --notice-color: #000; \
					   --related-link-hover: #fff; \
					   --country-list-text-color: #fff; \
					   --search-results-list: #fff; \
					   --search-results-description: #d2bf37e0; \
					   --search-results-title: #92d084e8; \
					   --modal-title-shadow: #000; \
					}',

		attr: {

			'media': 'all',
			'class': 'revolver__night'

		}

	});

	R.night = true;

};

R.logging = ( x, e = 'html' ) => {

	let lines = x.split('</meter>')[0].split('<meter value="0">')[1].replace('<!--', '').replace('-->', '').trim().split("\n");

	let s = [];

	let l = 0;

	for ( let i of lines ) {

		if( l > 1 && l < lines.length - 2 ) {

			if( i.length ) {

				s.push( i );

			}

		}

		l++;

	}

	s.push( new Date().toString() );

	s.push( 'Route: '+ self.route );	

	let tpause = 1000;

	let tshift = 0;

	for ( let line of s ) {

		void setTimeout(() => {

			console.log( line );

		}, tpause + ( tshift * 500 ) );	

		tshift++;

	}

};

R.switchAttendanceDate = ( x ) => {

	for( let d in x ) {

		if( x[ d ][ 0 ] === 'choosen' ) {

			R.loadURI(

				document.location.origin + document.location.pathname + '?date='+ x[ d ][ 1 ], 'attendance'

			);

		}

	}

};

R.useCaptcha = ( p ) => {

	self.overprint = atob( p.split('*')[2] );
	self.oprintvar = atob( p.split('*')[0] );

	self.flag = null;

	let pixels = R.sel('#drawpane div');

	if( pixels ) {

		for( let i of R.sel('#drawpane div') ) {

			i.addEventListener('click', function(e) {

				e.preventDefault();

				let choosen = this.dataset.selected;

				if( e.isTrusted ) {

					self.flag = true;

					if( choosen === 'null' || choosen === 'false' ) {

						this.style = 'background: #7e333d;';
						this.dataset.selected = 'true';
						this.className = 'active';

					}
					else {

						this.style = 'background: #a2a2a2; transform: scale(1);';
						this.dataset.selected = 'null';
						this.className = 'inactive';

					}

				}

			});

		}

	}

	const finger = R.sel('#overprint');

	if( finger ) {

		function oPrint( m ) {

			let c = finger[0].getContext('2d');

			function walk( e, i ) {

				let s = e.split(':');

				let style = '#' + ( s[ 0 ] == 1 ? '888888' : 'D99FAF' );

				c.fillStyle = style;

				c.fillRect( s[ 1 ], s[ 2 ], 24, 24 );

				c.stroke();

			}

			m.forEach( walk );

		}

		let d = atob( p.split('*')[ 1 ] ).split('|').sort();

		let xy = [];

		for( let i of d ) {

			xy.push( i.split('-')[ 1 ] );

		}

		oPrint( xy );

	}

};

R.cleanNotifications = (t) => {

	let timeShift = 0;

	for( let e of t ) {

		void setTimeout(() => {

			R.styleApply(e.querySelectorAll('.revolver__statuses-heading') , ['display:none'], () => {

				R.animate(e.children, ['height:0px:800:elastic']);

				R.animate([e], ['height:0px:1500:wobble', 'color:rgba(255,255,255,.1):700:elastic', 'opacity:0:1200:harmony'], () => {

					R.rem([e]);

				});

			});

		}, 10000 / ++timeShift);

	}

};

// Make interface
R.fetchRoute = ( intro ) => {

	let dateTime = new Date();

	let hours = dateTime.getHours();

	let nightStyle = R.sel('.revolver__night');

	if( hours >= 20 || hours <= 8 ) {

		if( !R.night ) {

			R.nightMode();

			console.log('Night come ...');

		}

	} 
	else {

		if( nightStyle ) {

			R.rem(nightStyle);

			console.log('Day come ...');

		}

	}

	R.menuMove = null;

	R.mainMenues = R.sel('.revolver__main-menu ul');

	R.styleApply(R.mainMenues, ['left:'+ R.menuPosition + 'px']);

	// Privacy policy
	setTimeout(() => {

		R.fetch('/secure/?policy=get', 'get', 'json', null, function() {

			const key = atob( this.privacy ).split('::');

			if( key[0] !== 'accepted' ) {

				const nPolicy = {

					html: '<div class="revolver__statuses-heading">... Privacy policy notification <i>+</i></div><div class="privacy-policy-notification"><p>This domain use cookies only to improve privacy and make possible correct work our services.</p><p>You can <a href="/privacy/">read domain cookie policy</a> and <a href="'+ document.location.pathname +'?notification=accept-privacy-policy">accept</a> it.</p></div>',

					attr: {

						class : 'revolver__status-notifications revolver__notice'

					}

				};

				R.new('div', '.revolver__main-contents', 'before', nPolicy);

				let forms = R.sel('form');

				let c = 0;

				for( let f of forms ) {

					if( c >= 1 ) {

						R.event([f.parentElement], 'click', (e) => {

							if( e.isTrusted ) {

								R.new('div', '.revolver__form-wrapper', 'before', nPolicy);

							}

						});

						for(let i of f.querySelectorAll('input, textarea')) {

							i.disabled = 'disabled';

						}

					}

					c++;

				}

				R.styleApply('.revolver__captcha-wrapper', ['display:none']);

				R.setAllow( null );

			}
			else {

				if( intro ) {

					let cform = R.sel('#comment-add-form');

					let route = cform ? cform[0].action.replace( document.location.origin, '' ) : document.location.pathname;

					if( route !== '/' && route !== '/logout/' ) {

						R.fetch('/secure/?route='+ route, 'get', 'json', null, function() {

							R.useCaptcha( this.key );

						});

					}

				}

				R.setAllow( key[1] );

			}

			// Lazy load
			setTimeout(() => {

				R.lazyLoad();

			}, 4000);

			// Stop preview
			clearInterval( R.preview );

			// Hide status messages
			clearInterval( R.notificationsInterval );

			R.notificationsInterval = null;

			R.notificationsInterval = setInterval(() => {

				let notifications = R.sel('.revolver__status-notifications');

				if( notifications ) {

					R.cleanNotifications( notifications );

				}

			}, 20000);

			R.event('.revolver__status-notifications .revolver__statuses-heading i', 'click', function(e) {

				e.preventDefault();

				if( e.isTrusted ) {

					R.styleApply([this.parentElement], ['display:none'], () => {

						R.animate(this.parentElement.parentElement.children, ['height:0px:500:elastic']);

						R.animate([this.parentElement.parentElement], ['height:0px:1500:wobble', 'color:rgba(255,255,255,.1):700:elastic', 'opacity:0:1000:harmony']);

						void setTimeout(() => {

							R.rem([this.parentElement.parentElement]);

						}, 1300);

					});

				}

			});

			for( let i of R.sel('a') ) {

				if( i.target === '_blank') {

					R.addClass( [ i ], ['external'] );

				}

			}

			R.event('a', 'click', function(e) {

				e.preventDefault();

				if( e.isTrusted ) {

					if( R.hasClass( [ this ], 'external' ) ) {

						self.open( e.target.href );

						return;

					}

					if( !this.href.includes( 'webp', 'svg' ,'png', 'jpg', 'jpeg', 'gif', 'zip' ) ) {

						R.loadURI(

							this.href, this.innerText

						);

					}

				}

			});

		});

	}, 3000);

	// Intro
	if( intro ) {

		R.attr('.revolver__header h1 a, .revolver__main-menu ul li a, .revolver__main-contents', {

			style: null

		});

		R.styleApply('input[type="search"]', ['width:50vw']);

		R.styleApply('.revolver__header h1 a', ['color: rgba(220, 220, 220, .8)', 'display:inline-block', 'opacity:.1'], () => {

			R.styleApply('.revolver__main-menu ul li a', ['display:inline-block', 'opacity:.1']);

			R.animate('.revolver__header h1 a', ['transform: scale(.5, .5, .5) rotate(360deg, 360deg, 360deg):1500:bouncePast']);
			R.animate('.revolver__header h1 a', ['opacity:.9:1500:bouncePast', 'transform: scale(1, 1, 1) rotate(0deg,0deg,0deg):2000:elastic', 'color:rgba(111, 111, 111, 0.9):6000:wobble']);

			R.animate('.revolver__main-menu ul li a ', ['opacity:1:1000:bouncePast']);

			R.styleApply('.revolver__main-contents', ['opacity:0.5', 'display:inline-block']);

			R.animate('.revolver__main-contents', [

				'opacity:1:1000:elastic',
				'transform:scale(.5,.5,.5):500:bouncePast', 
				'transform:scale(1,1,1):1550:elastic'

			], () => {

				if( !R.isM ){

					R.hint();

				}

			});

		});

	}

	// Parallax effects
	const articleBlocks = R.sel('article');

	R.parallaxBlocks( articleBlocks );

	const menuBlock = R.sel('.revolver__main-menu ul');

	R.parallaxBlocks( menuBlock );

	const relatedBlock = R.sel('.revolver__related div');

	R.parallaxBlocks( relatedBlock );

	const codeBlocks = R.sel('code');

	if( codeBlocks ) {

		for( let i of codeBlocks ) {

			i.innerHTML = R.syntax( i.innerHTML );

		}

	}

	// Highlight menu
	setTimeout(() => {

		const menu = R.sel('.revolver__main-menu li');

		if( menu ) {

			R.styleApply('.revolver__site-description', ['top:8.82vw', 'z-index:50000', 'opacity:1']);

			for( let e of menu ) {

				let rgxp = document.location.pathname;
				let pass = R.attr( e.children[0], 'href')[0];

				if( (rgxp.includes(pass) && pass !== '/') || (rgxp === '/' && rgxp.includes(pass)) ) {

					void setTimeout(() => {

						R.addClass([ e ], 'route-active');

						R.styleApply('.revolver__site-description', ['top:8.82vw', 'z-index:50000', 'opacity:1']);

						R.reParallax('.revolver__main-menu ul');

					}, 1000);

				}

			}

		}

	}, 1500);


	setTimeout(() => {

		R.event('.revolver-rating li', 'click::lock', (e) => {

			e.preventDefault();

			let paramsBlock = e.target.closest('ul');
			let rateValue	= e.target.dataset.rated;
			let ratingType 	= paramsBlock.dataset.type;

			if( !R.storage('rate-'+ ratingType +'-'+ paramsBlock.dataset.node, 'get') ) {

				R.removeClass(paramsBlock.querySelectorAll('li'), 'point');

				R.addClass([ e.target ], 'point');

				let data = new FormData();

				data.append( btoa('revolver_rating_node'), R.utoa( paramsBlock.dataset.node +'~:::~text~:::~'+ -1) );
				data.append( btoa('revolver_rating_user'), R.utoa( paramsBlock.dataset.user +'~:::~text~:::~'+ -1) );
				data.append( btoa('revolver_rating_value'), R.utoa( rateValue +'~:::~text~:::~'+ -1) );
				data.append( btoa('revolver_rating_type'), R.utoa( paramsBlock.dataset.type +'~:::~text~:::~'+ -1) );

				R.FormData = data;

				// Perform parameterized fetch request
				R.fetch('/rating-d/', 'POST', 'text', true, function() {

					R.storage('rate-'+ ratingType +'-'+ paramsBlock.dataset.node +'=1', 'set');

					R.FormData = null;

					console.log('Node rated :: '+ paramsBlock.dataset.node +'::'+ paramsBlock.dataset.user +'::'+ rateValue);

				});

			} 
			else {

				console.log('You already rate node '+ paramsBlock.dataset.node);

			}

		});

	}, 1000);

	R.event(R.mainMenues, 'mousedown', (e) => {

		e.preventDefault();

		if( !R.menuMove ) {

			R.menuLeft = R.curxy[0];

			R.MenuMoveObserver = R.event('body', 'mousemove', (e) => {

				e.preventDefault();

				R.styleApply(R.mainMenues, ['transition: all 0s ease']);

				R.menuMove = true;

				R.menuPosition = ( R.menuLeft - R.curxy[0] ) *-1;

				R.styleApply(R.mainMenues, ['left:'+ R.menuPosition +'px']);

				R.event('body', 'mouseup', (e) => {

					e.preventDefault();

					for( i of R.MenuMoveObserver ) {

						R.detachEvent(i[ 2 ]);

					}

					void setTimeout(() => { 

						R.menuMove = null;

					}, 50);

					void setTimeout(() => {

						if( !R.menuMove ) {

							R.styleApply(R.mainMenues, ['left: 0px', 'transition: all 2.5s cubic-bezier(.175, .885, .32, 1.275)']);

						}

					}, 2500);

				});

			});

		}

	});

	R.event(R.mainMenues, 'touchstart', (e) => {

		e.preventDefault();

		R.menuMove = null;

		R.event('body', 'touchend', (e) => {

			e.preventDefault();

			if( !R.menuMove ) {

				R.touchFreeze = null;

				let target = e.changedTouches[0].target;

				if( R.isO(R.MenuMoveObserver) ) {

					for( i of R.MenuMoveObserver ) {

						R.detachEvent( i[ 2 ] );


					}

				}

				if( target.tagName === 'A' && !R.touchFreeze ) {

					R.loadURI(target.href, target.title);

					R.touchFreeze = true;

					R.menuMove = null;

				}

				void setTimeout(() => {

					if( !R.menuMove ) {

						R.styleApply(R.mainMenues, ['left: 0px', 'transition: all 2.5s cubic-bezier(.175, .885, .32, 1.275)']);

					}

				}, 2500);

			}

		});

		if( !R.menuMove ) {

			R.menuLeft = e.changedTouches[0].screenX;

			R.MenuMoveObserver = R.event('body', 'touchmove', (e) => {

				e.preventDefault();

				R.styleApply(R.mainMenues, ['transition: all 0s ease']);

				R.menuMove = true;

				R.menuPosition = ( R.menuLeft - e.changedTouches[0].screenX ) *-1; 

				R.styleApply(R.mainMenues, ['left:'+ R.menuPosition +'px']);

					R.event('body', 'touchend', (e) => {

						R.menuMove = null;

					});

			});

		}

	});

	/* Quick edit handler */
	setTimeout(() => {

		R.event('.revolver__quick-edit-handler', 'click', (e) => {

			let articleArea = e.target.closest('article');
			let editorArea  = articleArea.querySelector('.revolver__article-contents, .revolver__comments-contents');

			R.toggleClass([ editorArea ], 'quick-edit-enbaled');

			if( R.isU(e.target.dataset.editing) || e.target.dataset.editing === 'null' ) {

				//articleArea.requestFullscreen();

				//R.reParallax();

				R.attr(editorArea, { 

					'contenteditable': true

				});

				R.attr(e.target, {

					'data-editing': true

				});

				e.target.innerText = '[ Ok! ]';

				console.log('Enter quick edit mode');

			} 
			else {

				console.log('Exit quick edit mode :: saving ... ');

				let figs = editorArea.querySelectorAll('figure img');

				if( figs ) {

					for( let i of figs ) {

						i.removeAttribute('data-src');
						i.removeAttribute('class');
						i.removeAttribute('style');

					}

				}

				setTimeout(() => {

					R.attr(editorArea, { 

						'contenteditable': false

					});

					R.attr(e.target, {

						'data-editing': null

					});

					let data = new FormData();

					data.append( btoa('revolver_quedit_user'), R.utoa( editorArea.dataset.user +'~:::~text~:::~'+ -1) );
					data.append( btoa('revolver_quedit_node'), R.utoa( editorArea.dataset.node +'~:::~text~:::~'+ -1) );
					data.append( btoa('revolver_quedit_data'), R.utoa( editorArea.innerHTML +'~:::~text~:::~'+ -1) );
					data.append( btoa('revolver_quedit_type'), R.utoa( editorArea.dataset.type +'~:::~text~:::~'+ -1) );

					R.FormData = data;

					// Perform parameterized fetch request
					R.fetch('/quedit-d/', 'POST', 'text', true, function() {

						R.FormData = null;

						//document.exitFullscreen();

						R.fetchRoute(true);

						console.log('Quick edit mode :: node saved ... ');


					});

					e.target.innerText = '[ Quick Edit ]';


				}, 1500);

			}


		});

	}, 3000);

	setTimeout(() => {

		// Forms styles
		R.formBeautifier();

		// Enable editor
		if( R.sel('textarea') ) {

			R.markupEditor();

		}

		// Tabs
		if( R.sel('#tabs') ) {

			R.tabs('#tabs li.revolver__tabs-tab', '#tabs div');

		}

		// Collapsible elements
		if( R.sel('.collapse dd, .revolver__referers-list li pre') ) {

			for( let i of R.sel('.collapse dd, .revolver__referers-list dd') ) {

				R.toggle( [ i ] );

			}

		}

		R.event('.collapse dt, .revolver__referers-list dt', 'click', () => {

			R.reParallax();

		});

		R.expand('.collapse dt, .revolver__collapse-form-legend, .revolver__referers-list dt');

		R.event('input[type="submit"]', 'click', (e) => {

			if( e.isTrusted ) {

				if( self.flag ) {

					let m = [];
					let c = 0;

					let draw = R.sel('#drawpane div');

					for( let a of draw ) {

						m[ c ] = ( a.dataset.selected === 'true' ? 1 : 0 ) +':'+ a.dataset.xy;

						c++;

					}

					function encoder( s ) {

						let e = '';

						for ( let j = 0; j < s.length; j++ ) {

							e += String.fromCharCode( s.charCodeAt( j ) ^ 51 );

						}

						return e;

					}

					let s = '';
					let e = encoder( '{\"value\":'+ '"'+ self.oprintvar +'*'+ m.join('|') +'"'+ '}' );

					for ( let i = 0; i < e.length; i++ ) {

						s += e.charCodeAt( i );

						if( i < e.length - 1 ) {

							s += '|';

						}

					}

					R.attr('.revolver__captcha-wrapper input[type="hidden"]', {

						'value': btoa( s ) +'*'+ btoa( self.overprint ) +'*'+ btoa( document.location.pathname )

					});

				}

			}

		});

		// Fetch Submit
		R.fetchSubmit('form.revolver__new-fetch', 'text', function() {

			// Prevent search box fetching
			if( !self.searchAction ) {

				R.sel('#RevolverRoot')[0].innerHTML = '';

				for( let i of R.convertSTRToHTML(this) ) {

					if( i.tagName === 'TITLE' ) {

						var title = i.innerHTML;

					}

					if ( i.id === 'RevolverRoot' ) {

						var contents = i.innerHTML;

					}

					if( i.tagName === 'META') {

						if( i.name === 'host') {

							eval( 'window.route="'+ i.content +'";' );

						}

					}

					if( i.className === 'revolver__privacy-key' ) {

						R.sel('.revolver__privacy-key')[0].dataset.xprivacy = i.dataset.xprivacy;

					}

				}

				R.insert( R.sel('#RevolverRoot'), contents );

				R.location(title, self.route);

				R.scroll();

				R.logging(this, 'body');

				clearInterval( R.notificationsInterval );

				R.notificationsInterval = null;

				R.fetchRoute( true );

			}

		});

		// Search
		R.event('.revolver__search-box form', 'submit', function(e) {

			e.preventDefault();

			if( e.isTrusted ) {

				// Prevent search box fetching
				self.searchAction = true;

				R.fetch('/search/?query='+ this.querySelectorAll('input[type="search"]')[0].value, 'get', 'html', true, function() {

					if( this.length ) {

						R.insert('.revolver__main-contents', '<article class="revolver__article published"><div class="revolver__article-contents">'+ this +'<div></article>');

						R.logging(this, 'div');

						void setTimeout(() => {

							self.searchAction = null;

							clearInterval( R.notificationsInterval );

							R.notificationsInterval = null;

							R.fetchRoute( null );

						}, 500);

					}

				});

			}

		});

		// Terminal fetch
		R.fetchSubmit('form.revolver__terminal-fetch', 'json', function() {

			// Prevent search box fetching
			if( !self.searchAction ) {

				R.new('li', '.revolver__terminal-session-store ul', 'after', {

					html: '<span class="revolver__collapse-form-legend">'+ this.command +'</span><pre class="revolver__collapse-form-contents" style="overflow: hidden; width: 0; height: 0; line-height: 0; display: inline-block;">'+ this.output +'</pre>'

				});

				R.fetch('/secure/?route=/terminal/', 'get', 'json', null, function() {

					R.useCaptcha( this.key );

				});

				R.fetchRoute( null );

			}


		});


	}, 3000);

	R.loadURI = ( url, title ) => {

		R.fetch(url, 'get', 'html', true, function() {

			R.sel('#RevolverRoot')[0].innerHTML = '';

			for( let i of R.convertSTRToHTML( this ) ) {

				if( i.tagName === 'TITLE' ) {

					var title = i.innerHTML;

				}

				if ( i.id === 'RevolverRoot' ) {

					var contents = i.innerHTML;

				}

				if( i.tagName === 'META') {

					if( i.name === 'host') {

						eval( 'window.route="'+ i.content +'";' );

					}

				}

				if( i.className === 'revolver__privacy-key' ) {

					R.sel('.revolver__privacy-key')[0].dataset.xprivacy = i.dataset.xprivacy;

				}

			}

			R.insert( R.sel('#RevolverRoot'), contents );

			R.location( title, self.route );

			let hash = url.split('#');

			if( !R.isU( hash[ 1 ] ) ) {

				setTimeout(

					R.scroll('#'+ hash[ 1 ] ), 2500

				);

			}
			else {

				R.scroll();

			}

			R.logging(this);

			clearInterval( R.notificationsInterval );

			R.notificationsInterval = null;

			R.fetchRoute( true );

		});

	};

	// History states
	self.onpopstate = void function(e) {

		R.loadURI(

			e.state.url, e.state.title

		);

	}

};

// Perform parametrized fetch query
if( typeof R === 'object' ) {

	R.fetchRoute( true );

}
else {

	console.log(

		decodeURIComponent(

			atob( 'JUYwJTlGJTlCJTkxJTIwSW5zdGFuY2UlMjBub3QlMjBhbGxvd2VkJTIwLi4u' )

		)

	);

}
