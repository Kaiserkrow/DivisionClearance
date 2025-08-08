<?php
session_start();
include "connection.php";

if (isset($_POST['submit'])) {
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
    $sql = "INSERT INTO entries 
      (fullName, position, district, school, purposeOfClearance, additionalNote, startDate, endDate, divisionSigned, schoolDistrictSigned) 
      VALUES 
      ('$fullName','$position', '$district', '$school', '$purpose', '$additionalNote', '$startDate', '$endDate', '$divisionSigned','$dateSigned')";
  } else {
    $sql = "INSERT INTO entries 
      (fullName, position, district, school, purposeOfClearance, additionalNote, dateOfAction, divisionSigned, schoolDistrictSigned) 
      VALUES 
      ('$fullName','$position', '$district', '$school', '$purpose', '$additionalNote', '$dateOfAction','$divisionSigned','$dateSigned')";
  }

  if ($conn->query($sql) === TRUE) {
    header("Refresh:0");
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Division Clearance</title>
  <link rel="stylesheet" href="asset/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="asset/css/sidebar.css">
  <link rel="stylesheet" href="asset/css/receipt.css">
  <link rel="stylesheet" href="asset/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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
              <a href="search.php" class="list-items py-md-4 links hover-links ">
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
            <li class="py-md-2">
              <a href="report.php" class="selected list-items py-md-4 links hover-links ">
                <div class="d-flex">
                  <div><img class="sidebar-img dashboard tint" src="asset/img/report.png" alt=""/></div>
                  <div><div class="text-white ms-4 tint">Report</div></div>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="form-wrapper mb-4">
    <h2 class="title text-center mb-4">Division Clearance Form</h2>
    <form action="index.php" method="POST" id="whole-form">
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
              <!-- from dropDownFilter.js -->
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
          <button type="button" class="btn btn-primary px-4 full-width" data-bs-toggle="modal" data-bs-target="#saveModal" onclick="generateReceipt();">Submit</button>
        </div>
      </div>

      <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="save" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="save">Save Data?</h1>
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

  <script src="asset/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="asset/js/sidebar.js"></script>
  <script src="asset/js/dropDownFilter.js"></script>
  <script src="asset/js/purposeChoices.js"></script>
  <script defer src="asset/js/receipt.js"></script>
</body>
</html>
