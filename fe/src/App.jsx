import { useEffect, useState } from "react";

import Echo from "laravel-echo";
import { WaveConnector } from "laravel-wave";
window.Echo = new Echo({ broadcaster: WaveConnector, endpoint: 'http://127.0.0.1:8001/wave', });

export default function App() {

    const [data, setData] = useState({
        rain: 0,
        soil: 0,
        light: 0,
        temperature: 0,
        humidity: 0
    })

    useEffect(() => {
        window.Echo.channel(`sensors`).listen(".sensorsdata.created", (e) => {
            console.log(e.data)
            setData(e.data)
        });
    }, []);

    return (
        <>
            <div className="bg-gray-800 p-4 rounded-lg shadow-md text-white text-4xl text-center">
                Rain: {data.rain}
            </div>
            <div className="bg-gray-800 p-4 rounded-lg shadow-md text-white text-4xl text-center">
                Soil: {data.soil}
            </div>
            <div className="bg-gray-800 p-4 rounded-lg shadow-md text-white text-4xl text-center">
                Light: {data.light}
            </div>
            <div className="bg-gray-800 p-4 rounded-lg shadow-md text-white text-4xl text-center">
                Temperature: {data.temperature}
            </div>
            <div className="bg-gray-800 p-4 rounded-lg shadow-md text-white text-4xl text-center">
                Humidity: {data.humidity}
            </div>
        </>
    );
}
