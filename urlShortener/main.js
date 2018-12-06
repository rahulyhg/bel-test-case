//В случае если бы проект был больше, уместно было бы использовать модули

document.querySelector('#js-url-form').addEventListener('submit', (e) => {
    e.preventDefault();

    let url = document.getElementById("js-url").value;

    let data = new FormData();
    data.append('requestType', 'uploadUrl');
    data.append('url', url);

    let oReq = new XMLHttpRequest();
    oReq.open('post', './app.php', true);
    oReq.send(data);
    oReq.onload = (oReq) => {
        if (oReq.target.responseText == '') {
            //что-то пошло не так на стороне сервера
            console.log('server error');
        }
        // добавим укороченную ссылку на страницу
        let el = document.createElement('li');
        el.innerHTML = oReq.target.responseText;
        document.querySelector('#js-url-list').appendChild(el);
    };
    oReq.onerror = (err) => {
        console.log('Ajax Error :-S', err);
    };
}, false);

let hash = window.location.pathname.substr(1);

if (hash != "") {
    redirect = (hash) => {
        let data = new FormData();
        data.append('requestType', 'getUrl');
        data.append('hash', hash);

        let oReq = new XMLHttpRequest();
        oReq.open('post', './app.php', true);
        oReq.send(data);

        oReq.onload = (oReq) => {
            if (oReq.target.responseText == '') {
                //что-то пошло не так на стороне сервера
                console.log('server error');
            } else if( oReq.target.responseText == '0') {
                // если сервер вернул код ошибки с запроса на несуществующую
                // cсылку - редиректим его на домашнюю страницу
                alert('Несуществующая ссылка!')
                window.location.href = '/';
            } else {
                // редирект по ссылке
                window.location.href = oReq.target.responseText;
            }
        };
        oReq.onerror = (err) => {
            console.log('Ajax Error :-S', err);
        }
    };
    redirect(hash);
};

