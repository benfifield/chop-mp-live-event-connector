## Church Online Platform to Ministry Platform Live Event Connector

This is a connector written in PHP using the PHP MP API wrapper by Scott Madeira (https://github.com/smadeira/ministry-platform-api).

It is designed to be run from command-line PHP, but will also work when run through a web server. You can run it on a schedule using cron or your preferred task scheduler.

### Current Features
*  Calls the ChOP API to get live event info
*  Puts the live status and event start time into a custom record in MP

### Future Features
*  Get the Title of the upcoming/live event from ChOP - this is currently not available in the API endpoint that this script uses for the Live/Not Live status and event start time.
  
### But Why
The current purpose of this connector is to make the live event information available in the MP database so that we can use that data in the stored procedures that build the Dashboard of our [Pocket Platform app](https://pocketplatform.io). Rather than do a complicated API process in the stored procedure, we run this PHP script on a schedule to update that data. That keeps the dashboard build process speedy while having current enough data.

### Using the connector
#### Prepare Ministry Platform
1.  Run setup/create_ChOP_Current_Event_table.sql
2.  Create the Page in MP:
    1.  On System Setup/Pages:
	    *  Display Name: `ChOP Current Event`
		*  Singular Name: `ChOP Current Event`
		*  Description: `Live/Not Live status of the current/upcoming event in Church Online Platform`
		*  Table Name: `ChOP_Current_Event`
		*  Primary Key: `ChOP_Current_Event_ID`
		*  Display Search: `False`
		*  Default Field List: `ChOP_Current_Event.Live, ChOP_Current_Event.Start_Date`
		*  Selected Record Expression: `ChOP_Current_Event.Title`
		*  Display Copy: `False`
		*  Suppress New Button: `False`
	2.  Add it to a Page Section, if desired. Northwoods added the page to a `Custom Integrations` Page Section.
	3.  Grant Edit access to the page for your desired Security Role
3.  Create a new record on ChOP Current Event (this creates record ID 1)
4.  Go back to the Page setup on System Setup/Pages and change Suppress New Button to `True`
5.  Grant Edit access to the new page for the desired API Client

#### Running the Connector
1.  Install PHP 7
    *  Ensure that the curl and mbstring extensions are installed
2.  Clone/Download the repository into a directory of your choice
3.  Copy `.env.example` to `.env` and edit it with your MP URL, ChOP URL, local time zone, API Client ID, and API Client Secret
4.  Set up [Composer](https://getcomposer.org/download/) in the project and run a Composer update to download the various needed dependencies
5.  Run the script: `php update_MP.php`

#### Automation
Use your automation tool of choice (cron, Windows Task Scheduler, etc.) to run the script on a regular basis. The recommended schedule is every 5-30 minutes, depending on how fresh you want the data to be.
