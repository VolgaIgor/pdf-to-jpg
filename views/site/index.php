<?php

/** @var yii\web\View $this */

$this->title = 'PDF to JPG';
?>
<main id="main" role="main">
    <h1>PDF to JPG</h1>
    <div id="input-block">
        <div id="input-form-error" class="text-danger-emphasis d-none"></div>
        <form id="input-form" method="post" enctype="multipart/form-data">
            <label id="input-label">Выберите или перетащите файл</label>
            <input id="input-file" type="file" name="file" accept=".pdf">
        </form>
    </div>
    <div id="wait-block" class="d-none">
        <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
            <div id="progress-bar" class="progress-bar progress-bar-striped" style="width: 50%"></div>
        </div>
        <div id="wait-spinner" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div id="wait-status"></div>
    </div>
    <div id="link-block" class="d-none">
        <a id="link-button" href="#" download>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
            </svg>
            <span>Скачать архив</span>
        </a>
        <div class="form-text">Ссылка будет активна 10 минут</div>
        <button id="load-more" class="btn btn-sm btn-link">Загрузить ещё…</div>
    </div>
</main>