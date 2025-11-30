// -----------------------------------------------------------
// Function: updateBanner()
// Updates the scrolling banner with the current date and time
// -----------------------------------------------------------
function updateBanner() {
    // Get the banner element from the page
    const banner = document.getElementById('scrollingBanner');
    
    // Only update if the banner exists on this page
    if (banner) {
        // Get the current date and time
        const now = new Date();

        // Format the date properly (e.g., Monday, January 15, 2025)
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const currentDate = now.toLocaleDateString('en-US', options);

        // Format the time (HH:MM:SS)
        const currentTime = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });

        // Update the text shown in the banner
        banner.textContent = 
            `Welcome to the Booklify website! Today is ${currentDate}, and the time is ${currentTime}`;
    }
}

// -----------------------------------------------------------
// Run when the page finishes loading
// -----------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    updateBanner();              // Update banner immediately when page loads
    setInterval(updateBanner, 1000); // Update every second to keep time accurate
});
