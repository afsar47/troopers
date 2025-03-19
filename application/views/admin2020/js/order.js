
function changePublishStatus(frm, o_id, status)
{
    with (frm)
    {
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to change status?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                frm.oid.value = o_id;
                frm.action.value = "change_publish";
                frm.publish.value = status;
                frm.submit();
            }
        });
    }
}



