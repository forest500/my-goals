document.getElementById('all-goals').addEventListener('click', fetchGoals);
document.addEventListener('DOMContentLoaded', fetchGoals);
// document.getElementById('goal-submit').addEventListener('click', addCategory);

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
        });
}

function fetchCategoryGoals(e) {
    var goalUl = document.getElementById("goals-list");
    while (goalUl.firstChild) {
        goalUl.removeChild(goalUl.firstChild);
    }

    e.stopImmediatePropagation();
    let id = e.target.getAttribute("id");

    const urlGetCategoryGoals = `http://localhost:8000/get_category_goals/${id}`;
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
        });
}

function addGoal(e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    const url = `http://localhost:8000/new_goal`;

    let goalName = document.getElementById('goal-text').value;

    fetch(url, {
        method: 'POST',
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        }),
        body: `name=${goalName}&description`,
        credentials: 'same-origin',
    })
        .then((response) => response.json())
        .then((responseText) => {
            var goalUl = document.getElementById("goals-list");
            while (goalUl.firstChild) {
                goalUl.removeChild(goalUl.firstChild);
            }

            fetchGoals(e);

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
