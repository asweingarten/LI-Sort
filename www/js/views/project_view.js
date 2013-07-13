define([
	'jquery',
	'underscore',
	'backbone',
	'models/project_model',
	'text!templates/project.html'
], function( $, _, Backbone, ProjectModel, Template )
{
	var ProjectView = Backbone.View.extend(
	{
		className: 'person',
		tagName: 'li',

		events:
		{

		},

		initialize: function( model )
		{
			_.bindAll( this, 'render' );
			this.model = model;
		},

		// Returns its HTML to be used by collection
		render: function()
		{
			var compiled_template = _.template( Template, { model: this.model } );
			return compiled_template;
		}
	});

	return ProjectView;

});