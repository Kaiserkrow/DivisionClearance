<?php
session_start();
include "connection.php";
if (isset($_GET['deleteid'])) {
    $id = intval($_GET['deleteid']);
    $stmt = $conn->prepare("DELETE FROM entries WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redirect to same page without deleteid to avoid accidental re-delete
    $redirectUrl = "search.php?page=" . ($_GET['page'] ?? 1);
    if (isset($_GET['search'])) {
        $redirectUrl .= "&search=" . urlencode($_GET['search']);
    }

    header("Location: $redirectUrl");
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
    <img src="asset/img/cam-norte.png" alt="" class="background-img">
  </div>

  <header class="navbar-section">
    <nav class="navbar navbar-expand-lg">
      <div id="main">
        <button class="openbtn" id="open-close-btn" onclick="openNav()">☰</button>
      </div>
    </nav>
  </header>

  <!-- Sidebar -->
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

<div id="whole-header" class="search-neg-margin">
  <h1 class="text-center">Search Clearance Forms</h1>

  <div class="filter-container">
    <!-- Search -->
     <div class="d-flex search justify-content-start">
      <div class="w-100">
        <input type="text" id="searchInput" class="w-100"placeholder=" Search by Full Name..." />
      </div>

    <!-- Purpose Filter -->
      <div class=" ms-3 filter-item d-flex justify-content-center align-items-center">
        <select id="purposeFilter">
          <option value="">All Purposes</option>
          <option value="travel">Travel</option>
          <option value="retirement">Retirement</option>
          <option value="resigned">Resignation</option>
          <option value="sick Leave">Sick Leave</option>
          <option value="transferred Out">Transferred Out</option>
        </select>
      </div>

     </div>
    
    <!-- Button -->
    <div class="d-flex justify-content-center align-items-center" style="text-align: center;">
      <div>
        <button id="generate-pdf">Generate PDF</button>
      </div>
      
    </div>
  </div>
</div>

  <!-- Table -->
  <div class="table_component" role="region" tabindex="0">
    <table>
      <thead>
        <tr>
          <th>
            <span class="mb-2">
              All
            </span>
            <div class="mt-2">
              <input type='checkbox' id='check-all' onclick="checkUncheckAll()">
            </div>
            
          </th>
          <th>Full Name</th>
          <th>Position</th>
          <th>District</th>
          <th>School / <span class="violet">Office</span></th>
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
      <tbody id="entriesTableBody">
        <!-- AJAX content -->
      </tbody>
    </table>
  </div>

  
  <div class="d-flex justify-content-end align-items-center">
    <div class="margin-top me-3 filter-by">
      <p>Filter by:</p>      
    </div>
    <div class="d-flex justify-content-center align-items-center year-month-container">
      <div class="me-4">
        <label for="monthFilter" class="me-2">Month:</label>
        <select id="monthFilter" class="text-center">
          <option value="">All</option>
          <option value="Jan">January</option>
          <option value="Feb">February</option>
          <option value="Mar">March</option>
          <option value="Apr">April</option>
          <option value="May">May</option>
          <option value="Jun">June</option>
          <option value="Jul">July</option>
          <option value="Aug">August</option>
          <option value="Sep">September</option>
          <option value="Oct">October</option>
          <option value="Nov">November</option>
          <option value="Dec">December</option>
        </select>
      </div>
      

    
      <div>
        <label for="yearFilter" class="me-2">Year:</label>
        <select id="yearFilter" class="text-center">
          <option value="">All</option>
          <option value="2023">2023</option>
          <option value="2024">2024</option>
          <option value="2025">2025</option>
          <option value="2026">2026</option>
          <option value="2027">2027</option>
          <option value="2028">2028</option>
          <option value="2029">2029</option>
          <option value="2030">2030</option>
          <option value="2031">2031</option>
          <option value="2032">2032</option>
          <option value="2033">2033</option>
          <option value="2034">2034</option>
          <option value="2035">2035</option>
        </select>
      </div>
    </div>
    <!-- for future developers/mainteners: just add more years kapag kulang HAHAHAHA -->
    <div class="margin-top">
      <p id="totalEntries">Total Entries: <span id="totalNumber" class="deped-sdo chonky-text"></span></p>      
    </div>
  </div>
  

  <script src="asset/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="asset/js/sidebar.js"></script> 
  <script src="asset/js/generateClearance.js"></script>
  <script src="asset/js/deleteHandler.js"></script>
  <script src="asset/js/giveColorIndicator.js"></script>
  <script src="asset/js/pagination.js"></script>
  <script src="asset/js/liveSearch.js"></script>
  <script src="asset/js/checkUncheckAll.js"></script>
  <script src="asset/js/yearMonthFilter.js"></script>
</body>
</html>
