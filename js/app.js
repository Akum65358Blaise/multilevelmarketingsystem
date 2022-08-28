
$(document).ready(function() {

    //fadeout message notification
    $(".close").click(function(){
        $("div.alert-msg").fadeOut(1000);
    });

     //Signup Button
    $("button#signup").click(function(e)
    {
        e.preventDefault();
        var ref_username = $("#ref_username").val(),
            firstname = $("#firstname").val(),
            lastname = $("#lastname").val(),
            phone = $("#phone").val(),
            email = $("#email").val(),
            username = $("#username").val(),
            password = $("#password").val(),
            con_password = $("#con_password").val(),
             error = 0,
            dataString = "refName="+ ref_username+"&Fname="+firstname+"&Lname="+lastname+"&phone="+phone+"&email="+email+"&username="+username+"&pass="+password+"&conPass="+con_password+"&signup=1";
            
        if(con_password != password){
            alert("Confirm password does not match password");
            error = 1;
        }
        if(error == 0){
            //Display the process spinner
            $(this).html("<i class=\"fa fa-spinner fa-pulse\"></i> <span class=\"wait\">Please wait...</span>");
            $.ajax({
                type: "POST",
                url: "classes/class.post.php",
                data: dataString,
                cache: false,
                success: function(result)
                {
                    if(result == "home"){
                        window.location.href = result;
                    }
                    else{
                        $(".signup_response").css("display", "block");
                        $(".signup_response").html(result);
                        $("button#signup").html("SIGN UP");
                    }
                }
            });
        }
    });


     //Create User Account Button
    $("button#createUser").click(function(e)
    {
        e.preventDefault();
        var ref_username = $("#ref_username").val(),
            firstname = $("#firstname").val(),
            lastname = $("#lastname").val(),
            phone = $("#phone").val(),
            email = $("#email").val(),
            username = $("#username").val(),
            password = $("#password").val(),
            con_password = $("#con_password").val(),
             error = 0,
            dataString = "refName="+ ref_username+"&Fname="+firstname+"&Lname="+lastname+"&phone="+phone+"&email="+email+"&username="+username+"&pass="+password+"&conPass="+con_password+"&signup=1";
            
        if(con_password != password){
            alert("Confirm password does not match password");
            error = 1;
        }
        if(error == 0){
            //Display the process spinner
            $(this).html("<i class=\"fa fa-spinner fa-pulse\"></i> <span class=\"wait\">Please wait...</span>");
            $.ajax({
                type: "POST",
                url: "classes/class.post.php",
                data: dataString,
                cache: false,
                success: function(result)
                {
                    if(result == "home"){
                        window.location.href = result;
                    }
                    else{
                        $(".signup_response").css("display", "block");
                        $(".signup_response").html(result);
                        $("button#createUser").html("CREATE USER");
                    }
                }
            });
        }
    });
     //Create User Account Button
    $("button#UpdateUser").click(function(e)
    {
        e.preventDefault();
        var Id= $("#User_Id").val(),
            firstname = $("#firstname").val(),
            lastname = $("#lastname").val(),
            phone = $("#phone").val(),
            email = $("#email").val(),
            username = $("#username").val(),
            error = 0,
            utypeId=$("#user_type_id").val();
            if(utypeId == "2"){
                var addUser = $("#addUser").prop("checked"),
                    deleteUser = $("#deleteUser").prop("checked"),
                    canAddPck = $("#canAddPck").prop("checked"),
                    canDeleteEditPck = $("#canDeleteEditPck").prop("checked"),
                    canMatch = $("#canMatch").prop("checked"),
                    canViewGross = $("#canViewGross").prop("checked"),
                    canSendEmail = $("#canSendEmail").prop("checked"),
                    canViewTransactions = $("#canViewTransactions").prop("checked"),
                    canSendBulkSms = $("#canSendBulkSms").prop("checked"),
                    canViewSendTickets = $("#canViewSendTickets").prop("checked"),
                    canChangeSettings = $("#canChangeSettings").prop("checked"),
                dataString = "Fname="+firstname+"&Lname="+lastname+"&phone="+phone+"&email="+email+"&username="
                +username+"&Id="+Id+"&UpdateUser=1&utypeId="+utypeId+"&addUser="+addUser+"&deleteUser="+deleteUser+"&canAddPck="
                +canAddPck+"&canDeleteEditPck="+canDeleteEditPck+"&canMatch="+canMatch+"&canViewGross="+canViewGross+"&canSendEmail="
                +canSendEmail+"&canViewTransactions="+canViewTransactions+"&canSendBulkSms="+canSendBulkSms+"&canViewSendTickets="
                +canViewSendTickets+"&canChangeSettings="+canChangeSettings;

                //alert(dataString);
            }
            else{
                var dataString = "Fname="+firstname+"&Lname="+lastname+"&phone="+phone+"&email="+email+"&username="
                +username+"&Id="+Id+"&UpdateUser=1&utypeId="+utypeId;
            }

        if(error == 0){
            //Display the process spinner
            $(this).html("<i class=\"fa fa-spinner fa-pulse\"></i> <span class=\"wait\">Please wait...</span>");
            $.ajax({
                type: "POST",
                url: "classes/class.post.php",
                data: dataString,
                cache: false,
                success: function(result)
                {
                    if(result == "admin?tab=view_users"){
                        window.location.href = result;
                    }
                    else{
                        $(".signup_response").css("display", "block");
                        $(".signup_response").html(result);
                        $("button#UpdateUser").html("UPDATE");
                    }
                }
            });
        }
    });

    //Sign in button
    $("button#signin").click(function(e)
    {
            $(this).html("<i class=\"fa fa-spinner fa-pulse\"></i> <span class=\"wait\">Please wait...</span>");
            
    });

    $(".pm-times").click(function(e){
	    return confirm( "Do you want to execute this commmand?" );
    });

    //Create User Account Button
    $("button#pledge").click(function(e)
    {
        e.preventDefault();
        var UserId = $("#UserId").val(),
            PckId = $("#PckId").val(),
            pAmount = $("#pledge_amount").val(),
            dataString = "UserId="+UserId+"&PckId="+PckId+"&pAmount="+pAmount+"&pledge=1";
        
	    if(confirm('Action can not be reversed. Do you want to proceed?')){
            //Hide the button
            $(".pledge-close-times").css("display", "none");
            $("button#pledge").css("display", "none");
            $(".close-pledge").css("display", "none");
            //Display the process spinner
            $(".pledge_response").html("<i class=\"fa fa-spinner fa-pulse fa-3x\"></i> <span class=\"wait\">Please wait...</span>");
            $(".pledge_response").css("display", "block");

            $.ajax({
                type: "POST",
                url: "classes/class.post.php",
                data: dataString,
                cache: false,
                success: function(result)
                {
                    if(result == "dashboard"){
                        window.location.href = result;
                    }
                    else{
                        $(".pledge-close-times").css("display", "block");
                        $(".pledge_response").css("display", "block");
                        $(".pledge_response").html(result);
                        $("button#pledge").css("display", "block");
                        $(".close-pledge").css("display", "block");
                    }
                }
            });
        }
    });

	//Get suggestion of usernames based on typed keywords and return username
	$("#r_username").keyup(function() 
	{
        var r_username = $(this).val(),
            dataString = "r_username="+r_username+"&find_r_user=1";
        
        if(r_username=='')
        {
            $("#display_r_users").html("");
            $("#display_r_users").hide();
        }
        else
        {
            $.ajax({
            type: "POST",
            url: "classes/class.post.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
                $("#display_r_users").html(html).show();
            }
            });
        }
        return false;    
	});

	//Add User to Receiver
	$("button#add_reciever").click(function(e) 
	{
        e.preventDefault();
        var PckId = $("#PckId").val(),
            r_UserId = $("#r_UserId").val(),
            r_username = $("#r_username").val(),
            r_amount = $("#r_amount").val(),
            dataString = "PckId="+PckId+"&r_UserId="+r_UserId+"&r_username="+r_username+"&r_amount="+r_amount+"&add_reciever=1";
        //alert("Data is: "+dataString);
        if(r_username ==' ')
        {
            $(".add_reciever_response").html("Username field is empty.");
            $(".add_reciever_response").show();
        }
        else
        {
            //Display the process spinner
            $("#add_reciever").html("<i class=\"fa fa-spinner fa-pulse fa-2x\"></i> <span class=\"wait\">Please wait...</span>");
            $.ajax({
            type: "POST",
            url: "classes/class.post.php",
            data: dataString,
            cache: false,
            success: function(result)
            {
                if(result == "admin?tab=view_recievers"){
                        window.location.href = result;
                }
                else{
                    $(".add_reciever_response").html(result).show();
                    $("button#add_reciever").html("Submit");
                }
            }
            });
        }
        return false;    
	});


    //Select bulksms contact
    $("#ContactSelector").change(function(){
        var selectedVal = $(this).val();
        if(selectedVal == 0)
        {
            $("#telephone").val("");
            $("#telephone").focus();
        }
        else
        {
            var dataString = "smsContact=1&option="+selectedVal;
            $.ajax({
                type: "POST",
                url: "classes/class.post.php",
                data: dataString,
                cache: false,
                success: function(result)
                {
                    if(result != "")
                    {
                        $("#telephone").val("");
                        $("#telephone").val(result);
                        $("#telephone").focus();
                    }
                    else alert("There is no contact for the selected option.");
                }
            });
            
        }
    });

});


function RUserSearch( val1, val2 )
{
	$("#r_UserId").val(val1);
	$("#r_username").val(val2);
	$('#display_r_users').hide();

}