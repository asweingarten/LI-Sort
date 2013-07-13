define([
	'jquery',
	'underscore',
	'backbone',
	'models/project_model'
], function( $, _, Backbone, ProjectModel )
{
	var ProjectView = Backbone.View.extend(
	{
		model: ProjectModel,
		className: 'person',
		tagName: 'li',

		events:
		{

		},

		initialize: function()
		{
			this.listenTo( this.model, "change", this.render );
		}
	});

	return ProjectView;

});