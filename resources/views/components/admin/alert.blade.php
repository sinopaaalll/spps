@if (session('success'))
    <script>
        toastr.success('{{ session('success') }}', 'Success');
    </script>
@endif

@if (session('warning'))
    <script>
        toastr.warning('{{ session('warning') }}', 'Warning');
    </script>
@endif

@if (session('error'))
    <script>
        toastr.error('{{ session('error') }}', 'Error');
    </script>
@endif

<script>
    function reloadTable(tableId) {
        $('#' + tableId).DataTable().ajax.reload(null, false);
    }

    // Handle Delete
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let tableId = $(this).data('table');
        let url = $(this).data('url').replace('__ID__', id);

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data ini akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "DELETE",
                    success: function(response) {
                        toastr.success(response.message, 'Deleted');
                        reloadTable(tableId);
                    },
                    error: function(xhr) {
                        toastr.error("Gagal menghapus data!", 'Error');
                    }
                });
            }
        });
    });
</script>

<script>
    // Logged out
    $(document).on('click', '.btnLogout', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out from your account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Logout it!',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-outline-secondary waves-effect'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
