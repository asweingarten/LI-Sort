define([
	'jquery',
	'underscore',
	'backbone',
	'views/person_view',
	'text!templates/person_collection.html'
], function( $, _, Backbone, PersonView, template )
{
	var PersonCollectionView = Backbone.View.extend(
	{
		el: $( '#person-collection-view-wrapper' ),

		render: function()
		{
			var data = {};

			var compiled_template = _.template( template, data );

			this.$el.append( compiled_template );
		}

	});

	return PersonCollectionView;

});