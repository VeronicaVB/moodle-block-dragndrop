<?php

// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

    /**
     * A block to  mark off students and check their overall mood.
     *
     * @package    block_dragndrop
     * @copyright 2020 Veronica Bermegui
     * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    defined('MOODLE_INTERNAL') || die();

    class block_dragndrop extends block_base {

        public function init() {
            $this->title = get_string('dragndrop', 'block_dragndrop');
        }

        public function get_content() {
            global $DB, $OUTPUT;

            if ($this->content !== null) {
                return $this->content;
            }

            require_login();

            $this->content = new stdClass;

            $fs = get_file_storage();

            $instanceid = $DB->get_record('block_instances', ['parentcontextid' => 2, 'blockname' => get_string('blockname', 'block_dragndrop')]);
            $contextid = $DB->get_record('context', ['instanceid' => $instanceid->id, 'contextlevel' => 80]);
            $files = $fs->get_area_files($contextid->id, 'block_dragndrop', 'attachment', false, '', false);
            $index =0;

            foreach ($files as $file) {
                $filename = $file->get_filename();
                if ($filename != '.') {
                    $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $filename);

                $filelist['files'][] = ['url' => $url,
                    'index' => $index,
                    'filename' => $filename,
                    'mimetype' => $file->get_mimetype(),                   
                   ];
                }

                $index++;
            }
            $filelist['files'][]=['total' => count($filelist['files'])];
            $filelist['instanceid'] = $this->instance->id;
          #var_dump($filelist); exit;
            $this->content->text = $OUTPUT->render_from_template('block_dragndrop/dnd', $filelist);



            return $this->content;
        }

        public function hide_header() {
            return false;
        }

        public function specialization() {
            if (isset($this->config)) {
                if (empty($this->config->title)) {
                    $this->title = get_string('defaulttitle', 'block_dragndrop');
                } else {
                    $this->title = $this->config->title;
                }

                if (empty($this->config->text)) {
                    $this->config->text = get_string('defaulttext', 'block_dragndrop');
                }
            }
        }

        public function instance_allow_multiple() {
            return false;
        }

        public function get_required_javascript() {
            parent::get_required_javascript();

            $this->page->requires->js_call_amd('block_dragndrop/control', 'init', [
                'instanceid' => $this->instance->id,

            ]);
        }

    }
