// SVG-иконка для удаления записи
const svgRemove = `
    <svg
        width="24"
        height="24"
        viewBox="0 0 24 24"
        style="color: red";
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            d="M8 11C7.44772 11 7 11.4477 7 12C7 12.5523 7.44772 13 8 13H16C16.5523 13 17 12.5523 17 12C17 11.4477 16.5523 11 16 11H8Z"
            fill="currentColor"
        />
        <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12ZM21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
            fill="currentColor"
        />
    </svg>`;

// Объект с элементами ввода данных
const inputData = {
    value: getElementByName('value'),      // Получение значения
    dateEnd: getElementByName('dateEnd'),  // Получение даты окончания
    disposable: getElementByName('disposable'),  // Получение признака одноразовости
    restaurantId: getElementByName('restaurantId'),  // Получение идентификатора ресторана
    extraId: getElementByName('extraId'),  // Получение идентификатора дополнительных элементов
};

// Массивы для хранения ресторанов и дополнительных элементов
const restaurants = []; // Массив для хранения ресторанов
const extras = []; // Массив для хранения дополнительных элементов

// Тип модификации (добавление или изменение) и выбранный идентификатор
let modifyType = ""; // Тип модификации
let selectedId = "null"; // Выбранный идентификатор

// Асинхронные функции запросов к серверу

// GET запрос
async function getRequest(url, params) {
    const urlParams =  new URLSearchParams(params || { });
    const newUrl = url + '?' + urlParams;

    const response = await fetch(newUrl);
    const json = await response.json();

    return json;
}

// DELETE запрос
async function deleteRequest(url, params) {
    const urlParams =  new URLSearchParams(params || { });
    const newUrl = url + '?' + urlParams;

    const response = await fetch(newUrl, {
        method: "DELETE",
    });

    const json = await response.json();

    return json;
}

// POST запрос
async function postRequest(url, params) {
    const urlParams =  new URLSearchParams(params || { });
    const newUrl = url + '?' + urlParams;
    const response = await fetch(newUrl, {
        method: "POST",
    });

    const json = await response.json();
    return json;
}

// Функция удаления контента
async function removeContent(id) {
    const conf = confirm('Вы действительно хотите удалить запись?');

    if(conf) {
        const res = await deleteRequest("content.php", {
            ID: id
        });

        if (res.ERROR) {
            alert(res.ERROR);
            return;
        }

        await fetchContent();
        hideInputData();
    }
}

// Функция отправки данных контента на сервер
async function postContent() {
    const data = getContentInputData();
    const body = {
        ID: selectedId,
        TYPE: modifyType,
        ...data
    }
    console.log(body);
    const res = await postRequest("content.php", body);

    if (res.ERROR) {
        alert(res.ERROR);
        return;
    }

    fetchContent();
}

// Снятие выделения с выбранной записи
function disableSelection() {
    const children = document.querySelector('.content-rows').children;

    for(let i = 0; i < children.length; i++) {
        children[i].classList.remove('row-selected');
    }
}

// Выбор контента для редактирования/просмотра
function selectContent(id, row) {
    selectedId = id;
    modifyType = "edit";
    const rowVal = JSON.parse(row.getAttribute("row"));

    disableSelection();
    row.classList.add('row-selected');

    inputData.value.value = rowVal.VALUE;
    inputData.dateEnd.value = rowVal.DATE_END;
    inputData.disposable.checked = rowVal.DISPOSABLE;
    inputData.extraId.value = rowVal.EXTRA_ID;
    inputData.restaurantId.value = rowVal.RESTAURANT_ID;

    setInputModifyType("edit");
    showInputData();
}

// Получение и отображение контента
async function fetchContent() {
    const table = document.querySelector('.content-rows');
    table.innerHTML = "";

    await getRequest("content.php", {})
    .then(response => {
        console.log(response);
        response.forEach(el => {
            table.innerHTML += 
            <tr class="clickable" onclick="selectContent(${el.ID}, this);" row='${JSON.stringify(el)}'>
            <td>${el.VALUE}</td>
            <td>${el.DATE_END}</td>
            <td>${el.DISPOSABLE ? 'Да' : 'Нет'}</td>
            <td>${el.RESTAURANT_NAME}</td>
            <td>${el.EXTRA_NAME || ''}</td>
            <td onclick="removeContent(${el.ID})">${svgRemove}</td>
            </tr>;
        });
    });
}

// Заполнение списка ресторанов и дополнительных элементов
function fillContent() {
    fetchContent();

    getRequest("content.php", {
            GET_TYPE: 'RESTAURANTS',
        }).then(response => {
            const restSelect = inputData.restaurantId;

            restSelect.innerHTML += <option value="null" selected>Не выбран</option>;

            response.forEach(el => {
                restSelect.innerHTML += <option value="${el.ID}">${el.NAME}</option>;
            });
        });

    getRequest("content.php", {
        GET_TYPE: 'EXTRAS',
    }).then(response => {
        const extraSelect = inputData.extraId;

        extraSelect.innerHTML += <option value="null" selected>Не выбран</option>;

        response.forEach(el => {
            extraSelect.innerHTML += <option value="${el.ID}">${el.NAME}</option>;
        });
    });
}

// Сброс введенных данных
function resetInputContent() {
    inputData.value.value = "";
    inputData.dateEnd.value = "";
    inputData.extraId.value = "null";
    inputData.restaurantId.value = "null";
    inputData.disposable.value = false;
}

// Скрытие данных ввода контента
function hideInputData() {
    const elements = document.querySelector('.content-editor-panel').children;

    for(let i = 0; i < elements.length; i++) {
        elements[i].style.visibility = 'collapse';
    }

    resetInputContent();
    disableSelection();
}

// Отображение данных ввода контента
function showInputData() {
    const elements = document.querySelector('.content-editor-panel').children;

    for(let i = 0; i < elements.length; i++) {
        elements[i].style.visibility = 'visible';
    }
}

// Установка типа модификации (добавление или изменение)
function setInputModifyType(type) {
    if (type === "edit") {
        document.querySelector(".content-editor-panel a").innerHTML = "Изменение записи";
        document.querySelector("button[name='addEditBtn']").innerHTML = "Изменить";
        modifyType = "edit";
    } else {
        document.querySelector(".content-editor-panel a").innerHTML = "Добавление записи";
        document.querySelector("button[name='addEditBtn']").innerHTML = "Добавить";
        modifyType = "add";
    }
}

// Получение HTML-элемента по имени
function getElementByName(name) {
    content = document.querySelector([name="${name}"]);

    return content;
}

// Получение данных ввода контента
function getContentInputData() {
    const value = getElementByName('value').value;
    const dateEnd = getElementByName('dateEnd').value;
    const disposable = getElementByName('disposable').checked;
    const restaurantId = getElementByName('restaurantId').value;
    const extraId = getElementByName('extraId').value;

    const obj = {
        VALUE: value,
        DATE_END: dateEnd,
        DISPOSABLE: disposable,
        EXTRA_ID: extraId,
        RESTAURANT_ID: restaurantId
    };

    return obj;
}

// Заполнение контента и скрытие данных ввода при загрузке страницы
fillContent();
hideInputData();