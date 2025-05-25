// 设计一个局中文字弹窗函数，3秒后自动关闭
function showDialog(text) {
    let dialog = document.createElement("dialog");
    dialog.innerHTML = text;
    document.body.appendChild(dialog);
    dialog.showModal();
    setTimeout(() => dialog.close(), 3000);
}