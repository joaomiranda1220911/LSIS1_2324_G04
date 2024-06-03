<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("ImportSQL.php");

?>

<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "colecoes";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['export'])) {
    // Get the selected table from the form
    $selected_table = $_POST['table'];

    // Fetch data from the selected table
    $query = "SELECT * FROM $selected_table";
    $result = $conn->query($query);

    // Create CSV file content
    $csv_content = "";

    // Output data
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $csv_content .= implode(",", $row) . "\n";
        }
    }

    // Set headers for CSV download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $selected_table . '.csv"');

    // Output CSV content
    echo $csv_content;
}

// Close connection
$conn->close();
?>