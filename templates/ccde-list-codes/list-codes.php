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
			<span slot="name"><?php _e( 'Name e-mail', 'croco-edd-custom-discounts' ); ?></span>
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
			<span slot="code">{{ getServiceLabel( item.service ) }}</span>
			<span slot="dates">{{ getProviderLabel( item.provider ) }}</span>
			<span slot="uses">{{ item.date }}</span>
			<span slot="status">{{ item.slot }} - {{ item.slot_end }}</span>
			<div slot="actions">
				<cx-vui-button
					button-style="link-accent"
					size="mini"
					tag-name="a"
					:url="getSinglePageLink( item )"
				><span slot="label"><?php _e( 'Edit', 'croco-edd-custom-discounts' ); ?></span></cx-vui-button>
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
			_e( 'Are you sure? Deleted appointment can\'t be restored.', 'croco-edd-custom-discounts' );
		?></div>
	</cx-vui-popup>
</div>