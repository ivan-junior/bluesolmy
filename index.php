<?php
require_once(__DIR__ . '../../../config.php');
require_once('lib/locallib.php');
require_login();

$title = "My Moodle Progress";
$pagetitle = "My Moodle Progress";
$url = new moodle_url("/local/mymoodleprogress/index.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standart');
$PAGE->requires->css('/local/mymoodleprogress/style/style.css');
$PAGE->navbar->add($title, $url);
echo $OUTPUT->header();

$data = get_all_information($USER->id);
echo "<div class='row mt-4'>";
foreach ($data as $id => $course) {
	echo make_data($course['id'], $course['fullname'], $course['timecompleted'], $course['progress']);
}
echo "</div>";
echo $OUTPUT->footer();
