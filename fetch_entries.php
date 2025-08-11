<?php
include "connection.php";

$search = $_GET['search'] ?? '';
$purpose = $_GET['purpose'] ?? '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 7;
$offset = ($page - 1) * $limit;

$where = "WHERE 1";
$params = [];
$types = "";

if ($search !== '') {
  $where .= " AND fullName LIKE ?";
  $params[] = "%$search%";
  $types .= "s";
}

if ($purpose !== '') {
  $where .= " AND purposeOfClearance = ?";
  $params[] = $purpose;
  $types .= "s";
}

// Count total entries
$countSql = "SELECT COUNT(*) as total FROM entries $where";
$countStmt = $conn->prepare($countSql);
if ($params) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$totalResult = $countStmt->get_result();
$totalEntries = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalEntries / $limit);

// Fetch entries
$sql = "SELECT * FROM entries $where ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$fullParams = $params;
$fullTypes = $types . "ii";
$fullParams[] = $limit;
$fullParams[] = $offset;
$stmt->bind_param($fullTypes, ...$fullParams); 

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
      <td><input type='checkbox' class='entry-checkbox' value='{$row['id']}'></td>
      <td>" . ucwords($row['fullName']) . "</td>
      <td>" . ucfirst($row['position']) . "</td>
      <td>" . ucwords(strtolower($row['district'])) . "</td>
      <td>{$row['school']}</td>
      <td>" . ucfirst($row['purposeOfClearance']) . "</td>
      <td class=\"".($row['dateOfAction'] === null ? 'not-applicable-indicator': '') ."\">" . (!empty($row['dateOfAction']) && $row['dateOfAction'] !== '0000-00-00' ? date("M d, Y", strtotime($row['dateOfAction'])) : 'N/A') . "</td>
      <td class=\"".($row['startDate'] === null ? 'not-applicable-indicator': '') ."\">" . (!empty($row['startDate']) && $row['startDate'] !== '0000-00-00' ? date("M d, Y", strtotime($row['startDate'])) : 'N/A') . "</td>
      <td class=\"".($row['endDate'] === null ? 'not-applicable-indicator': '') ."\" >" . (!empty($row['endDate']) && $row['endDate'] !== '0000-00-00' ? date("M d, Y", strtotime($row['endDate'])) : 'N/A') . "</td>
      <td class=\"".($row['additionalNote'] === "N/A" ? 'not-applicable-indicator': '') ."\">" . (!empty($row['additionalNote']) ? $row['additionalNote'] : 'N/A') . "</td>
      <td>" . date("M d, Y", strtotime($row['schoolDistrictSigned'])) . "</td>
      <td>" . date("M d, Y", strtotime($row['divisionSigned'])) . "</td>
      <td>
        <div class=\"d-flex justify-content-center align-items-center\">
            <a class=\"me-2\" href=\"edit.php?id={$row['id']}\"><img class=\"edit-btn\" src=\"./asset/img/edit.png\"></a>
            <button type=\"button\" class=\"btn btn-delete\" data-id=\"{$row['id']}\" data-bs-toggle=\"modal\" data-bs-target=\"#Modal{$row['id']}\"><img class=\"edit-btn\" src=\"./asset/img/delete.png\"></button>
            <div class=\"modal fade\" id=\"Modal{$row['id']}\" tabindex=\"-1\" aria-hidden=\"true\">
                <div class=\"modal-dialog modal-dialog-centered\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                          <h5 class=\"modal-title\">Delete {$row['fullName']}?</h5>
                          <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\"></button>
                        </div>
                        <div class=\"modal-body text-start\">This can't be undone.</div>
                        <div class=\"modal-footer\">
                          <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Close</button>
                          <a href=\"search.php?deleteid={$row['id']}&page=$page" . ($search ? "&search=" . urlencode($search) : "") . "\"><button type=\"button\" class=\"btn btn-danger\">Delete</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </td>
    </tr>";
}

} else {
  echo "<tr><td colspan='12' class='text-center'>No matching entries found.</td></tr>";
}

// PAGINATION OUTPUT
echo "<!-- PAGINATION -->";
for ($i = 1; $i <= $totalPages; $i++) {
  $active = $i == $page ? 'active' : '';
  echo "<li class='page-item $active'><a href='#' class='page-link' data-page='$i'>$i</a></li>";
}
?>
