<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharma_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product data based on the provided ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    // Check if the product was found
    if (!$product) {
        die("Product not found.");
    }
} else {
    die("Product ID is required.");
}

// Check if the form was submitted to update the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $brand_name = $_POST['brand_name'];
    $lot_no = $_POST['lot_no'];
    $mfg_date = $_POST['mfg_date'];
    $expiry_date = $_POST['expiry_date'];
    $packing = $_POST['packing'];
    $storage_temp = $_POST['storage_temp'];
    $rh = $_POST['rh'];
    $description = $_POST['description'];
    $identification = $_POST['identification'];
    $weight = $_POST['weight'];
    $disintegration_time = $_POST['disintegration_time'];
    $moisture_content = $_POST['moisture_content'];
    $dosage_unit = $_POST['dosage_unit'];
    $bacterial_count = $_POST['bacterial_count'];
    $molds_yeast_count = $_POST['molds_yeast_count'];
    $salmonella = $_POST['salmonella'];
    $escherichia_coli = $_POST['escherichia_coli'];
    $staphylococcus_aureus = $_POST['staphylococcus_aureus'];
    $sodium_ascorbate = $_POST['sodium_ascorbate'];
    $zinc_sulfate = $_POST['zinc_sulfate'];
    $initial_test = $_POST['initial_test'];  // Removed the extra underscore

    // Update product
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, brand_name = ?, lot_no = ?, mfg_date = ?, expiry_date = ?, packing = ?, storage_temp = ?, rh = ?, description = ?, identification = ?, weight = ?, disintegration_time = ?, moisture_content = ?, dosage_unit = ?, bacterial_count = ?, molds_yeast_count = ?, salmonella = ?, escherichia_coli = ?, staphylococcus_aureus = ?, sodium_ascorbate = ?, zinc_sulfate = ?, initial_test = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssssssssssssi", $product_name, $brand_name, $lot_no, $mfg_date, $expiry_date, $packing, $storage_temp, $rh, $description, $identification, $weight, $disintegration_time, $moisture_content, $dosage_unit, $bacterial_count, $molds_yeast_count, $salmonella, $escherichia_coli, $staphylococcus_aureus, $sodium_ascorbate, $zinc_sulfate, $initial_test, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect to stability.php after update
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating record: " . $stmt->error . "</div>";
    }

    // Close statement
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Product</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="form.css">
  <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

  <style>
  .container {
    max-width: 100%;
  }
</style>
</head>
<body>
<div class="container mt-5">

            <h2 class="text-center">Stability Data Input Form</h2>

        <form id="stabilityForm" method="POST" action="">
        <!-- First Table for General Product Information -->
        <table id="dataTable" class="table table-bordered">
            <tr>
                <th colspan="2">PRODUCT<br>NAME</th>
                <td colspan="2"><input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" readonly></td>
                <th colspan="2">LOT NO.</th>
                <td colspan="2"><input type="number" name="lot_no" class="form-control"  value="<?php echo htmlspecialchars($product['lot_no']); ?>" readonly></td>
            </tr>
            <tr>
                <th colspan="2">PACKING</th>
                <td colspan="2"><input type="text" name="packing" class="form-control" value="<?php echo htmlspecialchars($product['packing']); ?>" readonly></td>
                <th colspan="2">BRANDNAME</th>
                <td colspan="2"><input type="text" name="brand_name" class="form-control" placeholder="enter brand name" value="<?php echo htmlspecialchars($product['brand_name']); ?>"readonly></td>
            </tr>
            <tr>
                <th colspan="2">STORAGE TEMPERATURE</th>
                <td colspan="2"><input type="text" name="storage_temp" class="form-control" value="<?php echo htmlspecialchars($product['storage_temp']); ?>" readonly></td>
                <th colspan="2">MFG. DATE</th>
                <td colspan="2"><input type="date" name="mfg_date" class="form-control" value="<?php echo htmlspecialchars($product['mfg_date']); ?>" readonly></td>
            </tr>
            <tr>
                <th colspan="2">EXPIRY DATE</th>
                <td colspan="2"><input type="date" name="expiry_date" class="form-control" value="<?php echo htmlspecialchars($product['expiry_date']); ?>" readonly></td>
                <th colspan="2">RH</th>
                <td colspan="2"><input type="text" name="rh" class="form-control"  value="<?php echo htmlspecialchars($product['rh']); ?>" readonly></td>
            </tr>
            </table>
            <table id="dataTable2" class="table table-bordered">
            <tr class="header">
              <th>PARAMETER</th>
              <th>SPECIFICATIONS</th>
              <th>Start Date</th>
              <th>1st Cycle Date</th>
              <th>2nd Cycle Date</th>
              <th>3rd Cycle Date</th>
              <th>4th Cycle Date</th>
              <th>5th Cycle Date</th>
              <th>6th Cycle Date</th>
              <th>7th Cycle Date</th>
            </tr>
            <tr>
              <td>DESCRIPTION</td>
              <td><input type="text" name="description" class="form-control"  value="<?php echo htmlspecialchars($product['description']); ?>" readonly></td>
              <td><input type="date" id="initial_test" name="initial_test" class="form-control"  value="<?php echo htmlspecialchars($product['initial_test']); ?>" readonly onchange="generateCycleDates()"></td>
              <td><input type="text" name="cycle_1_date" id="cycle_1_date" class="form-control" readonly></td>
              <td><input type="text" name="cycle_2_date" id="cycle_2_date" class="form-control" readonly></td>
              <td><input type="text" name="cycle_3_date" id="cycle_3_date" class="form-control" readonly></td>
              <td><input type="text" name="cycle_4_date" id="cycle_4_date" class="form-control" readonly></td>
              <td><input type="text" name="cycle_5_date" id="cycle_5_date" class="form-control" readonly></td>
              <td><input type="text" name="cycle_6_date" id="cycle_6_date" class="form-control" readonly></td>
              <td><input type="text" name="cycle_7_date" id="cycle_7_date" class="form-control" readonly></td>
            </tr>
            <tr>
              <td>IDENTIFICATION</td>
              <td><input type="text" name="identification" class="form-control" value="<?php echo htmlspecialchars($product['identification']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>WEIGHT PER CAPSULE</td>
              <td><input type="text" name="weight" class="form-control" value="<?php echo htmlspecialchars($product['weight']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>DISINTEGRATION TIME</td>
              <td><input type="text" name="disintegration_time" class="form-control" value="<?php echo htmlspecialchars($product['disintegration_time']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>MOISTURE CONTENT</td>
              <td><input type="text" name="moisture_content" class="form-control"  value="<?php echo htmlspecialchars($product['moisture_content']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>UNIFORMITY OF DOSAGE UNIT</td>
              <td><input type="text" name="dosage_unit" class="form-control"  value="<?php echo htmlspecialchars($product['dosage_unit']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr class="header">
              <td colspan="10">MICROBIAL LIMITS</td>
            </tr>
            <tr>
              <td>Total Bacterial Count</td>
              <td><input type="text" name="bacterial_count" class="form-control"  value="<?php echo htmlspecialchars($product['bacterial_count']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>Molds & Yeasts Count</td>
              <td><input type="text" name="molds_yeast_count" class="form-control"  value="<?php echo htmlspecialchars($product['molds_yeast_count']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>Salmonella Species</td>
              <td><input type="text" name="salmonella" class="form-control"  value="<?php echo htmlspecialchars($product['salmonella']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>Escherichia coli</td>
              <td><input type="text" name="escherichia_coli" class="form-control"  value="<?php echo htmlspecialchars($product['escherichia_coli']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>Staphylococcus Aureus</td>
              <td><input type="text" name="staphylococcus_aureus" class="form-control"  value="<?php echo htmlspecialchars($product['staphylococcus_aureus']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>Sodium Ascorbate</td>
              <td><input type="text" name="sodium_ascorbate" class="form-control"  value="<?php echo htmlspecialchars($product['sodium_ascorbate']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td>Zinc Sulfate</td>
              <td><input type="text" name="zinc_sulfate" class="form-control"  value="<?php echo htmlspecialchars($product['zinc_sulfate']); ?>" readonly></td>
              <td colspan="8"></td>
            </tr>
          </table>
        <br>

        <input type="button" value="Download as Excel" class="btn btn-primary" onclick="downloadExcel()">
       
        </form>


    </div>
    <script>
     function addMonthsToDate(date, months) {
        var d = new Date(date);
        d.setMonth(d.getMonth() + months);
        return d.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
    }

    function generateCycleDates() {
        var startDate = document.getElementById('initial_test').value;
        
        // Ensure the date is in correct format before proceeding
        if (startDate) {
            document.getElementById('cycle_1_date').value = addMonthsToDate(startDate, 3);
            document.getElementById('cycle_2_date').value = addMonthsToDate(startDate, 6);
            document.getElementById('cycle_3_date').value = addMonthsToDate(startDate, 9);
            document.getElementById('cycle_4_date').value = addMonthsToDate(startDate, 12);
            document.getElementById('cycle_5_date').value = addMonthsToDate(startDate, 15);
            document.getElementById('cycle_6_date').value = addMonthsToDate(startDate, 18);
            document.getElementById('cycle_7_date').value = addMonthsToDate(startDate, 21);
        }
    }

    // Trigger generateCycleDates when page loads if initial_test already has a value
    window.onload = function() {
        generateCycleDates();
    };


        function downloadExcel() {
            var wb = XLSX.utils.book_new();
            
            // Create worksheet for dataTable
            var ws1 = XLSX.utils.table_to_sheet(document.getElementById('dataTable'));
            var ws2 = XLSX.utils.table_to_sheet(document.getElementById('dataTable2'));

            // Function to update worksheet with input field values
            function updateWorksheetWithInputs(ws, tableId) {
                var table = document.getElementById(tableId);
                var rows = table.querySelectorAll('tr');
                
                rows.forEach(function(row, rowIndex) {
                    var cells = row.querySelectorAll('td, th');
                    
                    cells.forEach(function(cell, cellIndex) {
                        var input = cell.querySelector('input');
                        if (input) {
                            var cellAddress = XLSX.utils.encode_cell({ r: rowIndex, c: cellIndex });
                            ws[cellAddress] = { v: input.value }; // Update cell value with input value
                        }
                    });
                });
            }
            function updateWorksheetWithInputs(ws, tableId) {
                var table = document.getElementById(tableId);
                var rows = table.querySelectorAll('tr');
                
                rows.forEach(function(row, rowIndex) {
                    var cells = row.querySelectorAll('td, th');
                    
                    var cellIndex = 0; // Initialize a variable to track the correct column index
                    cells.forEach(function(cell) {
                        var colspan = cell.getAttribute('colspan') || 1;
                        var rowspan = cell.getAttribute('rowspan') || 1;
                        var input = cell.querySelector('input');
                        
                        // Update only if the cell has an input field
                        if (input) {
                            var cellAddress = XLSX.utils.encode_cell({ r: rowIndex, c: cellIndex });
                            ws[cellAddress] = { v: input.value }; // Update cell value with input value
                        }
                        
                        // Handle merged cells by skipping columns for colspans
                        cellIndex += parseInt(colspan); // Skip the number of columns the cell spans
                    });
                });
            }

            // Update worksheets with input field values
            updateWorksheetWithInputs(ws1, 'dataTable');
            updateWorksheetWithInputs(ws2, 'dataTable2');

            // Set column widths for the first table
            ws1['!cols'] = [
                { wpx: 200 }, // Column 1 width
                { wpx: 400 }, // Column 2 width
                { wpx: 150 }, // Column 3 width
                { wpx: 150 }, // Column 4 width
                { wpx: 150 }, // Column 5 width
                { wpx: 150 }, // Column 6 width
                { wpx: 150 }, // Column 7 width
                { wpx: 150 }, // Column 8 width
                { wpx: 150 }, // Column 9 width
                { wpx: 150 }  // Column 10 width
            ];

            // Apply border styling to all cells for the first table
            var range1 = XLSX.utils.decode_range(ws1['!ref']);
            for (var row = range1.s.r; row <= range1.e.r; row++) {
                for (var col = range1.s.c; col <= range1.e.c; col++) {
                    var cell = ws1[XLSX.utils.encode_cell({ r: row, c: col })];
                    if (!cell) continue;
                    cell.s = cell.s || {};
                    cell.s.border = {
                        top: { style: 'thin' },
                        left: { style: 'thin' },
                        bottom: { style: 'thin' },
                        right: { style: 'thin' }
                    };
                }
            }

            // Set row heights if needed for the first table
            ws1['!rows'] = [
                { hpx: 30 }, // Row 1 height
                { hpx: 30 }, // Row 2 height
                { hpx: 30 }, // Row 3 height
                { hpx: 30 }  // Row 4 height
                // Add more rows if needed
            ];

            // Set column widths for the second table
            ws2['!cols'] = [
                { wpx: 200 }, // Column 1 width
                { wpx: 400 }, // Column 2 width
                { wpx: 150 }, // Column 3 width
                { wpx: 150 }, // Column 4 width
                { wpx: 150 }, // Column 5 width
                { wpx: 150 }, // Column 6 width
                { wpx: 150 }, // Column 7 width
                { wpx: 150 }, // Column 8 width
                { wpx: 150 }, // Column 9 width
                { wpx: 150 }  // Column 10 width
            ];

            // Apply border styling to all cells for the second table
            var range2 = XLSX.utils.decode_range(ws2['!ref']);
            for (var row = range2.s.r; row <= range2.e.r; row++) {
                for (var col = range2.s.c; col <= range2.e.c; col++) {
                    var cell = ws2[XLSX.utils.encode_cell({ r: row, c: col })];
                    if (!cell) continue;
                    cell.s = cell.s || {};
                    cell.s.border = {
                        top: { style: 'thin' },
                        left: { style: 'thin' },
                        bottom: { style: 'thin' },
                        right: { style: 'thin' }
                    };
                }
            }

            // Set row heights if needed for the second table
            ws2['!rows'] = [
                { hpx: 30 }, // Row 1 height
                { hpx: 30 }, // Row 2 height
                { hpx: 30 }, // Row 3 height
                { hpx: 30 }  // Row 4 height
                // Add more rows if needed
            ];

            // Append worksheets to the workbook
            XLSX.utils.book_append_sheet(wb, ws1, "Product Info");
            XLSX.utils.book_append_sheet(wb, ws2, "Test Results");

            // Save the workbook
            XLSX.writeFile(wb, 'Stability_Data.xlsx');
        }

    </script>
  <!-- Optional JavaScript -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>