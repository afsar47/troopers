function confirmDeleteYoutubeLink(frm, y_id)
{
    with (frm)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                frm.youtubelinkid.value = y_id;
                frm.action.value = "delete";
                frm.submit();
            }
        });
    }
}





