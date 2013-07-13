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
		el: $( '#people-wrapper' ),

		initialize: function()
		{
			this.collection = new PersonCollection();
			this.collection.add( {} );

		},

		render: function()
		{
			var data = {};

			var compiled_template = _.template( template, data );

			this.$el.append( compiled_template );
		}

	});

	return PersonCollectionView;

});