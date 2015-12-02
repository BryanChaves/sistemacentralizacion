<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendarTokenUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    private $calendarId = 'primary';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        define('APPLICATION_NAME', env('GOOGLE_APPLICATION_NAME', 'Google Calendar API PHP Quickstart'));
        define('CREDENTIALS_PATH', env('GOOGLE_CREDENTIALS_PATH', '~/.credentials/calendar-php-quickstart.json'));
        define('CLIENT_SECRET_PATH', env('GOOGLE_CLIENT_SECRET_PATH', '~/.credentials/client_secret.json'));
        define('SCOPES', implode(' ', [Google_Service_Calendar::CALENDAR]));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service = $this->getService();

        $this->printTop($service, 5);

    }

    public function createEvent($service)
    {
        $event = new Google_Service_Calendar_Event(
            array(
                'summary' => 'English Evaulation',
                'location' => 'Go-Labs',
                'description' => 'English evaluation process',
                'start' => array(
                    'dateTime' => '2015-12-07T08:00:00-06:00',
                    'timeZone' => 'America/Costa_Rica',
                ),
                'end' => array(
                    'dateTime' => '2015-12-07T12:00:00-06:00',
                    'timeZone' => 'America/Costa_Rica',
                ),
                'attendees' => array(
                    array('email' => 'crojas@utn.ac.cr'),
                    array('email' => 'j.m.z.r.neta@gmail.com'),
                ),
                'reminders' => array(
                    'useDefault' => true
                    /*'overrides' => array(
                        array('method' => 'email', 'minutes' => 24 * 60)
                    ),*/
                )
            )
        );

        $event = $service->events->insert($this->calendarId, $event);
        printf('Event created: %s\n', $event->htmlLink);


    }

    private function printTop($service, $limit)
    {
        $optParams = array(
            'maxResults' => $limit,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($this->calendarId, $optParams);

        if (count($results->getItems()) == 0) {
            $this->info('No upcoming events found.');
        } else {
            $this->info('Upcoming events:');
            foreach ($results->getItems() as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                $this->info($event->getSummary() . ' ' . $start);
            }
        }
    }

    private function getService()
    {
        $client = $this->getClient();
        return new Google_Service_Calendar($client);
    }

    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME);
        $client->setScopes(SCOPES);
        $client->setAuthConfigFile(CLIENT_SECRET_PATH);
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory(CREDENTIALS_PATH);
        if (file_exists($credentialsPath)) {
            $accessToken = file_get_contents($credentialsPath);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            $this->info("Open the following link in your browser: \n");
            $this->info($authUrl);
            $authCode = $this->ask('Enter verification code:');

            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, $accessToken);
            $this->info("Credentials saved to " . $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, $client->getAccessToken());
        }
        return $client;
    }

    /**
    * Expands the home directory alias '~' to the full path.
    * @param string $path the path to expand.
    * @return string the expanded path.
    */
    private function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }
}
