"use strict";

const CACHE_VERSION = 'v1';
const CACHE_NAME = `offline-cache-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';

// ⚠️ NE mettez en cache que les fichiers qui EXISTENT vraiment
const filesToCache = [
    OFFLINE_URL
    // Supprimez '/', '/manifest.json', '/css/app.css', '/js/app.js'
    // car ils génèrent des erreurs 404 qui bloquent l'installation
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('📦 Mise en cache...');
                return cache.addAll(filesToCache);
            })
            .then(() => {
                console.log('✅ Cache installé');
                return self.skipWaiting();
            })
            .catch(err => {
                console.error('❌ Erreur:', err);
            })
    );
});

self.addEventListener("fetch", (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => caches.match(OFFLINE_URL))
        );
    } else {
        event.respondWith(
            caches.match(event.request)
                .then(response => response || fetch(event.request))
                .catch(() => caches.match(OFFLINE_URL))
        );
    }
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames
                        .filter(name => name !== CACHE_NAME)
                        .map(name => caches.delete(name))
                );
            })
            .then(() => self.clients.claim())
    );
});
