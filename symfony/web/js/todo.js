

function todoComplete(id){

    fetch(window.location.href + 'complete/' + id, {
        method: 'GET',
        headers: {
            'Accept' : '*/*',
        }
    })
        .then()
        .catch(e => console.log('error: ', e));

    let element = document.getElementById(id);
    element.classList.add("task-done");

    document.getElementById("complete" + id).disabled = true;
    document.getElementById("edit" + id).disabled = true;

}

function todoDelete(id){

    fetch(window.location.href + 'del/' + id, {
        method: 'GET',
        headers: {
            'Accept' : '*/*',
        }
    })
        .then()
        .catch(e => console.log('error: ', e));

    let itemToRemove = document.getElementById(id);
    itemToRemove.parentNode.removeChild(itemToRemove);

}
