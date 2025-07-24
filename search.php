<?php
session_start();
include "connection.php";

if (isset($_GET['deleteid'])) {
  $id = intval($_GET['deleteid']);
  $conn->query("DELETE FROM entries WHERE id = $id");
  $searchQuery = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
  header("Location: search.php?page=1$searchQuery");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Division Clearance - Search</title>
  <link rel="stylesheet" href="asset/bootstrap/css/bootstrap.css"/>
  <link rel="stylesheet" href="asset/css/search.css"/>
  <link rel="stylesheet" href="asset/css/sidebar.css"/>
  <link rel="stylesheet" href="asset/css/style.css"/>
  <link rel="stylesheet" href="asset/css/table.css"/>
  <link rel="shortcut icon" href="asset/img/icon.png" type="image/x-icon"/>
</head>
<body>
  <div>
    <img src="asset/img//cam-norte.png" alt="" class="background-img">
  </div>
  <header class="navbar-section">
    <nav class="navbar navbar-expand-lg">
      <div id="main">
        <button class="openbtn" id="open-close-btn" onclick="openNav()">☰</button>
      </div>
    </nav>
  </header>

  <div>
    <div id="mySidebar" class="sidebar">
      <a href="javascript:void(0)" id="close-btn1" class="text-center" onclick="openNav()">
        <span id="close-btn2" class="hidden "></span>
      </a>
      <div class="d-flex flex-column justify-content-around h-100">
        <div class="h-100 mt-5 hyperlinks">
          <ul class="mt-md-5">
            <li class="py-md-2">
              <a href="search.php" class="selected list-items py-md-4 links hover-links ">
                <div class="d-flex">
                  <div><img class="sidebar-img dashboard" src="asset/img/search.png" alt=""/></div>
                  <div><div class="text-white ms-4">Search</div></div>
                </div>
              </a>
            </li>
            <li class="py-md-2">
              <a href="index.php" class="list-items py-md-4 links hover-links ">
                <div class="d-flex">
                  <div><img class="sidebar-img dashboard tint" src="asset/img/form.png" alt=""/></div>
                  <div><div class="text-white ms-4 tint">Clearance Form</div></div>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <h1 class="text-center search-neg-margin mb-5">Search Clearance Forms</h1>

  <!-- ================= Search Form ================= -->
  <div class="container mb-4">
    <form method="GET" action="search.php" class="d-flex justify-content-center">
      <input type="text" name="search" class="form-control w-50 me-2" placeholder="Search by Full Name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <button type="submit" class="btn custom-search-btn">
        <img src="asset/img/search.png" alt="" class="search-icon">
      </button>
    </form>
  </div>

  <!-- ================== Table Start ================== -->
  <div class="table_component" role="region" tabindex="0">
    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Position</th>
          <th>District</th>
          <th>School</th>
          <th>Purpose</th>
          <th>Date of Action</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Remarks</th>
          <th>School/Division <p>- Date Signed</p></th>
          <th>Division Clearance <p>- Date Signed</p></th>
          <th>Edit / Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $resultsPerPage = 7; 
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $resultsPerPage;
        $search = isset($_GET['search']) ? trim($_GET['search']) : "";
        $searchSql = $search ? "WHERE fullName LIKE ?" : "";

        // Total count
        $totalSql = "SELECT COUNT(*) as total FROM entries $searchSql";
        $stmt = $conn->prepare($totalSql);
        if ($search) {
          $searchTerm = "%" . $search . "%";
          $stmt->bind_param("s", $searchTerm);
        }
        $stmt->execute();
        $totalResult = $stmt->get_result();
        $totalEntries = $totalResult->fetch_assoc()['total'];
        $totalPages = ceil($totalEntries / $resultsPerPage);

        // Data fetch
        $dataSql = "SELECT * FROM entries $searchSql ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($dataSql);
        if ($search) {
          $stmt->bind_param("sii", $searchTerm, $resultsPerPage, $offset);
        } else {
          $stmt->bind_param("ii", $resultsPerPage, $offset);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $divisionSignedDate = date("M d, Y", strtotime($row['divisionSigned']));
            $schoolDistrictSignedDate = date("M d, Y", strtotime($row['schoolDistrictSigned']));
            $actionDate = date("M d, Y", strtotime($row['dateOfAction']));
            $startDate = date("M d, Y", strtotime($row['startDate']));
            $endDate = date("M d, Y", strtotime($row['endDate']));
            $purposeOfClearance = ucfirst($row['purposeOfClearance']);
            echo "
            <tr>
              <td data-label='Full Name'>{$row['fullName']}</td>
              <td data-label='Position'>{$row['position']}</td>
              <td data-label='District'>{$row['district']}</td>
              <td data-label='School'>{$row['school']}</td>
              <td data-label='Purpose'>{$purposeOfClearance}</td>
              <td data-label='Date of Action'>" . (!empty($row['dateOfAction']) && $row['dateOfAction'] !== '0000-00-00' ? $actionDate : 'N/A') . "</td>
              <td data-label='Start Date'>" . (!empty($row['startDate']) && $row['startDate'] !== '0000-00-00' ? $startDate : 'N/A') . "</td>
              <td data-label='End Date'>" . (!empty($row['endDate']) && $row['endDate'] !== '0000-00-00' ? $endDate : 'N/A') . "</td>
              <td data-label='Travel Reason'>" . (!empty($row['additionalNote']) ? $row['additionalNote'] : 'N/A') . "</td>

              <td data-label='School District Signed'>{$schoolDistrictSignedDate}</td>
              <td data-label='Date Signed'>{$divisionSignedDate}</td>
              <td>
                <div class=\"d-flex justify-content-center align-items-center\">
                  <a class=\"me-2\" href=\"edit.php?id={$row['id']}\"><img class=\"edit-btn\" src=\"./asset/img/edit.png\"></a>
                  <button type=\"button\" class=\"btn\" data-bs-toggle=\"modal\" data-bs-target=\"#Modal{$row['id']}\"><img class=\"edit-btn\" src=\"./asset/img/delete.png\"></button>
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
          echo "<tr><td colspan='12' class='text-center'>No records found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- ================== Pagination ================== -->
  <?php if ($totalPages > 1): ?>
    <div class="d-flex justify-content-center mt-5">
      <nav>
        <ul class="pagination">
          <?php
          $baseUrl = "search.php?" . ($search ? "search=" . urlencode($search) . "&" : "");
          ?>
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="<?= $baseUrl ?>page=<?= $page - 1 ?>">Previous</a>
            </li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
              <a class="page-link" href="<?= $baseUrl ?>page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="<?= $baseUrl ?>page=<?= $page + 1 ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  <?php endif; ?>

  <script src="asset/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="asset/js/sidebar.js"></script>
</body>
</html>
