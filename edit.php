<?php
session_start();
include "connection.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Invalid ID.");
}

$id = intval($_GET['id']);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $fullName = $_POST['fullName'];
  $position = $_POST['position'];
  $district = $_POST['district'];
  $school = $_POST['school'];
  $purpose = $_POST['purpose'];
  $dateSigned = $_POST['dateSigned'];
  $divisionSigned = $_POST['divisionSigned'];

  $dateOfAction = isset($_POST['dateOfAction']) ? $_POST['dateOfAction'] : null;
  $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
  $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : null;
  $additionalNote = isset($_POST['additionalNote']) && $_POST['additionalNote'] !== "" ? $_POST['additionalNote'] : "N/A";

  if ($purpose == "travel" || $purpose == "sick Leave") {
    $stmt = $conn->prepare("UPDATE entries SET fullName=?, position=?, district=?, school=?, purposeOfClearance=?, additionalNote=?, startDate=?, endDate=?, divisionSigned=?, schoolDistrictSigned=? WHERE id=?");
    $stmt->bind_param("ssssssssssi", $fullName, $position, $district, $school, $purpose, $additionalNote, $startDate, $endDate, $divisionSigned, $dateSigned, $id);
  } else {
    $stmt = $conn->prepare("UPDATE entries SET fullName=?, position=?, district=?, school=?, purposeOfClearance=?, additionalNote=?, dateOfAction=?, divisionSigned=?, schoolDistrictSigned=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $fullName, $position, $district, $school, $purpose, $additionalNote, $dateOfAction, $divisionSigned, $dateSigned, $id);
  }

  if ($stmt->execute()) {
    header("Location: search.php?page=1&updated=true");
    exit();
  } else {
    echo "Update error: " . $conn->error;
  }
}

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM entries WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$entry = $result->fetch_assoc();

if (!$entry) {
  die("Record not found.");
}

$stmt = $conn->prepare("SELECT * FROM entries WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$entry = $result->fetch_assoc();

if (!$entry) {
  die("Record not found.");
}

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $stmt = $conn->prepare("SELECT * FROM entries WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $entry = $result->fetch_assoc();
    $fullName = $entry['fullName']; // ✅ Get the name
  } else {
    echo "Entry not found.";
    exit;
  }
} else {
  echo "No ID provided.";
  exit;
}
?>



<!DOCTYPE html>
<html>
<head>
  <title>Division Clearance - Edit Entry</title>
  <link rel="stylesheet" href="asset/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="asset/css/receipt.css">
  <link rel="stylesheet" href="asset/css/edit.css"/>
  <link rel="shortcut icon" href="asset/img/icon.png" type="image/x-icon"/>

</head>

<body class="p-4">
  <div>
    <img src="asset/img//cam-norte.png" alt="" class="background-img">
  </div>
  <header class="navbar-section">
    <nav class="navbar navbar-expand-lg">
      <div>
        <a href="search.php"><img src="asset/img/back-arrow.png" alt="" class="back-btn"></a>
      </div>
    </nav>
  </header>
  <div class="form-wrapper">
    <h2 class="title text-center mb-4">Edit Data for <?= htmlspecialchars($fullName) ?></h2>
    <form action="edit.php?id=<?= $id ?>" method="POST" id="whole-form">
      <div class="row g-3">
        <div class="col-md-6">
          <label for="fullName" class="form-label">Full Name</label>
          <input type="text" class="form-control" name="fullName" id="fullName" required>
        </div>
        <div class="col-md-6">
          <label for="position" class="form-label">Position</label>
          <input type="text" class="form-control" name="position" id="position" required>
        </div>

        <div class="col-12">
          <fieldset>
            <legend>Elementary or High School?</legend>
            <div class="form-check form-check-inline ms-3">
              <input onchange="districtFilter()" class="form-check-input" type="radio" name="elem-or-hs" value="elem">
              <label class="form-check-label">Elementary</label>
            </div>
            <div class="form-check form-check-inline">
              <input onchange="districtFilter()" class="form-check-input" type="radio" name="elem-or-hs" value="hs">
              <label class="form-check-label">High School</label>
            </div>
          </fieldset>
        </div>

        <div class="col-md-6">
          <label for="district" class="form-label">District</label>
          <select id="district" name="district" class="form-select" onchange="schoolFilter()" required></select>
        </div>
        <div class="col-md-6">
          <label for="school" class="form-label">School</label>
          <select id="school" name="school" class="form-select" required></select>
        </div>

        <div class="col-12">
          <fieldset>
            <legend>Purpose of Clearance</legend>
            <div class="row">
              <div class="col-md-6">
                <div class="form-check">
                  <input onchange="purposeChoice()" class="form-check-input" type="radio" value="travel" name="purpose">
                  <label class="form-check-label">Travel</label>
                </div>
                <div class="form-check">
                  <input onchange="purposeChoice()" class="form-check-input" type="radio" value="retirement" name="purpose">
                  <label class="form-check-label">Retirement</label>
                </div>
                <div class="form-check">
                  <input onchange="purposeChoice()" class="form-check-input" type="radio" value="resigned" name="purpose">
                  <label class="form-check-label">Resigned/Separated</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check">
                  <input onchange="purposeChoice()" class="form-check-input" type="radio" value="sick Leave" name="purpose">
                  <label class="form-check-label">Sick Leave</label>
                </div>
                <div class="form-check">
                  <input onchange="purposeChoice()" class="form-check-input" type="radio" value="transferred Out" name="purpose">
                  <label class="form-check-label">Transferred Out</label>
                </div>
              </div>
            </div>
          </fieldset>
        </div>

        <div id="purposeChoice" class="col-12"></div>

        <div class="col-md-6">
          <label for="dateSigned" class="form-label">School/District - Date Signed</label>
          <input type="date" class="form-control" id="dateSigned" name="dateSigned" required>
        </div>
        <div class="col-md-6">
          <label for="divisionSigned" class="form-label">Division Clearance - Date Signed</label>
          <input type="date" class="form-control" id="divisionSigned" name="divisionSigned" required>
        </div>

        <div class="col-12 text-center mt-4">
          <button type="button" class="btn btn-primary px-4 full-width" data-bs-toggle="modal" data-bs-target="#saveModal" onclick="generateReceipt();">Edit</button>
        </div>
      </div>

      <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="save" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="save">Edit Entry?</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body"></div>
            <div class="modal-footer">
              <input type="submit" name="submit" class="btn btn-primary" value="Confirm">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <script id="entry-data" type="application/json"><?= json_encode($entry) ?></script>

<script src="asset/bootstrap/js/bootstrap.bundle.js"></script>
<script src="asset/js/dropDownFilter.js" defer></script>
<script src="asset/js/purposeChoices.js" defer></script>
<script src="asset/js/receipt.js" defer></script>
<script src="asset/js/edit.js" defer></script>

</body>
</html>