// DOM Elements
const addButton = document.getElementById("add");
const addPanel = document.getElementById("panelAdd");
const addPetForm = document.getElementById("addPetForm");
const saveButton = document.getElementById("save");
const cancelButton = document.getElementById("cancel");
const activityContainer = document.getElementById("activity");
const infoPanel = document.getElementById("petinfo");
const filterSelect = document.getElementById("filter");
let currentPets = [];

// Check login status before adding a pet
addButton.addEventListener("click", () => {
    fetch("../database/checkLogin.php")
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                addPanel.classList.remove("hidden");
                addPetForm.reset();
                
            } else {
                alert("You must log in to add a pet!");
                window.location.href = "../login/login.php";
            }
        });
});

// Save a new pet to the database
saveButton.addEventListener("click", () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = parseInt(document.getElementById("petAge").value.trim(), 10);
    const history = document.getElementById("medicalHistory").value.trim();

    if (!type || !name || isNaN(age) || age <= 0 || !history) {
        alert("Please fill all the fields correctly.");
        return;
    }

    const formData = new FormData();
    formData.append("type", type);
    formData.append("name", name);
    formData.append("age", age);
    formData.append("history", history);

    fetch("savePet.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                createPetProfile(type, name, age, history);
                addPanel.classList.add("hidden");
                addPetForm.reset();
            } else {
                alert(data.message);
            }
        });
});

// Create a pet profile card
const createPetProfile = (type, name, age, history, id = null) => {
    const petItem = document.createElement("div");
    petItem.classList.add("pet-item", type);
    petItem.dataset.type = type;
    petItem.dataset.id = id;

    petItem.innerHTML = `
        <strong>${name}</strong> (${age} years old)
        <button class="delete-button">Delete</button>
        <button class="edit-button">Edit</button>
    `;

    // Open edit panel
    petItem.querySelector(".edit-button").addEventListener("click", () => {
        addPanel.classList.remove("hidden");
        document.getElementById("petSelect").value = type;
        document.getElementById("petName").value = name;
        document.getElementById("petAge").value = age;
        document.getElementById("medicalHistory").value = history;

        saveButton.replaceWith(saveButton.cloneNode(true));
        const updatedSaveButton = document.getElementById("save");
        updatedSaveButton.addEventListener("click", () => {
            const updatedType = document.getElementById("petSelect").value.trim();
            const updatedName = document.getElementById("petName").value.trim();
            const updatedAge = parseInt(document.getElementById("petAge").value.trim(), 10);
            const updatedHistory = document.getElementById("medicalHistory").value.trim();

            if (!updatedType || !updatedName || isNaN(updatedAge) || updatedAge <= 0 || !updatedHistory) {
                alert("Please fill all the fields correctly.");
                return;
            }

            editPet(petItem.dataset.id, updatedType, updatedName, updatedAge, updatedHistory);
            petItem.classList.remove(type);
            petItem.classList.add(updatedType);
            petItem.innerHTML = `
                <strong>${updatedName}</strong> (${updatedAge} years old)
                <button class="delete-button">Delete</button>
                <button class="edit-button">Edit</button>
            `;
            addPanel.classList.add("hidden");
        });
    });

    // Delete pet
    petItem.querySelector(".delete-button").addEventListener("click", () => {
        deletePet(petItem.dataset.id, petItem);
    });

    activityContainer.appendChild(petItem);
    currentPets.push(petItem);
};

// Delete a pet from the database
function deletePet(petId, petItem) {
    const formData = new FormData();
    formData.append("id", petId);

    fetch("deletePet.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                activityContainer.removeChild(petItem);
                currentPets = currentPets.filter(pet => pet !== petItem);
            } else {
                alert(data.message);
            }
        });
}

// Edit a pet in the database
function editPet(petId, updatedType, updatedName, updatedAge, updatedHistory) {
    const formData = new FormData();
    formData.append("id", petId);
    formData.append("type", updatedType);
    formData.append("name", updatedName);
    formData.append("age", updatedAge);
    formData.append("history", updatedHistory);

    fetch("editPet.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert(data.message);
            }
        });
}

// Cancel adding or editing a pet
cancelButton.addEventListener("click", () => {
    addPanel.classList.add("hidden");
    addPetForm.reset();
});

// Filter pets by type
filterSelect.addEventListener("change", () => {
    const filterValue = filterSelect.value;
    currentPets.forEach(pet => {
        if (filterValue === "all" || pet.dataset.type === filterValue) {
            pet.style.display = "";
        } else {
            pet.style.display = "none";
        }
    });
});
