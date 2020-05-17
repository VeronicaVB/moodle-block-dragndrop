<?php

    class block_dragndrop_edit_form extends block_edit_form {

        protected function specific_definition($mform) {
            // Section header title according to language file.
            $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

            // A sample string variable with a default value.
            $mform->addElement('text', 'config_text', get_string('blockstring', 'block_dragndrop'));
            $mform->setDefault('config_text', get_string('defaulttext', 'block_dragndrop'));
            $mform->setType('config_text', PARAM_RAW);

            // A sample string variable with a default value.
            $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_dragndrop'));
            $mform->setDefault('config_title', 'Drag and Drop');
            $mform->setType('config_title', PARAM_TEXT);

            $type = 'filemanager';
            $name = 'config_filename';
            $label = get_string('attachment', 'block_dragndrop');
            $options = array('subdirs' => 0, 'maxbytes' => 5000000, 'maxfiles' => 10, 'accepted_types' => '*');
            $mform->addElement($type, $name, $label, null, $options);

            //print_object($mform); exit;
        }

        /**
         * Return submitted data.
         *
         * @return object submitted data.
         */
        public function get_data() {
            //  global $DB;
            $data = parent::get_data();
            //print_object($data); exit;

            if ($data) {
                // Save images.
                if (!empty($data->config_filename)) {
                    $draftitemid = file_get_submitted_draft_itemid('config_filename');
                    file_save_draft_area_files($draftitemid, $this->block->context->id, 'block_dragndrop',
                        'attachment',$draftitemid);
                }
            }

            return $data;
        }

        /**
         * Set form data.
         *
         * @param array $defaults
         * @return void
         */
        public function set_data($defaults) {
            //print_object($defaults); exit;

            if (empty($entry->id)) {
                $entry = new stdClass;
                $entry->id = null;
            }

            $draftitemid = file_get_submitted_draft_itemid('config_filename');

            file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_dragndrop', 'attachment', $entry->id,
                array('subdirs' => 0, 'maxbytes' => 5000000, 'maxfiles' => 50));


            // Set form data.
            parent::set_data($defaults);
        }

    }
