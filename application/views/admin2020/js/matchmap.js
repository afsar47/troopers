function confirmDeleteMatchmap(frm, m_id)
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
                frm.macthmapid.value = m_id;
                frm.action.value = "delete";
                frm.submit();
            }
        });
    }
}





