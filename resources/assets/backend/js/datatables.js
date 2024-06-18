"use strict";

import 'laravel-datatables-vite';

window.deleteEntry = (event, button) => {
    event.preventDefault();

    let url = button.dataset.url;

    axios.delete(url).then(() => {
        $('#products-table').DataTable().ajax.reload();
    }).catch(error => {
        console.log(error);
    });
}
