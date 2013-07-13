require.define( [
	'jquery',
	'underscore',
	'backbone'
], function( $, _, Backbone, Session )
{
	var AppRouter = Backbone.Router.extend(
	{
		routes:
		{
			'/people': 'showPeople',
			'*actions': 'defaultAction'
		}
	});

	var initialize = function()
	{
		var app_router = AppRouter;

		app_router.on( 'showPeople', function()
		{
			console.log( 'Showing People' );
		});

		app_router.on( 'defaultAction', function()
		{
			console.log( 'No route: default action' );
		});

		Backbone.history.start();
	};

	return { initialize: initialize };

});