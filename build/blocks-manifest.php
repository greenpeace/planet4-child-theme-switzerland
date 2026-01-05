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
	)
);
