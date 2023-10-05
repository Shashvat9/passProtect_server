

# to validate \(^_^)/
 1. set validate = 1 in post
 2. pass this json to api
    {
        "api_key":"",
        "email":"",
        "password":""
    }
 3. in response php will echo email which will be stored in share dprefrance 
    if there is no account with that email will echo 0

    CODES; 
            code = 1 > success > print a email
            code = 2 > wrong password
            code = 3 > no rows or no user with that email
            code = 4 > db problame

# to add user \(^_^)/
    1.  set add_user = 1 using post
    2.  pass this json to api
        {
            "api_key":"",
            "name":"",
            "email":"",
            "mobile":""
            "password":""
        }
    3.  in response php will echo 1 if done 0 if there is a error;

    CODES: 
            code = 5 > user allrady exists
            code = 6 > success 
            code = 6 > db problame
            code = 7 > db error


# to send otp over mail
    1. set send_mail_otp = 1
    2. pass this json to api
        {
            "api_key":""
            "email":""
        }
    CODES:
        code = 8 > email sent
        code = 9 > wrong email
        code = 10 > cant send email

# to add user password
    1. set add_pass = 1

    2.  pass this json to api
        {
            "api_key":"",
            "name":"",
            "email":"",
            "password":""
        }
    CODES:
    code = 11 > password added
    code = 12 > server error

#  to get password
    1. set get_pass = 1

    2.  pass this json to api
        {
            "api_key":"",
            "name":"",
            "email":"",
            "password":""
        }
    CODES:
    code = 130 > data
    code = 140 > query execution error



