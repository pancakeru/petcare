const addButton = document.getElementById("add");
const addPanel = document.getElementById("panelAdd");
const addPetForm = document.getElementById("addPetForm");
const saveButton = document.getElementById("save");
const activityContainer = document.getElementById("activity");
const infoPanel = document.getElementById("petinfo");
const filterSelect = document.getElementById("filter");
const closeButton = document.getElementById("close");

let currentPets = [];

// Create pet profile card
const createPetProfile = (type, name, age, history, id) => {
    const petItem = document.createElement("div");
    petItem.classList.add("pet-item", type);
    petItem.dataset.id = id;
    petItem.dataset.type = type;

    petItem.innerHTML = `
        <strong>${name}</strong> (${age} years old)
        <button class="delete-button">Delete</button>
        <button class="edit-button">Edit</button>
    `;

    // Edit button functionality
    petItem.querySelector(".edit-button").addEventListener("click", () => {
        const updatedType = prompt("Enter new type:", type);
        const updatedName = prompt("Enter new name:", name);
        const updatedAge = parseInt(prompt("Enter new age:", age), 10);
        const updatedHistory = prompt("Enter new history:", history);

        fetch('editPet.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, type: updatedType, name: updatedName, age: updatedAge, history: updatedHistory })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                petItem.dataset.type = updatedType;
                petItem.innerHTML = `
                    <strong>${updatedName}</strong> (${updatedAge} years old)
                    <button class="delete-button">Delete</button>
                    <button class="edit-button">Edit</button>
                `;
            } else {
                alert(data.message);
            }
        });
    });

    // Delete button functionality
    petItem.querySelector(".delete-button").addEventListener("click", () => {
        fetch('deletePet.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                activityContainer.removeChild(petItem);
            } else {
                alert(data.message);
            }
        });
    });

    activityContainer.appendChild(petItem);
};

// Add pet button functionality
addButton.addEventListener("click", () => {
    fetch('../database/checkLogin.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                addPanel.classList.remove("hidden");
                addPetForm.reset();
            } else {
                alert("You must log in to add a pet.");
                window.location.href = "login.html";
            }
        });
});

// Save pet functionality
saveButton.addEventListener("click", () => {
    const type = document.getElementById("petSelect").value.trim();
    const name = document.getElementById("petName").value.trim();
    const age = parseInt(document.getElementById("petAge").value.trim(), 10);
    const history = document.getElementById("medicalHistory").value.trim();

    fetch('savePet.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type, name, age, history })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            createPetProfile(type, name, age, history, data.id);
            addPanel.classList.add("hidden");
        } else {
            alert(data.message);
        }
    });
});

// Close pet info panel
closeButton.addEventListener("click", () => {
    infoPanel.classList.add("hidden");
});

// Filter functionality
filterSelect.addEventListener("change", () => {
    const filterValue = filterSelect.value;
    currentPets.forEach(pet => {
        pet.style.display = (filterValue === "all" || pet.dataset.type === filterValue) ? "" : "none";
    });
});
