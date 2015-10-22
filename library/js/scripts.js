/*
 * Bones Scripts File
 * Author: Eddie Machado
 *
 * This file should contain any js scripts you want to add to the site.
 * Instead of calling it in the header or throwing it inside wp_head()
 * this file will be called automatically in the footer so as not to
 * slow the page load.
 *
 * There are a lot of example functions and tools in here. If you don't
 * need any of it, just remove it. They are meant to be helpers and are
 * not required. It's your world baby, you can do whatever you want.
*/
var dammit;

/*
 * Get Viewport Dimensions
 * returns object with viewport dimensions to match css in width and height properties
 * ( source: http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript )
*/
function updateViewportDimensions() {
	var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
	return { width:x,height:y };
}
// setting the viewport width
var viewport = updateViewportDimensions();


/*
 * Throttle Resize-triggered Events
 * Wrap your actions in this function to throttle the frequency of firing them off, for better performance, esp. on mobile.
 * ( source: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed )
*/
var waitForFinalEvent = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

// how long to wait before deciding the resize has stopped, in ms. Around 50-100 should work ok.
var timeToWaitForLast = 100;


/*
 * Here's an example so you can see how we're using the above function
 *
 * This is commented out so it won't work, but you can copy it and
 * remove the comments.
 *
 *
 *
 * If we want to only do it on a certain page, we can setup checks so we do it
 * as efficient as possible.
 *
 * if( typeof is_home === "undefined" ) var is_home = $('body').hasClass('home');
 *
 * This once checks to see if you're on the home page based on the body class
 * We can then use that check to perform actions on the home page only
 *
 * When the window is resized, we perform this function
 * $(window).resize(function () {
 *
 *    // if we're on the home page, we wait the set amount (in function above) then fire the function
 *    if( is_home ) { waitForFinalEvent( function() {
 *
 *	// update the viewport, in case the window size has changed
 *	viewport = updateViewportDimensions();
 *
 *      // if we're above or equal to 768 fire this off
 *      if( viewport.width >= 768 ) {
 *        console.log('On home page and window sized to 768 width or more.');
 *      } else {
 *        // otherwise, let's do this instead
 *        console.log('Not on home page, or window sized to less than 768.');
 *      }
 *
 *    }, timeToWaitForLast, "your-function-identifier-string"); }
 * });
 *
 * Pretty cool huh? You can create functions like this to conditionally load
 * content and other stuff dependent on the viewport.
 * Remember that mobile devices and javascript aren't the best of friends.
 * Keep it light and always make sure the larger viewports are doing the heavy lifting.
 *
*/

/*
 * We're going to swap out the gravatars.
 * In the functions.php file, you can see we're not loading the gravatar
 * images on mobile to save bandwidth. Once we hit an acceptable viewport
 * then we can swap out those images since they are located in a data attribute.
*/
function loadGravatars() {
	// set the viewport using the function above
	viewport = updateViewportDimensions();
	// if the viewport is tablet or larger, we load in the gravatars
	if (viewport.width >= 768) {
		jQuery('.comment img[data-gravatar]').each(function(){
			jQuery(this).attr('src',jQuery(this).attr('data-gravatar'));
		});
	}
} // end function


/*
* Put all your regular jQuery in here.
*/
jQuery(document).ready(function($) {
	
	var win = $(window);
	
	/*
	* Let's fire off the gravatar function
	* You can remove this if you don't need it
	*/
	loadGravatars();
	
	win.resize(function() {
		waitForFinalEvent( function() {
			mobileDeviceBodyClass();
			setSidebarHeight();
			headerHeight();
			thumbGridSize();
		}, timeToWaitForLast, 'resizeWindow');
	});
	
	// Hide wp admin bar
	$('#wpadminbar').addClass('hidden').append('<div class="wpadminbar-activator"></div>').hover(
		function() {
			$(this).removeClass('hidden');
		},
		function() {
			$(this).addClass('hidden');
		}
	)
	
	function mobileDeviceType() {
		if (win.width() > 1024) {
			return false;
		} else if (win.width() < 768) {
			return 'mobile';
		} else {
			return 'tablet';
		}
	}
	function mobileDeviceBodyClass() {
		if (mobileDeviceType() == 'mobile') {
			$('body').addClass('mobile').removeClass('tablet');
		} else if (mobileDeviceType() == 'tablet') {
			$('body').addClass('tablet').removeClass('mobile');
		} else {
			$('body').removeClass('mobile tablet');
		}
	}
	mobileDeviceBodyClass();
	
	$('.TRIGGER_NAV').click(function(e) {
		e.preventDefault();
		$(this).add('.MAIN_NAV').toggleClass('active');
		$('html').toggleClass('mobile-nav-active');
	});
	
	$('.MAIN_NAV').on('click','a',function() {
		if (mobileDeviceType()) {
			$('.TRIGGER_NAV').click();
		}
	});
	
	// For the panel of book cover thumbs on the home page and author pages. Creates a slider.
	var grid = $('.THUMB_GRID');
	var gridInner = grid.find('.THUMB_GRID_INNER');
	var thumbList = grid.find('.THUMBS');
	var thumbs = thumbList.children('li');
	var thumbNav = grid.find('.THUMB_GRID_NAV');
	function thumbGridInit() {
		thumbGridSize();
		thumbNav.find('a').click(function(e) {
			e.preventDefault();
			directionMultiplier = $(this).hasClass('PREV') ? '-1' : '1';
			gridInner.animate({
				scrollLeft: thumbGridScrollDistance($(this))
			}, 400, function() {
				thumbNavLinkVisibility();
			});
		});
	}
	function thumbGridSize() {
		gridCols = win.width() < 481 ? 3 : 4;
		var containerWidth = gridInner.width();
		var thumbWidth = containerWidth / gridCols;
		var panelWidth = thumbWidth * thumbs.length;
		panelWidth = panelWidth >= containerWidth ? panelWidth : containerWidth;
		thumbs.width(thumbWidth);
		thumbList.width(panelWidth);
		thumbNavLinkVisibility();
		if (panelWidth <= containerWidth) {
			thumbNav.addClass('inactive');
		} else {
			thumbNav.removeClass('inactive');
		}
	}
	function thumbGridScrollDistance(button) {
		return gridInner.scrollLeft() + (gridInner.width() * (button.hasClass('PREV') ? '-1' : '1'));
	}
	function thumbGrid() {
	}
	thumbGridInit();
	
	function thumbNavLinkVisibility() {
		var grid = $('.THUMB_GRID');
		var gridInner = grid.find('.THUMB_GRID_INNER');
		var thumbList = grid.find('.THUMBS');
		var thumbNav = grid.find('.THUMB_GRID_NAV');
		thumbNav.find('a').removeClass('inactive');
		if (gridInner.scrollLeft() == 0) {
			thumbNav.find('.PREV').addClass('inactive');
		} else if (gridInner.scrollLeft() + gridInner.width() >= thumbList.width()) {
			thumbNav.find('.NEXT').addClass('inactive');
		}
	}
	
	// Equal Heights - group must be a jQuery object. If masterElem is used, items in group will be set to masterElem's height. It should be a jQuery object with only one element in it. If outer is true, masterElem's outerHeight will be used. modifier is just a number to add or subtract to the masterElem height.
	function equalHeight(group, reset, masterElem, outer, modifier) {
		group.each(function() {
			console.log($(this).outerHeight());
		})
		console.log(masterElem.outerHeight());
		if (reset) {
			group.css('height','auto');
		}
		if (masterElem.length > 0) {
			console.log('equal heights: master');
			var newHeight = outer ? masterElem.outerHeight() : masterElem.height();
			if (modifier) {
				newHeight += modifier;
			}
			group.css('height', newHeight);
		} else {
			console.log('equal heights: plain group')
			var tallest = 0;
			group.each(function() {
				var thisHeight = $(this).outerHeight();
				if(thisHeight > tallest) {
					tallest = thisHeight;
				}
			});
			group.css('height', tallest);
		}
	}
	setSidebarHeight = function() {
		if (mobileDeviceType() == 'mobile') {
			$('#main, #sidebar').css('height', 'auto');
		} else {
			// var modifier = parseInt($('#content').css('margin-bottom').replace('px', ''));
			equalHeight($('#main, #sidebar'), true, $('#content'));
		}
	}
	/* THIS REQUIRES Google's Web Font loader to run before it can go
	The Web Font Loader script is enqueued in library/bones.php
	The script that runs that actually loads up the fonts we're using is also enqueued in library/bones.php, and is located in library/js/libs/webfonts.js
	It makes use of jQuery's $.callbacks() to defer the running of the setSidebarHeight() function until the fonts are loaded
	see http://stackoverflow.com/questions/11941883/call-jquery-function-after-fonts-are-loaded
	also see https://github.com/typekit/webfontloader for instructions on using Web Font Loader
	*/
	activeCallback.add(function() {
		setSidebarHeight();
    });
	
	function headerHeight() {
		if (win.scrollTop() > 30) {
			$('body').addClass('scrolled');
		} else {
			$('body').removeClass('scrolled');
		}
	}
	headerHeight();
	win.scroll(function() {
		headerHeight();
	});

}); /* end of as page load scripts */


