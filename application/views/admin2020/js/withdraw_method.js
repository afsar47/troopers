function confirmDeleteWithdrawMethod(frm, d_id)
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
                frm.withdrawmethodid.value = d_id;
                frm.action.value = "delete";
                frm.submit();
            }
        });
    }
}

function changePublishStatus(frm, d_id, status)
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
                frm.withdrawmethodid.value = d_id;
                frm.action.value = "change_publish";
                frm.publish.value = status;
                frm.submit();
            }
        });
    }
}



