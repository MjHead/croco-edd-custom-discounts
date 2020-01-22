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
				perPage: 100,
				deleteDialog: false,
				deleteItem: false,
				isLoading: false,
				filters: {},
				generatePopup: false,
				generateCodesNum: 100,
				generated: 0,
				generating: false,
				generateHash: 'c55441e06674374fc26f2f9f5b1b11de',
				generateStep: 200,
				propsList: window.CCDEConfig.props,
				codeMap: JSON.parse( JSON.stringify( window.CCDEConfig.code ) ),
				downloadsList: window.CCDEConfig.downloads_list,
				exportColumns: [],
				exportPopup: false,
			};
		},
		mounted: function() {
			this.getItems();
		},
		computed: {
			currentPage: function() {
				return Math.floor( this.offset / this.perPage ) + 1;
			},
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
			handleExport: function() {

				if ( ! this.generateHash || ! this.exportColumns.length ) {
					return;
				}

				window.location = window.ajaxurl + '?action=' + window.CCDEConfig.ajax.export_codes + '&hash=' + this.generateHash + '&columns=' + this.exportColumns.join( ',' ) + '&nonce=' + window.CCDEConfig.nonce;
			},
			handleGenerate: function() {

				var number = this.generateCodesNum;

				if ( number > this.generateStep ) {
					number = this.generateStep;
				}

				this.generating = true;

				this.generateCodesRequest( 0, number );
			},
			generateCodesRequest: function( offset, number ) {

				offset = parseInt( offset, 10 );
				number = parseInt( number, 10 );


				var self  = this,
					total = offset + number;

				self.generateCodesNum = parseInt( self.generateCodesNum, 10 );

				jQuery.ajax({
					url: window.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CCDEConfig.ajax.generate_codes,
						nonce: window.CCDEConfig.nonce,
						code: self.codeMap,
						number: number,
						offset: offset,
					},
				}).done(function( response ) {

					if ( ! response.success ) {
						self.$CXNotice.add( {
							message: response.data,
							type: 'error',
							duration: 7000,
						} );
					} else {

						if ( self.generateCodesNum > total ) {

							number = self.generateCodesNum - total;

							if ( number > self.generateStep ) {
								number = self.generateStep;
							}

							self.generateCodesRequest( total, number );

						}

						self.generateHash = response.hash;
						self.generated    = response.number;

					}

				}).fail(function() {
					self.$CXNotice.add( {
						message: 'Error!',
						type: 'error',
						duration: 7000,
					} );
				});
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
