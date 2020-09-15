import axios from "axios";
export const Reply = (reply) => {
    if (reply) {
        let repliesEl = document.querySelector('div#replies');
        let html =
            `<div class='card mb-4' id='reply-${reply.id}'>
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="/profile/${reply.user.name}">${reply.user.name}</a> said 
                            ${reply.date}
                            <small 
                            class="d-block font-weight-bold">${reply.favoritesCount} ${reply.favoritesCount > 1 ? 'Favorites' : 'Favorite'}</small>
                        </div>
                        ${favoriteEl(reply.isAuth, reply.isFavorite)}
                    </div>
                </div>
                <div class="card-body">${reply.body}</div>
                <div class="card-footer d-flex">
                ${updateEl(reply.canUpdate)}
                ${deleteEl(reply.canDelete)}
                </div>
            </div>`;
        html = html.replace(/^\s+/g, '').replace(/\r?\n|\r/g, '');
        repliesEl.innerHTML += html
    }
}

function updateEl(can) {
    if (can) {
        return '<button class="btn btn-primary btn-sm mr-1 edit">Edit</button>';
    }
    return '';
}

function deleteEl(can) {
    if (can) {
        return '<form class="delete"><button type="submit"' +
            'class="btn btn-danger btn-sm mr-1">Delete</button></form>';
    }
    return '';
}

function favoriteEl(isAuth, isFav) {
    if (isAuth) {
        return `<form class="favorite"><button type="submit" 
        class="btn btn-primary">${isFav ? 'Unfavorite':'Favorite'}</button></form>`;
    }
    return '';
}

let prevEl = null;
let prevData = null;
let id = null;
document.addEventListener('click', function (e) {
    if (e.target.tagName == 'BUTTON' && e.target.classList.contains('edit')) {
        loadEditView(e);
    }
});
document.addEventListener('submit', function (e) {
    if (e.target.tagName == 'FORM' && e.target.classList.contains('save')) {
        saveData(e);
    }
});

document.addEventListener('submit', function (e) {
    if (e.target.tagName == 'FORM' && e.target.classList.contains('favorite')) {
        favoriteReply(e);
    }
});

document.addEventListener('click', function (e) {
    if (e.target.tagName == 'BUTTON' && e.target.classList.contains('cancel')) {
        loadOldView();
    }
});

function loadEditView(e) {
    let card = e.target.parentElement.parentElement;
    if (!card.hasAttribute('id')) {
        return;
    }
    if (prevEl != null) {
        loadOldView();
    }
    id = card.getAttribute('id').split('-')[1];
    let footer = e.target.parentElement;
    let children = footer.children;
    let reply = e.target.parentElement.previousElementSibling;
    let body = reply.textContent;
    let textarea = document.createElement('textarea');
    textarea.className = 'px-3 pt-3 form-control';
    card.replaceChild(textarea, reply);
    textarea.value = body;
    textarea.focus();
    prevEl = textarea;
    prevData = body;

    footer.innerHTML =
        '<form class="save">' +
        '<button type="submit" class="btn btn-success btn-sm mr-1">Save</button>' +
        '</form>' +
        '<button type="submit" class="btn btn-secondary btn-sm mr-1 cancel">Cancel</button>';
}

function loadOldView() {
    if (checkErrors()) {
        prevEl.nextElementSibling.remove();
    }
    let div = document.createElement('div');
    let card = prevEl.parentElement;
    let footer = card.lastElementChild;
    div.className = 'card-body';
    div.textContent = prevData;
    card.replaceChild(div, prevEl);
    footer.innerHTML =
        `<button class="btn btn-primary btn-sm mr-1 edit">Edit</button>
         <form class='delete'>
            <button type="submit" class="btn btn-danger btn-sm mr-1">Delete</button>
         </form>`;
    prevEl = null;
    prevData = null;
    id = null;
}

function saveData(e) {
    e.preventDefault();
    let body = prevEl.value.trim();
    if (!body) {
        if (!checkErrors('reply field is required.')) {
            prevEl.classList.add('is-invalid');
            prevEl.insertAdjacentHTML('afterend', `<span 
            class="px-2 pb-1 font-weight-bold invalid-feedback">reply field is required.</span>`);
        }
    } else {
        axios.put(`/replies/${id}`, {
                'body': body
            })
            .then(response => {
                console.log(response);
                if (response.data.success) {
                    prevData = body;
                    loadOldView();
                    Flash(response.data.success);
                }
            })
            .catch(errors => {
                const error = errors.response.data;
                if (!checkErrors(error.errors.body[0])) {
                    console.log(error.errors);
                    prevEl.classList.add('is-invalid');
                    prevEl.insertAdjacentHTML('afterend', `<span 
            class="px-2 pb-1 font-weight-bold invalid-feedback">${error.errors.body[0]}</span>`);
                }
            });
    }
}

function favoriteReply(e) {
    e.preventDefault();
    let formEl = e.target;
    let card = formEl.parentElement.parentElement.parentElement;
    if (!card.hasAttribute('id')) {
        return;
    }
    let id = card.getAttribute('id').split('-')[1];
    let countEl = formEl.parentElement.firstElementChild.lastElementChild;
    let count = countEl.textContent.split(' ')[0];
    let favoriteEl = formEl.firstElementChild;
    let isfavorite = favoriteEl.textContent.trim().toLowerCase();
    if (isfavorite == 'favorite') {
        axios.post(`/replies/${id}/favorites`)
            .then(response => {
                if (response.data.success) {
                    favoriteEl.textContent = 'Unfavorite';
                    count++;
                    countEl.textContent = `${count} ${count > 1 ? 'Favorites' : 'Favorite'}`;
                    Flash(response.data.success);
                }
            })
            .catch(error => {
                console.log(`Error: ${error}`);
            });
    } else if (isfavorite == 'unfavorite') {
        axios.delete(`/replies/${id}/favorites`)
            .then(response => {
                if (response.data.success) {
                    favoriteEl.textContent = 'Favorite';
                    count--;
                    countEl.textContent = `${count} ${count > 1 ? 'Favorites' : 'Favorite'}`;
                    Flash(response.data.success);
                }
            })
            .catch(errors => {
                console.log(`Error: ${errors}`);
            });
    }
}

function checkErrors($error) {
    return prevEl.nextElementSibling.classList.contains('invalid-feedback') &&
        prevEl.nextElementSibling.textContent == $error;
}
