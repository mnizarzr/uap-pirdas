import { useEffect } from "react";

import Echo from "laravel-echo";
import { WaveConnector } from "laravel-wave";
window.Echo = new Echo({ broadcaster: WaveConnector, endpoint: 'http://127.0.0.1:8002/wave', });

export default function App() {

    useEffect(() => {
        window.Echo.channel(`sensors`).listen(".sensorsdata.created", (e) => {
            console.log(e.data);
        });
    }, []);

    return (
        <>
            <h1 className="text-3xl font-bold underline">Hello world!</h1>
        </>
    );
}
