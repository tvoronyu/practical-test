<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Full Stack Developer practical test</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>
        .d-flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        #result-list {
            height: 400px;
            min-width: 200px;
            overflow: hidden;
            overflow-y: auto;
            border: 1px solid black;
            padding: 5px;
        }

        .result-title {
            border-bottom: 1px solid black;
            text-align: center;
        }

    </style>
</head>
<body>
<main>
    <div class="d-flex justify-content-between">
        <div>
            <select id="places">
            </select>
        </div>
        <div>
            <label for="range">Radius</label>
            <input id="range" type="range" min="0" max="100" step="1" value="0">
            <span id="range-value">0</span>
        </div>
        <div id="result-list">
            <p class="result-title">Results</p>
        </div>
    </div>
</main>
</body>
<script>
    $( document ).ready(function() {
        let places = {};
        let selectPlace = {}
        let currentRadius = 0;
        let placesInRadius = [];

        handle();

        $.get('/getPlaces', (data) => {
            places = data.places;
            selectPlace = places[0];
            currentRadius = searchRadius(selectPlace.coordinates)
            update();
            console.log(currentRadius)
            places.forEach((val) => {
                $('#places').append('<option value="'+val.name+'">'+val.name+'</option>');
            })

        })

        function handle() {
            $( "#places" ).change((event) => {
                const placeName = $('#places').val();
                selectPlace = places.find((val) => val.name === placeName)
                console.log(selectPlace)
                currentRadius = searchRadius(selectPlace.coordinates)
                update();
                console.log(currentRadius)
            });

            $("#range").change(event => {
                currentRadius = event.target.value;
                update();
            })
        }

        function update() {
            setPlacesBySearch();
            setRangeValue();
        }

        function searchRadius(coordinates) {
            return (Number(coordinates.latitude))^2 + (Number(coordinates.longitude))^2
        }

        function setRangeValue() {
            $('#range').val(currentRadius)
            $('#range-value').text(currentRadius);
        }

        function searchPlacesByRadius(radius) {
            return places.filter(val => searchRadius(val.coordinates) <= radius)
        }

        function setPlacesBySearch() {
            $('#result-list').text('');
            placesInRadius = searchPlacesByRadius(currentRadius)
            placesInRadius.sort((a, b) => {
                const distA = Number(currentRadius) - searchRadius(a.coordinates);
                const distB = Number(currentRadius) - searchRadius(b.coordinates);
                if (distA < distB)
                    return -1;
                if (distA => distB)
                    return 1
                return 0;
            }).forEach(val => {
                const dist = Number(currentRadius) - searchRadius(val.coordinates);
                $('#result-list').append('<p>'+val.name+'  dist: '+dist+'km</p>')
            })
            console.log(placesInRadius, 'placesInRadius')
        }

    });
</script>
</html>
