


<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>

    <p style="color: green; font-weight: bold;">Job added successfully!</p>
    
<?php endif; ?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Job</title>
  <link rel="stylesheet" href="addjob_style.css">
  <style>
    /* Optional styling */
    .pac-container {
      z-index: 10000 !important;
    }
  </style>
</head>
<body>
   <header>
        <div class="logo">
            <a href="professional_dashboard.php" style="text-decoration: none;">QuickHire</a>
        </div>
        <nav>
            <ul>
                <li><a href="company_dashboard.php">Home</a></li>
            </ul>
        </nav>
    </header>

  <div class="add-job-page">

  <h2>Add a New Job</h2>

  <form method="POST" action="process_add_job.php">
    <label>Job Title</label>
    <input type="text" name="job_title" required>

    <label>Job Description</label>
    <textarea name="job_description" rows="4" required></textarea>

    <label for="location-input">Location</label>
    <input type="text" name="location" id="location-input" required placeholder="Enter a city">

    <label>Salary</label>
    <input type="text" name="salary">

    <label>Company</label>
    <input type="text" name="company">

    <label for="job_type">Job Type</label>
    <select name="job_type" id="job_type" class="job-select" required>
      <option value="">-- Select --</option>
      <option value="On-site">On-site</option>
      <option value="Remote">Remote</option>
      <option value="Hybrid">Hybrid</option>
    </select>

    <button type="submit" name="submit">Add Job</button>
  </form>

  <!-- Load Google Places API -->
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
  </div>

</body>
</html>

 