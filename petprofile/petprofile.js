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
const createPetProfile = (id, type, name, age, history, createdDate) => {
    if (!id || !type || !name || !age || !history) {
        console.warn("Invalid pet data:", { id, type, name, age, history });
        return;
    }

    const petItem = document.createElement("div");
    petItem.classList.add("pet-item", type);
    petItem.dataset.type = type;
    petItem.dataset.created = createdDate || new Date().toLocaleString();

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

        updatedSaveButton.addEventListener("click", async () => {
            const updatedType = document.getElementById("petSelect").value.trim();
            const updatedName = document.getElementById("petName").value.trim();
            const updatedAge = parseInt(document.getElementById("petAge").value.trim(), 10);
            const updatedHistory = document.getElementById("medicalHistory").value.trim();

            if (!updatedType || !updatedName || isNaN(updatedAge) || updatedAge <= 0 || !updatedHistory) {
                alert("Please fill all the blank fields, and age must be a positive number!");
                return;
            }

            try {
                const response = await fetch("petSave.php?action=save_edits", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id, type: updatedType, name: updatedName, age: updatedAge, medical_history: updatedHistory }),
                });
                const result = await response.json();

                if (result.status === "success") {
                    petItem.classList.remove(type);
                    petItem.classList.add(updatedType);
                    petItem.dataset.type = updatedType;
                    petItem.innerHTML = `
                        <strong>${updatedName}</strong> (${updatedAge} years old)
                        <button class="delete-button"></button>
                        <button class="edit-button"></button>
                    `;
                    addPanel.classList.add("hidden");
                } else {
                    alert("Please login before adding your pets.");
                }
            } catch (error) {
                console.error("Error updating pet:", error);
                alert("Please login before adding your pets.");
            }
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
        deletePetFromBackend(id);
        activityContainer.removeChild(petItem);
        currentPets = currentPets.filter((pet) => pet !== petItem);
    });

    currentPets.push(petItem);
    activityContainer.appendChild(petItem);
};

// Fetch existing pets from backend
const fetchPetsFromBackend = async () => {
    try {
        const response = await fetch("petSave.php?action=fetch_pets");
        if (!response.ok) throw new Error("Failed to fetch pets.");
        const pets = await response.json();
        pets.forEach((pet) => {
            createPetProfile(pet.id, pet.type, pet.name, pet.age, pet.medical_history, pet.created_date);
        });
    } catch (error) {
        console.error("Error fetching pets:", error);
        alert("Failed to fetch pets. Please try again later.");
    }
};

// Delete pet from backend
const deletePetFromBackend = async (id) => {
    try {
        await fetch(`petSave.php?action=delete_pet&id=${id}`, { method: "DELETE" });
    } catch (error) {
        console.error("Error deleting pet:", error);
        alert("Failed to delete pet.");
    }
};

// Event Listeners
addButton.addEventListener("click", () => {
    addPanel.classList.remove("hidden");
    addPetForm.reset();
});

cancelButton.addEventListener("click", () => {
    addPanel.classList.add("hidden");
    addPetForm.reset();
});

saveButton.addEventListener("click", async () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = parseInt(document.getElementById("petAge").value.trim(), 10);
    const history = document.getElementById("medicalHistory").value.trim();

    if (!type || !name || isNaN(age) || age <= 0 || !history) {
        alert("Please fill all the blank fields, and age must be a positive number!");
        return;
    }

    try {
        const response = await fetch("petSave.php?action=save_pet", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ type, name, age, medical_history: history }),
        });
        const result = await response.json();

        if (result.status === "success") {
            createPetProfile(result.id, type, name, age, history);
            addPanel.classList.add("hidden");
            addPetForm.reset();
        } else {
            alert("Failed to save pet. Please try again.");
        }
    } catch (error) {
        console.error("Error saving pet:", error);
        alert("An error occurred. Please try again.");
    }
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

// Close Pet Info Panel
document.getElementById("close").addEventListener("click", () => {
    infoPanel.classList.add("hidden");
});

// Load pets on page load
fetchPetsFromBackend();
