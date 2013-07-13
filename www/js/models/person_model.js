define([
	'underscore',
	'backbone'
], function( _, Backbone )
{
	var PersonModel = Backbone.Model.extend(
	{

		defaults:
		{
			name: 'Ariel Weingarten'
		},

		// urlRoot: 'localhost/php/person',

		initialize: function()
		{

		}
	});

	return PersonModel;
});