// -----------------------------------------------------------
// Constructor Function for Destination objects
// Each destination contains basic travel information:
// name, city, region, best time to visit, hotel count,
// resort count, and typical price range.
// -----------------------------------------------------------
function Destination(name, city, region, bestTime, hotels, resorts, price) {
    this.name = name;
    this.city = city;
    this.region = region;
    this.bestTime = bestTime;
    this.hotels = hotels;
    this.resorts = resorts;
    this.price = price;
}

// -----------------------------------------------------------
// Initial Array of Popular Omani Destinations
// These will be displayed on the page when it loads.
// -----------------------------------------------------------
let destinationsArray = [
    new Destination("Muscat", "Muscat", "Capital", "Oct - Apr", 45, 12, "80-200"),
    new Destination("Salalah", "Salalah", "Dhofar", "Jun - Sep (Khareef)", 25, 8, "50-150"),
    new Destination("Nizwa", "Nizwa", "Ad Dakhiliyah", "Oct - Mar", 15, 5, "60-180"),
    new Destination("Sur", "Sur", "Ash Sharqiyah", "Nov - Feb", 10, 3, "40-120"),
    new Destination("Sohar", "Sohar", "Al Batinah", "Oct - Apr", 12, 2, "45-100")
];

// -----------------------------------------------------------
// Display ALL destinations in the table
// Called on page load or after adding new destination
// -----------------------------------------------------------
function displayAllDestinations() {
    const tableBody = document.getElementById('destinationsTableBody');
    tableBody.innerHTML = ''; // Clear previous rows
    
    // Loop through all destinations and create table rows
    for (let i = 0; i < destinationsArray.length; i++) {
        const dest = destinationsArray[i];
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td><strong>${dest.name}</strong></td>
            <td>${dest.city}</td>
            <td>${dest.region}</td>
            <td>${dest.bestTime}</td>
            <td>${dest.hotels}+</td>
            <td>${dest.resorts}+</td>
            <td>${dest.price}</td>
        `;
        
        tableBody.appendChild(row);
    }
}

// -----------------------------------------------------------
// Display filtered destinations based on a search query
// User can search by: name, city, or region
// -----------------------------------------------------------
function displayFilteredDestinations(searchQuery) {
    const tableBody = document.getElementById('destinationsTableBody');
    tableBody.innerHTML = '';
    
    const query = searchQuery.toLowerCase().trim();
    
    // Filter destinations that match user input
    const filteredDestinations = destinationsArray.filter(dest => {
        return dest.name.toLowerCase().includes(query) ||
               dest.city.toLowerCase().includes(query) ||
               dest.region.toLowerCase().includes(query);
    });
    
    // Show message if no matches found
    if (filteredDestinations.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="7" class="text-center text-muted">
            No destinations found matching "${searchQuery}"
        </td>`;
        tableBody.appendChild(row);
        return;
    }
    
    // Display matching destinations
    for (let i = 0; i < filteredDestinations.length; i++) {
        const dest = filteredDestinations[i];
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td><strong>${dest.name}</strong></td>
            <td>${dest.city}</td>
            <td>${dest.region}</td>
            <td>${dest.bestTime}</td>
            <td>${dest.hotels}+</td>
            <td>${dest.resorts}+</td>
            <td>${dest.price}</td>
        `;
        
        tableBody.appendChild(row);
    }
}

// -----------------------------------------------------------
// MAIN LOGIC — Runs when the page finishes loading
// Handles both adding new destinations and searching
// -----------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    
    // Show all destinations on initial load
    displayAllDestinations();
    
    // -------------------------------
    // Add Destination Form Submission
    // -------------------------------
    const addForm = document.getElementById('addDestinationForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page reload
            
            // Get user input values
            const name = document.getElementById('destName').value.trim();
            const city = document.getElementById('destCity').value.trim();
            const region = document.getElementById('destRegion').value.trim();
            const bestTime = document.getElementById('destTime').value.trim();
            const hotels = parseInt(document.getElementById('destHotels').value);
            const resorts = parseInt(document.getElementById('destResorts').value);
            const price = document.getElementById('destPrice').value.trim();
            
            // Validate all fields before adding new record
            if (name && city && region && bestTime && !isNaN(hotels) && !isNaN(resorts) && price) {
                
                // Create new Destination and add to array
                const newDestination = new Destination(name, city, region, bestTime, hotels, resorts, price);
                destinationsArray.push(newDestination);
                
                displayAllDestinations(); // Refresh table
                addForm.reset();          // Clear form
                
                alert('Destination added successfully!');
            } else {
                alert('Please fill in all fields correctly.');
            }
        });
    }
    
    // -------------------------------
    // Search Destination Form
    // -------------------------------
    const searchForm = document.getElementById('searchDestinationForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const searchQuery = document.getElementById('searchDest').value;
            
            // If search box empty → show all destinations
            if (searchQuery.trim() === '') {
                displayAllDestinations();
            } else {
                displayFilteredDestinations(searchQuery);
            }
        });
    }
});
