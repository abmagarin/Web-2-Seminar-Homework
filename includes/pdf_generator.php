<?php
session_start();
require_once('../data/db_connection.php');
require_once('../TCPDF-main/tcpdf.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID first
$username = $_SESSION['username'];
$userQuery = "SELECT id FROM users WHERE username = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows === 0) {
    die("User not found");
}

$userData = $userResult->fetch_assoc();
$userId = $userData['id'];

// Fetch basket items with complete notebook details
$query = "
    SELECT 
        b.basket_id,
        b.user_id,
        b.product_id,
        b.quantity,
        n.manufacturer,
        n.type,
        n.price,
        n.pieces AS stock_available
    FROM baskets b
    JOIN notebook n ON b.product_id = n.id
    WHERE b.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Check if basket is empty
if ($result->num_rows === 0) {
    echo "<script>
    alert('Your basket is empty.');
    window.location.href = '../pages/basket_page.php';
    </script>";
    exit();
}

// Collect basket items
$basketItems = [];
$totalPrice = 0;

while ($row = $result->fetch_assoc()) {
    $itemTotal = $row['price'] * $row['quantity'];
    $basketItems[] = [
        'notebook_id' => $row['product_id'],
        'manufacturer' => $row['manufacturer'],
        'type' => $row['type'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'stock_available' => $row['stock_available'],
        'item_total' => $itemTotal
    ];
    $totalPrice += $itemTotal;
}

// Debugging: Print basket items
error_log("Basket Items: " . print_r($basketItems, true));
error_log("Total Price: $totalPrice");

// Rest of the PDF generation code remains the same as in the previous example

// Create PDF Invoice with Enhanced Design
class InvoicePDF extends TCPDF {
    private $companyName = 'Laptop Heaven';
    private $companyTagline = 'Your Gateway to Technology';
    private $companyAddress = 'GAMF Street, Innovation District';
    private $companyContact = '+36 (20) 2222222 | support@NJE.com';

    public function Header() {
        // Background color
        $this->SetFillColor(240, 240, 240);
        $this->Rect(0, 0, $this->getPageWidth(), 30, 'F');

        // Company Name and Tagline
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(33, 33, 33);
        $this->Cell(0, 10, $this->companyName, 0, 1, 'L');
        
        $this->SetFont('helvetica', 'I', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, $this->companyTagline, 0, 1, 'L');

        // Invoice Title
        $this->SetY(10);
        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(0, 120, 200);
        $this->Cell(0, 15, 'INVOICE', 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }
    
    public function Footer() {
        // Position from bottom
        $this->SetY(-20);
        
        // Company Contact Info
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, $this->companyContact, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Page number
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        

        $this->Ln(10);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        
        $disclaimerText = "🎓 Academic Disclaimer: This project is developed for educational purposes only. " .
                          "Any data entered is purely for demonstration and learning. " .
                          "Rest assured, your information is not stored, shared, or used by any third parties. " .
                          "Feel free to explore and test with confidence! 🖥️";
        
        // Multiline cell for the disclaimer
        $this->MultiCell(0, 6, $disclaimerText, 0, 'C');
    }

    public function CreateInvoiceBody($username, $basketItems, $totalPrice) {
        // Customer and Invoice Details
        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(0, 0, 0);
        
        // Invoice Details
        $this->Cell(0, 6, 'Invoice Date: ' . date('Y-m-d H:i:s'), 0, 1);
        $this->Cell(0, 6, 'Customer: ' . $username, 0, 1);
        $this->Ln(10);

        // Table Header
        $this->SetFont('helvetica', 'B', 12);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(100, 10, 'Product Description', 1, 0, 'L', 1);
        $this->Cell(30, 10, 'Unit Price', 1, 0, 'R', 1);
        $this->Cell(20, 10, 'Qty', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Total', 1, 1, 'R', 1);

        // Table Rows
        $this->SetFont('helvetica', '', 10);
        $this->SetFillColor(240, 240, 240);
        $fill = false;
        foreach ($basketItems as $item) {
            $this->Cell(100, 8, $item['manufacturer'] . ' ' . $item['type'], 1, 0, 'L', $fill);
            $this->Cell(30, 8, number_format($item['price'], 2) . ' HUF', 1, 0, 'R', $fill);
            $this->Cell(20, 8, $item['quantity'], 1, 0, 'C', $fill);
            $this->Cell(40, 8, number_format($item['item_total'], 2) . ' HUF', 1, 1, 'R', $fill);
            $fill = !$fill;
        }

        // Total Section
        $this->Ln(10);
        $this->SetFont('helvetica', 'B', 14);
        $this->SetFillColor(220, 255, 220);
        $this->Cell(150, 12, 'Total Amount:', 1, 0, 'R', 1);
        $this->Cell(40, 12, number_format($totalPrice, 2) . ' HUF', 1, 1, 'R', 1);

        // Payment Instructions
        $this->Ln(10);
        $this->SetFont('helvetica', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, 'Thank you for your purchase! Please retain this invoice for your records.', 0, 1, 'C');
        $this->Cell(0, 6, 'For any inquiries, contact our customer support.', 0, 1, 'C');
    }
}

// Create new PDF document
$pdf = new InvoicePDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($username);
$pdf->SetTitle('Invoice - Laptop Heaven');
$pdf->SetSubject('Purchase Invoice');

// Set default header data
$pdf->SetHeaderData('', 0, 'Invoice', '');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Create invoice body
$pdf->CreateInvoiceBody($username, $basketItems, $totalPrice);

// Output PDF
$pdf->Output('Invoice_' . $username . '_' . date('YmdHis') . '.pdf', 'D');

// Optional: Clear basket after generating invoice
$clearBasketQuery = "DELETE FROM baskets WHERE user_id = ?";
$clearStmt = $conn->prepare($clearBasketQuery);
$clearStmt->bind_param("i", $userId);
$clearStmt->execute();

// Close statements and connection
$userStmt->close();
$stmt->close();
$clearStmt->close();
$conn->close();