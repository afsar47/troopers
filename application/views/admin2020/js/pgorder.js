

function changePublishStatus(frm, pg_id, status)
{
    with (frm)
    {
        var agree;
        if (status == 1)
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure to complete this order? You cannot do the reverse",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    frm.depositid.value = pg_id;
                    frm.action.value = "change_publish";
                    frm.publish.value = status;
                    frm.submit();
                }
            });
        else
            Swal.fire({
                title: 'Are you sure?',
                text: "re you sure to fail this order?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    frm.depositid.value = pg_id;
                    frm.action.value = "change_publish";
                    frm.publish.value = status;
                    frm.submit();
                }
            });
    }
}



