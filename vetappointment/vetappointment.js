document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("appointmentForm");
    const dateInput = document.getElementById("date");
    const timeInput = document.getElementById("time");

    const today = new Date();
    const formattedToday = today.toISOString().split("T")[0];
    dateInput.setAttribute("min", formattedToday);
    
    // Form submission
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        // Get form input values
        const petSelect = document.getElementById("petSelect").value;
        const date = dateInput.value;
        const time = timeInput.value;
        const reason = document.getElementById("reason").value;

        // Validate inputs
        if (!petSelect || !date || !time || !reason.trim()) {
            alert("Please fill in all fields before submitting the form.");
            return;
        }

        // Validate date and time
        const selectedDateTime = new Date(`${date}T${time}`);
        const now = new Date();
        if (selectedDateTime <= now) {
            alert("Please select a date and time in the future.");
            return;
        }

        // Display a success message
        alert(
            `Appointment booked successfully!\n\n` +
            `Pet: ${petSelect}\n` +
            `Date: ${date}\n` +
            `Time: ${time}\n` +
            `Reason: ${reason}`
        );

        // Reset the form
        form.reset();
    });
});
