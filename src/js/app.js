// https://dev.to/krowemoh/a-vue3-tutorial-08-vue-components-without-a-build-system-2-a-better-way-g1g
// VS plug-in: Comment tagged templates
const template = /*html*/`
	
    <div class="container main-container" v-if="isVisioRoute == false">
		<div class="border rounded p-5">

			<div v-if="global_logo_url != ''" class="text-center pb-3">
				<img class="logo logo-home" :src="global_logo_url">
			</div>

			<h1>{{ $t('title') }}</h1>
			
			<form class="row g-3">
			
				<div class="col-12">
					<label for="visio_code" class="form-label">{{ $t('visio_code') }}</label>
					<input type="text" class="form-control" id="visio_code" v-model="visio_code" placeholder="">
				</div>

				<div class="col-12">
					<label for="user_name" class="form-label">{{ $t('user_name') }}</label>
					<input type="text" class="form-control" id="user_name" v-model="user_name" placeholder="">
				</div>

				<div class="col-12">
					<label for="visio_pwd" class="form-label">{{ $t('visio_password') }}</label>
					<input type="text" class="form-control" id="visio_pwd" v-model="visio_pwd" placeholder="">
					<small class="text-muted">{{ $t('visio_password_desc') }}</small> 
				</div>
				<div class="col-12">
					<button class="btn btn-primary">{{ $t('btn_create_login') }}</button>
				</div>
			</form>
		</div>
	</div>

	<visio v-if="isVisioRoute" :code="visioCode" :room="roomCode"></visio>
`;

let urlParams = new URLSearchParams(window.location.search)

$(function() {
	let app = Vue.createApp({
		data() {
			return {
				visio_code: "",
				visio_pwd: "",
				user_name: "",
				global_logo_url : global_logo_url
			}
		},
		components: {
			'visio': Vue.defineAsyncComponent( () => import('./visio.js')),
		},
		template: template,

		computed: {
			isVisioRoute() { return urlParams.has("code"); },

			visioCode() { return urlParams.get("code"); },
			roomCode() { return urlParams.has("room") ? urlParams.get("room"): ''; },

		},
		methods: {
			
		},
		mounted() {
			
		}
	})

	const i18n = VueI18n.createI18n({
		locale: global_locale,
		messages: global_lang_messages,
	}) 

	app.use(i18n)

	app.mixin({
		methods: {
			ellipsis: function(str, maxLength = 50)
			{
				if (str == undefined || str == null) return '';
				if (str.length > maxLength - 3) return str.substring(0, maxLength - 3) + '...';
				else return str;
			},
		}
	});
	app.mount('#app')
	
})

