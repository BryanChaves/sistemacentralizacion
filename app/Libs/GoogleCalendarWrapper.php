<?php

namespace App\Libs;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendarWrapper
{
    const CALENDAR_ID = 'primary';
    const TIME_ZONE   = 'America/Costa_Rica';

    private $service;
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
        $this->service = $this->getService();
    }

    public function getEvents($limit)
    {
        $optParams = ['maxResults' => $limit, 'orderBy' => 'startTime', 'singleEvents' => true, 'timeMin' => date('c')];
        return $this->service->events->listEvents(self::CALENDAR_ID, $optParams);
    }

    public function createEvent($summary, $location, $description, $startDate, $endDate, $attendees)
    {
        $event = new Google_Service_Calendar_Event(
            [
                'summary' => $summary,
                'location' => $location,
                'description' => $description,
                'start' => [ 'dateTime' => $startDate, 'timeZone' => self::TIME_ZONE ],
                'end' => [ 'dateTime' => $endDate, 'timeZone' => self::TIME_ZONE ],
                'attendees' => $attendees,
                'reminders' => [ 'useDefault' => true ]
            ]
        );
        return $this->service->events->insert(self::CALENDAR_ID, $event);
    }

    private function getService()
    {
        $client = $this->getClient();
        return new Google_Service_Calendar($client);
    }

    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName(env('GOOGLE_APPLICATION_NAME', 'utn-calendar'));
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfigFile(env('GOOGLE_CLIENT_SECRET_PATH', '~/.credentials/client_secret.json'));
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        if ($this->user->google_calendar_credentials) {
            $accessToken = $this->user->google_calendar_credentials;
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate($authCode);

            // Store the credentials
            $this->user->google_calendar_credentials = $accessToken;
            $this->user->save();
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            $this->user->google_calendar_credentials = $client->getAccessToken();
        }
        return $client;
    }
}
