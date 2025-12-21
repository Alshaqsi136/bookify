<?php

class Hotel {
    private $hotel_id;
    private $hotel_name;
    private $city;
    private $country;
    private $hotel_type;
    private $star_rating;
    private $price_per_night;
    private $description;
    private $amenities;

    public function __construct($hotel_id, $hotel_name, $city, $country, $hotel_type, $star_rating, $price_per_night, $description, $amenities) {
        $this->hotel_id = $hotel_id;
        $this->hotel_name = $hotel_name;
        $this->city = $city;
        $this->country = $country;
        $this->hotel_type = $hotel_type;
        $this->star_rating = $star_rating;
        $this->price_per_night = $price_per_night;
        $this->description = $description;
        $this->amenities = $amenities;
    }

    // Getters
    public function getHotelId() { return $this->hotel_id; }
    public function getHotelName() { return $this->hotel_name; }
    public function getCity() { return $this->city; }
    public function getCountry() { return $this->country; }
    public function getHotelType() { return $this->hotel_type; }
    public function getStarRating() { return $this->star_rating; }
    public function getPricePerNight() { return $this->price_per_night; }
    public function getDescription() { return $this->description; }
    public function getAmenities() { return $this->amenities; }

    // Setters
    public function setHotelName($name) { $this->hotel_name = $name; }
    public function setCity($city) { $this->city = $city; }
    public function setCountry($country) { $this->country = $country; }
    public function setHotelType($type) { $this->hotel_type = $type; }
    public function setStarRating($rating) { $this->star_rating = $rating; }
    public function setPricePerNight($price) { $this->price_per_night = $price; }
    public function setDescription($desc) { $this->description = $desc; }
    public function setAmenities($amenities) { $this->amenities = $amenities; }

    public function display() {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($this->hotel_id) . "</td>";
        echo "<td>" . htmlspecialchars($this->hotel_name) . "</td>";
        echo "<td>" . htmlspecialchars($this->city) . "</td>";
        echo "<td>" . htmlspecialchars($this->hotel_type) . "</td>";
        echo "<td>";
        for ($i = 0; $i < $this->star_rating; $i++) {
            echo "★";
        }
        echo "</td>";
        echo "<td>$" . number_format($this->price_per_night, 2) . "</td>";
        echo "<td>" . htmlspecialchars($this->amenities) . "</td>";
        echo "</tr>";
    }
}

class Booking {
    private $booking_id;
    private $hotel_id;
    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $check_in_date;
    private $check_out_date;
    private $number_of_adults;
    private $number_of_children;
    private $number_of_rooms;
    private $special_requests;
    private $confirmation_method;
    private $total_price;
    private $booking_status;

    public function __construct($booking_id, $hotel_id, $first_name, $last_name, $email, $phone, 
                                $check_in_date, $check_out_date, $number_of_adults, $number_of_children, 
                                $number_of_rooms, $special_requests, $confirmation_method, $total_price, $booking_status = 'confirmed') {
        $this->booking_id = $booking_id;
        $this->hotel_id = $hotel_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->check_in_date = $check_in_date;
        $this->check_out_date = $check_out_date;
        $this->number_of_adults = $number_of_adults;
        $this->number_of_children = $number_of_children;
        $this->number_of_rooms = $number_of_rooms;
        $this->special_requests = $special_requests;
        $this->confirmation_method = $confirmation_method;
        $this->total_price = $total_price;
        $this->booking_status = $booking_status;
    }

    // Getters
    public function getBookingId() { return $this->booking_id; }
    public function getHotelId() { return $this->hotel_id; }
    public function getFirstName() { return $this->first_name; }
    public function getLastName() { return $this->last_name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getCheckInDate() { return $this->check_in_date; }
    public function getCheckOutDate() { return $this->check_out_date; }
    public function getNumberOfAdults() { return $this->number_of_adults; }
    public function getNumberOfChildren() { return $this->number_of_children; }
    public function getNumberOfRooms() { return $this->number_of_rooms; }
    public function getSpecialRequests() { return $this->special_requests; }
    public function getConfirmationMethod() { return $this->confirmation_method; }
    public function getTotalPrice() { return $this->total_price; }
    public function getBookingStatus() { return $this->booking_status; }

    // Setters
    public function setFirstName($name) { $this->first_name = $name; }
    public function setLastName($name) { $this->last_name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setCheckInDate($date) { $this->check_in_date = $date; }
    public function setCheckOutDate($date) { $this->check_out_date = $date; }
    public function setNumberOfRooms($rooms) { $this->number_of_rooms = $rooms; }
    public function setTotalPrice($price) { $this->total_price = $price; }
    public function setBookingStatus($status) { $this->booking_status = $status; }

    /**
     * Display booking information as formatted HTML
     */
    public function display() {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($this->booking_id) . "</td>";
        echo "<td>" . htmlspecialchars($this->first_name . " " . $this->last_name) . "</td>";
        echo "<td>" . htmlspecialchars($this->email) . "</td>";
        echo "<td>" . htmlspecialchars($this->phone) . "</td>";
        echo "<td>" . htmlspecialchars($this->check_in_date) . " to " . htmlspecialchars($this->check_out_date) . "</td>";
        echo "<td>" . htmlspecialchars($this->number_of_adults) . " adults, " . htmlspecialchars($this->number_of_children) . " children</td>";
        echo "<td>" . htmlspecialchars($this->number_of_rooms) . "</td>";
        echo "<td>$" . number_format($this->total_price, 2) . "</td>";
        echo "<td><span class='badge bg-" . ($this->booking_status === 'confirmed' ? 'success' : 'warning') . "'>" . htmlspecialchars($this->booking_status) . "</span></td>";
        echo "</tr>";
    }
}

class Feedback {
    private $feedback_id;
    private $customer_name;
    private $email;
    private $phone;
    private $booking_reference;
    private $overall_satisfaction;
    private $how_heard_about;
    private $features_used;
    private $feedback_message;
    private $improvement_suggestions;
    private $would_recommend;

    public function __construct($feedback_id, $customer_name, $email, $phone, $booking_reference, 
                               $overall_satisfaction, $how_heard_about, $features_used, 
                               $feedback_message, $improvement_suggestions, $would_recommend) {
        $this->feedback_id = $feedback_id;
        $this->customer_name = $customer_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->booking_reference = $booking_reference;
        $this->overall_satisfaction = $overall_satisfaction;
        $this->how_heard_about = $how_heard_about;
        $this->features_used = $features_used;
        $this->feedback_message = $feedback_message;
        $this->improvement_suggestions = $improvement_suggestions;
        $this->would_recommend = $would_recommend;
    }

    // Getters
    public function getFeedbackId() { return $this->feedback_id; }
    public function getCustomerName() { return $this->customer_name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getBookingReference() { return $this->booking_reference; }
    public function getOverallSatisfaction() { return $this->overall_satisfaction; }
    public function getHowHeardAbout() { return $this->how_heard_about; }
    public function getFeaturesUsed() { return $this->features_used; }
    public function getFeedbackMessage() { return $this->feedback_message; }
    public function getImprovementSuggestions() { return $this->improvement_suggestions; }
    public function getWouldRecommend() { return $this->would_recommend; }

    // Setters
    public function setCustomerName($name) { $this->customer_name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setOverallSatisfaction($rating) { $this->overall_satisfaction = $rating; }
    public function setFeedbackMessage($message) { $this->feedback_message = $message; }
    public function setWouldRecommend($recommend) { $this->would_recommend = $recommend; }

    /**
     * Display feedback information as formatted HTML
     */
    public function display() {
        $starDisplay = str_repeat("★", $this->overall_satisfaction) . str_repeat("☆", 5 - $this->overall_satisfaction);
        echo "<tr>";
        echo "<td>" . htmlspecialchars($this->feedback_id) . "</td>";
        echo "<td>" . htmlspecialchars($this->customer_name) . "</td>";
        echo "<td>" . htmlspecialchars($this->email) . "</td>";
        echo "<td>" . $starDisplay . " (" . htmlspecialchars($this->overall_satisfaction) . "/5)</td>";
        echo "<td>" . htmlspecialchars(substr($this->feedback_message, 0, 50)) . "...</td>";
        echo "<td><span class='badge bg-" . ($this->would_recommend === 'yes' ? 'success' : ($this->would_recommend === 'maybe' ? 'warning' : 'danger')) . "'>" . ucfirst(htmlspecialchars($this->would_recommend)) . "</span></td>";
        echo "</tr>";
    }
}

function displayHotelsTable($hotels) {
    if (empty($hotels)) {
        echo "<p class='alert alert-info'>No hotels found.</p>";
        return;
    }
    
    echo "<table class='table table-striped table-hover'>";
    echo "<thead class='table-dark'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Hotel Name</th>";
    echo "<th>City</th>";
    echo "<th>Type</th>";
    echo "<th>Rating</th>";
    echo "<th>Price/Night</th>";
    echo "<th>Amenities</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($hotels as $hotel) {
        $hotel->display();
    }
    
    echo "</tbody>";
    echo "</table>";
}

function displayBookingsTable($bookings) {
    if (empty($bookings)) {
        echo "<p class='alert alert-info'>No bookings found.</p>";
        return;
    }
    
    echo "<table class='table table-striped table-hover'>";
    echo "<thead class='table-dark'>";
    echo "<tr>";
    echo "<th>Booking ID</th>";
    echo "<th>Guest Name</th>";
    echo "<th>Email</th>";
    echo "<th>Phone</th>";
    echo "<th>Dates</th>";
    echo "<th>Guests</th>";
    echo "<th>Rooms</th>";
    echo "<th>Total Price</th>";
    echo "<th>Status</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($bookings as $booking) {
        $booking->display();
    }
    
    echo "</tbody>";
    echo "</table>";
}

function displayFeedbackTable($feedback) {
    if (empty($feedback)) {
        echo "<p class='alert alert-info'>No feedback found.</p>";
        return;
    }
    
    echo "<table class='table table-striped table-hover'>";
    echo "<thead class='table-dark'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Customer</th>";
    echo "<th>Email</th>";
    echo "<th>Satisfaction</th>";
    echo "<th>Feedback</th>";
    echo "<th>Recommend</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($feedback as $item) {
        $item->display();
    }
    
    echo "</tbody>";
    echo "</table>";
}

?>
