<?php

/**
 * Serve the files from the dragndrop file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function block_dragndrop_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $DB;

    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false;
    }

    require_login();
    
    

    if ($filearea != 'attachment') {
        return false;
    }

    $itemid = (int)array_shift($args);


    $fs = get_file_storage();
 
    $filename = array_pop($args);
    if (empty($args)) {
        $filepath = '/';
    } else {
        $filepath = '/'.implode('/', $args).'/';
    }

    $file = $fs->get_file($context->id, 'block_dragndrop', $filearea, $itemid, $filepath, $filename);
   
    if (!$file) {
        return false;
    }

    // finally send the file
    send_stored_file($file, 0, 0, false, $options); // download MUST be forced - security!
}