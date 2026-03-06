import Swal from "sweetalert2";

const toastServicess = {
  show(title, message, mode = 'success') {
    Swal.fire({
      title: title,
      text: message,
      icon: mode,
      allowOutsideClick: mode !== 'error',
      allowEscapeKey: mode !== 'error',
      timer: 10000,
      timerProgressBar: true,
    });
  },
};


const toastService = {
  show(title, message, mode = 'success') {
    Swal.fire({
      toast: true,
      position: 'top-right',
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
      icon: mode,
      title: title,
      text: message,
      showCloseButton: true,
      customClass: {
        popup: 'my-swal-toast',
        closeButton: 'my-swal-close-btn',
        timerProgressBar: 'my-swal-timer-progress'
      },
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });
  },
};



export default toastService;
