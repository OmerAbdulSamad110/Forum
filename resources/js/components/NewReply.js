export const NewReply = () => {
    if (document.querySelector('div#new-reply')) {
        const newReplyEl = document.querySelector('div#new-reply');
        const formEl = document.createElement('form');
        formEl.className = 'new-reply';
        formEl.innerHTML = `
        <div class="form-group">
        <textarea name="body" id="body" cols="3" rows="3" class="form-control" placeholder="Have something to say..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        `;
        newReplyEl.replaceWith(formEl);
    }
}
