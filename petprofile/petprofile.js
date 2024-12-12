const addButton = document.getElementById("add");
const addPanel = document.getElementById("panelAdd");
const addPetForm = document.getElementById("addPetForm");
const saveButton = document.getElementById("save");
const cancelButton = document.getElementById("cancel");
const activityContainer = document.getElementById("activity");
const infoPanel = document.getElementById("petinfo");
const filterSelect = document.getElementById("filter");

// Keep track of current pets
let currentPets = [];

// Create a pet profile card and initialize buttons
const createPetProfile = (id, type, name, age, history) => {
    const petItem = document.createElement("div");
    petItem.classList.add("pet-item", type);
    petItem.dataset.type = type;
    petItem.dataset.id = id;

    petItem.innerHTML = `
        <strong>${name}</strong> (${age} years old)
        <button class="delete-button">Delete</button>
        <button class="edit-button">Edit</button>
    `;

    // Add functionality to edit button
    petItem.querySelector(".edit-button").addEventListener("click", () => {
        // Populate the form with existing data
        addPanel.classList.remove("hidden");
        document.getElementById("petSelect").value = type;
        document.getElementById("petName").value = name;
        document.getElementById("petAge").value = age;
        document.getElementById("medicalHistory").value = history;

        // Rebind save button for editing
        const updatedSaveButton = saveButton.cloneNode(true);
        saveButton.replaceWith(updatedSaveButton);

        updatedSaveButton.addEventListener("click", () => {
            const updatedType = document.getElementById("petSelect").value.trim();
            const updatedName = document.getElementById("petName").value.trim();
            const updatedAge = parseInt(document.getElementById("petAge").value.trim(), 10);
            const updatedHistory = document.getElementById("medicalHistory").value.trim();

            if (!updatedType || !updatedName || isNaN(updatedAge) || updatedAge <= 0 || !updatedHistory) {
                alert("Please fill all fields, and age must be a positive number!");
                return;
            }

            fetch("editPet.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    pet_id: id,
                    type: updatedType,
                    name: updatedName,
                    age: updatedAge,
                    history: updatedHistory,
                }),
            })
                .then(response => response.text())
                .then(data => {
                    if (data.startsWith("Success:")) {
                        petItem.dataset.type = updatedType;
                        petItem.innerHTML = `
                            <strong>${updatedName}</strong> (${updatedAge} years old)
                            <button class="delete-button">Delete</button>
                            <button class="edit-button">Edit</button>
                        `;
                        alert(data);
                        // Reinitialize buttons
                        createPetProfile(id, updatedType, updatedName, updatedAge, updatedHistory);
                        addPanel.classList.add("hidden");
                        addPetForm.reset();
                    } else {
                        alert(data);
                    }
                })
                .catch(err => console.error("Error editing pet:", err));
        });
    });

    // Add functionality to delete button
    petItem.querySelector(".delete-button").addEventListener("click", () => {
        fetch("deletePet.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ pet_id: id }),
        })
            .then(response => response.text())
            .then(data => {
                if (data.startsWith("Success:")) {
                    activityContainer.removeChild(petItem);
                    currentPets = currentPets.filter(pet => pet !== petItem);
                    alert(data);
                } else {
                    alert(data);
                }
            })
            .catch(err => console.error("Error deleting pet:", err));
    });

    // Add pet to the activity container and update currentPets
    activityContainer.appendChild(petItem);
    currentPets.push(petItem);
};

// Add Pet
addButton.addEventListener("click", () => {
    fetch("../database/checkLogin.php")
        .then(response => response.text())
        .then(data => {
            if (data === "loggedIn") {
                addPanel.classList.remove("hidden");
                addPetForm.reset();
            } else {
                alert("You must log in to add a pet!");
                window.location.href = "../login/login.php";
            }
        })
        .catch(err => console.error("Error checking session:", err));
});

// Save Pet
saveButton.addEventListener("click", () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = parseInt(document.getElementById("petAge").value.trim(), 10);
    const history = document.getElementById("medicalHistory").value.trim();

    if (!type || !name || isNaN(age) || age <= 0 || !history) {
        alert("Please fill all fields, and age must be a positive number!");
        return;
    }

    fetch("savePet.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ type, name, age, history }),
    })
        .then(response => response.text())
        .then(data => {
            if (data.startsWith("Success:")) {
                alert(data);
                const newId = Date.now(); // Replace with actual ID from the server response
                createPetProfile(newId, type, name, age, history);
                addPanel.classList.add("hidden");
                addPetForm.reset();
            } else {
                alert(data);
            }
        })
        .catch(err => console.error("Error saving pet:", err));
});

// Cancel Add Pet
cancelButton.addEventListener("click", () => {
    addPanel.classList.add("hidden");
    addPetForm.reset();
});

// Close Pet Info Panel
document.getElementById("close").addEventListener("click", () => {
    infoPanel.classList.add("hidden");
});

// Filter Pets
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
