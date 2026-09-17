const CACHE_NAME = 'staff-resi-v1';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', (event) => {
    // Online first strategy
    if (event.request.method !== 'GET') return;
    
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});
