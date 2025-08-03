<?php 
session_start();


require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'professional') {
    header("Location: login.php");
    exit();
}


$user_name = $_SESSION['name'];     

$user_email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professional Dashboard - QuickHire</title>
    <link rel="stylesheet" href="professional_dash.css">
</head>
<body>
    <header>
        <div class="logo">
           <a href="professional_dashboard.php" style="text-decoration: none;">QuickHire</a>
        </div>
        <nav>
            <ul>
                <li><a href="update_profile.php">Update Profile</a></li>
                <li><a href="#">Applied Jobs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="welcome">
            <h1>Welcome, <?php echo $_SESSION['name']; ?>!</h1>
            <p>Your next opportunity is just a few clicks away.</p>
        </section>

    
        <section class="search-jobs">
         <h2>Find Your Next Opportunity</h2>
         <form action="search_jobs.php" method="GET">
            <input class = "Job-role" type="text" name="role" placeholder="Job Role (e.g., Web Developer)">
            <input class = "location" type="text" name="location" id="location-input" placeholder="Location (e.g., Delhi)">
           <button type="submit">Search</button>
         </form>
        </section>
    </main>
    
    
    <?php include('config.php'); ?>

<script>
  function initAutocomplete() {
    const input = document.getElementById('location-input');
    const autocomplete = new google.maps.places.Autocomplete(input, {
      types: ['(cities)'],
      componentRestrictions: { country: "in" }
    });

    autocomplete.addListener('place_changed', function () {
      const place = autocomplete.getPlace();
      input.value = place.formatted_address || input.value;
    });
  }

  // Ensure it's globally accessible
  window.initAutocomplete = initAutocomplete;
</script>

<script
  src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLE_MAPS_API_KEY; ?>&libraries=places&callback=initAutocomplete"
  async defer>
</script>


</body>
</html>
