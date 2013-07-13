define([
	'underscore',
	'backbone',
	'models/person_model'
], function( _, Backbone, PersonModel )
{
	var PersonCollection = Backbone.Collection.extend(
	{
		model: PersonModel,

		url: 'php/person',

		initialize: function()
		{
			this.fetch({
				success: function( collection, resp, options )
				{
					console.log( 'fetch success' );

				},
				error: function( collection, resp, options )
				{
					console.log( 'fetch failure' );
				}
			});
		}

	});

	return PersonCollection;

});