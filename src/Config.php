<?php namespace Mamok\TestParser;


/**
 * Return list of configure props of app
 */
class Config {
	public static function getConfig() {
		return [
			'requestFieldsData' => [
				"limit" => 10000000,
				"ClassifiersFieldData.SiteSectionType" => "bef4c544-ba45-49b9-8e91-85d9483ff2f6",	// ЖД, авиа, авто, контейнерные перевозки
			],

			'dbConfig' =>  [
				'host'           => '127.0.0.1',
				'port'           => 3306,
				'database'       => 'test_parse',
				'username'       => 'root',
				'password'       => '',
				'charset'        => 'utf8mb4',
			],

			'consoleHeaders' => ['Номер лота', 'Организатор', 'Ссылка на страницу', 'Дата начала подачи заявок', 'Документация'],
			
	
		];
	}
}
