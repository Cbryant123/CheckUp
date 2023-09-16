**431 - Documentation**

Chandler Bryant

**HOW TO RUN/SETUP:  
**

- Download and install xampp, and setup php and sql.

- Download the zip file that contains the project folder.

- Start Apache and MySQL in XAMPP

- Make sure you have no DATABASE IN PHP phpmyadmin named “***login_db***” as that is the name of the database this project revolves around.

- Insert project into htdocs, and open **index.html** into the url of any web browser

  - Here is an example: [*http://localhost/431-Project/index.html*](http://localhost/431-Project/index.html)

- IF YOU WANT TO INSERT THE PRESET TEST USERS AND DATA, CLICK THE BUTTON “**Click here to run the installation script for the Tables & Database!!!**”

- After clicking that button, log in or register an account.

- Every preset account is that person’s name as their username, with no space.

- Every password preset is preset to lowercase “**password**”

  - So if you want to login as john doe, this is how you would enter it:  
    Username: **johndoe**

> Password: **password**

- **The project should now be able to be used and tested freely!**

***Planned Features:***

**1. User authentication:** Implement a sign-up and login system to differentiate between medical offices and other users (e.g., doctors, medical device companies, pharmaceutical companies). This will help manage access to the platform and provide customized views. The passwords are salted and hashed.

**2. Profile creation:** Allow medical offices to create and update their profile with relevant information such as address, contact details, and specialties. This will help other users to learn more about the practices they wish to connect with.

**3. Advanced scheduling:** Improve the scheduling functionality by allowing medical offices to set recurring availability, block out specific dates, and set limits on the number of meetings per day/week. Availability was implemented, and then reworked to setting a schedule.

**4. Search and filtering:** Enable users to search for medical practices based on location, specialty, or other criteria. Can implement a filtering system to narrow down the results based on availability, nearest to farther, practitioner name, food preferences, etc.

**5. Approval system**: Design an approval system for medical offices to review, approve, or reject meeting requests from other users. This can be done through notifications, email alerts, or an integrated dashboard.

**6. Messaging:** Allow users to message other clinics/users via the searching system, and create meetings with the use of those messages.

**7. Reporting and analytics:** Provide both medical offices and users with reporting and analytics features to track the number of meetings, successful referrals.

**8. UI:** Make proper adjustments to enhance the site for the users to navigate the site and go about the website

***Things I did not finish and/or would like to improve upon:*  
**

- The search system is not nearly as robust as I would like it to be. I couldn’t implement a filter by distance option in enough time. I also wanted to implement a javascript that showed results as you typed them. For example, if I type the letter ‘J’, I wanted to show all results of doctor’s starting with J, and for each letter added it would further filter the list.

- The scheduling system does not cover whether or not the person you are scheduling with has an appointment.

- The availability schedule does not affect the scheduling system. I did not have enough time for a user to set their hours, and it block out the hours the user did not set onto the calendar.

- The UI and design of the site is about as basic as it can get. Changing buttons, layouts, and design would help.

- Names are not separated by first and last, There is only 1 text box for the full address bar. Rather than a normal address system of city, state, street, zip, etc. There is no email confirmation when making an account.’

