// https://dev.to/krowemoh/a-vue3-tutorial-08-vue-components-without-a-build-system-2-a-better-way-g1g
// VS plug-in: Comment tagged templates
const template = /*html*/`
	<div ref="visio_ref"></div>

    <div ref='div_jitsi' :style="{
		height: height,
		top: top + 'px',
		position: 'fixed',
		width: '100vw'
	}"></div>
`;
export default {
	data() {
		return {
			// global_jitsi_prefix
			height: "0px",
			top: 0
		}
	},
	template: template,

	props: {
		code: { type: String },
		room: { type: String, default: "" },
	},

	computed: {

	},

	methods: {
		
	},
	mounted() {
		this.top = $(this.$refs.visio_ref).position().top;

		let customHeight = this.top + $('#body_after').height();
		
		this.height = "calc(100vh - " + customHeight + "px)";

		const options = {
			roomName: global_jitsi_prefix + this.code + (this.room == '' ? '' : "-" + this.room),
			width: "100%",
			height: "100%",
			parentNode: this.$refs.div_jitsi,
			userInfo: {
				email: '',
				displayName: this.getLocalStorageItem('user_name', "")
			}
		};
		
		const api = new JitsiMeetExternalAPI(global_jitsi_domain, options);
		
	}
}


