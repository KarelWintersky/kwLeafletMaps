<?php
require_once 'backend/required.php';

$project_alias
    = isset($_GET['project'])
    ? $_GET['project']
    : die('No such project!');

// read data

$filename = 'storage/' . $project_alias . '.json';

$data = json_decode( file_get_contents( $filename ), true );

echo '<pre>';
var_dump($data);

// form arrays

$template_data = array(
    'project_alias'         =>  $project_alias,
    'maps_list'             =>  $data,
    'project_title'         =>  "Список карт по проекту {$project_alias}"
);

// build template

$template_file = 'maps_list.html';
// $html = websun_parse_template_path($template_data, $template_file, LFME_TEMPLATES_PATH );

// print

//echo $html;