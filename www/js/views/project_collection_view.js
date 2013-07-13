define([
	'jquery',
	'underscore',
	'backbone',
	'views/project_view',
	'collections/project_collection',
	'text!templates/project_collection.html'
], function( $, _, Backbone, ProjectView, ProjectCollection, Template )
{
	var ProjectCollectionView = Backbone.View.extend(
	{
		el: $( '#projects-wrapper' ),

		initialize: function()
		{
			_.bindAll( this, 'render' );
			this.collection = new ProjectCollection();
			this.collection.on( 'add', this.render, this );
		},

		render: function()
		{
			$( '#projects-wrapper' ).empty();

			var data = {};

			var compiled_template = _.template( Template, data );

			_.each( this.collection.models, function( model )
			{
				var view = new ProjectView( model );
				this.$el.append( view.render() );
			}, this );

			$( '#projects-wrapper' ).append( this.$el );
		}

	});

	return ProjectCollectionView;

});