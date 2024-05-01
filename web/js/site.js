const nodes = {
    inputBlock: document.getElementById('input-block'),
    waitBlock: document.getElementById('wait-block'),
    linkBlock: document.getElementById('link-block'),

    inputElem: document.getElementById('input-file'),
    dropZone: document.getElementById('input-form'),
    inputError: document.getElementById('input-form-error'),

    progressBarWrapper: document.querySelector('.progress'),
    progressBar: document.getElementById('progress-bar'),
    waitStatus: document.getElementById('wait-status'),
    waitSpinner: document.getElementById('wait-spinner'),

    linkButton: document.getElementById('link-button'),
    loadMoreButton: document.getElementById('load-more'),
};

function showInputBlock() {
    nodes.inputBlock.classList.remove('d-none');

    nodes.waitBlock.classList.add('d-none');
    nodes.linkBlock.classList.add('d-none');

    nodes.inputElem.value = '';
}

function showWaitBlock() {
    nodes.waitBlock.classList.remove('d-none');

    nodes.inputBlock.classList.add('d-none');
    nodes.linkBlock.classList.add('d-none');

    nodes.progressBarWrapper.classList.remove('d-none');
    nodes.progressBar.style.width = '0';
    nodes.waitSpinner.classList.add('d-none');
}

function showLinkBlock(link) {
    nodes.linkBlock.classList.remove('d-none');

    nodes.inputBlock.classList.add('d-none');
    nodes.waitBlock.classList.add('d-none');

    nodes.linkButton.href = link;
}

function showError(message) {
    showInputBlock();
    nodes.inputError.innerText = message;
    nodes.inputError.classList.remove('d-none');
}

function onSelectFile(file) {
    if (!file || file.type !== 'application/pdf') {
        showError('Недопустимый файл');
        return;
    }

    nodes.inputError.classList.add('d-none');

    const formData = new FormData();
    formData.set('file', file);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/upload');
    xhr.responseType = 'json';

    xhr.onload = function () {
        if (xhr.status != 200) {
            showError(`Ошибка ${xhr.status}: ${xhr.statusText}`);
        } else {
            if (!xhr.response.success) {
                showError(xhr.response.message);
            } else {
                showLinkBlock(xhr.response.link);
            }
        }
    };
    xhr.onerror = function () {
        showError('Проблема с соединением');
    };

    xhr.upload.onprogress = function (event) {
        nodes.progressBar.style.width = (event.loaded / event.total) * 100 + '%';
    };
    xhr.upload.onload = function () {
        nodes.progressBarWrapper.classList.add('d-none');
        nodes.waitStatus.innerText = 'Обработка';
        nodes.waitSpinner.classList.remove('d-none');
    };
    xhr.upload.onerror = function () {
        showError('Ошибка загрузки');
    };

    xhr.send(formData);

    showWaitBlock();
    nodes.waitStatus.innerText = 'Загрузка';
}

if (nodes.inputElem) {
    nodes.inputElem.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            onSelectFile(files[0]);
        }
    });
}

if (nodes.dropZone) {
    let hoverClassName = 'hover';

    nodes.dropZone.addEventListener("dragenter", function (e) {
        e.preventDefault();
        nodes.dropZone.classList.add(hoverClassName);
    });

    nodes.dropZone.addEventListener("dragover", function (e) {
        e.preventDefault();
        nodes.dropZone.classList.add(hoverClassName);
    });

    nodes.dropZone.addEventListener("dragleave", function (e) {
        e.preventDefault();
        nodes.dropZone.classList.remove(hoverClassName);
    });

    nodes.dropZone.addEventListener("drop", function (e) {
        e.preventDefault();
        nodes.dropZone.classList.remove(hoverClassName);

        const files = Array.from(e.dataTransfer.files);
        if (files.length > 0) {
            onSelectFile(files[0]);
        }
    });
}

if (nodes.loadMoreButton) {
    nodes.loadMoreButton.addEventListener('click', () => {
        showInputBlock();
    });
}