define([
	'underscore',
	'backbone'
], function( _, Backbone )
{
	var PersonModel = Backbone.model.extend(
	{

		defaults:
		{
			name: 'Ariel Weingarten'
		}

		initialize: function()
		{

		}
	});

	return PersonModel;
});