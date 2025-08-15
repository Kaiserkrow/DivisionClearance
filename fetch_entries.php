<?php
include "connection.php";

$search = $_GET['search'] ?? '';
$purpose = $_GET['purpose'] ?? '';

$where = "WHERE 1";
$params = [];
$types = "";

// Search filter
if ($search !== '') {
    $where .= " AND fullName LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// Purpose filter
if ($purpose !== '') {
    $where .= " AND purposeOfClearance = ?";
    $params[] = $purpose;
    $types .= "s";
}

$sql = "SELECT * FROM entries $where ORDER BY id DESC";
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td><input type='checkbox' class='entry-checkbox' value='{$row['id']}'></td>
            <td>" . ucwords($row['fullName']) . "</td>
            <td>" . ucfirst($row['position']) . "</td>
            <td>" . ucwords(strtolower($row['district'])) . "</td>
            <td class=\"" . ($row['district'] == 'Division Office' ? 'violet' : '') . "\">{$row['school']}</td>
            <td>" . ucfirst($row['purposeOfClearance']) . "</td>
            <td class=\"" . ($row['dateOfAction'] === null ? 'not-applicable-indicator' : '') . "\">" .
                (!empty($row['dateOfAction']) && $row['dateOfAction'] !== '0000-00-00'
                    ? date("M d, Y", strtotime($row['dateOfAction']))
                    : 'N/A') . "</td>
            <td class=\"" . ($row['startDate'] === null ? 'not-applicable-indicator' : '') . "\">" .
                (!empty($row['startDate']) && $row['startDate'] !== '0000-00-00'
                    ? date("M d, Y", strtotime($row['startDate']))
                    : 'N/A') . "</td>
            <td class=\"" . ($row['endDate'] === null ? 'not-applicable-indicator' : '') . "\">" .
                (!empty($row['endDate']) && $row['endDate'] !== '0000-00-00'
                    ? date("M d, Y", strtotime($row['endDate']))
                    : 'N/A') . "</td>
            <td class=\"" . ($row['additionalNote'] === 'N/A' ? 'not-applicable-indicator' : '') . "\">" .
                (!empty($row['additionalNote']) ? $row['additionalNote'] : 'N/A') . "</td>
            <td>" . date("M d, Y", strtotime($row['schoolDistrictSigned'])) . "</td>
            <td class=\"division-signed-date\">" . date("M d, Y", strtotime($row['divisionSigned'])) . "</td>
            <td>
                <div class='d-flex justify-content-center align-items-center'>
                    <a class='me-2' href='edit.php?id={$row['id']}'><img class='edit-btn' src='./asset/img/edit.png'></a>
                    <button type='button' class='btn btn-delete' data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#Modal{$row['id']}'><img class='edit-btn' src='./asset/img/delete.png'></button>
                    <div class='modal fade' id='Modal{$row['id']}' tabindex='-1' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title'>Delete {$row['fullName']}?</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                </div>
                                <div class='modal-body text-start'>This can't be undone.</div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <a href='search.php?deleteid={$row['id']}" . ($search ? "&search=" . urlencode($search) : "") . "'>
                                        <button type='button' class='btn btn-danger'>Delete</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr id=\"no-entries-available\"><td colspan='13' class='text-center'>No matching entries found.</td></tr>";
}
?>
