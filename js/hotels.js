// -----------------------------------
// Hotel Constructor (Blueprint Object)
// -----------------------------------
// Creates a hotel object with all required details.
function Hotel(name, stars, city, area, pool, spa, restaurant, priceRange, rating) {
    this.name = name;
    this.stars = stars;
    this.city = city;
    this.area = area;
    this.pool = pool;
    this.spa = spa;
    this.restaurant = restaurant;
    this.priceRange = priceRange;
    this.rating = rating;
}

// ----------------------------------------------------
// Converts a number of stars into ★ symbols for display
// ----------------------------------------------------
function getStarDisplay(stars) {
    let starDisplay = '';
    for (let i = 0; i < stars; i++) {
        starDisplay += '★';
    }
    return starDisplay;
}

// -------------------------------------------
// Predefined list of hotels displayed by default
// -------------------------------------------
let hotelsArray = [
    new Hotel("The Chedi Muscat", 5, "Muscat", "Beachfront", "✓", "✓", "✓", "120-200", 9.3),
    new Hotel("Al Bustan Palace", 5, "Muscat", "Al Bustan", "✓", "✓", "✓", "150-250", 9.2),
    new Hotel("Salalah Rotana Resort", 4, "Salalah", "Beachfront", "✓", "✗", "✓", "50-100", 8.2),
    new Hotel("Anantara Al Jabal Al Akhdar", 5, "Nizwa", "Mountain", "✓", "✓", "✓", "180-300", 8.9),
    new Hotel("Grand Hyatt Muscat", 5, "Muscat", "Downtown", "✓", "✓", "✓", "100-180", 8.7),
    new Hotel("Crowne Plaza Muscat", 4, "Muscat", "City Center", "✓", "✗", "✓", "60-120", 8.5)
];

// ---------------------------------------------------------------
// Displays ALL hotels inside the HTML table
// Called on page load or after adding a new hotel
// ---------------------------------------------------------------
function displayAllHotels() {
    const tableBody = document.getElementById('hotelsTableBody');
    tableBody.innerHTML = ''; // Clear previous list
    
    for (let i = 0; i < hotelsArray.length; i++) {
        const hotel = hotelsArray[i];
        const row = document.createElement('tr');
        const starDisplay = getStarDisplay(hotel.stars);
        
        // Insert hotel info into table row
        row.innerHTML = `
            <td><strong>${hotel.name}</strong></td>
            <td>${starDisplay}</td>
            <td>${hotel.city}</td>
            <td>${hotel.area}</td>
            <td>${hotel.pool}</td>
            <td>${hotel.spa}</td>
            <td>${hotel.restaurant}</td>
            <td>${hotel.priceRange}</td>
            <td>${hotel.rating}</td>
        `;
        
        tableBody.appendChild(row);
    }
}

// ------------------------------------------------------------
// Filters hotels based on a search query (name, city, or area)
// ------------------------------------------------------------
function displayFilteredHotels(searchQuery) {
    const tableBody = document.getElementById('hotelsTableBody');
    tableBody.innerHTML = ''; // Clear table
    
    const query = searchQuery.toLowerCase().trim();
    
    // Find hotels that match the search text
    const filteredHotels = hotelsArray.filter(hotel => {
        return hotel.name.toLowerCase().includes(query) ||
               hotel.city.toLowerCase().includes(query) ||
               hotel.area.toLowerCase().includes(query);
    });
    
    // Show message if no results found
    if (filteredHotels.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="9" class="text-center text-muted">
            No hotels found matching "${searchQuery}"
        </td>`;
        tableBody.appendChild(row);
        return;
    }
    
    // Display filtered results
    for (let i = 0; i < filteredHotels.length; i++) {
        const hotel = filteredHotels[i];
        const row = document.createElement('tr');
        const starDisplay = getStarDisplay(hotel.stars);
        
        row.innerHTML = `
            <td><strong>${hotel.name}</strong></td>
            <td>${starDisplay}</td>
            <td>${hotel.city}</td>
            <td>${hotel.area}</td>
            <td>${hotel.pool}</td>
            <td>${hotel.spa}</td>
            <td>${hotel.restaurant}</td>
            <td>${hotel.priceRange}</td>
            <td>${hotel.rating}</td>
        `;
        
        tableBody.appendChild(row);
    }
}

// -----------------------------------------------
// MAIN PAGE LOGIC — runs when the page is loaded
// -----------------------------------------------
document.addEventListener('DOMContentLoaded', function() {

    // Show all hotels on page load
    displayAllHotels();

    // ------------- Add New Hotel Form -------------
    const addForm = document.getElementById('addHotelForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page reload
            
            // Get all input values
            const name = document.getElementById('hotelName').value.trim();
            const stars = parseInt(document.getElementById('hotelStars').value);
            const city = document.getElementById('hotelCity').value.trim();
            const area = document.getElementById('hotelArea').value.trim();
            const pool = document.getElementById('hotelPool').value;
            const spa = document.getElementById('hotelSpa').value;
            const restaurant = document.getElementById('hotelRestaurant').value;
            const priceRange = document.getElementById('hotelPrice').value.trim();
            const rating = parseFloat(document.getElementById('hotelRating').value);
            
            // Validate inputs before adding
            if (name && !isNaN(stars) && city && area && pool && spa && restaurant && priceRange && !isNaN(rating)) {
                
                // Create and add new hotel
                const newHotel = new Hotel(name, stars, city, area, pool, spa, restaurant, priceRange, rating);
                hotelsArray.push(newHotel);
                
                displayAllHotels(); // Refresh list
                addForm.reset();    // Clear the form
                
                alert('Hotel added successfully!');
            } else {
                alert('Please fill in all fields correctly.');
            }
        });
    }
    
    // ------------- Search Hotel Form -------------
    const searchForm = document.getElementById('searchHotelForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const searchQuery = document.getElementById('searchHotel').value;
            
            // If search box empty → show all
            if (searchQuery.trim() === '') {
                displayAllHotels();
            } else {
                displayFilteredHotels(searchQuery);
            }
        });
    }
});
