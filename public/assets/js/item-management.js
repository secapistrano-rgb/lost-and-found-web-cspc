(() => {
    'use strict';
    const page = document.querySelector('.im-page');
    if (!page) return;
    const form = page.querySelector('#item-filters');
    // Submit to the server so search and filters include every page of results.
    let searchTimer;
    const submitFilters = () => {
        clearTimeout(searchTimer);
        if (form.requestSubmit) form.requestSubmit();
        else form.submit();
    };
    form.querySelectorAll('select').forEach(select => select.addEventListener('change', submitFilters));
    form.querySelector('[name="search"]').addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(submitFilters, 600);
    });
    form.addEventListener('submit', () => clearTimeout(searchTimer));
    page.querySelectorAll('.im-delete-form').forEach(deleteForm => {
        deleteForm.addEventListener('submit', event => {
            if (!window.confirm(`Delete “${deleteForm.dataset.itemTitle}”? This permanently removes the report.`)) event.preventDefault();
        });
    });
    page.addEventListener('error', event => {
        if (event.target.matches('.im-thumbnail img')) event.target.hidden = true;
    }, true);
    page.querySelectorAll('.im-thumbnail img').forEach(thumbnail => {
        if (thumbnail.complete && !thumbnail.naturalWidth) thumbnail.hidden = true;
    });
    const dialog = page.querySelector('#item-preview');
    const items = JSON.parse(page.querySelector('#item-preview-data').textContent);
    const image = page.querySelector('#im-preview-image');
    const placeholder = page.querySelector('#im-preview-placeholder');
    const controls = page.querySelector('#im-image-controls');
    let currentItem;
    let photoIndex = 0;
    const setText = (id, value) => { page.querySelector(`#im-preview-${id}`).textContent = value; };
    function showPhoto() {
        const images = currentItem.images;
        image.hidden = true;
        image.removeAttribute('src');
        placeholder.hidden = images.length > 0;
        controls.hidden = images.length < 2;
        if (!images.length) return;
        image.alt = `${currentItem.title}, photo ${photoIndex + 1}`;
        image.src = images[photoIndex];
        image.hidden = false;
        page.querySelector('#im-image-count').textContent = `${photoIndex + 1} / ${images.length}`;
    }
    image.addEventListener('error', () => { image.hidden = true; placeholder.hidden = false; });
    function openPreview(index) {
        currentItem = items[index];
        if (!currentItem) return;
        if (!dialog.showModal) { window.location.assign(currentItem.viewUrl); return; }
        photoIndex = 0;
        setText('title', currentItem.title);
        setText('id', `Report #${currentItem.id}`);
        setText('type', currentItem.type ? currentItem.type[0].toUpperCase() + currentItem.type.slice(1) : 'Unknown type');
        page.querySelector('#im-preview-type').className = `im-badge ${currentItem.type === 'lost' ? 'im-red' : 'im-green'}`;
        for (const field of ['status', 'category', 'location', 'date', 'claims']) setText(field, currentItem[field]);
        setText('description', currentItem.description || 'No description was provided for this report.');
        setText('reporter', currentItem.reporter || 'Reporter not recorded');
        setText('reported', `Reported ${currentItem.reported}`);
        page.querySelector('#im-preview-details').href = currentItem.viewUrl;
        showPhoto();
        dialog.showModal();
    }
    page.querySelectorAll('[data-preview-index]').forEach(button => {
        button.addEventListener('click', () => openPreview(Number(button.dataset.previewIndex)));
    });
    page.querySelectorAll('[data-close-preview]').forEach(button => button.addEventListener('click', () => dialog.close()));
    dialog.addEventListener('click', event => {
        const bounds = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) dialog.close();
    });
    for (const [id, direction] of [['im-image-previous', -1], ['im-image-next', 1]]) {
        page.querySelector(`#${id}`).addEventListener('click', () => {
            if (!currentItem || !currentItem.images.length) return;
            photoIndex = (photoIndex + direction + currentItem.images.length) % currentItem.images.length;
            showPhoto();
        });
    }
})();
