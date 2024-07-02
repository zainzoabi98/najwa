<?php
require_once('tcpdf/tcpdf.php');

// קבלת מזהה ההזמנה מה-POST
$orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : 0;

// חיבור למסד הנתונים
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orders";

// יצירת חיבור
$conn = new mysqli($servername, $username, $password, $dbname);

// בדיקת החיבור
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// שליפת פרטי ההזמנה
$sql = "SELECT * FROM orders WHERE id = $orderId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    die("Order not found");
}

$conn->close();

// יצירת אובייקט TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// יצירת תוכן ה-PDF
$html = '
<h1>تفاصيل الطلب</h1>
<table>
    <tr>
        <th>اسم العميل:</th>
        <td>' . htmlspecialchars($order['customerName']) . '</td>
    </tr>
    <tr>
        <th>عنوان السكن:</th>
        <td>' . htmlspecialchars($order['address']) . '</td>
    </tr>
    <tr>
        <th>رقم الهاتف:</th>
        <td>' . htmlspecialchars($order['phone']) . '</td>
    </tr>
    <tr>
        <th>الشكل:</th>
        <td>' . htmlspecialchars($order['shape']) . '</td>
    </tr>
    <tr>
        <th>اللون:</th>
        <td><span style="background-color: ' . htmlspecialchars($order['color']) . '; width: 20px; height: 20px; display: inline-block;"></span></td>
    </tr>
    <tr>
        <th>الحجم:</th>
        <td>' . htmlspecialchars($order['size']) . '</td>
    </tr>
    <tr>
        <th>المشروع:</th>
        <td>' . htmlspecialchars($order['project']) . '</td>
    </tr>
    <tr>
        <th>الكمية:</th>
        <td>' . htmlspecialchars($order['quantity']) . '</td>
    </tr>
    <tr>
        <th>نص مخصص:</th>
        <td>' . htmlspecialchars($order['customText']) . '</td>
    </tr>
</table>
';

// כתיבת תוכן ה-PDF
$pdf->writeHTML($html, true, false, true, false, '');

// סגירת וקידוד ה-PDF
$pdf->Output('order_details.pdf', 'D');
?>
