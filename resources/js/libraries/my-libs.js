export const swalSuccess = message => {
    return Swal.fire({
        icon: "success",
        title: "Succes!",
        text: message,
        showConfirmButton: false,
        timer: 2000
    })
};

export const swalError = message => {
    return Swal.fire({
        icon: "error",
        title: "Error!",
        text: message,
        showConfirmButton: true,
        confirmButtonText: 'U redu',
        confirmButtonColor: '#138496'
    });
};

export const checkIfNotEmpty = value => value !== null && value !== undefined && value !== "";
