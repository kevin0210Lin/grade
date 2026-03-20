function showSignature() {
    document.getElementById('signature').style.display = 'block';
}
function hideSignature() {
    document.getElementById('signature').style.display = 'none';
}

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