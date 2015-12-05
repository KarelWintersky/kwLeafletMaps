<?php

require_once 'backend/required.php';

$project_alias
    = isset($_GET['project'])
    ? $_GET['project']
    : die('No such project!');

$map_alias
    = isset($_GET['map'])
    ? $_GET['map']
    : die('No such map!');

$filename = 'storage/' . $project_alias . '.json';

$file_data = file_get_contents( $filename );

if ($file_data === FALSE)
    redirect( LFME_ROOT_PATH );

$data = json_decode($file_data , true );

// load mapinfo from .json file with given map_alias

$map_found = FALSE;
foreach($data['maps'] as $anymap) {
    if ($anymap['info']['alias'] == $map_alias) {
        $map_found = TRUE;
        $map = $anymap;

        if (!preg_match('/^(http|https):\/\//' , $map['image']['url'])) {
            $map['image']['link'] = LFME_ROOT_PATH . '/storage/' .
                $project_alias . '/' . $map['image']['url'];
        } else {
            $map['image']['link'] = $map['image']['url'];
        }
    }
}

// form arrays

$template_data = array(
    'project_alias'     =>  $project_alias,
    'map_alias'         =>  $map_alias,
    'map_title'         =>  $map['info']['title'],
    // map image
    'map_width'         =>  $map['image']['width'],
    'map_height'        =>  $map['image']['height'],
    'map_link'          =>  $map['image']['link'],
    // map zoom settings
    'zoom_min'          =>  $map['zoom']['zoom_min'],
    'zoom_max'          =>  $map['zoom']['zoom_max'],
    'zoom_cur'          =>  $map['zoom']['zoom_cur'],
    // centring settings
    'center_x'          =>  $map['zoom']['center_x'],
    'center_y'          =>  $map['zoom']['center_y'],
    // back url
    'back_url'          =>  LFME_ROOT_PATH . '/' . $project_alias
);

// build template

$template_file = 'map_view.html';
$html = websun_parse_template_path($template_data, $template_file, LFME_TEMPLATES_PATH );

// print

echo $html;