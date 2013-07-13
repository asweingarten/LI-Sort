define([
	'jquery',
	'underscore',
	'backbone',
	'views/person_view',
	'collections/person_collection',
	'text!templates/person_collection.html'
], function( $, _, Backbone, PersonView, PersonCollection, template )
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
			this.collection.add( {} );
			this.collection.add( {} );

		},

		render: function()
		{
			var data = {};

			var compiled_template = _.template( template, data );

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