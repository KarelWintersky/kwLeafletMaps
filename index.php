<?php
require_once 'backend/required.php';

// read data

$searchmask = 'storage/*.json';

$projects_list = array();

foreach (glob($searchmask) as $filename) {
    $data = json_decode( file_get_contents( $filename ), true );

    $project = array(
        'project_alias'     =>  $data['project']['alias'],
        'project_title'     =>  $data['project']['title']
    );

    $projects_list[] = $project;
}

// form arrays

$template_data = array(
    'projects_list'       =>  $projects_list,
    'index_title'         =>  LFME_INDEX_TITLE,
    'lfme_version'        =>  LFME_VERSION
);

// build template

$template_file = 'index.html';
$html = websun_parse_template_path($template_data, $template_file, LFME_TEMPLATES_PATH );

// print

echo $html;