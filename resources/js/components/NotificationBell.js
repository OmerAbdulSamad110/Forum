import axios from "axios";
const state = {
    notificationId: []
};
export async function NotificationBell() {
    if (document.querySelector('div.dropdown-menu.notify')) {
        let html = '';
        let divEl = document.querySelector('div.dropdown-menu.notify');
        await getNotifications();
        if (state.notifications.length > 0) {
            for (const notification of state.notifications) {
                state.notificationId.push(notification.id);
                html += `
                    <a href=${notification.data.link}
                    class="dropdown-item">${notification.data.message}</a>    
                `;
            }
        } else {
            html = `<a href="#" class="dropdown-item disabled">No Notifications</a>`;
        }
        divEl.innerHTML = html;
    }
}

const bellBtn = document.querySelector('a#notifications');
const parentEl = bellBtn.nextElementSibling;

document.addEventListener('click', function (e) {
    if (e.target.parentElement == parentEl) {
        markAsRead(e);
    }
})


async function getNotifications() {
    await axios.get('/notifications')
        .then(response => {
            if (response.status == 200) {
                state.notifications = response.data;
            }
        })
        .catch(errors => {
            console.log(errors);
        });
}

function markAsRead(e) {
    const index = [].indexOf.call(parentEl.children, e.target);
    const id = state.notificationId[index];
    axios.get(`/notifications/${id}/read`);
}
window.NotificationBell = NotificationBell;
