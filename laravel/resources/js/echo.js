import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

//jw:note This *is* necessary!
window.Echo.channel("sonicom-ecosystem")
    .listen(".test-event", (event) => {
	    console.log("sonicom-ecosystem: message = " + event.message);
	    //console.log(event);
        //alert(event?.message)
});
window.Echo.channel("sonicom-ecosystem").listen(".datafile-processed", (event) => { console.log("sonicom-ecosystem:datafile-processed(id = " + event.id +")"); });

/*
window.Echo.channel("test").listen(".test-event", (event) => {
	    console.log("test: message = " + event.message);
	    //console.log(event);
        //alert(event?.message)
});*/
