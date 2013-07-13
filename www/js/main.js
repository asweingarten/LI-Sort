require.config(
{
	paths:
	{
		jquery: 	'lib/jquery',
		underscore: 'lib/underscore',
		backbone: 	'lib/backbone',
		text: 		'lib/text',
		// bstrap:  '../style/bootstrap/js/bootstrap',
		templates: 	'../templates'
	},

	shim:
	{
		backbone:
		{
			deps: [ 'lib/Underscore', 'jquery' ],
			exports: 'Backbone'
		},

		underscore:
		{
			exports: '_'
		}


	}
});


require( [
			'app'
], function( App )
{
	App.initialize();
});