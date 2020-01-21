<div
	:class="{ 'ccde-loading': isLoading }"
>
	<div class="ccde-header">
		<h1 class="cx-vui-title">Discount Codes</h1>
		<cx-vui-button
			button-style="accent-border"
			size="mini"
			tag-name="a"
			:url="getSinglePageLink()"
		><span slot="label"><?php _e( 'Add New', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<cx-vui-button
			button-style="accent-border"
			size="mini"
			@click="generatePopup = true"
		><span slot="label"><?php _e( 'Cenerate', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<cx-vui-button
			button-style="accent-border"
			size="mini"
			@click="exportPopup = true"
		><span slot="label"><?php _e( 'Export', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
	</div>
	<cx-vui-list-table
		:is-empty="! itemsList.length"
		empty-message="<?php _e( 'No codes found', 'croco-edd-custom-discounts' ); ?>"
	>
		<cx-vui-list-table-heading
			:slots="[ 'id', 'name', 'code', 'dates', 'uses', 'status', 'actions' ]"
			slot="heading"
		>
			<span slot="id"><?php _e( 'ID', 'croco-edd-custom-discounts' ); ?></span>
			<span slot="name"><?php _e( 'Name', 'croco-edd-custom-discounts' ); ?></span>
			<span slot="code"><?php _e( 'Code', 'croco-edd-custom-discounts' ); ?></span>
			<span slot="dates"><?php _e( 'Active for', 'croco-edd-custom-discounts' ); ?></span>
			<span slot="uses"><?php _e( 'Uses', 'croco-edd-custom-discounts' ); ?></span>
			<span slot="status"><?php _e( 'Status', 'croco-edd-custom-discounts' ); ?></span>
			<span slot="actions"><?php _e( 'Actions', 'croco-edd-custom-discounts' ); ?></span>
		</cx-vui-list-table-heading>
		<cx-vui-list-table-item
			:slots="[ 'id', 'name', 'code', 'dates', 'uses', 'status', 'actions' ]"
			slot="items"
			v-for="( item, index ) in itemsList"
			:key="item.ID + item.code"
		>
			<span slot="id">{{ item.ID }}</span>
			<span slot="name">{{ item.name }}</span>
			<span slot="code">{{ item.code }}</span>
			<span slot="dates">{{ getDateString( item ) }}</span>
			<span slot="uses">{{ getUseString( item ) }}</span>
			<span slot="status">{{ item.status }}</span>
			<div slot="actions">
				<cx-vui-button
					button-style="link-accent"
					size="link"
					tag-name="a"
					:url="getSinglePageLink( item )"
				><span slot="label"><?php _e( 'Edit', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<cx-vui-button
					button-style="link-error"
					size="link"
					@click="showDeleteDialog( item.ID )"
				><span slot="label"><?php _e( 'Delete', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
			</div>
		</cx-vui-list-table-item>
	</cx-vui-list-table>
	<cx-vui-pagination
		v-if="perPage < totalItems"
		:total="totalItems"
		:page-size="perPage"
		@on-change="changePage"
	></cx-vui-pagination>
	<cx-vui-popup
		v-model="deleteDialog"
		body-width="460px"
		ok-label="<?php _e( 'Delete', 'croco-edd-custom-discounts' ) ?>"
		cancel-label="<?php _e( 'Cancel', 'croco-edd-custom-discounts' ) ?>"
		@on-cancel="deleteDialog = false"
		@on-ok="handleDelete"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Are you sure? Deleted code can\'t be restored.', 'croco-edd-custom-discounts' );
		?></div>
	</cx-vui-popup>
	<cx-vui-popup
		v-model="exportPopup"
		body-width="850px"
		:show-ok="false"
		:show-cancel="false"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Export promocodes', 'croco-edd-custom-discounts' );
		?></div>
		<div slot="content">
			<cx-vui-input
				label="Generate hash"
				description="Export codes with this hash"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="generateHash"
			></cx-vui-input>
			<cx-vui-f-select
				label="Columns to export"
				:wrapper-css="[ 'equalwidth' ]"
				placeholder="Select columns..."
				:multiple="true"
				:options-list="propsList"
				v-model="exportColumns"
			></cx-vui-f-select>
			<cx-vui-button
				button-style="accent"
				size="mini"
				@click="handleExport"
			><span slot="label"><?php _e( 'Export', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
		</div>
	</cx-vui-popup>
	<cx-vui-popup
		v-model="generatePopup"
		body-width="850px"
		:show-ok="false"
		:show-cancel="false"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Generate promocodes', 'croco-edd-custom-discounts' );
		?></div>
		<div v-if="generating" slot="content">
			Generating codes: {{ generated }} / {{ generateCodesNum }}.
		</div>
		<div slot="content" v-else>
			<cx-vui-input
				label="Codes number"
				description="Discount codes number to generate"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="generateCodesNum"
			></cx-vui-input>
			<cx-vui-input
				label="Name"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="codeMap.name"
			></cx-vui-input>
			<cx-vui-select
				label="Type"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:options-list="[
					{
						value: 'percentage',
						label: 'Percentage',
					},
					{
						value: 'flat',
						label: 'Flat Amount',
					}
				]"
				v-model="codeMap.type"
			></cx-vui-select>
			<cx-vui-input
				label="Amount"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="codeMap.amount"
			></cx-vui-input>
			<cx-vui-f-select
				label="Download Requirements"
				:wrapper-css="[ 'equalwidth' ]"
				placeholder="Select option..."
				:multiple="true"
				:options-list="downloadsList"
				v-model="codeMap.meta.required_downloads"
			></cx-vui-f-select>
			<cx-vui-input
				label="Start date"
				type="date"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="codeMap.start_date"
			></cx-vui-input>
			<cx-vui-input
				label="Expiration date"
				type="date"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="codeMap.end_date"
			></cx-vui-input>
			<cx-vui-input
				label="Max Uses"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="codeMap.max_uses"
			></cx-vui-input>
			<cx-vui-textarea
				label="Included Pricing IDs"
				description="Format: 9::6,9::7,9::9."
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="codeMap.meta.included_pricing_ids"
			></cx-vui-textarea>
			<cx-vui-textarea
				label="Excluded Pricing IDs"
				description="Format: 9::6,9::7,9::9."
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="codeMap.meta.excluded_pricing_ids"
			></cx-vui-textarea>
			<cx-vui-button
				button-style="accent"
				size="mini"
				@click="handleGenerate"
			><span slot="label"><?php _e( 'Start', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
		</div>
	</cx-vui-popup>
</div>