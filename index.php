<?php

require_once 'backend/required.php';

$template_file = 'index.html';

$template_data = array(
);

$html = websun_parse_template_path($template_data, $template_file, LFME_TEMPLATES_PATH );

echo $html;

