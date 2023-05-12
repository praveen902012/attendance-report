<?php
$servername = "localhost";
$username = "root";
$password = "philiphetti";
$dbname = 'ews';

function dump_array_in_file($array, $file_name, $exists)
{

    if ($exists) {
        $fp = fopen($file_name, 'a');
    } else {
        $fp = fopen($file_name, 'w');
    }
    // Loop through file pointer and a line
    fputcsv($fp, $array);
    fclose($fp);
}

function fetch_case_data()
{
 $sql = "select student_id,detection_date from new_case";
}
//dump $data_array in file
dump_array_in_file($data_array, "attendance.csv", true);

CREATE TABLE `new_case` (
  `case_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `date_of_bts` date DEFAULT current_timestamp(),
  `detection_date` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`case_id`),
  KEY `student_id` (`student_id`),
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

