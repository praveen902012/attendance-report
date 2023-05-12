<?php
$servername = "localhost";
$username = "root";
$password = "philiphetti";
$dbname = 'ews';
$sql = 'select `detected_case`.`id` as case_id,`class`,`s1`.`school_id`,`t3`.`name` as dname,`z`.`name` as zname,`section`,`dob`,`mobile`,`father`,`mother`,`day`,`student_id`,thirty_days_criteria,seven_days_criteria from detected_case join master.student as s1 on s1.id=student_id join master.school_mapping as t2 on t2.school_id=s1.school_id join master.district as t3 on t3.id=t2.district_id join master.zone as z on z.id=t2.zone_id where student_id in  (



20010109354  
    );';
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

ini_set('memory_limit', '-1');
set_time_limit(0);
$begin = new DateTimeImmutable("2022-10-01");
$end = new DateTimeImmutable("2022-10-31");


$fields = array('Case ID',
    'Class',
    'School ID',
    'District Name',
    'Zone Name',
    'Section',
    'DoB',
    'Mobile',
    'Father Name',
    'Mother Name',
    'Day Of Detection',
    'Student_ID',
    'seven_days_criteria',
    'thirty_days_criteria',
    'Present(1-15 August)',
    'Absent(1-15 August)',
    'Leave(1-15 August)',
    'Present(16-31 August)',
    'Absent(16-31 August)',
    'Leave(16-31 August)',
    'Present(1-15 Sept)',
    'Absent(1-15 Sept)',
    'Leave(1-15 Sept)',
    'Present(16-30 Sept)',
    'Absent(16-30 Sept)',
    'Leave(16-30 Sept)',
     'Present(1-15 Oct)',
    'Absent(1-15 Oct)',
    'Leave(1-15 Oct)',
    'Present(16-31 oct)',
    'Absent(16-31  oct)',
    'Leave(16-31 oct)',
    'Present(1-15 Nov)',
    'Absent(1-15 Nov)',
    'Leave(1-15 Nov)',
    'Present(16-30 Nov)',
    'Absent(16-30  Nov)',
    'Leave(16-30 Nov)',
    'Present(1-15 Dec)',
    'Absent(1-15 Dec)',
    'Leave(1-15 Dec)',
    'Present(16-31 Dec)',
    'Absent(16-31  Dec)',
    'Leave(16-31 Dec)'
);
dump_array_in_file($fields, "attendance.csv", false);


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $attendance_status = ['p', 'a', 'l'];
    $data_array = [];
    $string = "";
    while ($row = $result->fetch_assoc()) {
        //$string = $row['case_id'] . "," . $row['student_id'] . "," . $row['school_id'] . "," . $row['dname'] . "," . $row['zname'] . "," . $row['class'] . "," . $row['section'] . "," . $row['dob'] . "," . $row['mobile'] . "," . $row['father'] . "," . $row['mother'] . "," . $row['day'] . "," . $row['seven_days_criteria'] . "," . $row['thirty_days_criteria'] . ",";
        foreach ($row as $data) {
            $data_array[] = $data;
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-08-01', '%Y-%m-%d') and STR_TO_DATE('2022-08-15', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-08-16', '%Y-%m-%d') and STR_TO_DATE('2022-08-31', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-09-01', '%Y-%m-%d') and STR_TO_DATE('2022-09-15', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-09-16', '%Y-%m-%d') and STR_TO_DATE('2022-09-30', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();
            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-10-01', '%Y-%m-%d') and STR_TO_DATE('2022-10-15', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-10-16', '%Y-%m-%d') and STR_TO_DATE('2022-10-31', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-11-01', '%Y-%m-%d') and STR_TO_DATE('2022-11-15', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();
            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-11-16', '%Y-%m-%d') and STR_TO_DATE('2022-11-30', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();
            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {

            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-12-01', '%Y-%m-%d') and STR_TO_DATE('2022-12-15', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();
            $data_array [] = $row2['count(*)'];
        }
        foreach ($attendance_status as $att) {
            //$sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='".$att."';";
            $sql2 = "select attendance_status,count(*) from attendance where `student_id`=" . $row['student_id'] . " and (`date` between STR_TO_DATE('2022-12-16', '%Y-%m-%d') and STR_TO_DATE('2022-12-31', '%Y-%m-%d')) and attendance_status='" . $att . "';";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            $data_array [] = $row2['count(*)'];
        }
        dump_array_in_file($data_array, "attendance.csv", true);
        $data_array = [];
    }
} else {
    echo "0 results";
}



