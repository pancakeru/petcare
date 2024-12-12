const addButton = document.getElementById("add");
const addPanel = document.getElementById("panelAdd");
const addPetForm = document.getElementById("addPetForm");
const saveButton = document.getElementById("save");
const cancelButton = document.getElementById("cancel");
const activityContainer = document.getElementById("activity");
const filterSelect = document.getElementById("filter");

// Show the Add Pet form
addButton.addEventListener("click", () => {
    fetch("../login/checkSession.php") // Ensure the user is logged in
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                addPanel.classList.remove("hidden");
                addPetForm.reset();
            } else {
                alert("You must log in to add a pet!");
                window.location.href = "../login/login.php";
            }
        })
        .catch(err => console.error("Error checking session:", err));
});

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


// Save Pet
saveButton.addEventListener("click", () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = document.getElementById("petAge").value.trim();
    const history = document.getElementById("medicalHistory").value.trim();

    // Input validation
    if (!type || !name || !age || !history) {
        alert("All fields are required!");
        return;
    }
    if (isNaN(age) || age <= 0) {
        alert("Age must be a positive number!");
        return;
    }

    // Send data to savePet.php
    fetch("savePet.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ type, name, age, history }),
    })
        .then(response => response.text()) // Handle raw response
        .then(data => {
            // Redirects and success messages handled in PHP
            if (data.includes("successfully")) {
                alert("Pet added successfully!");
                addPanel.classList.add("hidden");
                addPetForm.reset();
                // Optionally, refresh the page or fetch new pet data
            } else if (data.includes("error")) {
                alert("Error adding pet: " + data);
            } else {
                console.error("Unexpected response:", data);
            }
        })
        .catch(err => console.error("Error saving pet:", err));
});

// Cancel Add Pet
cancelButton.addEventListener("click", () => {
    addPanel.classList.add("hidden");
    addPetForm.reset();
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
