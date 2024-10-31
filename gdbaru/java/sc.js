let myButton = document.getElementById('favorite');

// Tambahkan event listener untuk mendeteksi klik
myButton.addEventListener('click', function(event) {
  // Mencegah aksi default, seperti berpindah halaman
  event.preventDefault();
  
  // Tampilkan alert
  alert('Harap login dahulu');
});

