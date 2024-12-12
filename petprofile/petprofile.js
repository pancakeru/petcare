const addButton = document.getElementById("add");
const addPanel = document.getElementById("panelAdd");
const addPetForm = document.getElementById("addPetForm");
const saveButton = document.getElementById("save");
const cancelButton = document.getElementById("cancel");
const activityContainer = document.getElementById("activity");
const infoPanel = document.getElementById("petinfo");
const filterSelect = document.getElementById("filter");
let currentPets = [];

// Check login before showing the add panel
addButton.addEventListener("click", () => {
    fetch('checkLogin.php')
        .then(response => response.json())
        .then(data => {
            if (!data.loggedIn) {
                window.location.href = '../login/login.php';
            } else {
                addPanel.classList.remove("hidden");
                addPetForm.reset();
            }
        })
        .catch(error => console.error("Error checking login:", error));
});

// Create pet profile card
const createPetProfile = (id, type, name, age, history) => {
    const petItem = document.createElement("div");
    petItem.classList.add("pet-item", type);
    petItem.dataset.id = id;
    petItem.dataset.type = type;
    petItem.dataset.created = new Date().toLocaleString();

    petItem.innerHTML = `
        <strong>${name}</strong> (${age} years old)
        <button class="delete-button"></button>
        <button class="edit-button"></button>
    `;

    petItem.querySelector(".delete-button").addEventListener("click", () => {
        const confirmDelete = confirm("Are you sure you want to delete this pet?");
        if (confirmDelete) {
            fetch('deletePet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        activityContainer.removeChild(petItem);
                        currentPets = currentPets.filter((pet) => pet !== petItem);
                    } else {
                        alert("Failed to delete pet.");
                    }
                });
        }
    });

    petItem.querySelector(".edit-button").addEventListener("click", () => {
        addPanel.classList.remove("hidden");
        document.getElementById("petSelect").value = type;
        document.getElementById("petName").value = name;
        document.getElementById("petAge").value = age;
        document.getElementById("medicalHistory").value = history;

        saveButton.onclick = () => {
            const updatedType = document.getElementById("petSelect").value.trim();
            const updatedName = document.getElementById("petName").value.trim();
            const updatedAge = parseInt(document.getElementById("petAge").value.trim(), 10);
            const updatedHistory = document.getElementById("medicalHistory").value.trim();

            if (!updatedType || !updatedName || isNaN(updatedAge) || updatedAge <= 0 || !updatedHistory) {
                alert("Please fill all the fields correctly!");
                return;
            }

            fetch('editPet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&type=${updatedType}&name=${updatedName}&age=${updatedAge}&history=${updatedHistory}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        petItem.classList.remove(type);
                        petItem.classList.add(updatedType);
                        petItem.dataset.type = updatedType;
                        petItem.innerHTML = `
                            <strong>${updatedName}</strong> (${updatedAge} years old)
                            <button class="delete-button"></button>
                            <button class="edit-button"></button>
                        `;
                    } else {
                        alert("Failed to update pet.");
                    }
                    addPanel.classList.add("hidden");
                });
        };
    });

    activityContainer.appendChild(petItem);
};

// Save new pet
saveButton.addEventListener("click", () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = parseInt(document.getElementById("petAge").value.trim(), 10);
    const history = document.getElementById("medicalHistory").value.trim();

    if (!type || !name || isNaN(age) || age <= 0 || !history) {
        alert("Please fill all the fields correctly!");
        return;
    }

    fetch('savePet.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `type=${type}&name=${name}&age=${age}&history=${history}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createPetProfile(data.id, type, name, age, history);
                addPanel.classList.add("hidden");
                addPetForm.reset();
            } else {
                alert("Failed to save pet.");
            }
        });
});

// Close the add panel
cancelButton.addEventListener("click", () => {
    addPanel.classList.add("hidden");
    addPetForm.reset();
});

// Filter pets
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
