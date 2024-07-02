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

// בדיקת האם הוזן מזהה להזמנה כהושלמה
if (isset($_POST['complete_order_id'])) {
    $orderId = intval($_POST['complete_order_id']);
    $sql = "UPDATE orders SET completed = TRUE WHERE id = $orderId";
    $conn->query($sql);
}

// שליפת כל ההזמנות שלא הושלמו
$sql = "SELECT * FROM orders WHERE completed = FALSE";
$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>רשימת הזמנות</title>
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
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
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
        .complete-button {
            background-color: #d9534f;
        }
        .complete-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>רשימת הזמנות</h1>
        <table>
            <thead>
                <tr>
                    <th>מספר הזמנה</th>
                    <th>שם הלקוח</th>
                    <th>טלפון</th>
                    <th>פעולות</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customerName']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                        <td>
                            <form action="order_details.php" method="get" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                <button type="submit">פתח הזמנה</button>
                            </form>
                            <form action="orders_list.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="complete_order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="complete-button">סמן כהושלמה</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
