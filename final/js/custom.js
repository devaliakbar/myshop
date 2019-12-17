$(document).ready(function () {
      $(document).keydown(function (e) {
            if (e.keyCode == 80 && e.altKey) {
                  e.preventDefault();
                  window.open("http://localhost/final/purchase", '_blank');
            } else if (e.keyCode == 83 && e.altKey) {
                  e.preventDefault();
                  window.open("http://localhost/final/sales", '_blank');
            } else if (e.keyCode == 65 && e.altKey) {
                  e.preventDefault();
                  window.open("http://localhost/final/stock", '_blank');
            } else if (e.keyCode == 72 && e.altKey) {
                  e.preventDefault();
                  window.open("http://localhost/final/", '_blank');
            }
      });
});