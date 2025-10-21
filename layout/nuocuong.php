<?php
session_start();
include("../connect.php");

// Check if database connection is successful
if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set server time zone to match client's (UTC+7)
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Fetch products and their discounts (only active discounts)
$currentTime = date('Y-m-d H:i:s');
$sql = "SELECT s.idSanPham, s.tenSanPham, s.gia, s.hinhanh, d.phanTram, d.time_End 
        FROM sanpham s 
        LEFT JOIN discount d ON s.idSanPham = d.idSanPham 
        AND d.time_Start <= '$currentTime' AND d.time_End >= '$currentTime'
        WHERE s.loaiSanPham = 'Nước Uống'";
$result = mysqli_query($link, $sql);

// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($link));
}

// Prepare discount countdown data
$discountData = [];
while ($row = mysqli_fetch_assoc($result)) {
    if (!empty($row['time_End'])) {
        $endTime = strtotime($row['time_End']);
        $currentTime = time();
        $secondsLeft = $endTime - $currentTime;
        if ($secondsLeft > 0) {
            $discountData[$row['idSanPham']] = $secondsLeft;
        }
    }
}
mysqli_data_seek($result, 0); // Reset result pointer for reuse
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nước Uống | 5AE WebShop</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
      .section-title {
        margin-top: 10px;
        text-align: center;
        font-weight: bold;
        padding: 15px;
        background-color: var(--left-menu-color);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
      }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            max-width: 1200px; 
            margin: 0 auto; 
            justify-content: center; 
        }

        .product-card {
            width: 100%;
            max-width: 180px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: rgba(67, 71, 85, 0.27) 0px 0px 0.25em, rgba(90, 125, 188, 0.05) 0px 0.25em 1em;
            transition: transform 0.2s ease;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-3px);
        }

        .product-card img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .product-info {
            padding: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-info .description {
            text-align: center;
            font-size: 0.9rem;
            font-weight: bold;
            min-height: 36px;
        }

        .product-info .price {
            color: #d0021b;
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
        }

        .product-info .original-price {
            font-size: 0.85rem;
            text-decoration: line-through;
            color: #999;
            text-align: center;
            margin-top: 2px;
        }

        .add-to-cart {
            font-size: 0.9rem;
            font-weight: bold;
            text-align: center;
            margin-top: 8px;
            cursor: pointer;
        }

        .add-to-cart i {
            border: 1px solid var(--left-menu-color);
            color: var(--left-menu-color);
            border-radius: 5px;
            padding: 4px 5px;
            font-size: 15px;
            margin-left: 6px;
            transition: transform 0.2s ease;
        }

        .add-to-cart i:hover {
            transform: scale(1.1);
        }

        .countdown {
            font-size: 0.8rem;
            color: #ff5722;
            text-align: center;
            margin-top: 6px;
            font-weight: bold;
            background: #fff3e0;
            padding: 4px;
            border-radius: 4px;
        }

        .discount-tag {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: #ff3d00;
            color: #fff;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
        }

        @media (max-width: 600px) {
            .product-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .product-card {
                max-width: 100%;
            }
        }

        @media (min-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(6, 1fr); /* Enforces 6 items per row */
            }
        }
    </style>
</head>
<body>
    <?php include("include/left-menu.php"); ?>
    <div id="main">
        <?php include("include/navbar.php"); ?>
        <div id="main-content">
            <div class="section-title"><h3>NƯỚC UỐNG</h3></div>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="product-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $giaGoc = floatval($row['gia']);
                        $coGiamGia = !empty($row['phanTram']) && $row['phanTram'] > 0;
                        $giaGiam = $coGiamGia ? $giaGoc * (1 - $row['phanTram'] / 100) : $giaGoc;
                        $hinhanh = str_replace('\\', '/', $row['hinhanh']);
                        ?>
                        <div class="product-card">
                            <?php if ($coGiamGia): ?>
                                <div class="discount-tag">-<?php echo intval($row['phanTram']); ?>%</div>
                            <?php endif; ?>
                            <img src="../<?php echo htmlspecialchars($hinhanh); ?>" alt="<?php echo htmlspecialchars($row['tenSanPham']); ?>">
                            <div class="product-info">
                                <div class="description"><?php echo htmlspecialchars($row['tenSanPham']); ?></div>
                                <div class="price"><?php echo number_format($giaGiam, 0, ',', '.') . '₫'; ?></div>
                                <?php if ($coGiamGia): ?>
                                    <div class="original-price"><?php echo number_format($giaGoc, 0, ',', '.') . '₫'; ?></div>
                                <?php endif; ?>
                                <div class="add-to-cart">Thêm vào giỏ hàng<a href="ttsp.php?id=<?php echo $row['idSanPham']; ?>" class="add-to-cart"><i class="fas fa-plus"></i></a></div>
                                <?php if ($coGiamGia): ?>
                                    <div class="countdown" id="countdown-<?php echo $row['idSanPham']; ?>">
                                        Ưu đãi kết thúc sau: <span class="time-left"></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Không có sản phẩm nào trong danh mục này.</p>
            <?php endif; ?>
        </div>
        <?php include("include/footer.php"); ?>
    </div>

    <script>
        const discountCountdowns = <?php echo json_encode($discountData); ?>;

        for (const id in discountCountdowns) {
            const countdownEl = document.querySelector(`#countdown-${id} .time-left`);
            let timeLeft = discountCountdowns[id];

            if (countdownEl) {
                const interval = setInterval(() => {
                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        countdownEl.textContent = "Đã hết thời gian!";
                    } else {
                        const hours = Math.floor(timeLeft / 3600);
                        const minutes = Math.floor((timeLeft % 3600) / 60);
                        const seconds = timeLeft % 60;
                        countdownEl.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                        timeLeft--;
                    }
                }, 1000);
            }
        }
    </script>
    <script>
      $(document).ready(function () {
        $(".submenu").hide();
        $(".menu1").click(function (e) {
          e.preventDefault();
          const submenu = $(this).siblings(".submenu");
          submenu.slideToggle();
          $(this).find(".caret-icon").toggleClass("rotate");
        });
      });
    </script>
</body>
</html>
<?php mysqli_close($link); ?>