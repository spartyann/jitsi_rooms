// https://dev.to/krowemoh/a-vue3-tutorial-08-vue-components-without-a-build-system-2-a-better-way-g1g
// VS plug-in: Comment tagged templates
const template = /*html*/`
	<div id="visio_header">
		<img v-if="global_logo_url != ''" :src="global_logo_url" class="logo-visio" />
		
		<a :href="homeUrl" >{{ $t("visio_return_btn" )}}</a>

		<div class="separator"></div>

		<template v-if="conf != null">
		
			<a :href="getRoomUrl('')" >{{ $t("main_room") }}</a>

			<template v-for="(room) in conf.params.rooms">
				<a :href="getRoomUrl(room)" >{{ room }}</a>
			</template>

			<div style="flex:1"></div>
			<div class="me-5 fz-140"><b>{{ time }}</b></div>

		</template>
	</div>

	<jitsi :code="code" :room="room"></jitsi>
`;


let urlParams = new URLSearchParams(window.location.search)

export default {
	data() {
		return {
			conf_admin_password: this.getLocalStorageItem('conf_admin_password', ""),
			global_logo_url : global_logo_url,
			conf: null,
			time: "sdf"
		}
	},
	template: template,

	components: {
		'jitsi': Vue.defineAsyncComponent( () => import('./jitsi.js')),
	},

	props: {
		code: { type: String },
		room: { type: String, default: "" },
	},

	computed: {
		homeUrl() { return window.location.href.replace(window.location.search, '') },
		
	},

	methods: {
		refresh() {
			let self = this;
			this.api({
				action: 'getOrCreate',
				conf_code: this.code,
				conf_admin_password: this.conf_admin_password,
			}).then((response) => {
				self.conf = response.data.conf;
			})
		},

		refreshTime(){
			let date = new Date(); let h = date.getHours(); let m = date.getMinutes()
			this.time = (h < 10 ? '0' + h : h) + ":" + (m < 10 ? '0' + m : m)
		},

		getRoomUrl(room) {
			return "?code=" + encodeURIComponent(this.code) + (room == '' ? '' : "&room=" + encodeURIComponent(room))
		}
	},

	mounted() {
		this.refresh();
		this.refreshTime();
		let self = this;

		setInterval(() => {
			self.refresh();
		}, 5000);

		setInterval(() => {
			self.refreshTime();
		}, 1000);
	}
}


