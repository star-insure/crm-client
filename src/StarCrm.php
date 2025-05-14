<?php


namespace StarInsure\Crm;

use Illuminate\Support\Facades\Http;

class StarCrm
{
    private $apiUrl;

    private $client;

    private $timeout = 20;

    private $asRaw = false;

    private $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * Constructor for an CRM API instance
     *
     * @param string $version ("v1")
     * @param string|null $apiTokenOverride ("JWT")
     */
    public function __construct(string $version = '', string|null $apiTokenOverride = null)
    {
        // Define our API's URL
        $this->apiUrl = config('crm.api_url') . '/api/' . $version ?? config('crm.version');

        // We can interact either as an authenticated user, or as an application itself
        // We first look for a token that's been provided (appApi()), then in the session (user), and lastly config (app env variable)
        $token = $apiTokenOverride ?? session('access_token') ?? config('crm.token') ?? config('star.token');

        $headers = $this->headers;

        // Set the default headers for our API
        $this->client = Http::withHeaders($headers)
            ->withToken($token)
            ->timeout($this->timeout);
    }

    /**
     * Handles any call to the CRM API
     *
     * @param string $method (GET, POST, PUT, DELETE)
     * @param string $endpoint (The endpoint to call, e.g. "/users")
     * @param array $data (The data to send to the endpoint)
     * @return mixed (json response)
     */
    public function call(string $method, string $endpoint, array $data = [])
    {
        // Always prefix endpoints with a slash
        $url = $this->apiUrl . '/' . trim($endpoint, '/');

        // Convert the supplied method to a method that exists on the HTTP client
        $method = match ($method) {
            'GET' => 'get',
            'POST' => 'post',
            'PUT' => 'put',
            'DELETE' => 'delete',
        };

        // Make the request
        $res = $this->client
            ->timeout($this->timeout)
            ->$method($url, $data);

        if ($this->asRaw) {
            return $res;
        }

        // Body may not exist for empty content responses (e.g. on DELETE requests)
        $body = $res->json() ?? [];

        // Don't attach any additional headers if the API request was proxied
        if ($res->header('X-Proxied')) {
            return $body;
        }

        // Return a JSON response, along with the status code and OK status
        return [...$body, 'status' => $res->status(), 'ok' => $res->successful()];
    }

    /**
     * GET requests wrapper
     *
     * @param string $endpoint (ModelName or custom endpoint)
     * @param array $data (Query strings as an array)
     * @return mixed (json response)
     */
    public function get(string $endpoint, array $data = [])
    {
        return $this->call('GET', $endpoint, $data);
    }

    /**
     * POST requests wrapper
     *
     * @param string $endpoint (ModelName or custom endpoint)
     * @param array $data (An array of key/value pairs matching the model's db columns)
     * @return mixed (json response)
     */
    public function post(string $endpoint, array $data = [])
    {
        return $this->call('POST', $endpoint, $data);
    }

    /**
     * PUT requests wrapper
     *
     * @param string $endpoint (ModelName or custom endpoint)
     * @param string $data (An array of key/value pairs matching the model's db columns)
     * @return mixed (json response)
     */
    public function put(string $endpoint, array $data = [])
    {
        return $this->call('PUT', $endpoint, $data);
    }

    /**
     * DELETE requests wrapper
     *
     * @param string $endpoint (ModelName or custom endpoint)
     * @return mixed (json response)
     */
    public function del(string $endpoint)
    {
        return $this->call('DELETE', $endpoint);
    }

    /**
     * Return the raw response from the API call (e.g. for file downloads)
     */
    public function asRaw(): self
    {
        $this->asRaw = true;

        return $this;
    }

    /**
     * Set the timeout for the API request
     */
    public function timeout(int $seconds): self
    {
        $this->timeout = $seconds;

        return $this;
    }


    /**
     * Set the headers for the API request
     */
    public function headers(array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }
}
