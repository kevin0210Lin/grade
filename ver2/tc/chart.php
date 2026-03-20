<?php
require_once("../set.php");

if (isset($_GET["c"]) && isset($_GET["s"])) {
    if ($_GET["s"] == "鍾嬡妮" || isset($_GET["test"])) {
        $stu_class = $_GET["c"]; // 指定班級
        $test_name = $_GET["s"]; // 指定學生名字
    } else {
        echo "<script>alert('網頁錯誤 請洽管理員');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else if (isset($_GET["test"])) {
    $stu_class = '908';
    $test_name = '林甲鑫';
} else {
    echo "<script>alert('網頁錯誤 請洽管理員');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}

$subjects = [
    1 => '國文',
    2 => '數學',
    3 => '英文',
    4 => '地理',
    5 => '歷史',
    6 => '公民',
    7 => '生物',
    8 => '理化',
    9 => '地科',
];

$choose_class_ave = [];
$choose_grade_ave = [];
$choose_grade = [];
$choose_name = [];

for ($i = 1; $i <= 9; $i++) {
    $sql = "SELECT * FROM `junior3_grade_set` WHERE `subject` = '" . $subjects[$i] . "';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $choose_class_ave[$i][] = $row["$stu_class"];
            $choose_grade_ave[$i][] = $row["average"];
            $choose_name[$i][] = $row["test_name"];

            $choose_week = $row["week_ID"];
            $choose_id = $row["test_ID"];

            $sql1 = "SELECT * FROM `$choose_week` WHERE `name` = '$test_name';";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                $row1 = $result1->fetch_assoc();
                $choose_grade[$i][] = $row1["$choose_id"];
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="zh">

<head>
    <title><?= $stu_class . $test_name ?> 成績分析圖</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tc-shared-styles.css">
    <link href="/set/style.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        header {
            padding: 20px;
            text-align: center;
            background-color: #007BFF;
            color: white;
            font-size: 1.5em;
        }

        .flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 20px;
        }

        .chart-container {
            width: 100%;
            max-width: 900px;
            min-width: 300px;
            height: 400px;
            position: relative;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        canvas {
            display: block;
            width: 100%;
            height: 100%;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #007BFF;
            color: white;
            font-size: 0.9em;
            width: 100%;
        }
    </style>
</head>

<body>
    <header>
        <?= $stu_class ." ". $test_name ?> 各科成績分析圖
        <a href="" class="btn btn-warning btn-user">返回前一頁</a>

    </header>

    <div class="flex">
        <?php
        // 顯示每個科目的圖表
        foreach ($subjects as $key => $subject) {
            echo '<div>';
            echo "<h3 style='text-align: center;'>{$subject}</h3>";
            echo "<div class='chart-container'>
                    <canvas id='myChart{$key}'></canvas>
                  </div>";
            echo '</div>';
        }
        ?>
    </div>

    <footer>
        © 114六和高中國三成績系統
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // PHP传递的科目数据（按科目分组）
        const subjectData = {
            <?php foreach ($subjects as $key => $subject): ?>
            "<?php echo $key; ?>": {
                    classAverages: <?php echo json_encode($choose_class_ave[$key] ?? []); ?>,
                    gradeAverages: <?php echo json_encode($choose_grade_ave[$key] ?? []); ?>,
                    personalGrades: <?php echo json_encode($choose_grade[$key] ?? []); ?>,
                    subjectLabels: <?php echo json_encode($choose_name[$key] ?? []); ?>
                },
            <?php endforeach; ?>
        };

        // 動態創建每個圖表
        <?php foreach ($subjects as $key => $subject): ?>
            const data<?php echo $key; ?> = subjectData["<?php echo $key; ?>"];
            new Chart(document.getElementById('myChart<?php echo $key; ?>'), {
                type: 'bar',
                data: {
                    labels: data<?php echo $key; ?>.subjectLabels,
                    datasets: [
                        {
                            type: 'line',
                            label: '班級平均',
                            data: data<?php echo $key; ?>.classAverages,
                            borderColor: 'rgba(75, 192, 192, 1)',
                        },
                        {
                            type: 'line',
                            label: '年級平均',
                            data: data<?php echo $key; ?>.gradeAverages,
                            borderColor: 'rgba(54, 162, 235, 1)',
                        },
                        {
                            type: 'bar',
                            label: '個人成績',
                            data: data<?php echo $key; ?>.personalGrades,
                            backgroundColor: 'rgba(255, 99, 132, 0.8)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            });
        <?php endforeach; ?>
    </script>
</body>

</html>