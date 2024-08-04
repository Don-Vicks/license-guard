# LicenseGuard: Your Comprehensive License Management Solution

## Introduction
LicenseGuard is a robust, full-stack license management system designed to empower software sellers by ensuring only verified and paid users have access to their software. It effectively combats software nulling and provides a comprehensive suite of features for both admins and users.

## Key Features
 
 ### Admin Dashboard:

- Manage registered users
- Create and edit licenses
- Define license types (prices, duration)
- View payment history
- Create new users
- Access detailed statistics

### User Dashboard:

- Own multiple licenses
- Create licenses as a reseller
- Manage license activations
- View payment status
- Payment Integration:

### Other Features
- Seamless integration with Flutterwave for secure payments
- Automated email notifications for payment confirmations and license activations
 
### Subscription Model:
- Flexible subscription options based on license types
- Automated renewal reminders
- License revocation for missed payments

### API Integration:

- Validate and authorize software users
- Integrate license checks into your software


## Usage & Installation and Setup
- Firstly Download or Clone this Project
- Upload the Project to where you would like to host it
- Run 'cp .env.example .env' In your Terminal/CMD or copy or rename .env.example to .env
- Set up Mail by inputting your Credentials in the .env, also input your FLW_SECRET_KEY
- Visit https://generate.plus/en/base64 and change the default from 16 to 44 to generate a string, copy the string and paste it in the .env APP_KEY after base64:
- Create a Domain Based email of where you are hosting eg victor@teendev.dev
- Visit url/admin and register an account using the email account, you would be prompted to verify your email address once that's done the account has admin access
- Add a Cron Job to this URL(url/api/run/cronjob) Set this to run everytime(This is needed for Mailing)
- Horray, Welcome to LicenseGuard start enjoying for free!

## API Verification 
To verify if a license is active or not, Make a get request to url/api/validate/license, the parameters should contain 'domain' => 'DOMAIN', license_key => 'THE LICENSE KEY TO VERIFY' if this is verified and active, you would receive a 200 Ok Status Code else a 401 Unauthorized Status Code

### Demo Request
curl --location '127.0.0.1:8000/api/validate/license?domain=https%3A%2F%2Fteendev.dev&license_key=License_ZXzZ7U7UmLUFyNHN'
200 Ok Response : {
    "message": "Horray, Your activation has been confirmed"
}

401 Unauthorized Response : {
    "message": "Whoops, something isn\'t right, Kindly recheck the submitted details"
}

## Contributing
We welcome contributions to LicenseGuard! Please refer to me for more information.

## License
This project is licensed under the MIT License.

## Author
Don-Vicks (GitHub: https://github.com/Don-Vicks)
VictorShallang (X: https://twitter.com/VictorShallang)
