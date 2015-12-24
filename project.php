<?php
require_once 'backend/required.php';

$project_alias
    = isset($_GET['project'])
    ? $_GET['project']
    : die('No such project!');

// read data

$filename = 'storage/' . $project_alias . '.json';

$file_data = file_get_contents( $filename );

if ($file_data === FALSE)
    redirect( LFME_ROOT_PATH );

$data = json_decode($file_data , true );

$maps = $data['maps'];

foreach($data['maps'] as $map) {
    $maps_list[] = array(
        'map_alias'     =>  $map['info']['alias'],
        'map_title'     =>  $map['info']['title']
    );
}

// form arrays

$template_data = array(
    // project
    'project_alias'         =>  $project_alias,
    'project_title'         =>  "Карты: " . $data['project']['title'],
    'project_description'   =>  $data['project']['description'],
    //
    'maps_list'             =>  $maps_list,
    //
    'back_url'              =>  LFME_ROOT_PATH . '/',
    // copyrights
    'lfme_version'          =>  LFME_VERSION,
    // template base path
    'lfme_root'             =>  LFME_ROOT_PATH
);

// build template

$template_file = 'maps_list.html';
$html = websun_parse_template_path($template_data, $template_file, LFME_TEMPLATES_PATH );

// print

echo $html;