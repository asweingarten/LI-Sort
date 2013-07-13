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

		initialize: function()
		{

		}
	});

	return PersonModel;
});