define([
	'jquery',
	'underscore',
	'backbone',
	'views/person_view',
	'collections/person_collection',
	'text!templates/person_collection.html'
], function( $, _, Backbone, PersonView, PersonCollection, Template )
{
	var PersonCollectionView = Backbone.View.extend(
	{
		// el: $( '#people-wrapper' ),
		tagName: 'ul',
		className: 'personCollection',

		initialize: function()
		{
			_.bindAll( this, 'render' );
			this.collection = new PersonCollection();
			this.collection.on( 'add', this.render, this );
		},

		render: function()
		{

			$( '#people-wrapper' ).empty();

			var data = {};

			var compiled_template = _.template( Template, data );

			// this.$el.append( compiled_template );

			_.each( this.collection.models, function( model )
			{
				var view = new PersonView( model );
				this.$el.append( view.render() );
			}, this );


			$( '#people-wrapper' ).append( this.$el );
		}

	});

	return PersonCollectionView;

});