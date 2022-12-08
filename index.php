<html>
<body>
<table>
    <tr>
        <td>Student_ID</td>
        <td>leave</td>
        <!--<td>Absent</td>
        <td>Leave</td>-->
    </tr>
    </thead>
    <tbody>
<?php
$servername = "localhost";
$username = "root";
$password = "philiphetti";
$dbname='ews_staging';

$sql='select `day`,`student_id` from detected_case where student_id in  (20210180113,
20210484266,
20150358039,
20210218886,
20160213780,
20170403005,
20190249554,
20210183027,
20180305840,
20190276180,
20190511237,
20190150078,
20160278703,
20190558012,
20180074086,
20200331974,
20160278703,
20180216972,
20170273001,
20180250610,
20170341382,
20190154045,
20150339799,
20180012514,
20210143338,
20210481169,
20180086685,
20190111167,
20200092956,
20160194209,
20170225518,
20160307111,
20210074689,
20210150952,
20220260418,
20160241023,
20210237925,
20210388437,
20180045925,
20180198419,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425,
20190196425);';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $string="";
        $sql2="select attendance_status,count(*) from attendance where `student_id`=".$row['student_id']." and `date` >= STR_TO_DATE('" . $row['day'] . "', '%Y-%m-%d') and attendance_status='l';";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        echo "<tr><td>".$row['student_id']."</td><td>".$row['day']."</td><td>".$row2['count(*)']."</td></tr>";
    }
} else {
    echo "0 results";
}

?>


    </tbody>
</table>
</body>
</html>


