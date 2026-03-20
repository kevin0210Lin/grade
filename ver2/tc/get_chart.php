<?php
header('Content-Type: application/json');
require_once("../../set.php");

// 查询学生成绩
$sql = "SELECT name, `1` AS score1, `2` AS score2, `3` AS score3, `4` AS score4, ave AS average FROM `22`";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'name' => $row['name'],
            'scores' => [$row['score1'], $row['score2'], $row['score3'], $row['score4']],
            'average' => $row['average']
        ];
    }
    echo json_encode($data);
} else {
    echo json_encode("No data found");
}

// 返回JSON数据

$conn->close();
?>