document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggleSidebar");
  if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
      document.body.classList.toggle("sidebar-collapsed");
    });
  }

  // Toast helper
  window.showToast = (msg, type = "success") => {
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: type,
      title: msg,
      showConfirmButton: false,
      timer: 2500
    });
  };
});