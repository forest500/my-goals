document.addEventListener('DOMContentLoaded', fetchCategories);
document.getElementById('category-submit').addEventListener('click', addCategory);

function fetchCategories(e) {
    e.stopImmediatePropagation();
    const urlGetCategories = `http://localhost:8000/get_categories`;
    fetch(urlGetCategories, {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin'
    })
        .then(response => {
            if (!response.ok) {
                throw Error("Network request failed")
            }

            return response;
        })
        .then(results => results.json())
        .then(data => {
            data.map(category => {
                addCategoryNode(category);
            })
            // fetchGoals(e);
        });
}

function addCategory(e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    const url = `http://localhost:8000/new_category`;

    let categoryName = document.getElementById('category-text').value;

    fetch(url, {
        method: 'POST',
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        }),
        body: `name=${categoryName}&description`,
        credentials: 'same-origin',
    })
        .then((response) => response.json())
        .then((responseText) => {
            var categoryUl = document.getElementById("categories-list");
            while (categoryUl.firstChild) {
                categoryUl.removeChild(categoryUl.firstChild);
            }

            fetchCategories(e);
            document.getElementById('category-text').value = '';

            alert(responseText);
        })
        .catch((error) => {
            console.error(error);
        });
}

function deleteCategory(e) {
    e.stopImmediatePropagation();
    let id = e.target.parentElement.getAttribute("id");

    const urldeleteCategory = `http://localhost:8000/delete_category/${id}`;

    fetch(urldeleteCategory, {
        method: 'DELETE',
        credentials: 'same-origin'
    })
        .then(response => {
            if (!response.ok) {
                throw Error(response.statusText);
            }

            return response;
        })
        .then(results => results.json())
        .then(data => {
            alert(data);

            if (data === "Kategoria została usunieta") {
                e.target.parentElement.remove();
             }
        })
}

function addCategoryNode(category) {
    const categoryLi = document.createElement("li");
    categoryLi.classList.add("category-li");
    let categoryName = document.createTextNode(category.name);
    categoryLi.appendChild(categoryName);
    categoryLi.setAttribute("id", category.id)

    document.getElementById('categories-list').appendChild(categoryLi);

    addDeleteButton(categoryLi, deleteCategory);
}
