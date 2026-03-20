function updateField(id, field, value, tcName) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // 获取 CSRF Token
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // 显示 Toast 提示框，返回的消息中包括字段名和更新成功的提示
            showToast(`${tcName} - ${field} 更新成功`);
        }
    };
    xhr.send(`id=${id}&field=${field}&value=${encodeURIComponent(value)}&csrf_token=${csrfToken}`);
}

document.addEventListener('DOMContentLoaded', () => {



    const inputs = document.querySelectorAll('input[data-field]');
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            const id = input.dataset.id;
            const field = input.dataset.field;
            const value = input.value;

            // 获取教师名称（原来的值）
            const row = input.closest('tr'); // 获取所在的行
            const oldTcName = row.querySelector('td input[data-field="name"]').value; // 获取原教师名称（假设名称字段是第一个）

            // 更新所有相关 input 的 name 属性
            const inputsInRow = row.querySelectorAll('input');
            inputsInRow.forEach(inputInRow => {
                const fieldInRow = inputInRow.dataset.field;
                inputInRow.setAttribute('name', `${oldTcName}-${fieldInRow}`);
            });

            // 如果教师名称有变化，更新名称字段的 name 属性
            if (field === 'name') {
                const newTcName = value; // 获取新的教师名称
                row.querySelector('td input[data-field="name"]').setAttribute('name', `${newTcName}-name`);
                row.querySelector('td input[data-field="id"]').setAttribute('name', `${newTcName}-id`);
                row.querySelector('td input[data-field="password"]').setAttribute('name', `${newTcName}-password`);
            }

            // 调用更新函数
            updateField(id, field, value, oldTcName);
        });
    });
});

function foremail(){
    var newid = document.getElementById('newid').value;
    document.getElementById('newemail').value = newid + '@mail.lhvs.tyc.edu.tw';
}





// Toast 功能实现
function showToast(message) {
    const container = document.querySelector('.toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;

    container.appendChild(toast);

    // 自动移除 Toast
    setTimeout(() => {
        container.removeChild(toast);
    }, 4500); // 持续 4.5 秒
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}




// 新增老師視窗
function openaddModal() {
    document.getElementById('addTeacherModal').style.display = 'block';
}
function closeaddModal() {
    document.getElementById('addTeacherModal').style.display = 'none';
}
function submitAddTeacherForm() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // 获取 CSRF Token
    const formData = new FormData(document.getElementById('addTeacherForm'));
    formData.append('csrf_token', csrfToken);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_teacher_handler.php', true); // 将请求发送到处理新增教师的 PHP 脚本
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                alert(xhr.responseText); // 提示新增结果
                location.reload(); // 刷新页面以更新教师列表
            } else {
                alert('新增失敗，請稍後再試。'); // 错误提示
            }
        }
    };
    xhr.onerror = function () {
        alert('請求錯誤，請檢察網路連線。');
    };
    xhr.send(formData);
}




//更換老師視窗
function openchangeModal() {
    const button = event.currentTarget;
    const tcClass = button.getAttribute('tcclass');
    const tcName = button.getAttribute('tcname');
    document.querySelector('input[name="oldclassNum"]').value = tcClass;
    document.querySelector('input[name="oldteachername"]').value = tcName;
    document.getElementById('changeTeacherModal').style.display = 'block';
}
function closechangeModal() {
    document.getElementById('changeTeacherModal').style.display = 'none';
}
function submitchangeTeacherForm() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // 获取 CSRF Token
    const formData = new FormData(document.getElementById('changeTeacherForm'));
    formData.append('csrf_token', csrfToken);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'change_teacher_handler.php', true); // 将请求发送到处理新增教师的 PHP 脚本
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                alert(xhr.responseText); // 提示新增结果
                location.reload(); // 刷新页面以更新教师列表
            } else {
                alert('更新失敗，請稍後再試。'); // 错误提示
            }
        }
    };
    xhr.onerror = function () {
        alert('請求錯誤，請檢察網路連線。');
    };
    xhr.send(formData);
}


//刪除老師視窗
function opendeleteModal() {
    // 使用 currentTarget 確保指向按鈕
    const button = event.currentTarget;
    // 取得按鈕的 tcclass 和 tcname 屬性值
    const tcClass = button.getAttribute('tcclass');
    const tcName = button.getAttribute('tcname');
    // 設定隱藏輸入框的值
    document.querySelector('input[name="oldclassNumdelete"]').value = tcClass;
    document.querySelector('input[name="oldteachernamedelete"]').value = tcName;
    // 顯示模態框
    document.getElementById('TeachersetModal').style.display = 'none';
    document.getElementById('deleteTeacherModal').style.display = 'block';
}
function closedeleteModal() {
    document.getElementById('deleteTeacherModal').style.display = 'none';
    document.getElementById('TeachersetModal').style.display = 'block';
}
function submitdeleteTeacherForm() {
    const deletecheck = confirm("確定要刪除此帳號?");
    if (deletecheck) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // 获取 CSRF Token
        const formData = new FormData(document.getElementById('deleteTeacherForm'));
        formData.append('csrf_token', csrfToken);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_teacher_handler.php', true); // 将请求发送到处理新增教师的 PHP 脚本
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    alert(xhr.responseText); // 提示新增结果
                    location.reload(); // 刷新页面以更新教师列表
                } else {
                    alert('刪除失敗，請稍後再試。'); // 错误提示
                }
            }
        };
        xhr.onerror = function () {
            alert('請求錯誤，請檢察網路連線。');
        };
        xhr.send(formData);
    }
}



function openteachersetModal() {
    // 使用 currentTarget 確保指向按鈕
    const button = event.currentTarget;

    // 取得按鈕的 tcclass 和 tcname 屬性值
    const tcClass = button.getAttribute('tcclass');
    const tcName = button.getAttribute('tcname');
    const tcaccount = button.getAttribute('tcaccount');

    for (let i = 1; i <= 9; i++) {
        const checkboxValue = button.getAttribute(i.toString());
        const checkbox = document.querySelector(`input[name="setsubject${i}"]`);
        checkbox.checked = checkboxValue === '1';
    }

    document.querySelector('input[name="oldteachername1"]').value = tcName;
    document.querySelector('input[name="newteachername1"]').value = tcName;
    document.querySelector('input[name="oldteacheraccount1"]').value = tcaccount;
    document.querySelector('input[name="newteacheraccount1"]').value = tcaccount;

    document.getElementById('reserpass').setAttribute('tcaccount', tcaccount);
    document.getElementById('reserpass').setAttribute('tcName', tcName);

    document.getElementById('deleteaccount').setAttribute('tcclass', tcClass);
    document.getElementById('deleteaccount').setAttribute('tcName', tcName);
    document.getElementById('deleteaccount').setAttribute('tcaccount', tcaccount);

    // 顯示模態框
    document.getElementById('TeachersetModal').style.display = 'block';
}
function closeTeachersetModal() {
    document.getElementById('TeachersetModal').style.display = 'none';
}
function submitTeachersetForm() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // 获取 CSRF Token
    const formData = new FormData(document.getElementById('TeachersetForm'));
    formData.append('csrf_token', csrfToken);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'set_teacher_handler.php', true); // 将请求发送到处理新增教师的 PHP 脚本
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                alert(xhr.responseText); // 提示新增结果
                location.reload(); // 刷新页面以更新教师列表
            } else {
                alert('刪除失敗，請稍後再試。'); // 错误提示
            }
        }
    };
    xhr.onerror = function () {
        alert('請求錯誤，請檢察網路連線。');
    };
    xhr.send(formData);
}


function resetTeacherPassword(event) {
    let resetcheck = confirm("是否要重置密碼?");

    if (resetcheck) {
        const button = event.currentTarget;
        const tcaccount = button.getAttribute('tcaccount');
        const tcname = button.getAttribute('tcname');

        const xhr = new XMLHttpRequest();

        xhr.open('GET', 'resetpassword.php?tcaccount=' + encodeURIComponent(tcaccount) + '&tcname=' + encodeURIComponent(tcname), true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                alert(xhr.responseText); // 提示新增结果
            } else {
                alert('Request failed with status: ' + xhr.status);
            }
        };

        xhr.onerror = function () {
            alert('請求錯誤，請檢察網路連線。');
        };
        xhr.send();
    }
}



