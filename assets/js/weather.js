
// 天気情報を取得する関数
async function getWeather(getLat, getLon) {
    // OpenWeatherMap APIキーを設定
    const apiKey = '93459484ae7930aca529e99312e9550e';
    const lat = getLat;
    const lon = getLon;

    const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=ja`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (response.ok) {
            displayWeather(data);
        } else {
            document.getElementById('weather').innerText = `エラー: ${data.message}`;
        }
    } catch (error) {
        document.getElementById('weather').innerText = `エラー: ${error.message}`;
    }
}

// 天気情報を表示する関数
function displayWeather(data) {
    const weatherDiv = document.getElementById('weather');
    const temperature = data.main.temp;
    const weatherDescription = data.weather[0].description;
    const humidity = data.main.humidity;
    const windSpeed = data.wind.speed;
    const iconCode = data.weather[0].icon;
    const iconUrl = `http://openweathermap.org/img/wn/${iconCode}@2x.png`;

    weatherDiv.innerHTML = `
    <div class="weather">
        <img src="${iconUrl}" alt="天気アイコン">
        <p>${weatherDescription}/気温: ${temperature}°C</p>
    </div>
`;
}