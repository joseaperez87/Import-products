<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Import CSV file</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&subset=cyrillic">
    <style>
        body {
            font-family: "Montserrat";
            font-size: 14px;
        }

        .csv-form {
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        .import-res {
            margin-top: 10px;
        }

        .not-inserted-codes, .skipped-codes {
            display: none;
        }

        #toggleList {
            margin-top: 5px;
        }

        #toggleList:hover, #toggleSkipped:hover {
            cursor: pointer;
            color: dodgerblue;
        }

        .error {
            color: firebrick;
        }

        input, button {
            padding: 10px;
            border: 1px solid blue;
            border-radius: 5px;
        }

        button {
            color: #fff;
            background: dodgerblue;
            font-weight: 500;
        }
    </style>
</head>
<body>
<form id="csv-form" class="csv-form" enctype="multipart/form-data">
    <h3>Импортные товары</h3>
    <label for="csv-input"><strong>Выберите свой файл</strong></label>
    <input type="file" id="csv-input" class="input-file" accept="text/csv"/>
    <button id="import-button">Импорт</button>
    <div class="import-res"></div>
    <div class="error"></div>
    <p><a href="list.php">Список продуктов</a></p>
</form>
</body>
<script>
    const form = document.getElementById('csv-form');
    const button = document.getElementById('import-button');
    const inputFile = document.getElementById("csv-input");
    const importRes = document.querySelector('.import-res');
    const importError = document.querySelector('.error');
    form.addEventListener('submit', function (e) {
        e.preventDefault()
        let data = new FormData();
        if (inputFile.files.length > 0) {
            button.disabled = true;
            data.append("csv", inputFile.files[0]);
            importRes.innerHTML = 'Обрабатывающий файл ...';
            importError.innerHTML = '';
            fetch('api/', {method: 'POST', body: data})
                .then(data => data.text())
                .then(response => {
                    const data = JSON.parse(response)
                    importRes.innerHTML = '';
                    if (data.res) {
                        let html = '<p><strong>Общее количество найденных предметов: </strong>' + data.totalRows + '</p>' +
                            '<p><strong>Общее количество вставленных элементов: </strong>' + data.totalInserted + '</p>' +
                            '<p><strong>Общее количество элементов, которые уже существуют: </strong>' + data.skipped.length + '</p>';

                        if (data.skipped.length > 0) {
                            html += '<small id="toggleSkipped">Показать/скрыть список</small>' +
                                '<div class="skipped-codes">' +
                                '<ol>';
                            for (const code of data.skipped) {
                                html += '<li>' + code + '</li>';
                            }
                            html += '</ol></div>';
                        }
                        html += '<p><strong>Общее количество элементов с ошибкой: </strong>' + data.notInsertedCodes.length + '</p>';
                        if (data.notInsertedCodes.length > 0) {
                            html += '<small id="toggleList">Показать/скрыть список</small>' +
                                '<div><strong>Список пропущенных элементов:</strong></div>' +
                                '<div class="not-inserted-codes">' +
                                '<ol>';
                            for (const code of data.notInsertedCodes) {
                                html += '<li>' + code + '</li>';
                            }
                            html += '</ol></div>';
                        }
                        importRes.innerHTML = html
                        if (data.notInsertedCodes.length > 0)
                            document.getElementById('toggleList').addEventListener('click', toggleList)
                        if (data.skipped.length > 0)
                            document.getElementById('toggleSkipped').addEventListener('click', toggleSkipped)
                    } else {
                        importError.innerHTML = data.message;
                    }
                    button.disabled = false;
                }).catch(ex => {
                console.log(ex)
                importError.innerHTML = 'An unexpected error has happened.';
            })
        } else {
            importError.innerHTML = 'Вы должны выбрать csv-файл.';
        }
    })

    function toggleList() {
        const list = document.querySelector('.not-inserted-codes');
        if (list.style.display == 'block')
            list.style.display = 'none'
        else
            list.style.display = 'block'
    }

    function toggleSkipped() {
        const list = document.querySelector('.skipped-codes');
        if (list.style.display == 'block')
            list.style.display = 'none'
        else
            list.style.display = 'block'
    }
</script>
</html>
