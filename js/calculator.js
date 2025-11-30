// -------------------------------------------------------------
// CONSTANTS: Service prices and discount/tax information
// -------------------------------------------------------------
const SERVICE_PRICES = {
    breakfast: 5,   // OMR per night
    parking: 3,     // OMR per night
    wifi: 2,        // OMR per night
    spa: 15,        // OMR per night
    airport: 20     // One-time fee
};

const TAX_RATE = 0.05;               // 5% tax
const SENIOR_DISCOUNT_RATE = 0.10;   // 10% discount
const SENIOR_AGE_THRESHOLD = 60;     // Age required for discount

// -------------------------------------------------------------
// Main function that calculates the total booking cost
// -------------------------------------------------------------
function calculateTotal() {
    // Read user inputs
    const roomRate = parseFloat(document.getElementById('roomRate').value) || 0;
    const nights = parseInt(document.getElementById('nights').value) || 0;
    const rooms = parseInt(document.getElementById('rooms').value) || 0;
    const guestAge = parseInt(document.getElementById('guestAge').value) || 0;

    // Validate inputs
    if (roomRate <= 0 || nights <= 0 || rooms <= 0 || guestAge <= 0) {
        alert('Please fill in all required fields with valid values.');
        return;
    }

    // Base cost = nightly rate × nights × number of rooms
    const baseCost = roomRate * nights * rooms;

    // ---------------------------------------------------------
    // SERVICE COSTS
    // Calculates the costs for selected extra services
    // ---------------------------------------------------------
    let servicesCost = 0;
    const selectedServices = [];

    if (document.getElementById('breakfast').checked) {
        const breakfastCost = SERVICE_PRICES.breakfast * nights;
        servicesCost += breakfastCost;
        selectedServices.push(`Breakfast: OMR ${breakfastCost.toFixed(2)}`);
    }

    if (document.getElementById('parking').checked) {
        const parkingCost = SERVICE_PRICES.parking * nights;
        servicesCost += parkingCost;
        selectedServices.push(`Parking: OMR ${parkingCost.toFixed(2)}`);
    }

    if (document.getElementById('wifi').checked) {
        const wifiCost = SERVICE_PRICES.wifi * nights;
        servicesCost += wifiCost;
        selectedServices.push(`WiFi: OMR ${wifiCost.toFixed(2)}`);
    }

    if (document.getElementById('spa').checked) {
        const spaCost = SERVICE_PRICES.spa * nights;
        servicesCost += spaCost;
        selectedServices.push(`Spa: OMR ${spaCost.toFixed(2)}`);
    }

    if (document.getElementById('airport').checked) {
        servicesCost += SERVICE_PRICES.airport;
        selectedServices.push(`Airport Transfer: OMR ${SERVICE_PRICES.airport}`);
    }

    // ---------------------------------------------------------
    // DISCOUNT CALCULATION
    // Senior discount applies if guest age ≥ 60
    // ---------------------------------------------------------
    let discountAmount = 0;
    let discountReason = '';

    if (guestAge >= SENIOR_AGE_THRESHOLD) {
        discountAmount = baseCost * SENIOR_DISCOUNT_RATE;
        discountReason = `Senior Discount (${SENIOR_DISCOUNT_RATE * 100}% for guests aged ${SENIOR_AGE_THRESHOLD}+ )`;
    } else {
        discountAmount = 0;
        discountReason = 'No discount applied';
    }

    // Subtotal before tax: base cost + services - discount
    const subtotal = baseCost + servicesCost - discountAmount;

    // Tax amount based on subtotal
    const taxAmount = subtotal * TAX_RATE;

    // Final total cost
    const totalCost = subtotal + taxAmount;

    // Display the results to the user
    displayResults(
        baseCost, servicesCost, discountAmount, discountReason,
        subtotal, taxAmount, totalCost, roomRate, nights, rooms, guestAge
    );
}

// -------------------------------------------------------------
// Displays calculation results in the results card
// -------------------------------------------------------------
function displayResults(baseCost, servicesCost, discountAmount, discountReason, subtotal, taxAmount, totalCost, roomRate, nights, rooms, guestAge) {
    document.getElementById('resultsCard').style.display = 'block';

    // Update values inside the results card
    document.getElementById('baseCost').textContent = `OMR ${baseCost.toFixed(2)}`;
    document.getElementById('servicesCost').textContent = `OMR ${servicesCost.toFixed(2)}`;
    document.getElementById('discountAmount').textContent = `- OMR ${discountAmount.toFixed(2)}`;
    document.getElementById('discountReason').textContent = discountReason;
    document.getElementById('subtotal').textContent = `OMR ${subtotal.toFixed(2)}`;
    document.getElementById('taxAmount').textContent = `OMR ${taxAmount.toFixed(2)}`;
    document.getElementById('totalCost').textContent = `OMR ${totalCost.toFixed(2)}`;

    // ---------------------------------------------------------
    // Create a step-by-step formula explanation for the user
    // ---------------------------------------------------------
    let formulaText =
        `Base Cost = ${roomRate} × ${nights} × ${rooms} = OMR ${baseCost.toFixed(2)}`;

    if (servicesCost > 0) {
        formulaText += ` | Services = OMR ${servicesCost.toFixed(2)}`;
    }

    if (discountAmount > 0) {
        formulaText += ` | Discount: -OMR ${discountAmount.toFixed(2)}`;
    }

    formulaText += ` | Subtotal = OMR ${subtotal.toFixed(2)}`;
    formulaText += ` | Tax (${TAX_RATE * 100}%) = OMR ${taxAmount.toFixed(2)}`;
    formulaText += ` | Total = OMR ${totalCost.toFixed(2)}`;

    document.getElementById('formulaExplanation').textContent = formulaText;

    // Scroll to results card
    document.getElementById('resultsCard').scrollIntoView({
        behavior: 'smooth',
        block: 'nearest'
    });
}

// -------------------------------------------------------------
// Hides the results when user presses the "Reset" button
// -------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('calculatorForm');
    const resetButton = form.querySelector('button[type="reset"]');

    if (resetButton) {
        resetButton.addEventListener('click', function() {
            document.getElementById('resultsCard').style.display = 'none';
        });
    }
});
