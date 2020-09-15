import axios from "axios";
const state = {};
export function SubscribeBtn(id) {
    if (document.querySelector('#sub-btn')) {
        state.threadId = id;
        let divEl = document.querySelector('#sub-btn');
        state.active = JSON.parse(divEl.getAttribute('active'));
        let subEl = document.createElement('button');
        subEl.className = 'btn btn-primary subscribe';
        subEl.textContent = state.active ? 'Unsubscribe' : 'Subscribe';
        divEl.replaceWith(subEl);
        state.subEl = subEl;
    }
}
document.addEventListener('click', function (e) {
    if (e.target == state.subEl) {
        subscribe(e);
    }
});

function subscribe(e) {
    const subEl = e.target;
    axios[(state.active ? 'delete' : 'post')](`/threads/${state.channelId}/${state.threadId}/subscriptions`)
        .then(response => {
            if (response.status == 200) {
                state.active = state.active ? false : true;
                Flash(`${subEl.textContent}d`);
                subEl.textContent = state.active ? 'Unsubscribe' : 'Subscribe';
                state.subEl = subEl;
            }
        })
        .catch(errors => {
            console.log(errors);
        });
}

window.SubscribeBtn = SubscribeBtn;
