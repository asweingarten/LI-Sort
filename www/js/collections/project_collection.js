define([
	'underscore',
	'backbone',
	'models/project_model'
], function( _, Backbone, ProjectModel )
{
	var ProjectCollection = Backbone.Collection.extend(
	{
		model: ProjectModel,

		url: 'php/project',

		initialize: function()
		{
			this.fetch(
			{
				success: function( collection, resp, options )
				{
					console.log( 'fetch success' );
				},

				error: function( collection, resp, options )
				{
					console.log( 'fetch success' );
				}
			});
		}
	});

	return ProjectCollection;

});