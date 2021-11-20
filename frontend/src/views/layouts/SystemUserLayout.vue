<template>
	<div>
		<v-app-bar
			app
			elevation="0"
		>

		</v-app-bar>
		<v-main class="mx-4 mb-4">
			<slot />
		</v-main>
	</div>
</template>
<script>
	export default {
		name: "SystemUserLayout"
	};
</script>