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
    $stmt->bind_param("ssssssssssssssssssssssi", $product_name, $brand_name, $lot_no, $mfg_date, $expiry_date, $packing, $storage_temp, $rh, $description, $identification, $weight, $disintegration_time, $moisture_content, $dosage_unit, $bacterial_count, $molds_yeast_count, $salmonella, $escherichia_coli, $staphylococcus_aureus, $sodium_ascorbate, $zinc_sulfate, $initial_test, $id);

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
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">

    <!-- Custom CSS for 80% width modal -->
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
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <th colspan="2">PRODUCT<br>NAME</th>
                    <td colspan="2"><input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required></td>
                    <th colspan="2">LOT NO.</th>
                    <td colspan="2"><input type="number" name="lot_no" class="form-control" value="<?php echo htmlspecialchars($product['lot_no']); ?>" required></td>
                </tr>
                <tr>
                    <th colspan="2">PACKING</th>
                    <td colspan="2"><input type="text" name="packing" class="form-control" value="<?php echo htmlspecialchars($product['packing']); ?>" required></td>
                    <th colspan="2">BRANDNAME</th>
                    <td colspan="2"><input type="text" name="brand_name" class="form-control" placeholder="enter brand name" value="<?php echo htmlspecialchars($product['brand_name']); ?>" required></td>
                </tr>
                <tr>
                    <th colspan="2">STORAGE TEMPERATURE</th>
                    <td colspan="2"><input type="text" name="storage_temp" class="form-control" value="<?php echo htmlspecialchars($product['storage_temp']); ?>" required></td>
                    <th colspan="2">MFG. DATE</th>
                    <td colspan="2"><input type="date" name="mfg_date" class="form-control" value="<?php echo htmlspecialchars($product['mfg_date']); ?>" required></td>
                </tr>
                <tr>
                    <th colspan="2">EXPIRY DATE</th>
                    <td colspan="2"><input type="date" name="expiry_date" class="form-control" value="<?php echo htmlspecialchars($product['expiry_date']); ?>" required></td>
                    <th colspan="2">RH</th>
                    <td colspan="2"><input type="text" name="rh" class="form-control" value="<?php echo htmlspecialchars($product['rh']); ?>" required></td>
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
                    <td><input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($product['description']); ?>" required></td>
                    <td><input type="date" id="initial_test" name="initial_test" class="form-control" value="<?php echo htmlspecialchars($product['initial_test']); ?>" required onchange="generateCycleDates()"></td>
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
                    <td><input type="text" name="identification" class="form-control" value="<?php echo htmlspecialchars($product['identification']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td>WEIGHT PER CAPSULE</td>
                    <td><input type="text" name="weight" class="form-control" value="<?php echo htmlspecialchars($product['weight']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td>DISINTEGRATION TIME</td>
                    <td><input type="text" name="disintegration_time" class="form-control" value="<?php echo htmlspecialchars($product['disintegration_time']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td>MOISTURE CONTENT</td>
                    <td><input type="text" name="moisture_content" class="form-control" value="<?php echo htmlspecialchars($product['moisture_content']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td>UNIFORMITY OF DOSAGE UNIT</td>
                    <td><input type="text" name="dosage_unit" class="form-control" value="<?php echo htmlspecialchars($product['dosage_unit']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr class="header">
                    <td colspan="10">MICROBIAL LIMITS</td>
                </tr>
                <tr>
                    <td>Total Bacterial Count</td>
                    <td><input type="text" name="bacterial_count" class="form-control" value="<?php echo htmlspecialchars($product['bacterial_count']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td>Molds & Yeasts Count</td>
                    <td><input type="text" name="molds_yeast_count" class="form-control" value="<?php echo htmlspecialchars($product['molds_yeast_count']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td>Salmonella Species</td>
                    <td>
                        <select name="salmonella" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Positive" <?php if ($product['salmonella'] == 'Positive') echo 'selected'; ?>>Positive</option>
                            <option value="Negative" <?php if ($product['salmonella'] == 'Negative') echo 'selected'; ?>>Negative</option>
                        </select>
                    </td>
                    <td colspan="8"></td>
                </tr>

                <tr>
                    <td>Escherichia coli</td>
                    <td>
                        <select name="escherichia_coli" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Positive" <?php if ($product['escherichia_coli'] == 'Positive') echo 'selected'; ?>>Positive</option>
                            <option value="Negative" <?php if ($product['escherichia_coli'] == 'Negative') echo 'selected'; ?>>Negative</option>
                        </select>
                    </td>
                    <td colspan="8"></td>
                </tr>

                <tr>
                    <td>Staphylococcus Aureus</td>
                    <td>
                        <select name="staphylococcus_aureus" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Positive" <?php if ($product['staphylococcus_aureus'] == 'Positive') echo 'selected'; ?>>Positive</option>
                            <option value="Negative" <?php if ($product['staphylococcus_aureus'] == 'Negative') echo 'selected'; ?>>Negative</option>
                        </select>
                    </td>
                    <td colspan="8"></td>
                </tr>

                <tr>
                    <td>Sodium Ascorbate</td>
                    <td><input type="text" name="sodium_ascorbate" class="form-control" value="<?php echo htmlspecialchars($product['sodium_ascorbate']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td>Zinc Sulfate</td>
                    <td><input type="text" name="zinc_sulfate" class="form-control" value="<?php echo htmlspecialchars($product['zinc_sulfate']); ?>" required></td>
                    <td colspan="8"></td>
                </tr>
            </table>
            <br>


            <button type="submit" class="btn btn-primary">Submit</button>

            <!-- Cancel button with Bootstrap styling -->
            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Cancel</button>
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
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>