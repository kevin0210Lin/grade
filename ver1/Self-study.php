<?php
require_once("set.php");

// 檢查連線是否成功
if ($conn->connect_error) {
    die('連線失敗: ' . $conn->connect_error);
}

// 設定字符集
$conn->set_charset("utf8mb4");

$sql1 = "SELECT * FROM Self_study_time ORDER BY time_start ASC";
$sql2 = "SELECT * FROM Self_study_music_time ORDER BY time ASC";
$sql3 = "SELECT * FROM Self_study_subject ORDER BY time ASC";
$result1 = $conn->query($sql1);
$result2 = $conn->query($sql2);
$result3 = $conn->query($sql3);

$schedule = [];
$musictime = [];
$subject = [];
if ($result1->num_rows > 0) {
    while ($row1 = $result1->fetch_assoc()) {
        $schedule[] = $row1;
    }
}
if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        $musictime[] = $row2;
    }
}
if ($result3->num_rows > 0) {
    while ($row3 = $result3->fetch_assoc()) {
        $subject[] = $row3;
    }
}

//echo json_encode($subject);

$conn->close();

// 設定目標日期（會考日期）
$examDate = new DateTime("2025-05-18"); // 假設會考日期是 2025 年 5 月 18 日
$today = new DateTime();
$daysLeft = $examDate->diff($today)->days; // 計算倒數天數
?>


<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>大自習顯示器</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            color: #404040;
            text-align: center;
        }

        .header {
            background: linear-gradient(to right, #ff7f50, #ff4500);
            color: white;
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            border-bottom: 5px solid #dc3545;
            letter-spacing: 2px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header span {
            color: #ffff00;
            /* 黃色字體突出倒數天數 */
            font-size: 3rem;
        }

        .main-container {
            display: grid;
            grid-template-rows: auto 4fr 1fr;
            height: 100%;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 100%;
        }

        .left,
        .right {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 5.5rem;
            border: 1px solid #dee2e6;
        }

        .left {
            background-color: #f8f9fa;
        }

        .right {
            background-color: #e9ecef;
        }

        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header -->
        <div class="header">
            六和高中 國三114會考 考前大自習 倒數 <span><?php echo $daysLeft; ?></span> 天
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="left">
                <div><strong>第 節自習</strong></div>
                <div>0:00~0:00</div>
            </div>
            <div class="right">
                <div><strong>現在解題科目(測試中)</strong></div>
                <div class="subject"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <span id="current-time">--:--:--</span>
        </div>
        <button id="unlock-audio" class="btn btn-primary btn-user" style="padding: 10px;">自動鐘聲開啟</button>
    </div>

    <audio id="alarm-sound1" src="music/上課鐘聲.mp3" preload="auto"></audio>
    <audio id="alarm-sound2" src="music/下課鐘聲.mp3" preload="auto"></audio>
    <audio id="alarm-sound3" src="music/test.mp3" preload="auto"></audio>

    <script>
        const schedule = <?php echo json_encode($schedule); ?>;
        const musictime = <?php echo json_encode($musictime); ?>;
        const subject = <?php echo json_encode($subject); ?>;
        let alarmInitialized = false;

        // 解鎖音頻按鈕
        document.getElementById('unlock-audio').addEventListener('click', () => {
            const alarm1 = document.getElementById('alarm-sound1');
            const alarm2 = document.getElementById('alarm-sound2');
            const alarm3 = document.getElementById('alarm-sound3');
            alarm1.play().then(() => {
                alarm1.pause();
                alarm2.pause();
                alarm3.pause();
                alarmInitialized = true;
                document.getElementById('unlock-audio').style.display = 'none';
            }).catch(error => console.error("音頻解鎖失敗: ", error));
        });

        function updateTime() {
            const now = new Date();
            const options = { hour12: false };
            const currentTime = now.toLocaleTimeString('zh-TW', options);
            document.getElementById('current-time').textContent = currentTime;

            //取得當天日期
            const today = `${now.getMonth() + 1}/${now.getDate()}`;

            //預設取得資料為null
            let currentSession1 = null;
            let currentSession2 = null;

            // 篩選 schedule 資料，找到符合當前時間的節次
            for (let i = 0; i < schedule.length; i++) {
                const sessionStart1 = schedule[i].time_start;
                if (currentTime >= sessionStart1) {
                    currentSession1 = schedule[i];
                } else {
                    break;
                }
            }

            // 篩選 subject 資料，找到符合當前時間的科目
            for (let i = 0; i < subject.length; i++) {
                const sessionStart2 = subject[i].time;
                if (currentTime >= sessionStart2) {
                    currentSession2 = subject[i];
                } else {
                    break;
                }
            }

            if (currentSession1) {
                updateleftContent(currentSession1);
            }
            if (currentSession2) {
                updaterightContent(currentSession2,today);
            }

            musictime.forEach(item => {
                if (currentTime === item.time && alarmInitialized) {
                    playMusic(item.music);
                }
            });
        }

        function updateleftContent(session) {
            const left = document.querySelector('.left');
            left.innerHTML = `
                <div><strong>${session.type}</strong></div>
                <div>${session.time_set}</div>
            `;
        }
        function updaterightContent(session, today) {
            const rightsubject = document.querySelector('.subject');

            const nowsubjectData = session[today];

            if (nowsubjectData !== undefined && nowsubjectData !== '') {
                rightsubject.innerHTML = `${nowsubjectData}`;
            } else if (nowsubjectData === undefined) {
                rightsubject.innerHTML = `無當日資料`;
            } else {
                rightsubject.innerHTML = `無`;
            }
        }


        function playMusic(musicId) {
            const alarm1 = document.getElementById('alarm-sound1');
            const alarm2 = document.getElementById('alarm-sound2');
            const alarm3 = document.getElementById('alarm-sound3');

            if (musicId === "1") {
                alarm2.pause();
                alarm3.pause();
                alarm1.currentTime = 0;
                alarm1.play();
            } else if (musicId === "2") {
                alarm1.pause();
                alarm3.pause();
                alarm2.currentTime = 0;
                alarm2.play();
            } else if (musicId === "3") {
                alarm1.pause();
                alarm2.pause();
                alarm3.currentTime = 0;
                alarm3.play();
            }
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>

</html>