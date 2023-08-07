<?php

namespace block_evasys_sync\task;

use core\task\adhoc_task;

class evasys_bulk_task extends adhoc_task{

    public function execute()
    {
        $data = $this->get_custom_data();
        $courses = $data->courses;
        $evasyscategory = $data->evasyscategory;
        if(empty($evasyscategory) || empty($courses)){
            mtrace("No category or courses specified, exiting.");
        }
        $errors = \block_evasys_sync\evaluation_manager::set_default_evaluation_for($courses, $evasyscategory);
        if ($errors) {
            $erroroutput = '';
            foreach ($errors as $courseid => $error) {
                $erroroutput .= $courseid . ': ' . $error . '<br>';
            }
            mtrace($erroroutput);
        }
    }
}