<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List elements</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&subset=cyrillic">
    <style>
        body {
            font-family: "Montserrat";
            font-size: 14px;
            padding: 0 20px;
        }

        .list-table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
            overflow-x:auto;
        }
        td, th {
            border: 1px solid #333;
            padding: 2px;
        }

        .error{
            color: firebrick;
            text-align: center;
        }

        .link {
            margin: 10px 0;
            /*display: flex;
            align-items: center;
            justify-content: space-around;*/
        }

        .pages {
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pages a {
            color: blue;
            text-decoration: none;
        }

        .pages a:hover{
            text-decoration: underline;
        }

        .pages .p-item {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid blue;
            margin: 0 3px;
        }

        .pages .page-number {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .pages .page-number .num {
            margin: 3px;
        }

        .pages .page-number .num a.active{
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="list">
    <h3>Список продуктов</h3>
    <div class="link">
        <a href="/">Импорт данных</a>
    </div>
    <div class="pages">
        <div class="p-item goBack"><a href=""><</a></div>
        <div class="p-item page-number"></div>
        <div class="p-item next"><a href="">></a></div>
    </div>
    <div class="error"></div>
    <table class="list-table" id="listTable">
        <thead>
        <tr>
            <th>Id</th>
            <th>Код</th>
            <th>Наименование</th>
            <th>Уровень1</th>
            <th>Уровень2</th>
            <th>Уровень3</th>
            <th>Цена</th>
            <th>ЦенаСП</th>
            <th>Количество</th>
            <th>Поля свойств</th>
            <th>Совместные покупки</th>
            <th>Единица измерения</th>
            <th>Картинка</th>
            <th>Выводить на главной</th>
            <th>Описание</th>
            <th>Создан</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
</body>
<script>
    const table = document.getElementById('listTable');
    const error = document.querySelector('.error');
    const pageNumber = document.querySelector('.page-number');
    const back = document.querySelector('.goBack a')
    const next = document.querySelector('.next a')
    let page = 1
    let totalPages = 100
    let tbody = ''

    back.addEventListener('click', function (event){
        event.preventDefault();
        if(page - 1 > 0){
            page--;
            list();
        }
    })

    next.addEventListener('click', function (event){
        event.preventDefault();
        if(page + 1 <= totalPages){
            page++;
            list();
        }
    })

    function setNavigation(){
        let numbers = '';
        for (let i = 1; i <= totalPages; i++) {
            let a = '';
            if(page == i)
                a = 'class="active"';
            numbers += '<div class="num"><a href="" '+a+'">'+i+'</a></div>';
        }
        pageNumber.innerHTML = numbers;
        let number = pageNumber.querySelectorAll('.page-number a');
        number.forEach(function(num){
            num.addEventListener('click', function (event){
                event.preventDefault();
                page = this.innerHTML;
                list();
            })
        })
    }

    function list() {
        fetch('api/?page=' + page, {method: 'GET'}).then(data => data.text())
            .then(response => {
                const data = JSON.parse(response);
                if (data.products.length > 0) {
                    tbody = ''
                    table.tBodies[0].innerHTML = ''
                    for (const prod of data.products) {
                        const row = document.createElement("tr");
                        for (const p in prod) {
                            const cell = document.createElement("td");
                            cell.style.textAlign = 'center';
                            const cellText = document.createTextNode(prod[p]);
                            cell.appendChild(cellText);
                            row.appendChild(cell);
                        }
                        table.tBodies[0].appendChild(row);
                        totalPages = data.pages;
                        setNavigation();
                    }

                } else if (typeof response.res != 'undefined') {
                    error.innerHTML = 'An unexpected error has happened.'
                }else {
                    const row = document.createElement("tr");
                    const cell = document.createElement("td");
                    cell.colSpan = 16;
                    cell.style.textAlign = 'center';
                    const cellText = document.createTextNode('No data');
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                    table.tBodies[0].appendChild(row);
                }
            }).catch(e => {
            error.innerHTML = 'An unexpected error has happened.'
        })
    }

    list()
</script>
</html>
