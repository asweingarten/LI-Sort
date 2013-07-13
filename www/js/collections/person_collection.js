define([
	'underscore',
	'backbone',
	'models/person_model'
], function( _, Backbone, PersonModel )
{
	var PersonCollection = Backbone.Collection.extend(
	{
		model: PersonModel
	});

	return PersonCollection;

});