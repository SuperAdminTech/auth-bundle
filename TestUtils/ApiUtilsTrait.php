<?php


namespace SuperAdmin\Bundle\TestUtils;

use SuperAdmin\Bundle\Security\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Trait ApiUtilsTrait
 * @package SuperAdmin\Bundle\TestUtils
 */
trait ApiUtilsTrait {

    /** @var boolean $acceptJson */
    private $acceptJson = false;

    /** @var array $moreHeaders */
    private $moreHeaders = [];

    /** @var string $token */
    private $token = null;

    /** @var KernelBrowser $client  */
    private $client = null;

    /** @var array */
    private $mocks = [];

    /**
     * @param string $email
     * @param string $password
     * @param string $realm
     * @return $this
     */
    protected function login($email = 'test@example.com', $password = 'secret', $realm = 'default'): self {
        $credentials = ['username' => $email, 'password' => $password, 'realm' => $realm];
        $resp = $this->json()->request('POST', '/public/token', $credentials);
        if($resp->getStatusCode() != Response::HTTP_OK)
            throw new AuthenticationException("Authentication for user $email with password $password failed");
        $data = json_decode($resp->getContent());
        $this->token = $data->token;
        return $this;
    }

    /**
     * @return $this
     */
    protected function logout(): self {
        return $this->setToken(null);
    }

    /**
     * @return string
     */
    protected function getUserToken(){
        return $_ENV['USER_TOKEN'];
    }

    /**
     * @return string
     */
    protected function getAdminToken(){
        return $_ENV['ADMIN_TOKEN'];
    }

    /**
     * @param $username
     * @param $payload
     * @return string
     */
    protected function getTokenFromPayload($username, $payload) {
        $user = User::createFromPayload($username, $payload);
        return $this
            ->getApiClient()
            ->getContainer()
            ->get('lexik_jwt_authentication.jwt_manager')
            ->createFromPayload($user, $payload);
    }

    /**
     * @param $permissions
     * @param string[] $roles
     * @return string
     */
    protected function getTokenWithPermissions($permissions, $roles = ['ROLE_USER']) {
        $payload = [
            'id' => 'f3c9aeaa-9c05-4c66-ab20-1c3918b4915c',
            'ip' => '127.0.0.1',
            'roles' => $roles,
            'permissions' => $permissions,
            'application' => [
                'name' => 'Default App',
                'realm' => 'default'
            ]
        ];
        return $this->getTokenFromPayload('testing_user', $payload);
    }

    /**
     * @param $account
     * @param null $grants
     * @param null $roles
     * @param null $user
     * @return self
     */
    protected function simpleAuth($account, $grants = null, $roles = null, $user = null): self {
        $permissions = [['account' => $account]];
        $permissions[0]['grants'] = $grants?? ['ACCOUNT_WORKER'];
        $roles = $roles?? ['ROLE_USER'];
        $user = $user?? 'f3c9aeaa-9c05-4c66-ab20-1c3918b4915c';
        $payload = [
            'id' => $user,
            'ip' => '127.0.0.1',
            'roles' => $roles,
            'permissions' => $permissions,
            'application' => [
                'name' => 'Default App',
                'realm' => 'default'
            ]
        ];
        $token = $this->getTokenFromPayload('testing_user', $payload);
        $this->setToken($token);
        return $this;
    }

    /**
     * @return string
     */
    protected function getToken(string $grant): string
    {
        return $_ENV[$grant];
    }


    /**
     * @param $token
     * @return $this
     */
    protected function setToken($token){
        $this->token = $token;
        return $this;
    }

    /**
     * @return $this
     */
    protected function json(): self {
        $this->acceptJson = true;
        return $this;
    }

    protected function headers(array $headers): self {
        $this->moreHeaders = $headers;
        return $this;
    }

    /**
     * @return string[]
     */
    private function getAuthenticationHeaders(): array {
        if(!$this->token) return [];
        return ['HTTP_Authorization' => "Bearer $this->token"];
    }

    /**
     * @param null $data
     * @return string[]
     */
    private function getJsonHeaders($data = null): array {
        $headers = [];
        if($this->acceptJson) $headers = ['HTTP_Accept' => 'application/ld+json'];
        if ($data) $headers['CONTENT_TYPE'] = "application/ld+json";
        return $headers;
    }

    /**
     * @param $method
     * @param $uri
     * @param null $json
     * @return Response
     */
    protected function request($method, $uri, $json = null): Response
    {
        $client = $this->createApiClient();

        $server = $this->getJsonHeaders($json);
        $auth = $this->getAuthenticationHeaders();
        $headers = array_merge($server, $auth);
        $headers = array_merge($headers, $this->moreHeaders);

        if ($json) $client->request($method, $uri, [], [], $headers, json_encode($json));
        else $client->request($method, $uri, [], [], $headers);

        return $client->getResponse();
    }

    /**
     * @param $method
     * @param $uri
     * @param $files
     * @return Response
     */
    protected function upload($method, $uri, $files): Response {
        $client = $this->createApiClient();

        $files_upload = [];
        foreach ($files as $param => $fileName) {
            $projectDir = $client
                ->getContainer()
                ->getParameter('kernel.project_dir');
            $assetFilename = $projectDir . '/fixtures/assets/' . $fileName;
            $uploadFilename = sys_get_temp_dir() . '/' . uniqid('upload_') . $fileName;
            copy($assetFilename, $uploadFilename);
            $uf = new UploadedFile(
                $uploadFilename,
                'image.png',
                'image/png',
                null
            );
            $files_upload[$param] = $uf;
        }

        $server = $this->getJsonHeaders();
        $auth = $this->getAuthenticationHeaders();
        $headers = array_merge($server, $auth);
        $headers = array_merge($headers, $this->moreHeaders);

        $client->request(
            $method,
            $uri,
            [],
            $files_upload,
            $headers
        );
        return $client->getResponse();
    }

    /**
     * @param $service
     * @param $mock
     * @return $this
     */
    protected function setMock($service, $mock): self {
        $this->mocks[$service] = $mock;
        return $this;
    }

    /**
     * @return KernelBrowser
     */
    protected function createApiClient(): KernelBrowser {
        self::ensureKernelShutdown();
        $client = self::createClient();
        $this->client = $client;
        $container = self::$kernel->getContainer();
        foreach ($this->mocks as $service => $mock) {
            $container->set($service, $mock);
        }
        return $client;
    }

    /**
     * @return KernelBrowser
     */
    protected function getApiClient(): KernelBrowser {
        if(!$this->client) $this->createApiClient();
        return $this->client;
    }

    private static function decodeJWT($token){
        return json_decode(base64_decode(explode(".", $token)[1]));
    }
}