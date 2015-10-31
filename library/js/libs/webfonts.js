$ = jQuery;
var activeCallback = $.Callbacks();
WebFont.load({
	google: { families: [ 'Merriweather:400,700,400italic,700italic:latin', 'Fira+Sans:400,300:latin', 'Montserrat:400,700:latin' ] },
	active: function () { activeCallback.fire(); }
});