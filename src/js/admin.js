// https://dev.to/krowemoh/a-vue3-tutorial-08-vue-components-without-a-build-system-2-a-better-way-g1g
// VS plug-in: Comment tagged templates
const template = /*html*/`
	<div class="container main-container">
		<div class="border rounded p-5">

			<h1>{{ $t('edit_title') }}</h1>
			
			<form class="row g-3" v-if="conf != null">
				
				<div>
					<div><label for="conf_code" class="form-label">{{ $t('conf_code') }}</label></div>
					<div><b>{{ conf_code }}</b></div>
				</div>

				<div>
					<label for="conf_admin_password" class="form-label">{{ $t('visio_password') }}</label>
					<input type="text" class="form-control" id="conf_admin_password" v-model="new_conf_admin_password" placeholder="" minlength="0" maxlength="150">
				</div>

				<div class="col-12">
					<div><label for="rooms" class="form-label">{{ $t('rooms') }}</label></div>
					<div>
						<textarea class="form-control form-control-sm" v-model="new_rooms" rows="7"></textarea>
					</div>
					<small class="text-muted">{{ $t('rooms_desc') }}</small>
					<div class="my-2">
						<span class="badge bg-secondary me-2" v-for="(room) in newRoomsArray">{{ room }}</span>
					</div>
				</div>

				<div class="text-end">
					<a class="btn btn-secondary me-4" :href="homeUrl" >{{ $t('return') }}</a>
					<button class="btn btn-primary" type="button" @click="save">{{ $t('Enregistrer') }}</button>
				</div>
			</form>
		</div>
	</div>
`;
export default {
	data() {
		return {
			
		}
	},
	template: template,

	components: {
		
	},

	data() {
		return {
			conf_code: this.getLocalStorageItem('conf_code', ""),
			conf_admin_password: this.getLocalStorageItem('conf_admin_password', ""),

			new_conf_admin_password: null,
			new_rooms: null,

			conf: null,
			isAdmin: null,
		}
	},

	props: {
		code: { type: String },
		room: { type: String, default: "" },
	},


	computed: {
		homeUrl() { return window.location.href.replace(window.location.search, '') },

		newRoomsArray() {
			let list = this.new_rooms.split("\n");
			let res = [];
			for(let room of list)
			{
				let m = room.match("[a-zA-Z0-9]( |[a-zA-Z0-9_-])*");
				if (m !== null) res.push( m[0]);
			}
			return res;
		}
	},

	mounted() {
		this.refresh();

	},

	methods: {
		save(){
			let self = this;
			this.api({
				action: 'updateConf',
				conf_code: this.conf_code,
				conf_admin_password: this.conf_admin_password,
				rooms: JSON.stringify(this.newRoomsArray)
			}).then((response) => {
				alert(self.$t('conf_saved'));
				self.refresh();
			})
		},

		refresh()
		{
			let self = this;
			this.api({
				action: 'getOrCreate',
				conf_code: this.conf_code,
				conf_admin_password: this.conf_admin_password,
			}).then((response) => {
				self.conf = response.data.conf;
				self.isAdmin = response.data.isAdmin;
				self.new_conf_admin_password = self.conf.admin_password
				self.new_rooms = self.conf.params.rooms.join("\n")
			})
		}
	},
	
}


