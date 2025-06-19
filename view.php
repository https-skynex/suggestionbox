<?php
require_once('../../config.php');
require_once(__DIR__.'/lib.php');

$courseid = required_param('courseid', PARAM_INT);
$action = optional_param('action', '', PARAM_ALPHA);
$suggestionid = optional_param('suggestionid', 0, PARAM_INT);
$message = optional_param('message', '', PARAM_TEXT);

$course = get_course($courseid);
require_login($course);


$context = context_course::instance($courseid);
$url = new moodle_url('/local/suggestionbox/view.php', ['courseid' => $courseid]);

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');

// ========== BLOQUE ÚNICO DE PROCESAMIENTO ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Inicio de procesamiento POST - Action: $action");

    if ($action === 'submit' && has_capability('local/suggestionbox:submit', $context)) {
        $message = required_param('message', PARAM_TEXT);
        if (!empty($message)) {
            error_log("Registrando nueva sugerencia de usuario {$USER->id}");
            local_suggestionbox_submit_suggestion($courseid, $USER->id, $message);
            redirect($url, get_string('suggestion_submitted', 'local_suggestionbox'));
        }
    }elseif ($action === 'like' && has_capability('local/suggestionbox:like', $context)) {
        $suggestionid = required_param('suggestionid', PARAM_INT);
        local_suggestionbox_like_suggestion($suggestionid);
        redirect($url);
    }
}
// ========== FIN DE BLOQUE DE PROCESAMIENTO ==========

// Configurar página según rol
if (has_capability('local/suggestionbox:viewteacher', $context)) {
    $PAGE->set_title(get_string('teacher_title', 'local_suggestionbox'));
    $template = 'teacher_view';
} else {
    $PAGE->set_title(get_string('student_title', 'local_suggestionbox'));
    $template = 'student_view';
}

// Obtener sugerencias con información extendida
$suggestions = local_suggestionbox_get_suggestions($courseid);

// Preparar datos para plantilla
$data = [
    'courseid' => $courseid,
    'actionurl' => $url->out(false),
    'sesskey' => sesskey(),
    'suggestions' => array_values(array_map(function($s) {
        return [
            'id' => $s->id,
            'preview' => shorten_text(format_string($s->message), 50),
            'message' => format_string($s->message),
            'time' => userdate($s->timecreated, get_string('strftimedatetimeshort')),
            'liked' => (bool)$s->liked,
            'author' => fullname($s)
        ];
    }, $suggestions))
];

// Debug final
error_log("Total de sugerencias a mostrar: " . count($data['suggestions']));

// Renderizar la página
echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_suggestionbox/$template", $data);
echo $OUTPUT->footer();