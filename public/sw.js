self.addEventListener('install', function(e) {
  self.skipWaiting();
});
self.addEventListener('fetch', function(event) {
  // You can add caching logic here if needed
});