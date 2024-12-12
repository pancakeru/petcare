const addButton = document.getElementById("add");
const addPanel = document.getElementById("panelAdd");
const addPetForm = document.getElementById("addPetForm");
const saveButton = document.getElementById("save");
const cancelButton = document.getElementById("cancel");
const activityContainer = document.getElementById("activity");
const infoPanel = document.getElementById("petinfo");
const filterSelect = document.getElementById("filter");
let currentPets = [];

// Create pet profile card
const createPetProfile = (type, name, age, history) => {
    const petItem = document.createElement("div");
    petItem.classList.add("pet-item", type);
    petItem.dataset.type = type;
    petItem.dataset.created = new Date().toLocaleString();

    petItem.innerHTML = `
        <strong>${name}</strong> (${age} years old)
        <button class="delete-button"></button>
        <button class="edit-button"></button>
    `;

    // Open the edit panel
    petItem.querySelector(".edit-button").addEventListener("click", () => {
        event.stopPropagation();
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
                alert("Please fill all the blank, and age must be a positive number!");
                return;
            }

            petItem.classList.remove(type);
            petItem.classList.add(updatedType);
            petItem.dataset.type = updatedType;
            petItem.innerHTML = `
                <strong>${updatedName}</strong> (${updatedAge} years old)
                <button class="delete-button"></button>
                <button class="edit-button"></button>
            `;

            petItem.querySelector(".delete-button").addEventListener("click", () => {
                activityContainer.removeChild(petItem);
                currentPets = currentPets.filter((pet) => pet !== petItem);
            });
            petItem.querySelector(".edit-button").addEventListener("click", () => {
                event.stopPropagation();
                addPanel.classList.remove("hidden");
                document.getElementById("petSelect").value = updatedType;
                document.getElementById("petName").value = updatedName;
                document.getElementById("petAge").value = updatedAge;
                document.getElementById("medicalHistory").value = updatedHistory;
            });

            addPanel.classList.add("hidden");
        });
    });

    petItem.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-button")) return;

        infoPanel.classList.remove("hidden");
        document.getElementById("infoType").textContent = `Type: ${type}`;
        document.getElementById("infoName").textContent = `Name: ${name}`;
        document.getElementById("infoAge").textContent = `Age: ${age}`;
        document.getElementById("infoHistory").textContent = `Medical History: ${history}`;
        document.getElementById("infoCreated").textContent = `Created: ${petItem.dataset.created}`;
        document.getElementById("infoAccessed").textContent = `Last Accessed: ${new Date().toLocaleString()}`;
    });

    petItem.querySelector(".delete-button").addEventListener("click", () => {
        activityContainer.removeChild(petItem);
        currentPets = currentPets.filter((pet) => pet !== petItem);
    });

    currentPets.push(petItem);
    activityContainer.appendChild(petItem);
};

// Add Pet
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
        })
        .catch(err => console.error("Error checking session:", err));
});

cancelButton.addEventListener("click", () => {
    addPanel.classList.add("hidden");
    addPetForm.reset();
});

saveButton.addEventListener("click", () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = parseInt(document.getElementById("petAge").value.trim(), 10);
    const history = document.getElementById("medicalHistory").value.trim();

    if (!type || !name || isNaN(age) || age <= 0 || !history) {
        alert("Please fill all the blank, and age must be a positive number!");
        return;
    }

    fetch("/savePet.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ type, name, age, history }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                createPetProfile(type, name, age, history);
                addPanel.classList.add("hidden");
                addPetForm.reset();
            } else {
                alert("Failed to save pet. Please try again.");
            }
        })
        .catch((error) => console.error("Error saving pet:", error));
});


// Close Pet Info Panel
document.getElementById("close").addEventListener("click", () => {
    infoPanel.classList.add("hidden");
});

// Filter Pets
filterSelect.addEventListener("change", () => {
    const filterValue = filterSelect.value;
    currentPets.forEach((pet) => {
        if (filterValue === "all" || pet.dataset.type === filterValue) {
            pet.style.display = "";
        } else {
            pet.style.display = "none";
        }
    });
});
