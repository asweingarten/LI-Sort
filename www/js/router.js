define( [
	'jquery',
	'underscore',
	'backbone',
	'views/person_collection_view',
	'views/project_collection_view'
], function( $, _, Backbone, PersonCollectionView,
			 ProjectCollectionView )
{
	var AppRouter = Backbone.Router.extend(
	{
		routes:
		{
			'people'   : 'showPeople',
			'projects' : 'showProjects',
			'*actions' : 'defaultAction'
		}
	});

	var initialize = function()
	{
		var app_router = new AppRouter;

		app_router.on( 'route:showPeople', function()
		{
			var personCollectionView = new PersonCollectionView();
			personCollectionView.render();
			console.log( 'Showing People' );
		});

		app_router.on( 'route:showProjects', function()
		{
			var projectCollectionView = new ProjectCollectionView();
			projectCollectionView.render();
			console.log( 'Showing Projects' );
		});

		app_router.on( 'route:defaultAction', function()
		{
			console.log( 'No route: default action' );
		});

		Backbone.history.start();
	};

	return { initialize: initialize };

});