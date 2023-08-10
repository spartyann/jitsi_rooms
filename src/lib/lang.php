<?php


class Lang {

	public static function MESSAGES() {

		$messages = [
			'fr' => [
				'title' => 'Visioconférence',
				'btn_create_login' => 'Se connecter ou créer la conférence',
				'visio_code' => 'Code de la visioconférence',
				'visio_password' => 'Mot de passe admin',
				'user_name' => 'Entrez votre nom',
				'visio_password_desc' => 'Remplissez ce champs si vous êtes administrateur. Sinon laissez-le vide.',
			]
		];

		foreach(LANG_MESSAGES_OVERRIDE as $lang => $ovmsgs)
		{
			foreach ($ovmsgs as $key => $msg) $messages[$lang][$key] = $msg;
		}

		return $messages;
	}
	
}

