import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

let status = null;

// Kiểm tra
console.log(window.Echo);

// Lắng nghe channel
window.Echo.channel('qr-channel')
    .listen('.QRScan', async (e) => {
        console.log('QRScan event:', JSON.stringify({ id: e.message }));

        try {
            const res = await fetch(`/api/process-qr/${e.message}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            });

            const data = await res.json();
            console.log('API response:', data);
            status = data.status;

            renderForm(data);

        } catch (err) {
            console.error('API call failed:', err);
        }

    });

function renderForm(data) {
    const el = document.getElementById('formArea');
    if (status === null) {
        el.innerHTML = "";
    } else if (status === true) {
        let contracts = data.contracts;

        let html = "<div>";
        contracts.forEach(item => {
            html += `<form id="hiddenForm" action="/process-token" method="POST">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                <input type="hidden" name="id" value="${item.id}">
                <div>${item.contract_details}</div>
                <input type="" name="contract_amount" value="${item.contract_amount}">
                <input type="submit" value="submit">
            </form>`;
        });
        html += "</div>";

        el.innerHTML = html;
    } else {
        el.innerHTML = "❌ Lỗi khi xử lý";
    }
}
