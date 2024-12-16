<?php
// filter-reports.php
include "../includes/connect.php"; // Include your database connection file

$type = isset($_GET['type']) ? $_GET['type'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the query
$query = "SELECT * FROM `my_report` INNER JOIN `user` ON my_report.user_id = user.user_id WHERE `my_report`.admin_id IS NULL";

// Add filtering conditions
$conditions = [];
if ($type) {
    $conditions[] = "emergency_type = '$type'";
}
if ($status) {
    $conditions[] = "`my_report`.status = '$status'";
}
if ($search) {
    $conditions[] = "emergency_id LIKE '%$search%'";
}

// Append conditions to the query
if (!empty($conditions)) {
    $query .= " AND " . implode(' AND ', $conditions);
}

// Execute the query
$select_reports = mysqli_query($connect, $query);

while ($row = mysqli_fetch_array($select_reports)) {
    // Output the filtered report rows
    echo '<tr>';
    echo '<td>' . $row["emergency_id"] . '</td>';
    echo '<td>' . $row["emergency_type"] . '</td>';
    echo '<td>' . $row["8"] . '</td>';
    echo '<td>' . $row["date_created"] . '</td>';
    echo '<td class="d-flex">
        <button class="btn btn-info btn-sm view-details-btn mr-3" data-toggle="modal" data-target="#report-detail-modal' . $row["emergency_id"] . '">View Details</button>
        <form action="./manage-reports.php" method="post">
            <input type="hidden" name="emergency_id" value="' . $row["emergency_id"] . '">
            <button type="submit" name="delete_report" class="btn btn-danger btn-sm delete-alert-btn">Delete</button>
        </form>
    </td>';
    echo '</tr>';
}
