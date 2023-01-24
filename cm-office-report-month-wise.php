<?php
ini_set('memory_limit', '-1');
set_time_limit(0);

$servername = "localhost";
$username = "root";
$password = "philiphetti";
$dbname = 'ews';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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

function calculateNumberOfStudentsInPercentageCategory($object)
{
    $count90_100 = 0;
    $count80_89 = 0;
    $count70_79 = 0;
    $count60_69 = 0;
    $count50_59 = 0;
    $count40_49 = 0;
    $count30_39 = 0;
    $count20_29 = 0;
    $count10_19 = 0;
    $count0_9 = 0;
    foreach ($object as $row) {
        if ($row["total_present"] >= "90") {
            $count90_100++;
        } elseif ($row["total_present"] >= "80" && $row["total_present"] <= "89.99") {
            $count80_89++;
        } elseif ($row["total_present"] >= "70" && $row["total_present"] <= "79.99") {
            $count70_79++;
        } elseif ($row["total_present"] >= "60" && $row["total_present"] <= "69.99") {
            $count60_69++;
        } elseif ($row["total_present"] >= "50" && $row["total_present"] <= "59.99") {
            $count50_59++;
        } elseif ($row["total_present"] >= "40" && $row["total_present"] <= "49.99") {
            $count40_49++;
        } elseif ($row["total_present"] >= "30" && $row["total_present"] <= "39.99") {
            $count30_39++;
        } elseif ($row["total_present"] >= "20" && $row["total_present"] <= "29.99") {
            $count20_29++;
        } elseif ($row["total_present"] >= "10" && $row["total_present"] <= "19.99") {
            $count10_19++;
        } elseif ($row["total_present"] >= "0" && $row["total_present"] <= "9.99") {
            $count0_9++;
        }
    }
    return [
        "90-100" => $count90_100,
        "80-89" => $count80_89,
        "70_79" => $count70_79,
        "60_69" => $count60_69,
        "50_59" => $count50_59,
        "40_49" => $count40_49,
        "30_39" => $count30_39,
        "20_29" => $count20_29,
        "10_19" => $count10_19,
        "0_9" => $count0_9
    ];


}

function getStudentAttendancePercentage($work_days, $month, $school_id, $conn)
{
    $student_count_sql = "select student_id,count(*),count(*)/" . $work_days . "*100 as total_present from master.student as student
                 join ews.attendance as attendance on student.id=attendance.student_id 
                and date like '$month' 
                and attendance_status='p' 
                where student.school_id=" . $school_id . " group by student_id";
    return $conn->query($student_count_sql);


}


$fields = array(
    'School ID',
    'School Name',
    'Zone Name',
    'District Name',
    'Date',
    'Total Student',
    'Present',
    'Absent',
    'Leave',
    'Exam',
    'Number of students for whom attendance was reported',

);
dump_array_in_file($fields, "attendance-report-class.csv", false);
// output data of each row
$begin = new DateTimeImmutable("2022-10-01");
$end = new DateTimeImmutable("2022-12-31");
for($i=$begin;$i<=$end; $i->modify('+1 day'))
{

    $school_list_sql = "select distinct student.school_id,
             school.name as school_name, 
             zone.name as zone_name, 
             district.name as district_name 
             from master.student as student 
             join master.school as school on school.id=student.school_id 
             join master.school_mapping as mapping on school.id=mapping.school_id 
             join master.zone as zone on zone.id=mapping.zone_id 
             join master.district as district on district.id=mapping.district_id";
    $schools = $conn->query($school_list_sql);
    while ($school = $schools->fetch_assoc()) {
        $data_array[] = $school['school_id'];
        $data_array[] = $school['school_name'];
        $data_array[] = $school['zone_name'];
        $data_array[] = $school['district_name'];
        $data_array[] = $i->format("d-m-Y");
        $date=$i->format("Y-m-d");

        $student_count_sql = "select count(distinct id) as total_count from master.student as student where student.school_id=" . $school['school_id'];
        $student_count = $conn->query($student_count_sql);
        $student = $student_count->fetch_assoc();
        $data_array[] = $student['total_count'];
        //calculate
        $attendence_sql = "select school_id,count(CASE WHEN attendance_status='p' THEN 'p' ELSE null end) as present_count,
                 count(CASE WHEN attendance_status='a' THEN 'a' ELSE null end) as absent_count,
                 count(CASE WHEN attendance_status='l' THEN 'l' ELSE null end) as leave_count,
                 count(CASE WHEN attendance_status='e' THEN 'e' ELSE null end) as exam_count 
                 from ews.attendance as attendance join master.student as student on student.id=attendance.student_id where date = '$date' and student.school_id=" . $school['school_id'].";";
        $attendence_report = $conn->query($attendence_sql);
        $attendence = $attendence_report->fetch_assoc();
        $data_array[] = $attendence['present_count'];
        $data_array[] = $attendence['absent_count'];
        $data_array[] = $attendence['leave_count'];
        $data_array[] = $attendence['exam_count'];
        $data_array[] = $attendence['present_count']+$attendence['absent_count']+$attendence['exam_count'];

        // put data in csv file
        dump_array_in_file($data_array, "attendance-report-class.csv", true);
        $data_array = [];

    }

    $i = $i->modify('+1 day');


}






