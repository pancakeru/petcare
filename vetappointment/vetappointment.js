document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("appointmentForm");

    // Form submission event listener
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        // Get form input values
        const petSelect = document.getElementById("petSelect").value;
        const date = document.getElementById("date").value;
        const time = document.getElementById("time").value;
        const reason = document.getElementById("reason").value;

        // Validate inputs
        if (!petSelect || !date || !time || !reason.trim()) {
            alert("Please fill in all fields before submitting the form.");
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
