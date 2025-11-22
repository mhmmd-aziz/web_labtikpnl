// public/sw.js

// Saat notifikasi DITERIMA (saat web ditutup)
self.addEventListener('push', function(event) {
    const data = event.data.json();
    const options = {
        body: data.body,
        icon: data.icon,
        data: {
            url: data.data.url 
        }
    };
    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Saat notifikasi DI-KLIK
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});