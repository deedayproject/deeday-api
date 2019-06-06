<?php

return PhpCsFixer\Config::create()
	->setRules([
		'@PSR2' => true,
		'@Symfony' => true,
		'indentation_type' => true,
		'new_with_braces' => false,
	]);