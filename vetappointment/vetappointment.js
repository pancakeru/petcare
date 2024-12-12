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
                    option.value = pet.id; // Pet ID as value
                    option.textContent = `${pet.name} (${pet.type})`; // Display pet name and type
                    petSelect.appendChild(option);
                });
            } else {
                alert("Failed to load pets. Please add pets to your profile first.");
                console.error("Error loading pets:", data.error);
            }
        })
        .catch((error) => console.error("Error fetching pets:", error));

    // Form submission
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const petId = petSelect.value;
        const date = dateInput.value;
        const time = timeInput.value;
        const reason = document.getElementById("reason").value;

        if (!petId || !date || !time || !reason.trim()) {
            alert("Please fill in all fields before submitting the form.");
            return;
        }

        fetch("saveAppointment.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                petId: petId,
                date: date,
                time: time,
                reason: reason,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Appointment booked successfully!");
                    form.reset();
                } else {
                    alert("Failed to book appointment. Please try again.");
                }
            })
            .catch((error) => console.error("Error booking appointment:", error));
    });
});
