define([
	'jquery',
	'underscore',
	'backbone',
	'models/person_model',
	'text!templates/person.html'
], function( $, _, Backbone, PersonModel, template )
{
	var PersonView = Backbone.View.extend(
	{
		className: 'person',
		tagName: 'li',

		events:
		{

		},

		initialize: function( model )
		{
			_.bindAll( this, 'render' );
			this.model = model;
		},

		// Returns it's HTML to be used by collection
		render: function()
		{

			var compiled_template = _.template( template, { model: this.model } );
			return compiled_template;
		}

	});

	return PersonView;

});