define([
	'underscore',
	'backbone'
], function( _, Backbone )
{
	var ProjectModel = Backbone.Model.extend(
	{

		defaults:
		{
			name: 'Project X',
			creator_id: 1
		},

		urlRoot: '/php/project',

		initialize: function()
		{

		}
	});

	return ProjectModel;
});