(function () {

	"use strict";

	new Vue({
		el: '#ccde-single-code',
		template: '#ccde-single-code-template',
		data: function() {
			return {
				isEdit: window.CCDEConfig.is_edit,
				code: JSON.parse( JSON.stringify( window.CCDEConfig.code ) ),
				notFound: window.CCDEConfig.not_found,
				downloadsList: window.CCDEConfig.downloads_list,
				errors: {
					name: false,
					code: false,
					amount: false,
				},
				deleteDialog: false,
				isLoading: false,
			};
		},
		methods: {
			showDeleteDialog: function( itemID ) {
				this.deleteDialog = true;
			},
			getSinglePageLink: function( itemID ) {

				var baseURL = window.CCDEConfig.single_url;

				itemID = itemID || false;

				if ( itemID ) {
					baseURL += '&' + window.CCDEConfig.code_key + '=' + itemID;
				}

				return baseURL;

			},
			clearError: function( prop ) {
				this.$set( this.errors, prop, false );
			},
			handleDelete: function() {

				var self = this;

				if ( ! self.deleteItem ) {
					return;
				}

				jQuery.ajax({
					url: window.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CCDEConfig.ajax.delete_code,
						nonce: window.CCDEConfig.nonce,
						code: self.deleteItem,
					},
				}).done(function( response ) {

					if ( ! response.success ) {
						self.$CXNotice.add( {
							message: response.data,
							type: 'error',
							duration: 7000,
						} );
					}

					for ( var i = 0; i < self.itemsList.length; i++ ) {
						if ( self.itemsList[ i ].ID === self.deleteItem ) {
							self.itemsList.splice( i, 1 );
							break;
						}
					}

				}).fail(function() {
					self.$CXNotice.add( {
						message: 'Error!',
						type: 'error',
						duration: 7000,
					} );
				});

			},
			handleSave: function() {

				var self = this,
					requiredFields = [ 'name', 'code', 'amount' ],
					hasErrors = false;

				self.isLoading = true;

				for ( var i = 0; i < requiredFields.length; i++ ) {
					let prop = requiredFields[ i ];

					if ( ! self.code[ prop ] ) {
						self.$set( self.errors, prop, true );
						hasErrors = true;
					}

				}

				if ( hasErrors ) {
					self.isLoading = false;
					return;
				}

				jQuery.ajax({
					url: window.ajaxurl,
					type: 'GET',
					dataType: 'json',
					data: {
						action: window.CCDEConfig.ajax.save_code,
						nonce: window.CCDEConfig.nonce,
						code: self.code,
					},
				}).done(function( response ) {

					self.isLoading = false;

					if ( response.success ) {
						self.itemsList = response.items;
						if ( response.total ) {
							self.totalItems = parseInt( response.total, 10 );
						}
					}

				}).fail( function() {

					self.isLoading = false;

					self.$CXNotice.add( {
						message: 'Error!',
						type: 'error',
						duration: 7000,
					} );
				});

			},
		}
	});

})();
