<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_suggestionbox
 * @copyright   2025 Kevin Perez <perezkevinsan2002@outlook.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function local_suggestionbox_submit_suggestion($courseid, $userid, $message) {
    global $DB;

    $record = new stdClass();
    $record->courseid = $courseid;
    $record->userid = $userid;
    $record->message = $message;
    $record->timecreated = time();
    $record->liked = 0;

    return $DB->insert_record('local_suggestionbox', $record);
}

function local_suggestionbox_like_suggestion($suggestionid) {
    global $DB;

    // Verificación extrema con logging
    error_log("Intentando toggle like para sugerencia: $suggestionid");

    if (!$DB->record_exists('local_suggestionbox', ['id' => $suggestionid])) {
        error_log("ERROR: La sugerencia $suggestionid no existe");
        return false;
    }

    $current = $DB->get_field('local_suggestionbox', 'liked', ['id' => $suggestionid]);
    error_log("Estado actual del like: $current");

    $newStatus = $current ? 0 : 1;
    $success = $DB->set_field('local_suggestionbox', 'liked', $newStatus, ['id' => $suggestionid]);

    error_log("Resultado de la actualización: " . ($success ? "Éxito" : "Falló"));
    return $success;
}


function local_suggestionbox_get_suggestions($courseid) {
    global $DB, $USER;

    $suggestions = $DB->get_records_sql("
        SELECT s.*, u.firstname, u.lastname
        FROM {local_suggestionbox} s
        JOIN {user} u ON s.userid = u.id
        WHERE s.courseid = ?
        ORDER BY s.timecreated DESC
    ", [$courseid]);

    return array_map(function($s) use ($USER) {
        return (object)[
            'id' => $s->id,
            'courseid' => $s->courseid,
            'userid' => $s->userid,
            'firstname' => $s->firstname,
            'lastname' => $s->lastname,
            'message' => $s->message,
            'timecreated' => $s->timecreated,
            'liked' => $s->liked
        ];
    }, $suggestions);
}

function local_suggestionbox_extend_navigation_course(navigation_node $navigation, stdClass $course, context_course $context) {
    if (has_capability('local/suggestionbox:view', $context)) {
        $url = new moodle_url('/local/suggestionbox/view.php', ['courseid' => $course->id]);
        $node = $navigation->add(
            get_string('view', 'local_suggestionbox'),
            $url,
            navigation_node::TYPE_CUSTOM,
            null,
            'suggestionbox'
        );
    }
}