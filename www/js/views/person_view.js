define([
	'jquery',
	'underscore',
	'backbone',
	'models/person_model'
], function( $, _, Backbone, PersonModel )
{
	var PersonView = Backbone.View.extend(
	{
		model: PersonModel,
		className: 'person',
		tagName: 'li',

		events:
		{

		},

		initialize: function()
		{
			this.listenTo( this.model, "change", this.render );
		}
	});

	return PersonView;

});