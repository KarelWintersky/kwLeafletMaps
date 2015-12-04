<?php
require_once 'backend/required.php';

// read data

$searchmask = 'storage/*.json';

$projects_list = array();

foreach (glob($searchmask) as $filename) {
    $project_data = json_decode( file_get_contents( $filename ), true );

    echo '<pre>';
    var_dump($project_data);

    /* $project_alias = $project_data['info']['alias'];

    $projects_list[ $project_alias ] = array(
        'project_alias'     =>  $project_data['info']['alias'],
        'project_title'     =>  $project_data['info']['title']
    );
    */

}

// form arrays

$template_data = array(
    'project_alias'         =>  $project_alias,
    'maps_list'             =>  $maps_list,
    'project_title'         =>  "Список карт по проекту {$project_alias}"
);

// build template

$template_file = 'maps_list.html';
// $html = websun_parse_template_path($template_data, $template_file, LFME_TEMPLATES_PATH );

// print

// echo $html;