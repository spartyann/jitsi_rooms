<?php


class Lang {

	public static function MESSAGES() {

		$messages = [
			'fr' => [
				'title' => 'Visio',
			]
		];

		foreach(LANG_MESSAGES_OVERRIDE as $lang => $ovmsgs)
		{
			foreach ($ovmsgs as $key => $msg) $messages[$lang][$key] = $msg;
		}

		return $messages;
	}
	
}

