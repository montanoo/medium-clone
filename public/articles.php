<?php

// ! variables
$myTitle = 'Article title';
$someNumber = 123;

echo '<h1>' . $myTitle . '</h1>';
echo '<br>';
print 'Another one';

// ! array
$myArray = [1, 2, 3, 4, 5, 6];
echo '<br>';
// ? var_dump => prints my array (something like a debug.) no differente between array and dictionary
var_dump($myArray);

// ! dictionaries
$myDict = [
    'foo' => 'bar',
    2 => 'idk',
];
echo '<br>';
var_dump($myDict);

// ! explode and implode
$names = "john,mary,susan";
echo '<br>';
$separatedNames = explode(',', $names);

var_dump(implode(',', $separatedNames));
