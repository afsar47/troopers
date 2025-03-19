function confirmDeleteAdmin(frm, m_id)
{
    with(frm)
    {
        var agree=confirm("Are you sure to delete this Admin ?");
        if (agree)
        {            
            frm.adminid.value = m_id;
            frm.action.value = "delete";
            frm.submit();
        }
    }
}