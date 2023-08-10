<?php


class Lang {

	public static function MESSAGES() {

		$messages = [
			'fr' => [
				'title' => 'Visioconférence',
				'edit_title' => 'Edition de la visio',
				'btn_connect' => 'Se connecter',
				'btn_admin' => 'Administrer la conférence',
				'conf_code' => 'Code de la visioconférence',
				'conf_code_desc' => 'Utilisez seulement des lettres (sans accent), chiffres et les caractères "_" ou "-"',
				'visio_password' => 'Mot de passe admin',
				'user_name' => 'Entrez votre nom',
				'visio_password_desc' => 'Etre administrateur vous permet de gérer les salles de la conférence.',

				'i_am_user' => 'Je suis participant',
				'i_am_admin' => 'Je suis administrateur',

				'visio_return_btn' => "Accueil",
				'return' => "Retour",

				'invalid_password' => "Mot de passe invalide",

				'save' => 'Enregistrer',

				'rooms' => 'Salles de conférence',
				'rooms_desc' => 'Indiquez les noms des salles de conférence (une par ligne). Utilisez seulement des lettres (sans accent), chiffres et les caractères "_" ou "-"',
				'conf_saved' => "La conférence a bien été enregistrée",

				'main_room' => "Salle principale",
			]
		];

		foreach(LANG_MESSAGES_OVERRIDE as $lang => $ovmsgs)
		{
			foreach ($ovmsgs as $key => $msg) $messages[$lang][$key] = $msg;
		}

		return $messages;
	}

	public static function _($code){
		return self::MESSAGES()[LOCALE][$code];
	}
	
}


