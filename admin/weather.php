<?php
require('top.inc.php');

$api_key = 'cff6b39253c37021471f9dc1900934ff'; // Replace with your actual API key
$units = 'metric'; // Use 'imperial' for Fahrenheit

// Check if a city has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['city'])) {
    $city = urlencode($_POST['city']);
} else {
    $city = urlencode('kampala'); // Default city
}

$current_weather_url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$api_key}&units={$units}";
$forecast_url = "http://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$api_key}&units={$units}";

function get_api_data($url) {
    $response = @file_get_contents($url);
    if ($response === false) {
        return null;
    }
    return json_decode($response, true);
}

$current_weather = get_api_data($current_weather_url);
$forecast = get_api_data($forecast_url);

function get_weather_icon($icon_code) {
    $icon_map = [
        '01d' => 'fas fa-sun',
        '01n' => 'fas fa-moon',
        '02d' => 'fas fa-cloud-sun',
        '02n' => 'fas fa-cloud-moon',
        '03d' => 'fas fa-cloud',
        '03n' => 'fas fa-cloud',
        '04d' => 'fas fa-cloud',
        '04n' => 'fas fa-cloud',
        '09d' => 'fas fa-cloud-showers-heavy',
        '09n' => 'fas fa-cloud-showers-heavy',
        '10d' => 'fas fa-cloud-sun-rain',
        '10n' => 'fas fa-cloud-moon-rain',
        '11d' => 'fas fa-bolt',
        '11n' => 'fas fa-bolt',
        '13d' => 'far fa-snowflake',
        '13n' => 'far fa-snowflake',
        '50d' => 'fas fa-smog',
        '50n' => 'fas fa-smog'
    ];
    
    return $icon_map[$icon_code] ?? 'fas fa-question';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Search</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
        .weather-icon { font-size: 2em; }
    </style>
</head>
<body>
    
    <div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title"> Weather Forecast </h4>
        <h2 class="text-center mb-4">Weather Search</h2>
        
        <form method="post" class="mb-4">
            <div class="input-group">
                <input type="text" name="city" class="form-control" placeholder="Enter city name" required>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>

        <?php if ($current_weather && isset($current_weather['main'])): ?>
            <h2 class="text-center">Weather for <?php echo $current_weather['name']; ?></h2>
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">Current Weather</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-thermometer-half"></i> Temperature: <?php echo $current_weather['main']['temp']; ?>°C</p>
                            <p><i class="fas fa-tint"></i> Humidity: <?php echo $current_weather['main']['humidity']; ?>%</p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="fas fa-wind"></i> Wind Speed: <?php echo $current_weather['wind']['speed']; ?> m/s</p>
                            <p>
                                <i class="<?php echo get_weather_icon($current_weather['weather'][0]['icon']); ?> weather-icon"></i>
                                <?php echo ucfirst($current_weather['weather'][0]['description']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Current weather data unavailable. Error: <?php echo $current_weather['message'] ?? 'City not found or API error'; ?>
            </div>
        <?php endif; ?>

        <?php if ($forecast && isset($forecast['list'])): ?>
            <h3 class="text-center mb-3">5-Day Forecast</h3>
            <div class="row">
                <?php
                $forecast_days = array();
                foreach ($forecast['list'] as $forecast_item) {
                    $date = date('Y-m-d', $forecast_item['dt']);
                    if (!isset($forecast_days[$date]) && count($forecast_days) < 5) {
                        $forecast_days[$date] = $forecast_item;
                    }
                }
                foreach ($forecast_days as $date => $day):
                ?>
                    <div class="col-md-4 col-lg-2 mb-3">
                        <div class="card">
                            <div class="card-body text-center .bg-primary-subtle">
                                <h5 class="card-title"><?php echo date('D', strtotime($date)); ?></h5>
                                <i class="<?php echo get_weather_icon($day['weather'][0]['icon']); ?> weather-icon mb-2"></i>
                                <p class="card-text"><?php echo $day['main']['temp']; ?>°C</p>
                                <p class="card-text small"><?php echo ucfirst($day['weather'][0]['description']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Forecast data unavailable. Error: <?php echo $forecast['message'] ?? 'City not found or API error'; ?>
            </div>
        <?php endif; ?>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require('footer.inc.php');
?>