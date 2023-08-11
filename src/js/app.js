// https://dev.to/krowemoh/a-vue3-tutorial-08-vue-components-without-a-build-system-2-a-better-way-g1g
// VS plug-in: Comment tagged templates
const template = /*html*/`
	
	<visio v-if="isVisioRoute" :code="visioCode" :room="roomCode"></visio>
	<div v-else-if="isAdminRoute">
		<admin></admin>
	</div>
    <div class="container main-container" v-else>
		<div class="border rounded p-5">

			<div v-if="global_logo_url != ''" class="text-center pb-3">
				<img class="logo logo-home" :src="global_logo_url">
			</div>

			<h1>{{ $t('title') }}</h1>

			<div v-if="$t('home_description') != ''" class="my-4" v-html="$t('home_description')"></div>
			
			<form class="row g-3">
				
				<div class="col-12">
					<label for="conf_code" class="form-label">{{ $t('conf_code') }}</label>
					<input type="text" class="form-control" id="conf_code" v-model="conf_code" placeholder="" minlength="1" maxlength="150">
					<small class="text-muted">{{ $t('conf_code_desc') }}</small> 
				</div>

				<div class="col-12">
					<label for="user_name" class="form-label">{{ $t('user_name') }}</label>
					<input type="text" class="form-control" id="user_name" v-model="user_name" placeholder="" minlength="1" maxlength="150">
				</div>

				<div class="col-12">
					<div class="form-check">
						<input class="form-check-input" type="radio" id="radio_user" value="user" v-model="user_type">
						<label class="form-check-label" for="radio_user" >{{ $t('i_am_user') }}</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" id="radio_admin" value="admin" v-model="user_type">
						<label class="form-check-label" for="radio_admin" >{{ $t('i_am_admin') }}</label>
					</div>
				</div>

				<div class="col-12" v-if=" user_type == 'admin'">
					<label for="conf_admin_password" class="form-label">{{ $t('visio_password') }}</label>
					<input type="text" class="form-control" id="conf_admin_password" v-model="conf_admin_password" placeholder="" minlength="0" maxlength="150">
					<small class="text-muted">{{ $t('visio_password_desc') }}</small> 
				</div>
				<div class="col-12">
					<button class="btn btn-primary" type="button" @click="connectConf(false)" :disabled="validCode == false || validUserName == false">{{ $t('btn_connect') }}</button>
					<button class="btn btn-primary ms-3" type="button" v-if="user_type == 'admin'" @click="connectConf(true)" :disabled="validCode == false || validUserName == false">{{ $t('btn_admin') }}</button>
				</div>
			</form>
		</div>
	</div>

	
`;

let urlParams = new URLSearchParams(window.location.search)

$(function() {
	let app = Vue.createApp({
		data() {
			return {
				conf_code: this.getLocalStorageItem('conf_code', ""),
				conf_admin_password: this.getLocalStorageItem('conf_admin_password', ""),
				user_name: this.getLocalStorageItem('user_name', ""),
				user_type: this.getLocalStorageItem('user_type', "user"),
				global_logo_url : global_logo_url
			}
		},

		components: {
			'visio': Vue.defineAsyncComponent( () => import('./visio.js')),
			'admin': Vue.defineAsyncComponent( () => import('./admin.js')),
		},

		template: template,

		watch: {
			conf_code() {
				if (this.conf_code == "") return;
				if (this.conf_code.match("^[a-zA-Z0-9]( |[a-zA-Z0-9_-])*$") === null)
				{
					let m = this.conf_code.match("[a-zA-Z0-9]( |[a-zA-Z0-9_-])*");
					if (m !== null) this.conf_code = m[0]; else this.conf_code = ""
				}
			}
			
		},

		computed: {
			isVisioRoute() { return urlParams.has("code") && urlParams.has("admin") == false; },
			isAdminRoute() { return urlParams.has("admin") && urlParams.has("code"); },

			visioCode() { return urlParams.get("code"); },
			roomCode() { return urlParams.has("room") ? urlParams.get("room"): ''; },

			validCode() {
				return this.conf_code != '';
			},
			validUserName() {
				if (this.user_type == 'user') 
				return this.user_name != '';
			}
		},
		methods: {
			saveForm() 
			{
				this.setLocalStorageItem("conf_code", this.conf_code);
				this.setLocalStorageItem("conf_admin_password", this.conf_admin_password);
				this.setLocalStorageItem("user_name", this.user_name);
				this.setLocalStorageItem("user_type", this.user_type);
			},

			connectConf(admin = false){
				// Set password Null if user type
				if (this.user_type == "user") this.conf_admin_password = '';

				this.saveForm();
				let self = this;

				this.api({
					action: "getOrCreate",
					conf_code: this.conf_code,
					user_type: this.user_type,
					conf_admin_password: this.conf_admin_password,
					user_name: this.user_name,
				}).then(function (response) {
					if (admin)
					{
						if (response.data.isAdmin == false) alert(self.$t("invalid_password"));
						else location.href="?admin&code=" + encodeURIComponent(self.conf_code);
					}
					else location.href="?code=" + encodeURIComponent(self.conf_code);
				})
				
			},

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

			setLocalStorageItem(itemName, item)
			{
				window.localStorage.setItem(itemName, JSON.stringify(item));
			},
		
			getLocalStorageItem(itemName, defaultValue)
			{
				let val = window.localStorage.getItem(itemName);
				if (val === null) return defaultValue;
		
				try { val = JSON.parse(val); } catch (e) {}
		
				if (val === null) return defaultValue;
				return val;
			},

			async api(params)
			{
				return new Promise(function (resolve, reject) {
					axios.post('api.php', params).then(function (response) {
						resolve(response)
					}).catch(function (error) {
						if (error.response.status == 403 || error.response.status == 521) alert(error.response.data)

						reject(error)
					})
				})
			}
		}
	});
	app.mount('#app')
	
})

