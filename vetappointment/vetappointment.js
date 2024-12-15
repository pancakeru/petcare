document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("appointmentForm");
    const petSelect = document.getElementById("petSelect");
    const dateInput = document.getElementById("date");
    const timeInput = document.getElementById("time");

    // Set the minimum date to today
    const today = new Date();
    const formattedToday = today.toISOString().split("T")[0];
    dateInput.setAttribute("min", formattedToday);

    // Load pets from server
    fetch("getPets.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                data.pets.forEach((pet) => {
                    const option = document.createElement("option");
                    option.value = pet.id; 
                    option.textContent = `${pet.name} (${pet.type})`; 
                    petSelect.appendChild(option);
                });
            } else {
                alert("Please add pets to your profile first.");
                console.error("Error loading pets:", data.error);
            }
        })
        .catch((error) => console.error("Error fetching pets:", error));

    
    // Submit the appointment form
    form.addEventListener("submit", event => {
        event.preventDefault();

        const petId = petSelect.value;
        const date = dateInput.value;
        const time = timeInput.value;
        const reason = document.getElementById("reason").value;

        if (!petId || !date || !time || !reason.trim()) {
            alert("Please fill in all fields.");
            return;
        }

        fetch("saveAppointment.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ petId, date, time, reason }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    form.reset();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error("Error booking appointment:", error));
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const appointmentsContainer = document.getElementById("appointments");

    fetch("getAppointment.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                appointmentsContainer.innerHTML = "";
                data.appointments.forEach(appointment => {
                    const item = document.createElement("div");
                    item.classList.add("appointment-item");
                    item.innerHTML = `
                        <strong>${appointment.petName} (${appointment.petType})</strong>
                        <p>${appointment.date} at ${appointment.time}</p>
                        <p>${appointment.reason}</p>
                    `;
                    appointmentsContainer.appendChild(item);
                });
            } else {
                alert("Failed to load appointments.");
            }
        })
        .catch(error => console.error("Error loading appointments:", error));
});

