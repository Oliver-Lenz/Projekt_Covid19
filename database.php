<?php
$country = $_POST["country"];
$select = $_POST["select"];

    
//declare variables for db connection
$servername = "usersql.zedat.fu-berlin.de";
$username = "jstadie-sql";
$password = "qms584ct";
$dbname = "jstadie-db1";

//db connection
$conn = new mysqli($servername, $username, $password, $dbname);

//error handling
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "";

switch ($select) {
    case "countrys":
        //sql statement to run
        $sql = "SELECT name FROM LAND ORDER BY name";
        break;
        
    case "cases":
        //sql statement to run
        $sql = "SELECT R.date, R.cases FROM REPORT R, HAT H, LAND L WHERE R.repID = H.repID AND H.geoID= L.geoID AND L.name = '$country'";
        break;
        
    case "deaths":
        //sql statement to run
        $sql = "SELECT R.date, R.deaths FROM REPORT R, HAT H, LAND L WHERE R.repID = H.repID AND H.geoID= L.geoID AND L.name = '$country'";
        break;
    
    case "sums":
        //sql statement to run
        $sql = "SELECT SUM(cases), SUM(deaths) FROM REPORT R,HAT H, LAND L WHERE R.repID = H.repID AND L.geoID = H.geoID AND name='$country'";
        break;
        
    case "data":
        $sql = "SELECT MIN(date), MAX(date), popData, MAX(R.cases), MAX(R.deaths), SUM(R.cases), SUM(R.deaths) FROM REPORT R, HAT H, LAND L WHERE  R.repID = H.repID AND L.geoID = H.geoID AND name='$country'";
        break;
        
    case "choropleth":
        $sql = "SELECT R.date, R.cases, L.name FROM REPORT R, HAT H, LAND L WHERE R.repID = H.repID AND L.geoID = H.geoID ORDER BY date ASC";
        break;
        
    default:
        echo "switch error";
        
}

//run sql query and store into variable
$data = array();
$result = mysqli_query($conn, $sql);

foreach ($result as $row) {
    $data[] = $row;
}

//free memory and close db connection
$conn->close();
$result->close();

// IMPORTANT, output to json
echo json_encode($data);

?>