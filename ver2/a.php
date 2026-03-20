<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>網頁簽名功能</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        canvas {
            border: 1px solid #000;
            display: block;
            margin-bottom: 10px;
        }

        .buttons {
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>請在下方簽名：</h2>

    <canvas id="signatureCanvas" width="500" height="300"></canvas>

    <div class="buttons">
        <button onclick="clearCanvas()">清除簽名</button>
        <button onclick="saveSignature()">保存簽名</button>
    </div>

    <script src="signature.js"></script>
</body>

</html>

<script>
    let canvas = document.getElementById('signatureCanvas');
    let ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // 開始簽名
    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        lastX = e.offsetX;
        lastY = e.offsetY;
    });

    // 進行簽名
    canvas.addEventListener('mousemove', (e) => {
        if (!isDrawing) return;
        let currentX = e.offsetX;
        let currentY = e.offsetY;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
    });

    // 停止簽名
    canvas.addEventListener('mouseup', () => {
        isDrawing = false;
    });

    // 觸控支持
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        isDrawing = true;
        lastX = e.touches[0].clientX - canvas.offsetLeft;
        lastY = e.touches[0].clientY - canvas.offsetTop;
    });

    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        if (!isDrawing) return;
        let currentX = e.touches[0].clientX - canvas.offsetLeft;
        let currentY = e.touches[0].clientY - canvas.offsetTop;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
    });

    canvas.addEventListener('touchend', () => {
        isDrawing = false;
    });

    // 清除簽名
    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    // 保存簽名
    function saveSignature() {
        let dataURL = canvas.toDataURL();
        let link = document.createElement('a');
        link.href = dataURL;
        link.download = 'signature.png';
        link.click();
    }
</script>