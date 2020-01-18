(function () {

	"use strict";

	new Vue({
		el: '#ccde-list-codes',
		template: '#ccde-list-codes-template',
		data: function() {
			return {
				itemsList: [],
				totalItems: 0,
				offset: 0,
				perPage: 30,
				deleteDialog: false,
				deleteItem: false,
				isLoading: false,
				filters: {},
			};
		},
		mounted: function() {
			this.getItems();
		},
		methods: {
			changePage: function( page ) {
				this.offset = this.perPage * ( page - 1 );
				this.getItems();
			},
			showDeleteDialog: function( itemID ) {
				this.deleteItem   = itemID;
				this.deleteDialog = true;
			},
			getSinglePageLink: function( item ) {

				var baseURL = window.CCDEConfig.single_url;
				var itemID  = false;

				if ( item && item.ID ) {
					itemID  = item.ID;
				}

				if ( itemID ) {
					baseURL += '&' + window.CCDEConfig.code_key + '=' + itemID;
				}

				return baseURL;

			},
			getDateString: function( item ) {

				if ( item.start_date && item.end_date ) {
					return item.start_date + ' - ' + item.end_date;
				} else if ( item.start_date && ! item.end_date ) {
					return 'from ' + item.start_date;
				} else if ( ! item.start_date && item.end_date ) {
					return 'until ' + item.start_date;
				} else {
					return '';
				}

			},
			getUseString: function( item ) {
				var result = item.used;
				if ( 0 < parseInt( item.max_uses, 10 ) ) {
					result += '/' + item.max_uses;
				}
				return result;
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
			getItems: function() {

				var self = this;

				self.isLoading = true;

				jQuery.ajax({
					url: window.ajaxurl,
					type: 'GET',
					dataType: 'json',
					data: {
						action: window.CCDEConfig.ajax.get_codes,
						nonce: window.CCDEConfig.nonce,
						offset: self.offset,
						per_page: self.perPage,
					},
				}).done(function( response ) {

					self.isLoading = false;
					if ( response.success ) {
						self.itemsList = response.items;
						if ( response.total ) {
							self.totalItems = parseInt( response.total, 10 );
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
		}
	});

})();
