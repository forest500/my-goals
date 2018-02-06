var categoryId;
var goalId;
var showAll = true;
var isEditing = false;

document.addEventListener('DOMContentLoaded', fetchCategories);
document.getElementById('category-submit').addEventListener('click', addCategory);
document.getElementById('all-goals').addEventListener('click', handleAllGoals);

document.getElementById('goal-submit').addEventListener('click', addGoal);

//CATEGORY

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
            addEditLinks()
        });
}

function handleAllGoals(e) {
    showAll = true;
    fetchGoals(e);
    document.getElementById('new-goal').classList.remove('show');
    document.getElementById('new-goal').classList.add('hide');

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
            var categoryLi = document.getElementsByClassName("category-li");

            while (categoryLi[0]) {
                categoryLi[0].parentNode.removeChild(categoryLi[0]);
            }

            fetchCategories(e);
            document.getElementById('category-text').value = '';

            alert(responseText);
        })
        .catch((error) => {
            console.error(error);
        });
}

function editCategory(e, categoryName) {
    e.stopImmediatePropagation();
    e.preventDefault();
    let id = e.target.parentElement.getAttribute("id");

    const url = `http://localhost:8000/update_category/${id}`;

    // let categoryName = document.getElementById('category-text').value;

    fetch(url, {
        method: 'PUT',
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        }),
        body: `name=${categoryName}&description`,
        credentials: 'same-origin',
    })
        .then((response) => response.json())
        .then((responseText) => {
            var categoryLi = document.getElementsByClassName("category-li");

            while (categoryLi[0]) {
                categoryLi[0].parentNode.removeChild(categoryLi[0]);
            }

            fetchCategories(e);

            alert(responseText);
        })
        .catch((error) => {
            console.error(error);
        });
}

function handleEdit(e) {
    if(isEditing) return;

    let span = e.target.previousSibling;

    span.style.display = "none";

    text = span.innerHTML;

    input = document.createElement("input");
    input.type = "text";
    input.value = text;
    input.size = Math.max(text.length / 4 * 3, 4);
    span.parentNode.insertBefore(input, span);

    input.focus();
    input.onblur = function() {
        span.parentNode.removeChild(input);
        span.innerHTML = input.value == "" ? "&nbsp;" : input.value;
        span.style.display = "";
    }
    console.log(span.nextSibling)
    if(!isEditing) {
        addSaveButton(span);
        isEditing = true;
    }

    // editCategory(e, text)
}

function addSaveButton(span) {
    const saveButton = document.createElement("button");
    const buttonText = document.createTextNode("save");

    saveButton.appendChild(buttonText);
    saveButton.classList.add("save-edit");

    span.parentNode.insertBefore(saveButton, span.nextSibling)
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
    const categorySpan = document.createElement("span");
    let categoryName = document.createTextNode(category.name);

    categoryLi.appendChild(categorySpan);
    categorySpan.appendChild(categoryName);
    categoryLi.setAttribute("id", category.id)
    categoryLi.classList.add("category-li");
    categorySpan.classList.add("category-span");

    document.getElementById('categories-list').appendChild(categoryLi);

    addEditButton(categoryLi);
    addDeleteButton(categoryLi, deleteCategory);
}

//GOAL

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
            fetchStages(e);
        });
}

function fetchCategoryGoals(e) {
    e.stopImmediatePropagation();
    showAll = false;
    var goalUl = document.getElementById("goals-list");
    while (goalUl.firstChild) {
        goalUl.removeChild(goalUl.firstChild);
    }

    var isValidId = /^\d+$/.test(e.target.parentElement.getAttribute("id"));

    if (isValidId === true) {
        categoryId = e.target.parentElement.getAttribute("id");
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
            fetchCategoryStages(e);
            addNewStageLinks(addStage);
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
            document.getElementById('goal-text').value = '';

            alert(responseText);
        })
        .catch((error) => {
            console.error(error);
        });
}

function deleteGoal(e) {
    e.stopImmediatePropagation();
    let id = e.target.parentElement.getAttribute("id");

    const urldeleteGoal = `http://localhost:8000/delete_goal/${id}`;

    fetch(urldeleteGoal, {
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
    const stageOl = document.createElement("ol");

    goalLi.classList.add("goal-li");
    let goalName = document.createTextNode(goal.name);
    goalLi.appendChild(goalName);
    goalLi.setAttribute("id", goal.id);
    stageOl.setAttribute("id", `stage-list-${goal.id}`);
    stageOl.setAttribute("class", `stage-list`);

    document.getElementById('goals-list').appendChild(goalLi);
    addDeleteButton(goalLi, deleteGoal);
    goalLi.appendChild(stageOl);
    if (!showAll) {
        const stageForm = addStageForm(goal.id);
        goalLi.appendChild(stageForm);
    }
}

function addDeleteButton(categoryLi, deleteFunction) {
    const deleteBtn = document.createElement("button");
    const buttonTxt = document.createTextNode("X");

    deleteBtn.classList.add("delete-btn");
    deleteBtn.appendChild(buttonTxt);
    categoryLi.appendChild(deleteBtn);

    addDeleteLinks(deleteFunction)
}

function addEditButton(parentNode) {
    const editButton = document.createElement("button");
    const buttonTxt = document.createTextNode("Edit");
    editButton.appendChild(buttonTxt);

    editButton.classList.add("edit-category");

    parentNode.appendChild(editButton);

}

function addDeleteLinks(deleteFunction) {
    var deleteLinks = document.querySelectorAll('.delete-btn');

    Array.from(deleteLinks).forEach(link => {
        link.addEventListener('click', deleteFunction)
    });
}

function addNewStageLinks(newFunction) {
    var newLinks = document.querySelectorAll('.stage-submit');

    Array.from(newLinks).forEach(link => {
        link.addEventListener('click', newFunction)
    });
}

function addCategoryGoalsLinks() {
    var goalsLinks = document.querySelectorAll('.category-li');

    Array.from(goalsLinks).forEach(link => {
        link.addEventListener('click', fetchCategoryGoals);
    });
}

function addEditLinks() {
    var editLinks = document.querySelectorAll('.edit-category');

    Array.from(editLinks).forEach(link => {
        link.addEventListener('click', handleEdit);
    });
}

// STAGE

function fetchStages(e) {
    e.stopImmediatePropagation();

    const urlGetCategories = `http://localhost:8000/get_stages`;
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

            data.map(stage => {
                addStageNode(stage);
            })
        });
}

function fetchCategoryStages(e) {
    var isValidId = /^\d+$/.test(e.target.getAttribute("id"));

    if (isValidId === true) {
        categoryId = e.target.getAttribute("id");
    }

    const urlCategoryStages = `http://localhost:8000/get_category_stages/${categoryId}`;
    fetch(urlCategoryStages, {
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
            data.map(stage => {
                addStageNode(stage);
            })
        });
}

function fetchGoalStages(e) {
    console.log(goalId)
    const urlGoalStages = `http://localhost:8000/get_goal_stages/${goalId}`;
    fetch(urlGoalStages, {
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
            data.map(stage => {
                addStageNode(stage);
            })
        });
}

function addStage(e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    goalId = e.target.getAttribute("id").replace(/\D/g,'');
    const url = `http://localhost:8000/new_stage/${goalId}`;
    console.log(goalId)
    let stageName = document.getElementById(`stage-text-${goalId}`).value;

    fetch(url, {
        method: 'POST',
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        }),
        body: `name=${stageName}`,
        credentials: 'same-origin',
    })
        .then((response) => response.json())
        .then((responseText) => {
            var stageOl = document.getElementById(`stage-list-${goalId}`);
            while (stageOl.firstChild) {
                stageOl.removeChild(stageOl.firstChild);
            }
            fetchGoalStages(e)
            document.getElementById(`stage-text-${goalId}`).value = '';

            alert(responseText);
        })
        .catch((error) => {
            console.error(error);
        });
}

function addStageNode(stage) {
    const stagelLi = document.createElement("li");

    stagelLi.classList.add("stage-li");
    let goalName = document.createTextNode(stage.name);
    stagelLi.appendChild(goalName);
    stagelLi.setAttribute("id", stage.id)

    document.getElementById(`stage-list-${stage.goalId}`).appendChild(stagelLi);
    addDeleteButton(stagelLi, deleteStage);
}

function deleteStage(e) {
    e.stopImmediatePropagation();
    let id = e.target.parentElement.getAttribute("id");

    const urldeleteStage = `http://localhost:8000/delete_stage/${id}`;

    fetch(urldeleteStage, {
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

            if (data === "Poziom został usunięty") {
                e.target.parentElement.remove();
             }
        })
}

function addStageForm(goalId) {
    const div = document.createElement('div');
    div.classList.add('new-stage-div');
    const stageForm =
        `<form id ="new-stage-${goalId}" class="new-stage" method="post" onsubmit="return false;">
            <input type="text" id="stage-text-${goalId}">
            <button type="button" class="stage-submit" id="stage-submit-${goalId}">nowy poziom</button>
        </form>`;
    div.innerHTML = stageForm;

    return div;
}
