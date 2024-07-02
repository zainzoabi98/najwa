<?php
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

// קבלת מזהה ההזמנה מה-URL
$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// שליפת פרטי ההזמנה
$sql = "SELECT * FROM orders WHERE id = $orderId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    die("Order not found");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #c3ec52, #0ba29d);
            color: #333;
            text-align: center;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .order-details {
            text-align: left;
            margin-bottom: 20px;
        }
        .order-details dt {
            font-weight: bold;
            margin-top: 10px;
        }
        .order-details dd {
            margin: 0;
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>تفاصيل الطلب</h1>
        <dl class="order-details">
            <dt>اسم العميل:</dt>
            <dd><?php echo htmlspecialchars($order['customerName']); ?></dd>

            <dt>عنوان السكن:</dt>
            <dd><?php echo htmlspecialchars($order['address']); ?></dd>

            <dt>رقم الهاتف:</dt>
            <dd><?php echo htmlspecialchars($order['phone']); ?></dd>

            <dt>الشكل:</dt>
            <dd><?php echo htmlspecialchars($order['shape']); ?></dd>

            <dt>اللون:</dt>
            <dd><span style="background-color: <?php echo htmlspecialchars($order['color']); ?>; width: 20px; height: 20px; display: inline-block;"></span></dd>

            <dt>الحجم:</dt>
            <dd><?php echo htmlspecialchars($order['size']); ?></dd>

            <dt>المشروع:</dt>
            <dd><?php echo htmlspecialchars($order['project']); ?></dd>

            <dt>الكمية:</dt>
            <dd><?php echo htmlspecialchars($order['quantity']); ?></dd>

            <dt>نص مخصص:</dt>
            <dd><?php echo htmlspecialchars($order['customText']); ?></dd>
        </dl>
        <form action="generate_pdf.php" method="post">
            <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
            <button type="submit">הורד כקובץ PDF</button>
        </form>
    </div>
</body>
</html>
