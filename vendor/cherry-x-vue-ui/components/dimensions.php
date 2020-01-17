<cx-vui-component-wrapper
	:elementId="currentId"
	:label="label"
	:description="description"
	:wrapper-css="wrapperCss"
	:preventWrap="preventWrap"
	v-if="isVisible()"
>
	<div
		:class="controlClasses"
	>
		<div class="cx-vui-dimensions__units">
			<span
				v-for="unit in units"
				:class="{ active: unit.unit === currentValue['units'] }"
				@click="unitHandler(unit.unit)"
			>{{ unit.unit }}</span>
		</div>
		<div class="cx-vui-dimensions__inputs">
			<cx-vui-input
				:name="`${name}-top`"
				size="fullwidth"
				:wrapper-css="[ 'equalwidth' ]"
				type="number"
				:min="min"
				:max="max"
				:step="step"
				prevent-wrap="true"
				@input="handleInput"
				@on-change="handleChange"
				v-model="currentValue['top']"
			>
			</cx-vui-input>
			<cx-vui-input
				:name="`${name}-right`"
				size="fullwidth"
				:wrapper-css="[ 'equalwidth' ]"
				type="number"
				:min="min"
				:max="max"
				:step="step"
				prevent-wrap="true"
				@input="handleInput"
				@on-change="handleChange"
				v-model="currentValue['right']"
			>
			</cx-vui-input>
			<cx-vui-input
				:name="`${name}-bottom`"
				size="fullwidth"
				:wrapper-css="[ 'equalwidth' ]"
				type="number"
				:min="min"
				:max="max"
				:step="step"
				prevent-wrap="true"
				@input="handleInput"
				@on-change="handleChange"
				v-model="currentValue['bottom']"
			>
			</cx-vui-input>
			<cx-vui-input
				:name="`${name}-left`"
				size="fullwidth"
				:wrapper-css="[ 'equalwidth' ]"
				type="number"
				:min="min"
				:max="max"
				:step="step"
				prevent-wrap="true"
				@input="handleInput"
				@on-change="handleChange"
				v-model="currentValue['left']"
			>
			</cx-vui-input>
			<div
				class="cx-vui-dimensions__link"
				@click="linkHandler"
			>
				<svg v-if="isLink" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.2061 5.79298C12.0732 7.66201 12.0476 10.6585 10.2174 12.4989C10.2139 12.5027 10.2099 12.5067 10.2061 12.5105L8.10613 14.6105C6.25395 16.4627 3.24054 16.4624 1.38864 14.6105C-0.463547 12.7586 -0.463547 9.74485 1.38864 7.89298L2.5482 6.73342C2.8557 6.42592 3.38526 6.63029 3.40114 7.06485C3.42139 7.61867 3.5207 8.17507 3.70395 8.71238C3.76601 8.89432 3.72167 9.09557 3.58573 9.23151L3.17676 9.64048C2.30095 10.5163 2.27348 11.9424 3.14067 12.8267C4.01642 13.7198 5.45585 13.7251 6.33832 12.8427L8.43832 10.743C9.31929 9.86201 9.3156 8.43807 8.43832 7.56079C8.32266 7.44535 8.20616 7.35567 8.11516 7.29301C8.05079 7.2488 7.99763 7.19016 7.95994 7.12177C7.92225 7.05337 7.90106 6.97711 7.89807 6.89907C7.8857 6.56885 8.0027 6.22857 8.26363 5.96764L8.92157 5.30967C9.0941 5.13714 9.36476 5.11595 9.56482 5.25557C9.79393 5.41556 10.0085 5.59539 10.2061 5.79298V5.79298ZM14.6103 1.38855C12.7584 -0.46339 9.74504 -0.46364 7.89285 1.38855L5.79285 3.48854C5.7891 3.49229 5.78504 3.49636 5.7816 3.50011C3.95142 5.34048 3.92576 8.33701 5.79285 10.206C5.99044 10.4036 6.20503 10.5834 6.43414 10.7434C6.6342 10.883 6.90488 10.8618 7.07738 10.6893L7.73532 10.0314C7.99626 9.77042 8.11326 9.43013 8.10088 9.09992C8.09789 9.02188 8.07671 8.94562 8.03901 8.87722C8.00132 8.80883 7.94817 8.75019 7.88379 8.70598C7.79279 8.64332 7.67629 8.55363 7.56063 8.4382C6.68335 7.56092 6.67967 6.13698 7.56063 5.25601L9.66063 3.15633C10.5431 2.27386 11.9825 2.27917 12.8583 3.17226C13.7255 4.05664 13.698 5.4827 12.8222 6.35851L12.4132 6.76748C12.2773 6.90342 12.2329 7.10467 12.295 7.2866C12.4783 7.82392 12.5776 8.38032 12.5978 8.93413C12.6137 9.3687 13.1433 9.57307 13.4508 9.26557L14.6103 8.10601C16.4625 6.25417 16.4625 3.24042 14.6103 1.38855V1.38855Z" fill="#7B7E81"/>
				</svg>
				<svg v-if="!isLink" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M9.50186 12.6839C9.64829 12.8303 9.64829 13.0677 9.50186 13.2142L8.1058 14.6103C6.25383 16.4622 3.24039 16.4623 1.38827 14.6103C-0.463726 12.7582 -0.463726 9.74477 1.38827 7.89277L2.78436 6.49668C2.9308 6.35024 3.16824 6.35024 3.31471 6.49668L4.55214 7.73411C4.69858 7.88055 4.69858 8.11799 4.55214 8.26446L3.15605 9.66052C2.2788 10.5378 2.2788 11.9652 3.15605 12.8425C4.0333 13.7197 5.46074 13.7198 6.33805 12.8425L7.73411 11.4464C7.88055 11.3 8.11798 11.3 8.26445 11.4464L9.50186 12.6839ZM7.73411 4.55211C7.88055 4.69855 8.11798 4.69855 8.26445 4.55211L9.66051 3.15605C10.5378 2.27871 11.9652 2.27877 12.8425 3.15605C13.7198 4.03333 13.7198 5.46074 12.8425 6.33802L11.4464 7.73408C11.3 7.88052 11.3 8.11796 11.4464 8.26442L12.6839 9.50186C12.8303 9.6483 13.0677 9.6483 13.2142 9.50186L14.6103 8.10577C16.4623 6.25374 16.4623 3.2403 14.6103 1.38827C12.7582 -0.463724 9.74482 -0.463724 7.89276 1.38827L6.4967 2.78434C6.35027 2.93077 6.35027 3.16821 6.4967 3.31468L7.73411 4.55211ZM15.0725 15.7796L15.7796 15.0725C16.0725 14.7796 16.0725 14.3047 15.7796 14.0119L1.98671 0.218932C1.6938 -0.0739742 1.21893 -0.0739742 0.926053 0.218932L0.218929 0.926056C-0.0739768 1.21896 -0.0739768 1.69384 0.218929 1.98671L14.0118 15.7796C14.3047 16.0725 14.7796 16.0725 15.0725 15.7796V15.7796Z" fill="#7B7E81"/>
				</svg>
			</div>
		</div>
	</div>
</cx-vui-component-wrapper>
