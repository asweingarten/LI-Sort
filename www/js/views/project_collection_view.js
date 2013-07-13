define([
	'jquery',
	'underscore',
	'backbone',
	'models/project_model',
	'views/project_view',
	'collections/project_collection',
	'text!templates/project_collection.html',
	'text!templates/create_project.html'
], function( $, _, Backbone, ProjectModel, ProjectView, ProjectCollection, Template, createProjTemplate )
{
	var ProjectCollectionView = Backbone.View.extend(
	{

		// tagName:  'div',
		// className: 'projectCollection',
		el: $('#projects-wrapper'),

		events:
		{
			'click #launch-project-create' : 'launchProjectCreate',
			'click #create-project' : 'createProject'
		},

		initialize: function()
		{
			_.bindAll( this, 'render', 'createProject', 'launchProjectCreate', 'createProject' );
			this.collection = new ProjectCollection();
			this.collection.on( 'add', this.render, this );
		},

		render: function()
		{
			$( '#projects-wrapper' ).empty();

			var data = {};

			var compiled_template = _.template( Template, data );
			this.$el.empty(); //hack
			this.$el.append( compiled_template );

			_.each( this.collection.models, function( model )
			{
				var view = new ProjectView( model );
				this.$('.projects').append( view.render() );
			}, this );

			$( '#projects-wrapper' ).append( this.$el );
		},

		launchProjectCreate: function()
		{
			console.log('launched');
			var compiled_template = _.template( createProjTemplate );
			this.$el.append( compiled_template );
		},

		createProject: function()
		{
			console.log('created');

			var description = this.$( '#new-project-description' ).val();
			var title		= this.$( '#new-project-title' ).val();
			console.log( description + " " + title );

			var projectModel = new ProjectModel(
				{
					description: description,
					title: title
				});
			$.post( "php/project", projectModel.attributes );
			// projectModel.save( projectModel.attributes );

			this.$('#project-create-dialouge').remove();
		}

	});

	return ProjectCollectionView;

});