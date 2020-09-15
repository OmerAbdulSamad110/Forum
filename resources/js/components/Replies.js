import axios from "axios";
import {
    NewReply
} from "./NewReply";
import {
    Reply
} from './Reply';
import {
    Paginator
} from './Paginator';
const state = {
    repliesCount: 0,
    page: 1
};
NewReply();
export async function Replies(ids = null) {
    if (!state.threadId && !state.channelId && ids) {
        state.threadId = ids['threadId'];
        state.channelId = ids['channelId'];
    }
    if (document.querySelector('div#replies')) {
        await repliesData(state.page);
        if (state.replies) {
            let replies = state.replies;
            for (let reply of replies) {
                Reply(reply);
            }
            Paginator(state.data);
        } else {
            checkRepliesCount();
        }
    }
}

document.addEventListener('submit', function (e) {
    if (e.target.tagName == 'FORM' && e.target.classList.contains('new-reply')) {
        addNewReply(e);
    }
});

document.addEventListener('submit', function (e) {
    if (e.target.tagName == 'FORM' && e.target.classList.contains('delete')) {
        removeReply(e);
    }
});

function addNewReply(e) {
    e.preventDefault();
    const bodyEl = e.target.firstElementChild.firstElementChild;
    const body = bodyEl.value.trim();
    if (!body) {
        bodyEl.classList.add('is-invalid');
        bodyEl.insertAdjacentHTML('afterend', `<span 
        class="px-2 pb-1 font-weight-bold 
        invalid-feedback">reply field is required.</span>`);
    } else {
        axios.post(`/threads/${state.channelId}/${state.threadId}/replies`, {
                'body': body
            })
            .then(response => {
                console.log(response);
                if (response.data.success) {
                    bodyEl.value = '';
                    Reply(response.data.reply);
                    state.repliesCount++;
                    checkRepliesCount();
                    Flash(response.data.success);
                }
            })
            .catch(errors => {
                const error = errors.response.data;
                console.log(error.errors);
                bodyEl.classList.add('is-invalid');
                bodyEl.insertAdjacentHTML('afterend', `<span 
                class="px-2 pb-1 font-weight-bold invalid-feedback">${error.errors.body[0]}</span>`);
            });
    }
}

async function repliesData(page) {
    await axios.get(`/threads/${state.channelId}/${state.threadId}/replies?page=${page}`)
        .then(({
            data
        }) => {
            // console.log(data);
            if (data.data.length > 0) {
                state.repliesCount = data.data.length;
                state.data = data;
                state.replies = data.data;
            }
        })
        .catch(errors => {
            console.log(errors);
        });
}

function removeReply(e) {
    e.preventDefault();
    let card = e.target.parentElement.parentElement;
    if (!card.hasAttribute('id')) {
        return;
    }
    let id = card.getAttribute('id').split('-')[1];
    axios.delete(`/replies/${id}`)
        .then(response => {
            if (response.data.success) {
                card.remove();
                state.repliesCount--;
                checkRepliesCount();
                Flash(response.data.success);
            }
        })
        .catch(errors => {
            console.log(`Error: ${errors}`);
        });
}

function checkRepliesCount() {
    if (state.repliesCount == 0) {
        noReplyView();
    } else if (state.repliesCount == 1 && document.querySelector('div.card.card-body')) {
        document.querySelector('div.card.card-body').remove();
    }
}

function noReplyView() {
    const repliesEl = document.querySelector('div#replies');
    repliesEl.innerHTML = '<div class="card card-body"><h5 class="card-text">' +
        'No Replies</h5></div>';
}

document.addEventListener('changePg', function (e) {
    state.page = e.detail.page;
    document.querySelector('div#replies').innerHTML = '';
    document.querySelector('div#replies').insertAdjacentHTML('beforeend', '');
    Replies();
});

window.Replies = Replies;
