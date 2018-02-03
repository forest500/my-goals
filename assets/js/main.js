var categoryId;

document.addEventListener('DOMContentLoaded', fetchCategories);
document.getElementById('category-submit').addEventListener('click', addCategory);
document.getElementById('all-goals').addEventListener('click', fetchGoals);

document.getElementById('goal-submit').addEventListener('click', addGoal);

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
            fetchGoals(e);
            addCategoryGoalsLinks();
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
            console.log(categoryUl.firstChild);
            return;
            while (categoryUl.firstChild.classList.contains('category-li')) {
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

function fetchGoals(e) {
    e.stopImmediatePropagation();
    var goalUl = document.getElementById("goals-list");
    while (goalUl.firstChild) {
        goalUl.removeChild(goalUl.firstChild);
    }

    const urlGetCategories = `http://localhost:8000/get_goals`;
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
            data.map(goal => {
                addGoalNode(goal);
            })
            document.getElementById('new-goal').classList.remove('show');
            document.getElementById('new-goal').classList.add('hide');
        });
}

function fetchCategoryGoals(e) {
    var goalUl = document.getElementById("goals-list");
    while (goalUl.firstChild) {
        goalUl.removeChild(goalUl.firstChild);
    }

    e.stopImmediatePropagation();
    if(e.target.getAttribute("id") !== "goal-submit") {
        console.log('shit')
        categoryId = e.target.getAttribute("id");
    }

    const urlGetCategoryGoals = `http://localhost:8000/get_category_goals/${categoryId}`;
    fetch(urlGetCategoryGoals, {
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
            data.map(goal => {
                addGoalNode(goal);
            })
            document.getElementById('new-goal').classList.remove('hide');
            document.getElementById('new-goal').classList.add('show');
        });
}

function addGoal(e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    const url = `http://localhost:8000/new_goal/${categoryId}`;

    let goalName = document.getElementById('goal-text').value;

    fetch(url, {
        method: 'POST',
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        }),
        body: `name=${goalName}`,
        credentials: 'same-origin',
    })
        .then((response) => response.json())
        .then((responseText) => {
            var goalUl = document.getElementById("goals-list");
            while (goalUl.firstChild) {
                goalUl.removeChild(goalUl.firstChild);
            }
            fetchCategoryGoals(e);

            alert(responseText);
        })
        .catch((error) => {
            console.error(error);
        });
}

function deleteGoal(e) {
    e.stopImmediatePropagation();
    let id = e.target.parentElement.getAttribute("id");

    const urldeleteCategory = `http://localhost:8000/delete_goal/${id}`;

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

            if (data === "Cel został usunięty") {
                e.target.parentElement.remove();
             }
        })
}

function addGoalNode(goal) {
    const goalLi = document.createElement("li");
    goalLi.classList.add("goal-li");
    let goalName = document.createTextNode(goal.name);
    goalLi.appendChild(goalName);
    goalLi.setAttribute("id", goal.id)

    document.getElementById('goals-list').appendChild(goalLi);
    addDeleteButton(goalLi, deleteGoal);
}

function addDeleteButton(categoryLi, deleteFunction) {
    const deleteBtn = document.createElement("button");
    const buttonTxt = document.createTextNode("X");

    deleteBtn.classList.add("delete-btn");
    deleteBtn.appendChild(buttonTxt);
    categoryLi.appendChild(deleteBtn);

    addDeleteLinks(deleteFunction)
}

function addDeleteLinks(deleteFunction) {
    var deleteLinks = document.querySelectorAll('.delete-btn');

    Array.from(deleteLinks).forEach(link => {
        link.addEventListener('click', deleteFunction)
    });
}

function addCategoryGoalsLinks() {
    var goalsLinks = document.querySelectorAll('.category-li');

    Array.from(goalsLinks).forEach(link => {
        link.addEventListener('click', fetchCategoryGoals);
    });
}
