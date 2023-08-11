<?php
	require_once('lib/app.php');

		$home = isset($_GET['code']) == false;
		
		$debug = DEBUG;
		$debug_string = DEBUG ? 'true': 'false';

		$page_title = Lang::_('page_title');
		$body_before = $home ? Lang::_('body_before_home') : Lang::_('body_before');
		$body_after = $home ? Lang::_('body_after_home') :Lang::_('body_after');
		$custom_style = CUSTOM_CSS;
		$jitsi_prefix = JITSI_PREFIX;
		$jitsi_domain = JITSI_DOMAIN;
		
		$logo_url = LOGO_URL;
		
		$locale = LOCALE;
		$lang_messages = json_encode(Lang::MESSAGES());
		
		$vueUrl = DEBUG ? 'https://unpkg.com/vue@3.2.47/dist/vue.global.js' : 'https://unpkg.com/vue@3.2.47/dist/vue.global.prod.js';
		$vueI18nUrl = DEBUG ? 'https://unpkg.com/vue-i18n@9.1.0/dist/vue-i18n.global.js' : 'https://unpkg.com/vue-i18n@9.1.0/dist/vue-i18n.global.prod.js';

		$faviconCode = FAVICON_URL == '' ? '' : '<link href="' . FAVICON_URL . '" rel="icon">';

		$versionSfx = '?v=1';

		echo <<<HTML
<!doctype html>
<html lang="fr-fr" dir="ltr">
	<head>
		<title>$page_title</title>
		$faviconCode

		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link href="css/app.css{$versionSfx}" rel="stylesheet" />
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js" integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

		<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

		<script src='https://meet.jit.si/external_api.js'></script>
		
		<script src="$vueUrl"></script>
		<script src="$vueI18nUrl"></script>
		
		<style>{$custom_style}</style>
		
		<script>
			var global_debug={$debug_string};
			var global_locale="{$locale}";
			var global_lang_messages={$lang_messages};
			var global_jitsi_prefix="{$jitsi_prefix}";
			var global_jitsi_domain="{$jitsi_domain}";
			var global_logo_url="{$logo_url}";


		</script>

		<script src="js/app.js{$versionSfx}"></script>
	</head>
	<body>
		<div id="body_before">
			$body_before
		</div>
		<div id="app"></div>

		<div id="body_after">
			$body_after
		</div>
	</body>
</html>
HTML;

