<?php
// בדיקת האם הטופס נשלח
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST['customerName'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $shape = $_POST['shape'];
    $color = $_POST['color'];
    $quantity = $_POST['quantity'];
    $customText = $_POST['customText'];
    $size = $_POST['size'];
    $sizeDescription = $_POST['sizeDescription'];
    $sizeImage = $_POST['sizeImage'];
    $project = $_POST['project'];
    $projectDescription = $_POST['projectDescription'];
    $projectImage = $_POST['projectImage'];
    $shippingDays = ($quantity > 5) ? 14 : 5;

    // שמירת ההזמנה במסד נתונים
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

    $sql = "INSERT INTO orders (customerName, address, phone, shape, color, quantity, customText, size, sizeDescription, sizeImage, project, projectDescription, projectImage) VALUES ('$customerName', '$address', '$phone', '$shape', '$color', $quantity, '$customText', '$size', '$sizeDescription', '$sizeImage', '$project', '$projectDescription', '$projectImage')";

    if ($conn->query($sql) === TRUE) {
        $message = "سيتم معالجة طلبك في غضون $shippingDays أيام.";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

    // הפנייה מחדש למניעת שליחת הטופס שוב
    header("Location: index.php?message=" . urlencode($message));
    exit();
}

// הצגת הודעה אם קיימת
$message = isset($_GET['message']) ? $_GET['message'] : "";
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب منتجات مصنوعة يدويًا</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #c3ec52, #0ba29d);
            color: #333;
            text-align: center;
            padding: 20px;
            direction: rtl;
            position: relative;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="tel"],
        input[type="color"],
        input[type="number"],
        select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: right;
        }
        .product-shape {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .shape-option {
            width: 50px;
            height: 50px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .shape-option.selected {
            border-color: #333;
        }
        .shape-square {
            width: 40px;
            height: 40px;
            background-color: #333;
        }
        .shape-circle {
            width: 40px;
            height: 40px;
            background-color: #333;
            border-radius: 50%;
        }
        .shape-egg {
            width: 30px;
            height: 40px;
            background-color: #333;
            border-radius: 50% 50% 60% 60%;
        }
        #color-preview {
            width: 50px;
            height: 50px;
            margin: 10px auto;
            border-radius: 50%;
            border: 2px solid #333;
            display: inline-block;
        }
        .gallery {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .gallery img {
            width: 100px;
            height: 100px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .gallery img:hover {
            transform: scale(1.1);
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
        .message {
            color: red;
            margin: 20px 0;
        }
        .background-text {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.2);
            z-index: 0;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
        }
        .description {
            font-size: 14px;
            color: #666;
            text-align: center;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const shapeOptions = document.querySelectorAll('.shape-option');
            const shapeInput = document.getElementById('shape');
            const colorInput = document.getElementById('color');
            const colorPreview = document.getElementById('color-preview');
            const galleryImages = document.querySelectorAll('.gallery img');
            const sizeInput = document.getElementById('size');
            const sizeDescriptionInput = document.getElementById('sizeDescription');
            const sizeImageInput = document.getElementById('sizeImage');
            const projectInput = document.getElementById('project');
            const projectDescriptionInput = document.getElementById('projectDescription');
            const projectImageInput = document.getElementById('projectImage');

            shapeOptions.forEach(option => {
                option.addEventListener('click', () => {
                    shapeOptions.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                    shapeInput.value = option.dataset.shape;
                });
            });

            colorInput.addEventListener('input', () => {
                colorPreview.style.backgroundColor = colorInput.value;
            });

            galleryImages.forEach(img => {
                img.addEventListener('click', () => {
                    const gallery = img.parentElement.id;
                    const description = img.dataset.description;
                    const src = img.src;

                    if (gallery === 'sizeGallery') {
                        sizeInput.value = description;
                        sizeDescriptionInput.value = img.alt;
                        sizeImageInput.value = src;
                    } else if (gallery === 'projectGallery') {
                        projectInput.value = description;
                        projectDescriptionInput.value = img.alt;
                        projectImageInput.value = src;
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="background-text">جودة عالية · تسليم سريع · خدمة مميزة</div>
    <div class="container">
        <h1>طلب منتجات مصنوعة يدويًا</h1>
        <form action="index.php" method="post">
            <label for="customerName">اسم العميل</label>
            <input type="text" name="customerName" id="customerName" required>

            <label for="address">عنوان السكن</label>
            <input type="text" name="address" id="address" required>

            <label for="phone">رقم الهاتف</label>
            <input type="tel" name="phone" id="phone" required>

            <label for="shape">اختر الشكل</label>
            <div class="product-shape">
                <div class="shape-option" data-shape="مربع">
                    <div class="shape-square"></div>
                </div>
                <div class="shape-option" data-shape="دائري">
                    <div class="shape-circle"></div>
                </div>
                <div class="shape-option" data-shape="بيضة">
                    <div class="shape-egg"></div>
                </div>
            </div>
            <input type="hidden" name="shape" id="shape" required>

            <label for="color">اختر اللون</label>
            <input type="color" name="color" id="color" required>
            <div id="color-preview"></div>

            <label for="size">اختر الحجم</label>
            <div class="gallery" id="sizeGallery">
                <div>
                    <img src="./sizeGallery/IMG-20240702-WA0001.jpg" data-description="مربع" alt="Small Size">
                    <div class="description">مربع</div>
                </div>
                <div>
                    <img src="./sizeGallery/IMG-20240702-WA0003.jpg" data-description="دائري" alt="Medium Size">
                    <div class="description">دائري</div>
                </div>
                <div>
                    <img src="./sizeGallery/IMG-20240702-WA0004.jpg" data-description="بيضوي" alt="Large Size">
                    <div class="description">بيضوي</div>
                </div>
            </div>
            <input type="hidden" name="size" id="size" required>
            <input type="text" name="sizeDescription" id="sizeDescription" placeholder="أضف وصف للحجم">
            <input type="hidden" name="sizeImage" id="sizeImage" required>

            <label for="project">اختر امثله لمشاريع سابقه</label>
            <div class="gallery" id="projectGallery">
                <div>
                    <img src="./projectGallery/project1.jpg" data-description="مشروع 1" alt="Project 1">
                    <div class="description"> </div>
                </div>
                <div>
                    <img src="./projectGallery/project2.jpg" data-description="مشروع 2" alt="Project 2">
                    <div class="description"> </div>
                </div>
                <div>
                    <img src="./projectGallery/project3.jpg" data-description="مشروع 3" alt="Project 3">
                    <div class="description"></div>
                </div>
                <div>
                    <img src="./projectGallery/project4.jpg" data-description="مشروع 4" alt="Project 4">
                    <div class="description"></div>
                </div>
                <div>
                    <img src="./projectGallery/project5.jpg" data-description="مشروع 5" alt="Project 5">
                    <div class="description"></div>
                </div>

            </div>
            <input type="hidden" name="project" id="project" required>
            <input type="text" name="projectDescription" id="projectDescription" placeholder="أضف وصف للمشروع">
            <input type="hidden" name="projectImage" id="projectImage" required>

            <label for="quantity">الكمية</label>
            <input type="number" name="quantity" id="quantity" min="1" required>

            <label for="customText">ماذا تكتب على المنتج (اختياري)</label>
            <input type="text" name="customText" id="customText">

            <button type="submit">اطلب الآن</button>
        </form>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    </div>
</body>
</html>
