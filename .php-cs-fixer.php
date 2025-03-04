<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/routes')
    ->in(__DIR__ . '/database');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true);
