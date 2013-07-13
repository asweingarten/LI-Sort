define([
	'jquery',
	'underscore',
	'backbone',
	'views/person_view',
	'collections/person_collection',
	'text!templates/person_collection.html'
], function( $, _, Backbone, PersonView, PersonCollection, Template )
{
	var PersonCollectionView = Backbone.View.extend(
	{
		tagName: 'div',
		className: 'personCollection',

		events:
		{
			'click .filterable-person-name': 'filterByName'
		},


		initialize: function()
		{
			_.bindAll( this, 'render', 'collectNames', 'filterByName' );
			this.names = [];
			this.collection = new PersonCollection();
			this.collection.on( 'add', this.render, this );
			this.collection.on( 'add', this.collectNames, this );
		},

		render: function()
		{

			this.$el.empty();

			var data = {};

			var compiled_template = _.template( Template, data );

			this.$el.append( compiled_template );

			// Main Panel
			_.each( this.collection.models, function( model )
			{
				var view = new PersonView( model );
				this.$('#cur-persons').append( view.render() );
			}, this );


			// Sidebar
			this.$( '#person-names' ).append( _.template( "<li class='filterable-person-name' id=\"show-all-people\">Show All</li>" ) );
			_.each( this.names, function( name )
			{
				this.$( '#person-names' ).append( _.template( "<li class='filterable-person-name' id=\"<%=name%>\"><%=name%></li>", { name: name } ) );
			}, this );

			$( '#people-wrapper' ).append( this.$el );
		},

		collectNames: function()
		{
			this.names = [];
			_.each( this.collection.models, function( model)
				{
					this.names.push( model.get( 'name' ) );
				}, this );
			console.log( this.names );
		},

		filterByName: function( e )
		{
			var id = e.currentTarget.id;
			if( id == 'show-all-people' )
			{
				_.each( $( '#cur-persons' ).children(), function( person )
				{
					$( person ).show();
				}, this );
				return;
			}
			else
			{
				// var people = $( '#cur-persons' ).children();
				_.each( $( '#cur-persons' ).children(), function( person )
				{
					if( $( person ).attr( 'id' ) != id )
					{
						$( person ).hide();
					}
					else
					{
						$( person ).show();
					}
				}, this );
			}
			console.log( 'name clicked' );
		}

	});

	return PersonCollectionView;

});