const state = {
    page: 1,
    prevUrl: '',
    nextUrl: '',
    totalPages: 1
};
export function Paginator(data) {
    state.page = data.current_page;
    state.prevUrl = data.prev_page_url;
    state.nextUrl = data.next_page_url;
    state.totalPages = data.last_page;
    if (state.prevUrl || state.nextUrl) {
        const repliesEl = document.querySelector('div#replies');
        const pageEl = document.createElement('nav');
        pageEl.className = 'change-replies';
        pageEl.innerHTML = `
    <ul class="pagination">
        ${state.prevUrl ?`<li class="page-item">
        <a class="page-link" href="#" aria-label="Previous" rel="prev">
            &laquo;
          </a>
        </li>` : ''}

      ${pages(state.totalPages)}
      
      ${state.nextUrl ?`<li class="page-item">
        <a class="page-link" href="#" aria-label="Next" rel="next">
          &raquo;
        </a>
      </li>`:''}
    </ul>`;
        repliesEl.insertAdjacentElement('beforeend', pageEl);
    }
}

function pages(total) {
    let pagesEl = '';
    let active;
    if (total > 1) {
        for (let i = 1; i <= total; i++) {
            active = i == state.page ? 'active' : '';
            pagesEl += `<li class="page-item ${active}"><a
            class="page-link" href="#">${i}</a></li>`;
        }
    }
    return pagesEl;
}

document.addEventListener('click', function (e) {
    if (e.target.parentElement.parentElement.parentElement.className == 'change-replies') {
        if (e.target.tagName == 'A' && e.target.classList.contains('page-link')) {
            changePage(e);
            this.dispatchEvent(new CustomEvent('changePg', {
                detail: {
                    page: parseInt(state.page)
                }
            }));
        }
    }
});

function changePage(e) {
    e.preventDefault();
    const liEl = document.querySelectorAll('li.page-item');
    const aEl = e.target;
    let aText = aEl.textContent.trim();
    if (aText.match(/^\d+$/)) {
        liEl[state.page - 1].classList.remove('active');
        state.page = aText;
        liEl[state.page - 1].classList.add('active');
    } else if (!aText.match(/^\d+$/)) {
        if (liEl[0].firstElementChild == aEl) {
            if (state.page > 1) {
                liEl[state.page - 1].classList.remove('active');
                state.page--;
                liEl[state.page - 1].classList.add('active');
            }
        } else if (liEl[liEl.length - 1].firstElementChild == aEl) {
            if (state.totalPages != state.page) {
                liEl[state.page - 1].classList.remove('active');
                state.page++;
                liEl[state.page - 1].classList.add('active');
            }
        }
    }
}
