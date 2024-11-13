<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Voucher</title>
    <style>
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            width: 95%;
            max-width: 800px; /* Adjust max-width for larger screens */
            height: auto;
            max-height: 90vh; /* Limit max height to avoid overflow */
            overflow-y: auto; /* Enable scrolling within modal */
            box-sizing: border-box; /* Include padding in width/height */
            position: relative;
            border-radius: 8px;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Manage Vouchers</h1>

    <!-- Button to open the modal -->
    <button onclick="openModal()">Add New Voucher</button>

    <!-- Voucher List -->
    <h2>Existing Vouchers</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Points Required</th>
                <th>Expiration Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include the Voucher class and fetch vouchers
            require_once 'class/voucherclass.php';
            $voucherClass = new Voucher();
            $vouchers = $voucherClass->getAllVouchers();

            foreach ($vouchers as $voucher) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($voucher['voucher_title']) . "</td>";
                echo "<td>" . htmlspecialchars($voucher['description']) . "</td>";
                echo "<td>" . htmlspecialchars($voucher['points_required']) . "</td>";
                echo "<td>" . htmlspecialchars($voucher['expiration_date']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Create Voucher Modal -->
    <div id="voucherModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Create Voucher</h2>
            <form action="processes/process.voucher.php?action=createvouch" method="POST">
                <label for="voucher_title">Voucher Title:</label><br>
                <input type="text" name="voucher_title" id="voucher_title" required><br><br>

                <label for="description">Description:</label><br>
                <textarea name="description" id="description" required></textarea><br><br>

                <label for="points_required">Points Required:</label><br>
                <input type="number" name="points_required" id="points_required" required><br><br>

                <label for="expiration_date">Expiration Date:</label><br>
                <input type="date" name="expiration_date" id="expiration_date" required><br><br>

                <button type="submit">Create Voucher</button>
            </form>
        </div>
    </div>

    <script>
        // Open the modal
        function openModal() {
            document.getElementById("voucherModal").style.display = "flex";
        }

        // Close the modal
        function closeModal() {
            document.getElementById("voucherModal").style.display = "none";
        }

        // Close the modal when clicking outside the modal content
        window.onclick = function(event) {
            const modal = document.getElementById("voucherModal");
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
