<?php

require_once(__DIR__ . '../../../config.php');
require_once('lib/locallib.php');
require_login();

/*if ($USER->id = 81) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}*/

$title = "Página de Teste";
$pagetitle = "Página de Teste";
$url = new moodle_url("/local/bluesolmy/index.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standart');
$PAGE->requires->css('/local/bluesolmy/style/style.css');
echo $OUTPUT->header();


echo "<h2>Testando</h2>";
$data = get_all_information($USER->id);
echo "<div class='row topo'>";
foreach ($data as $id => $course) {
	//echo "ID Curso: " . $course['id'] . " Nome do curso: " . $course['fullname'] . " Concluído em: " . date('Y', $course['timecompleted']) . " Progresso: " . $course['progress'] . "% <br />";
	echo make_data($course['id'], $course['fullname'], $course['timecompleted'], $course['progress']);
}
echo "</div>";
//print_r($USER);
/*if (!empty($_SESSION['USER']->realuser)) {
    // Logged in as.
    $realuserid = $_SESSION['USER']->id;
    echo $realuserid;
}*/

echo $OUTPUT->footer();