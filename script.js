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


async function getRequest(url, params) {
    const urlParams =  new URLSearchParams(params && { });
    const newUrl = url + '?' + urlParams;

    const response = await fetch(newUrl);
    const json = await response.json();

    return json;
}

function fillContent() {
    getRequest("content.php", {})
        .then(response => {
            const table = document.querySelector('.content-rows');

            response.forEach(el => {
                table.innerHTML += `
                <tr class="clickable">
                <td>${el.VALUE}</td>
                <td>${el.DATE_END}</td>
                <td>${el.DISPOSABLE ? 'Да' : 'Нет'}</td>
                <td>${el.RESTAURANT_NAME}</td>
                <td>${el.EXTRA_NAME}</td>
                <td onclick="console.log('works')">${svgRemove}</td>
                </tr>`;
            });
        });
}

function resetInputContent() {

}

async function handleClick() {
    const res = await getRequest("content.php", {
        foo: 1,
        bar: "fee"
    });

    console.log(res);
}

async function postRequest(url, params) {

}

function hideInputData() {
    const elements = document.querySelector('.content-editor-panel').children;

    for(let i = 0; i < elements.length; i++) {
        elements[i].style.visibility = 'collapse';
    }

    resetInputContent();
}

function showInputData() {
    const elements = document.querySelector('.content-editor-panel').children;

    for(let i = 0; i < elements.length; i++) {
        elements[i].style.visibility = 'visible';
    }
}

function getElementByName(name) {
    content = document.querySelector(`input[name="${name}"]`);

    return content;
}

function getContentInputData() {
    const value = getElementByName('value').value;
    const dateEnd = getElementByName('dateEnd').value;
    const disposable = getElementByName('disposable').value;
    const restaurantId = getElementByName('restaurantId').value;
    const extraId = getElementByName('extraId').value;

    const obj = {
        VALUE: value,
        DATE_END: dateEnd,
        DISPOSABLE: disposable,
        EXTRA_ID: extraId,
        RESTAURANT_ID: restaurantId
    }

    console.log(obj);

    return obj;
}

fillContent();
hideInputData();