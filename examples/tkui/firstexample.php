<?php
/**
 * https://tkdocs.com/tutorial/firstexample.html
 * https://github.com/skoro/php-tkui
 */
require __DIR__ . '/../../vendor/autoload.php';

use Tkui\DotEnv;
use Tkui\TclTk\TkAppFactory;
use Tkui\Widgets\Entry;
use Tkui\Widgets\Label;
use Tkui\Widgets\Frame;
use Tkui\Widgets\Buttons\Button;
use Tkui\Windows\MainWindow;
use Tkui\TclTk\Variable;

function calculate(...$args) {
    global $feet, $meters;
    
    try {
        $value = $feet->asFloat();
        $value = (int)(0.3048 * $value * 10000.0 + 0.5) / 10000.0;
        $meters->set($value);
    } catch (\Throwable $e) {
        // echo $e->getMessage(), PHP_EOL;
    }
}

$env = DotEnv::create(dirname(__DIR__, 2));
$factory = new TkAppFactory('tkui');
$app = $factory->createFromEnvironment($env);
$root = new MainWindow($app, 'Feet to Meters');

$mainframe = new Frame($root, ['padding' => '3 3 12 12']);
$root->grid($mainframe, ['column' => 0, 'row' => 0, 'sticky' => 'nwes'])
    ->columnConfigure($mainframe, 0, ['weight' => 1])
    ->rowConfigure($mainframe, 0, ['weight' => 1]);

/** @var Variable */
$feet = $app->registerVar('feet');
$feet_entry = new Entry($mainframe, '', ['textVariable' => $feet, 'width' => 7]);
$mainframe->grid($feet_entry, ['column' => 2, 'row' => 1, 'sticky' => 'we']);

$gridOptions = [
    'padx' => 5,
    'pady' => 5,
];
/** @var Variable */
$meters = $app->registerVar('meters');
$mainframe->grid(new Label($mainframe, '', ['textVariable' => $meters]), [
    ...$gridOptions,
    'column' => 2,
    'row' => 2,
    'sticky' => 'we',
]);

$mainframe->grid(new Button($mainframe, 'Calculate', [
    'command' => 'calculate'
]), [
    ...$gridOptions,
    'column' => 3,
    'row' => 3,
    'sticky' => 'w'
]);

$mainframe->grid(new Label($mainframe, 'feet'), [
    ...$gridOptions,
    'column' => 3,
    'row' => 1,
    'sticky' => 'w'
]);

$mainframe->grid(new Label($mainframe, 'is equivalent to'), [
    ...$gridOptions,
    'column' => 1,
    'row' => 2,
    'sticky' => 'e'
]);

$mainframe->grid(new Label($mainframe, 'meters'), [
    ...$gridOptions,
    'column' => 3,
    'row' => 2,
    'sticky' => 'w'
]);

$feet_entry->focus();
$root->bind('Return', 'calculate');

$app->run();
