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
    'Class Name',
    'Total Number of Students enrolled',
    'Month',
    'Working days',
    'Average Number  of students present',
    'Average Number  of students absent',
    'Average Number  of students on leave',
    'Average Number of students who are marked as "e"',
    'Total number of students for whom attendance was reported on all working days',
    'Total number of students for whom attendance was reported',
);
dump_array_in_file($fields, "attendance-report-class.csv", false);
// output data of each row
$months = [
    "0" => '2022-10-%',
    "1" => '2022-11-%',
    "2" => '2022-12-%'
];


for ($i = 0; $i < 3; $i++) {
    $class_list_sql = "select class, count(*) as total_student from master.student group by class";
    $classes = $conn->query($class_list_sql);
    while ($school = $classes->fetch_assoc()) {
        $data_array[] = $class=$school['class'];
        $data_array[] = $school['total_student'];
        $data_array[] = ($months[$i] == '2022-10-%') ? "October" : (($months[$i] == '2022-11-%') ? "November" : "December");
        //number of days attendance marked class wise
        $day_sql="select count(distinct date) as days  from ews.attendance as a join master.student as s on a.student_id=s.id where class='$class' and date like '2022-10-%';";
        $work_days = $conn->query($day_sql);
        $work_days = $work_days->fetch_assoc();
        $data_array[] = $work_days['days'];
        $attendance_type=['p','a','l','e'];
        $attendance_reported=0;
        $total_student=0;
        foreach ($attendance_type as $status)
        {
            $count=$student_numbers=$full_att_count=$e=0;
            $student_attendance_sql="select student_id,count(*) as attendance_marked from ews.attendance as a join master.student as s on a.student_id=s.id where class='$class' and date like '2022-10-%' and attendance_status='$status' group by student_id;";
            $student_attendance = $conn->query($student_attendance_sql);


            foreach ($student_attendance as $row)
            {
                $student_numbers++;
                $total_student++;
                $count=$count+$row['attendance_marked'];
            }


            if($student_numbers!==0){
                $data_array[] = ceil($count/$student_numbers);
            }
            else{
                $data_array[] =0;
            }
            foreach ($student_attendance as $row)
            {
                if($row['attendance_marked']==$work_days['days'])
                $full_att_count++;
            }
            $data_array[] =$full_att_count;
        }

        //
        // put data in csv file
        dump_array_in_file($data_array, "attendance-report-class.csv", true);
        $data_array = [];

    }
}







