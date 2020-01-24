<div class="ccde-single">
	<h1 class="cx-bui-title" v-if="notFound">Code not found</h1>
	<div class="ccde-single-body" v-else>
		<h1 class="cx-bui-title" v-if="isEdit">Code #{{ code.ID }}</h1>
		<h1 class="cx-bui-title" v-else>Add new code</h1>
		<div class="ccde-single-wrap">
			<div class="ccde-single-fields">
				<div class="cx-vui-panel">
					<cx-vui-input
						label="Name"
						description="The name of this discount"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:error="errors.name"
						@on-input-change="clearError( 'name' )"
						v-model="code.name"
					></cx-vui-input>
					<cx-vui-input
						label="Code"
						description="Enter a code for this discount, such as 10PERCENT. Only alphanumeric characters are allowed."
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:error="errors.code"
						@on-input-change="clearError( 'code' )"
						v-model="code.code"
					></cx-vui-input>
					<cx-vui-select
						label="Type"
						description="The kind of discount to apply for this code"
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
						v-model="code.type"
					></cx-vui-select>
					<cx-vui-input
						label="Amount"
						description="Enter amount value. 10 = 10%, 10 = 10 USD"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:error="errors.amount"
						@on-input-change="clearError( 'amount' )"
						v-model="code.amount"
					></cx-vui-input>
					<cx-vui-f-select
						label="Download Requirements"
						description="Select Downloads relevant to this discount. If left blank, this discount can be used on any product"
						:wrapper-css="[ 'equalwidth' ]"
						placeholder="Select option..."
						:multiple="true"
						:options-list="downloadsList"
						v-model="code.meta.required_downloads"
					></cx-vui-f-select>
					<cx-vui-input
						label="Start date"
						description="Enter the start date for this discount code. For no start date, leave blank. If entered, the discount can only be used after or on this date."
						type="date"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						v-model="code.start_date"
					></cx-vui-input>
					<cx-vui-input
						label="Expiration date"
						description="Enter the expiration date for this discount code. For no expiration, leave blank."
						type="date"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						v-model="code.end_date"
					></cx-vui-input>
					<cx-vui-input
						label="Max Uses"
						description="The maximum number of times this discount can be used. Leave blank for unlimited."
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						v-model="code.max_uses"
					></cx-vui-input>
					<cx-vui-select
						label="Status"
						description="Code status - active or inactive"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:options-list="[
							{
								value: 'active',
								label: 'Active',
							},
							{
								value: 'inactive',
								label: 'Inactive',
							},
							{
								value: 'expired',
								label: 'Expired',
							}
						]"
						v-model="code.status"
					></cx-vui-select>
					<cx-vui-textarea
						label="Included Pricing IDs"
						description="Format: 9::6,9::7,9::9. Code will work only for specified download IDs and pricing option IDs pairs."
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						v-model="code.meta.included_pricing_ids"
					></cx-vui-textarea>
					<cx-vui-textarea
						label="Excluded Pricing IDs"
						description="Format: 9::6,9::7,9::9. Code will not work specified download IDs and pricing option IDs pairs presented in the cart."
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						v-model="code.meta.excluded_pricing_ids"
					></cx-vui-textarea>
					<cx-vui-input
						label="Affiliate Discount"
						description="If you would like to connect this discount to an affiliate, enter the name of the affiliate it belongs to."
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						v-model="code.meta.affiliate"
					></cx-vui-input>
				</div>
			</div>
			<div class="ccde-single-actions">
				<div class="code-single-actions-content">
					<cx-vui-button
						button-style="accent"
						:loading="isLoading"
						@click="handleSave"
					>
						<span slot="label" v-if="isEdit"><?php
							_e( 'Save Code', 'croco-edd-custom-discounts' );
						?></span>
						<span slot="label" v-else><?php
							_e( 'Add Code', 'croco-edd-custom-discounts' );
						?></span>
					</cx-vui-button>
				</div>
			</div>
		</div>
	</div>
</div>