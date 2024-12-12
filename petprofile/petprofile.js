const addButton = document.getElementById("add");
const addPanel = document.getElementById("panelAdd");
const addPetForm = document.getElementById("addPetForm");
const saveButton = document.getElementById("save");
const cancelButton = document.getElementById("cancel");
const activityContainer = document.getElementById("activity");
const infoPanel = document.getElementById("petinfo");
const filterSelect = document.getElementById("filter");
let currentPets = [];

// Redirect to login if user is not logged in
const checkLoginStatus = async () => {
    try {
        const response = await fetch("../database/checkLogin.php"); // Backend to check login status
        const result = await response.json();
        if (!result.loggedIn) {
            window.location.href = "../login/login.php?error=Please login to access pet profiles.";
        }
    } catch (error) {
        console.error("Error checking login status:", error);
        alert("An error occurred. Please try again.");
        window.location.href = "../login/login.php";
    }
};

// Fetch pets from backend
const fetchPetsFromBackend = async () => {
    try {
        const response = await fetch("../database/petSave.php?action=fetch_pets");
        if (!response.ok) throw new Error("Failed to fetch pets.");
        const pets = await response.json();
        pets.forEach((pet) => {
            createPetProfile(pet.id, pet.type, pet.name, pet.age, pet.medical_history, pet.created_date);
        });
    } catch (error) {
        console.error("Error fetching pets:", error);
        alert("An error occurred while fetching pets.");
    }
};

// Create pet profile card
const createPetProfile = (id, type, name, age, history, createdDate) => {
    const petItem = document.createElement("div");
    petItem.classList.add("pet-item", type);
    petItem.dataset.id = id;
    petItem.dataset.type = type;

    petItem.innerHTML = `
        <strong>${name}</strong> (${age} years old, ${type})
        <p>Medical History: ${history}</p>
        <p>Created: ${createdDate || new Date().toLocaleString()}</p>
        <button class="edit-button">Edit</button>
        <button class="delete-button">Delete</button>
    `;

    // Edit pet profile
    petItem.querySelector(".edit-button").addEventListener("click", () => {
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
                alert("Please fill in all fields and ensure age is a positive number.");
                return;
            }

            try {
                const response = await fetch("../database/petSave.php?action=save_edits", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        id,
                        type: updatedType,
                        name: updatedName,
                        age: updatedAge,
                        medical_history: updatedHistory,
                    }),
                });
                const result = await response.json();

                if (result.status === "success") {
                    petItem.innerHTML = `
                        <strong>${updatedName}</strong> (${updatedAge} years old, ${updatedType})
                        <p>Medical History: ${updatedHistory}</p>
                        <p>Created: ${petItem.dataset.created}</p>
                        <button class="edit-button">Edit</button>
                        <button class="delete-button">Delete</button>
                    `;
                    addPanel.classList.add("hidden");
                } else {
                    alert("Failed to update pet. Please try again.");
                }
            } catch (error) {
                console.error("Error updating pet:", error);
                alert("An error occurred. Please try again.");
            }
        });
    });

    // Delete pet profile
    petItem.querySelector(".delete-button").addEventListener("click", async () => {
        if (!confirm("Are you sure you want to delete this pet?")) return;

        try {
            const response = await fetch(`../database/petSave.php?action=delete_pet&id=${id}`, { method: "DELETE" });
            const result = await response.json();

            if (result.status === "success") {
                petItem.remove();
            } else {
                alert("Failed to delete pet. Please try again.");
            }
        } catch (error) {
            console.error("Error deleting pet:", error);
            alert("An error occurred. Please try again.");
        }
    });

    activityContainer.appendChild(petItem);
};

// Save new pet
saveButton.addEventListener("click", async () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = parseInt(document.getElementById("petAge").value.trim(), 10);
    const history = document.getElementById("medicalHistory").value.trim();

    if (!type || !name || isNaN(age) || age <= 0 || !history) {
        alert("Please fill in all fields and ensure age is a positive number.");
        return;
    }

    try {
        const response = await fetch("../database/petSave.php?action=save_pet", {
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

// Cancel adding a new pet
cancelButton.addEventListener("click", () => {
    addPanel.classList.add("hidden");
    addPetForm.reset();
});

// Redirect if not logged in and fetch pets
checkLoginStatus().then(fetchPetsFromBackend);
