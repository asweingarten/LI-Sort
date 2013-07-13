define([
	'underscore',
	'backbone',
	'models/project_model'
], function( _, Backbone, ProjectModel )
{
	var ProjectCollection = Backbone.Collection.extend(
	{
		model: ProjectModel
	});
});