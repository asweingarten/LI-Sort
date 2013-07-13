define([
	'underscore',
	'backbone'
], function( _, Backbone )
{
	var ProjectModel = Backbone.Model.extend(
	{

		defaults:
		{
			name: 'Project X'
		},

		initialize: function()
		{

		}
	});

	return ProjectModel;
});