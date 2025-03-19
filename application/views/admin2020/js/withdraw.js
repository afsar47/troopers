
function changePublishStatus(frm, a_id, status)
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
                frm.accountid.value = a_id;
                frm.action.value = "change_publish";
                frm.publish.value = status;
                frm.submit();
            }
        });
    }
}

function copyToClipboard(element,a_id) {    
    var $temp = $("<input>");
    $("body").append($temp);
    $(element).select();
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $(".copied" + a_id).text("Copied to clipboard").show().fadeOut(1200);
    $temp.remove();
}


