require.config(
{
	paths:
	{
		jquery: 	'lib/jquery',
		underscore: 'lib/underscore',
		backbone: 	'lib/backbone'
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