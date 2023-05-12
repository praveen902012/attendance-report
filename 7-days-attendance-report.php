<?php
$servername = "localhost";
$username = "root";
$password = "philiphetti";
$dbname = 'ews_staging';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
ini_set('memory_limit', '-1');
set_time_limit(0);
$begin = new DateTimeImmutable("2022-10-01");
$end = new DateTimeImmutable("2023-03-31");
$data_array[] = 'case_id';
$data_array[] = 'student_id';
for ($i = $begin; $i <= $end; $i = $i->modify('+7 Day')) {
    $start_date = $i->format("Y-m-d");
    $end_date = $i->modify('+7 Day');
    $end_date = $end_date->format("Y-m-d");
    $column_name = $start_date." to ".$end_date;
    $data_array[] = $column_name.' Total_present_count';
    $data_array[] = $column_name. ' Total_absent_count';
    $data_array[] = $column_name. ' Total_leave_count';
}
dump_array_in_file($data_array, "7-day-attendance-report.csv", false);
dump_attendance_data();
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

function dump_attendance_data()
{
    global $conn;
    $from_date = new DateTimeImmutable("2022-10-01");
    $to_date = new DateTimeImmutable("2023-03-31");
    $case_sql = "select id as case_id, student_id from detected_case where day between '2022-11-01' and '2023-01-31';";
    $res = $conn->query($case_sql);
    $data_array = [];
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $data_array[] = $row['case_id'];
            $data_array[] = $student_id = $row['student_id'];
            for ($i = $from_date; $i <= $to_date; $i = $i->modify('+7 Day')) {
                $start_date = $i->format("Y-m-d");
                $end_date = $i->modify('+7 Day');
                $end_date = $end_date->format("Y-m-d");
                $att_sql = "select student_id, count(CASE WHEN attendance_status='p' THEN 'p' ELSE null end) as present_count,  count(CASE WHEN attendance_status='a' THEN 'a' ELSE null end) as absent_count,  count(CASE WHEN attendance_status='l' THEN 'l' ELSE null end) as leave_count  from attendance where student_id=$student_id and date between \"$start_date\" and \"$end_date\";";
                $student_attendance = $conn->query($att_sql);
                $attendance_count = $student_attendance->fetch_assoc();
                $data_array[] = $attendance_count['present_count'];
                $data_array[] = $attendance_count['absent_count'];
                $data_array[] = $attendance_count['leave_count'];

            }
            dump_array_in_file($data_array, "7-day-attendance-report.csv", true);
            $data_array = [];

        }


    }

}





