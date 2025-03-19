<script>
    $(document).ready(function () {
        $("#member_form").validate({
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                user_name: {
                    required: true,
                },
                email_id: {
                    required: true,
                    email: true,
                },
                mobile_no: {
                    required: true,
                },
                dob: {
                    required: true,
                },
                referral_no: {
                    required: true,
                },
                gender: {
                    required: true,
                },
            },
            messages: {
                first_name: {
                    required: "Please enter First Name",
                },
                last_name: {
                    required: "Please enter Last Name",
                },
                user_name: {
                    required: "Please enter User Name",
                },
                email_id: {
                    required: "Please enter Email",
                },
                mobile_no: {
                    required: "Please enter monile No",
                },
                dob: {
                    required: "Please Select Date of Birth",
                },
                referral_no: {
                    required: "Please enter Referral No",
                },
                gender: {
                    required: "Please select Gender",
                },
            }
            ,
            errorPlacement: function (error, element)
            {
                error.insertAfter(element);
            },
        });
        $("#wallet_form").validate({
            rules: {
                'amount': {
                    required: true,
                    max: function () {
                        if ($('#wallet').val() == "join_money" && $('input[name="plus_minus"]:checked').val() == '-') {
                            return <?php echo $member_detail['join_money']; ?>;
                        } else if ($('#wallet').val() == "wallet_balance" && $('input[name="plus_minus"]:checked').val() == '-') {
                            return <?php echo $member_detail['wallet_balance']; ?>;
                        }
                    }
                },
                'plus_minus': {
                    required: true,
                },
                wallet: {
                    required: true,
                }
            },
            messages: {
                'amount': {
                    required: "Please,enter amount",
                },
                'plus_minus': {
                    required: "Please,select plus-minus",
                },
                wallet: {
                    required: "Please,select wallet",
                }
            },
            errorPlacement: function (error, element)
            {
                if (element.is(":radio"))
                {
                    error.insertAfter(element.parent().parent());
                } else
                {
                    error.insertAfter(element);
                }
            },
        });
    });
</script>
