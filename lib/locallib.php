<?php
/**
 * Return the course progress percentage
 *
 * @param $courseid The id of the course.
 * @return int
 */
function get_course_progress($courseid) {
	global $USER;
	$course = new stdClass();
	$course->id = $courseid;
	$courseprogress = round(\core_completion\progress::get_course_progress_percentage($course, $USER->id));
	return $courseprogress;
}

/**
 * Return the time completed course
 *
 * @param $courseid The id of the course.
 * @return int timestamp
 */
function get_user_time_completed_course($courseid) {
	global $DB, $USER;
	//$sql = "SELECT timecompleted FROM {course_completions} WHERE userid=? AND course=?";
	$timecompleted = $DB->get_record('course_completions', $params=['userid' => $USER->id, 'course' => $courseid], $fields='timecompleted');
	return $timecompleted->timecompleted;
}

/**
 * Gets some information about de user enrolled courses
 *
 * @param $userid The id of the user.
 * @return array
 */
function get_all_information($userid) {
	$record = enrol_get_all_users_courses($userid);
	foreach ($record as $id => $course) {
		$values[$id] = array(
			'id' => $course->id,
			'fullname' => $course->fullname,
			'timecompleted' => get_user_time_completed_course($course->id),
			'progress' => get_course_progress($course->id)
		);
	}
	return $values;
}

/**
 * Gets the first image on course summary files of the given course
 *
 * @param $course The id of the course.
 * @return string HTML fragment
 */
function course_image($courseid) {
    global $CFG, $COURSE, $DB;
    $course = $DB->get_record('course', ['id' => $courseid]);
    require_once($CFG->libdir. '/coursecatlib.php');
    $course = new \course_in_list($course);
    $courseimage = '';
    $imageindex = 1;
    foreach ($course->get_course_overviewfiles() as $file) {
        $isimage = $file->is_valid_image();
        $url = new moodle_url("$CFG->wwwroot/pluginfile.php" . '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
            $file->get_filearea(). $file->get_filepath(). $file->get_filename(), ['forcedownload' => !$isimage]);
        if ($isimage) {
            $courseimage = $url;
        }
        if ($imageindex == 2) {
            break;
        }
        $imageindex++;
    }

    $html = html_writer::img($courseimage, $alt = null, array('class' => 'img-curso img-responsive'));
    return $html;
}

/**
 * 
 *
 * @param int $id The id of the course.
 * @param string $name the fullname of the course.
 * @param int $timecompleted The course time completed
 * @param int $courseprogress 
 * @return string HTML fragment
 */
function make_data($id, $name, $timecompleted, $courseprogress) {
    global $CFG;
    $course = new stdClass();
    $course->id = $id;
    $completion = new \completion_info($course);
    if (!$completion->is_enabled()) {
        return null;
    }
    $strtime = null;
    //$courseprogress = round(\core_completion\progress::get_course_progress_percentage($course, 4292));
    $courseimage = course_image($id);
    if (!empty($timecompleted)) {
    	$strtime = "<div class='timecompleted'>Data de conclusão: " . date('d/m/Y H:i', $timecompleted) . "</div>";
        $strstatus = "
        <div>Concluído (100%)</div>
        <div class='progress'>
            <div class='progress-bar progress-bar-striped progress-bar-animated bg-dfo' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%;'>
            </div>
        </div>";
    } else {
        $strstatus = "
        <div>Em andamento (".$courseprogress."%)</div>
        <div class='progress'>
            <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow=".$courseprogress." aria-valuemin='0' aria-valuemax='100' style='width: ".$courseprogress."%;'>
            </div>
        </div>";
    }

    $html = "
    <div class='col-lg-3'>
        <div class='base'>
            <div class='base-conteudo'>
                <div class='conteudo-progresso'>
                    ".$courseimage."
                    <div class='fullcoursename'>
                        <strong><a href='".$CFG->wwwroot."/course/view.php?id=".$id."'>" . $name . "</a></strong>
                    </div>
                    <div class='area-progresso'>
                        <div class='status'>" . $strstatus . "</div>"
                        . $strtime . "
                    </div>
                </div>
            </div>
        </div>
    </div>
    ";
    return $html;
}
