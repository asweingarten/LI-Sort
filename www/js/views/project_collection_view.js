define([
	'jquery',
	'underscore',
	'backbone',
	'views/person_view',
	'text!templates/project_collection.html'
], function( $, _, Backbone, ProjectView, template )
{
	var ProjectCollectionView = Backbone.View.extend(
	{
		el: $( '#projects-wrapper' ),

		render: function()
		{
			var data = {};

			var compiled_template = _.template( template, data );

			this.$el.append( compiled_template );
		}

	});

	return ProjectCollectionView;

});