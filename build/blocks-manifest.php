<?php
// This file is generated. Do not modify it manually.
return array(
	'food-quiz' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'planet4-child-theme-switzerland/food-quiz',
		'version' => '0.1.0',
		'title' => 'Food Quiz',
		'category' => 'media',
		'icon' => 'carrot',
		'description' => 'CCC Interactive Food Quiz',
		'keywords' => array(
			'food',
			'quiz',
			'interactive'
		),
		'example' => array(
			
		),
		'supports' => array(
			'inserter' => true,
			'html' => false,
			'anchor' => true,
			'color' => array(
				'background' => false,
				'text' => false
			),
			'multiple' => false
		),
		'providesContext' => array(
			'planet4-child-theme-switzerland/food-quiz-tier-labels' => 'tierLabels'
		),
		'textdomain' => 'planet4-child-theme-switzerland',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'attributes' => array(
			'breakfastMeals' => array(
				'type' => 'array',
				'default' => array(
					array(
						'title' => 'Breakfast 1',
						'score' => 10,
						'imageUrl' => ''
					),
					array(
						'title' => 'Breakfast 2',
						'score' => 20,
						'imageUrl' => ''
					),
					array(
						'title' => 'Breakfast 3',
						'score' => 30,
						'imageUrl' => ''
					),
					array(
						'title' => 'Breakfast 4',
						'score' => 40,
						'imageUrl' => ''
					)
				)
			),
			'lunchMeals' => array(
				'type' => 'array',
				'default' => array(
					array(
						'title' => 'Lunch 1',
						'score' => 20,
						'imageUrl' => ''
					),
					array(
						'title' => 'Lunch 2',
						'score' => 40,
						'imageUrl' => ''
					),
					array(
						'title' => 'Lunch 3',
						'score' => 60,
						'imageUrl' => ''
					),
					array(
						'title' => 'Lunch 4',
						'score' => 80,
						'imageUrl' => ''
					)
				)
			),
			'dinnerMeals' => array(
				'type' => 'array',
				'default' => array(
					array(
						'title' => 'Dinner 1',
						'score' => 20,
						'imageUrl' => ''
					),
					array(
						'title' => 'Dinner 2',
						'score' => 40,
						'imageUrl' => ''
					),
					array(
						'title' => 'Dinner 3',
						'score' => 60,
						'imageUrl' => ''
					),
					array(
						'title' => 'Dinner 4',
						'score' => 80,
						'imageUrl' => ''
					)
				)
			),
			'drinks' => array(
				'type' => 'array',
				'default' => array(
					array(
						'title' => 'Drink 1',
						'score' => 3
					),
					array(
						'title' => 'Drink 2',
						'score' => 5
					),
					array(
						'title' => 'Drink 3',
						'score' => 5
					),
					array(
						'title' => 'Drink 4',
						'score' => 8
					)
				)
			),
			'tierLabels' => array(
				'type' => 'array',
				'default' => array(
					'Low',
					'Low-Medium',
					'Medium',
					'Medium-High',
					'High'
				)
			),
			'tierThresholds' => array(
				'type' => 'array',
				'default' => array(
					100,
					200,
					300,
					400,
					null
				)
			)
		)
	),
	'year-end-campaign-2026' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'planet4-child-theme-switzerland/year-end-campaign-2026',
		'version' => '0.1.0',
		'title' => 'Year end campaign 2026',
		'category' => 'media',
		'icon' => 'visibility',
		'description' => 'Interactive deep-sea animal case files for the 2026 year-end campaign.',
		'keywords' => array(
			'campaign',
			'deep sea',
			'donation',
			'Tatort Tiefsee'
		),
		'supports' => array(
			'inserter' => true,
			'html' => false,
			'anchor' => true,
			'multiple' => false,
			'color' => array(
				'background' => false,
				'text' => false
			)
		),
		'textdomain' => 'planet4-child-theme-switzerland',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'attributes' => array(
			'eyebrow' => array(
				'type' => 'string',
				'default' => 'Tatort Tiefsee · Dein Schutzauftrag'
			),
			'headline' => array(
				'type' => 'string',
				'default' => 'Wem gibst du eine Stimme?'
			),
			'intro' => array(
				'type' => 'string',
				'default' => 'Wähle das Tier, das deine Spende schützt.'
			),
			'dossierLabel' => array(
				'type' => 'string',
				'default' => 'Falldossier'
			),
			'emptyLabel' => array(
				'type' => 'string',
				'default' => 'Wähle ein Tier, um zu beginnen'
			),
			'ctaTemplate' => array(
				'type' => 'string',
				'default' => '{{name}} schützen'
			),
			'donationNote' => array(
				'type' => 'string',
				'default' => 'Ab CHF 30 · Einmalig oder regelmässig'
			),
			'tamaroAnchorId' => array(
				'type' => 'string',
				'default' => ''
			),
			'creatures' => array(
				'type' => 'array',
				'default' => array(
					array(
						'id' => 'glaskalmar',
						'imageId' => 0,
						'imageUrl' => '',
						'imageAlt' => '',
						'name' => 'Glaskalmar',
						'family' => 'Cranchia scabra',
						'status' => 'missing',
						'depth' => '1.200 m',
						'coordinates' => '15°N 145°W',
						'caseNumber' => 'TT-004',
						'description' => 'Nahezu vollständig transparent: Über seine Lebensweise ist fast nichts bekannt. Der Tiefseebergbau bedroht seinen Lebensraum, bevor wir ihn überhaupt kennen.'
					),
					array(
						'id' => 'vampirtintenfisch',
						'imageId' => 0,
						'imageUrl' => '',
						'imageAlt' => '',
						'name' => 'Vampirtintenfisch',
						'family' => 'Vampyroteuthis infernalis',
						'status' => 'endangered',
						'depth' => '900 m',
						'coordinates' => '8°S 122°E',
						'caseNumber' => 'TT-007',
						'description' => 'Mit leuchtenden Organen navigiert er durch absolute Dunkelheit. Ein fossiles Wunder, 300 Millionen Jahre alt und vom Aussterben bedroht.'
					),
					array(
						'id' => 'fledermausfisch',
						'imageId' => 0,
						'imageUrl' => '',
						'imageAlt' => '',
						'name' => 'Fledermausfisch',
						'family' => 'Ogcocephalus darwini',
						'status' => 'unknown',
						'depth' => '700 m',
						'coordinates' => '1°S 91°W',
						'caseNumber' => 'TT-012',
						'description' => 'Mit umgebauten Flossen stapft er über den Meeresgrund. Über seine Populationsgrösse wissen wir fast nichts.'
					),
					array(
						'id' => 'meeresengel',
						'imageId' => 0,
						'imageUrl' => '',
						'imageAlt' => '',
						'name' => 'Meeresengel',
						'family' => 'Clione limacina',
						'status' => 'missing',
						'depth' => '500 m',
						'coordinates' => '72°N 2°W',
						'caseNumber' => 'TT-019',
						'description' => 'Dieser Meeresengel treibt durch arktische Tiefen. Sein Habitat verschwindet schneller, als wir forschen können.'
					),
					array(
						'id' => 'schwarzdrachenfisch',
						'imageId' => 0,
						'imageUrl' => '',
						'imageAlt' => '',
						'name' => 'Schwarzdrachenfisch',
						'family' => 'Idiacanthus atlanticus',
						'status' => 'endangered',
						'depth' => '2000 m',
						'coordinates' => '72°N 2°W',
						'caseNumber' => 'TT-028',
						'description' => 'Tiefschwarz, unsichtbar im Dunkeln – mit einem leuchtenden Kinn-Anhängsel als Köder. Ein Meisterjäger, dessen Welt wir bereit sind zu zerstören.'
					)
				)
			)
		)
	),
	'result-tier' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'planet4-child-theme-switzerland/food-quiz-result-tier',
		'version' => '0.1.0',
		'title' => 'Food Quiz Result Tier',
		'parent' => array(
			'planet4-child-theme-switzerland/food-quiz'
		),
		'supports' => array(
			'html' => false
		),
		'usesContext' => array(
			'planet4-child-theme-switzerland/food-quiz-tier-labels'
		),
		'providesContext' => array(
			'planet4-child-theme-switzerland/food-quiz-tier-index' => 'tierIndex'
		),
		'attributes' => array(
			'tierIndex' => array(
				'type' => 'number',
				'default' => 0
			)
		),
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./style.css',
		'style' => 'file:./style-index.css'
	),
	'tier' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'planet4-child-theme-switzerland/food-quiz-tier',
		'version' => '0.1.0',
		'title' => 'Food Quiz Tier',
		'parent' => array(
			'planet4-child-theme-switzerland/food-quiz'
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'tierIndex' => array(
				'type' => 'number',
				'default' => 0
			)
		),
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./style.css',
		'style' => 'file:./style-index.css'
	),
	'result-tier-title' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'planet4-child-theme-switzerland/food-quiz-result-tier-title',
		'version' => '0.1.0',
		'title' => 'Food Quiz Result Tier Title',
		'parent' => array(
			'planet4-child-theme-switzerland/food-quiz-result-tier'
		),
		'supports' => array(
			'html' => false,
			'reusable' => false
		),
		'usesContext' => array(
			'planet4-child-theme-switzerland/food-quiz-tier-index',
			'planet4-child-theme-switzerland/food-quiz-tier-labels'
		),
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./style.css',
		'style' => 'file:./style-index.css',
		'icon' => 'button'
	)
);
