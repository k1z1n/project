<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BegetAPIService
{
    protected mixed $apiLogin;
    protected mixed $apiPassword;

    public function __construct()
    {
        $this->apiLogin = config('services.beget.login');
        $this->apiPassword = config('services.beget.password');
    }

    /**
     * Функция для создания сайта.
     */
    public function createSite($name)
    {
        $apiUrl = 'https://api.beget.com/api/site/add';

        $params = [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'input_format' => 'json',
            'output_format' => 'json',
            'input_data' => json_encode([
                'name' => $name . '.kplatforma.ru'
            ]),
        ];

        $response = Http::get($apiUrl, $params);

        if ($response->ok()) {
            $responseBody = $response->json();

            if (isset($responseBody['answer']['status']) && $responseBody['answer']['status'] === 'success') {
                return ['status' => 'success'];
            } else {
                return [
                    'status' => 'error',
                    'error_text' => 'Ошибка при добавлении сайта: ' . json_encode($responseBody),
                ];
            }
        } else {
            return [
                'status' => 'error',
                'error_text' => 'Ошибка при подключении к API Beget.',
                'http_status' => $response->status(),
                'details' => $response->body(),
            ];
        }
    }

    /**
     * Функция для создания поддомена.
     */
    public function createSubdomain($name)
    {
        $apiUrl = 'https://api.beget.com/api/domain/addSubdomainVirtual';

        $params = [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'input_format' => 'json',
            'output_format' => 'json',
            'input_data' => json_encode([
                'subdomain' => $name,
                'domain_id' => env('BEGET_DOMAIN')  // предполагается, что ID домена основного сайта известен и сохранен в переменной окружения
            ]),
        ];

        $response = Http::get($apiUrl, $params);

        if ($response->ok()) {
            $responseBody = $response->json();

            if (isset($responseBody['answer']['status']) && $responseBody['answer']['status'] === 'success') {
                return ['status' => 'success'];
            } else {
                return [
                    'status' => 'error',
                    'error_text' => 'Ошибка при добавлении поддомена: ' . json_encode($responseBody),
                ];
            }
        } else {
            return [
                'status' => 'error',
                'error_text' => 'Ошибка при подключении к API Beget.',
                'http_status' => $response->status(),
                'details' => $response->body(),
            ];
        }
    }

    /**
     * Фнкция для получения списка сайтов.
     */
    public function getSiteList()
    {
        $apiUrl = 'https://api.beget.com/api/site/getList';

        $params = [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'input_format' => 'json',
            'output_format' => 'json',
            'input_data' => json_encode([]),
        ];

        $response = Http::get($apiUrl, $params);

        if ($response->ok()) {
            $responseBody = $response->json();

            if (isset($responseBody['answer']['result'])) {
                return [
                    'status' => 'success',
                    'data' => $responseBody['answer']['result'],
                ];
            } else {
                return [
                    'status' => 'error',
                    'error_text' => 'Ошибка при получении списка сайтов: ' . json_encode($responseBody),
                ];
            }
        } else {
            return [
                'status' => 'error',
                'error_text' => 'Ошибка при подключении к API Beget.',
                'http_status' => $response->status(),
                'details' => $response->body(),
            ];
        }
    }

    /**
     * Функция для получения списка поддоменов.
     */
    public function getSubdomainList()
    {
        $apiUrl = 'https://api.beget.com/api/domain/getSubdomainList';

        $params = [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'input_format' => 'json',
            'output_format' => 'json',
            'input_data' => json_encode([]),
        ];

        $response = Http::get($apiUrl, $params);

        if ($response->ok()) {
            $responseBody = $response->json();

            if (isset($responseBody['answer']['result'])) {
                return [
                    'status' => 'success',
                    'data' => $responseBody['answer']['result'],
                ];
            } else {
                return [
                    'status' => 'error',
                    'error_text' => 'Ошибка при получении списка поддоменов: ' . json_encode($responseBody),
                ];
            }
        } else {
            return [
                'status' => 'error',
                'error_text' => 'Ошибка при подключении к API Beget.',
                'http_status' => $response->status(),
                'details' => $response->body(),
            ];
        }
    }

    /**
     * Фунция для поиска ID сайта по имени.
     */
    public function findSiteIdByName($sites, $name)
    {
        $fullName = $name . '.kplatforma.ru';

        foreach ($sites as $site) {
            if ($site['path'] === "{$fullName}/public_html") {
                return [
                    'status' => 'success',
                    'site_id' => $site['id'],
                ];
            }
        }

        return [
            'status' => 'error',
            'error_text' => 'Сайт не найден в списке сайтов.',
        ];
    }

    /**
     * Фунция для поиска ID поддомена по имени.
     */
    public function findSubdomainIdByName($subdomains, $name)
    {
        $fullName = $name . '.kplatforma.ru';

        foreach ($subdomains as $subdomain) {
            if (isset($subdomain['fqdn']) && $subdomain['fqdn'] === $fullName) {
                return [
                    'status' => 'success',
                    'subdomain_id' => $subdomain['id'],
                ];
            }
        }

        return [
            'status' => 'error',
            'error_text' => 'Поддомен не найден в списке поддоменов.',
        ];
    }

    /**
     * Основная функция для создания сайта и поддомена, получения их ID, создание ftp акканта, создание базы данных и передача всех данных.
     */
    public function createSiteAndRetrieveIds($name)
    {
        // Шаг 1: Создать сайт
        $createSiteResponse = $this->createSite($name);

        if ($createSiteResponse['status'] !== 'success') {
            return $createSiteResponse; // Возвращаем ошибку, если сайт не был добавлен
        }

        // Шаг 2: Создать поддомен
        $createSubdomainResponse = $this->createSubdomain($name);

        if ($createSubdomainResponse['status'] !== 'success') {
            return $createSubdomainResponse; // Возвращаем ошибку, если поддомен не был добавлен
        }

        // Шаг 3: Получить список сайтов
        $siteListResponse = $this->getSiteList();
        if ($siteListResponse['status'] !== 'success') {
            return $siteListResponse; // Возвращаем ошибку, если не удалось получить список сайтов
        }

        // Шаг 4: Найти ID созданного сайта в списке
        $findSiteIdResponse = $this->findSiteIdByName($siteListResponse['data'], $name);
        if ($findSiteIdResponse['status'] !== 'success') {
            return $findSiteIdResponse; // Возвращаем ошибку, если сайт не найден
        }

        // Шаг 5: Получить список поддоменов
        $subdomainListResponse = $this->getSubdomainList();
        if ($subdomainListResponse['status'] !== 'success') {
            return $subdomainListResponse; // Возвращаем ошибку, если не удалось получить список поддоменов
        }

        // Шаг 6: Найти ID созданного поддомена в списке
        $findSubdomainIdResponse = $this->findSubdomainIdByName($subdomainListResponse['data'], $name);
        if ($findSubdomainIdResponse['status'] !== 'success') {
            return $findSubdomainIdResponse; // Возвращаем ошибку, если поддомен не найден
        }

        $createFtpAccount = $this->addFtpAccount($name);
        if ($createFtpAccount['status'] !== 'success') {
            return $createFtpAccount; // Возвращаем ошибку, если поддомен не найден
        }

        $createDataBase = $this->addDataBase($name);
        if ($createDataBase['status'] !== 'success') {
            return $createDataBase; // Возвращаем ошибку, если поддомен не найден
        }

        $linkDomain = $this->link($findSubdomainIdResponse['subdomain_id'], $findSiteIdResponse['site_id']);
        if ($linkDomain['status'] !== 'success') {
            return $linkDomain; // Возвращаем ошибку, если поддомен не найден
        }

        $sshAcc = $this->toggleSsh($createFtpAccount['login']);
        if(!$sshAcc){
            return $sshAcc;
        }

        return [
            'status' => 'success',
            'link' => 'http://' . $name . '.kplatforma.ru/',
            'ftPLogin' => $createFtpAccount['login'],
            'ftPPassword' => $createFtpAccount['password'],
            'dbLogin' => $createDataBase['login'],
            'dbPassword' => $createDataBase['password'],
        ];
    }

    public function toggleSsh($ftpAcc)
    {
        $response = Http::get('https://api.beget.com/api/user/toggleSsh', [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'status' => 1,
            'ftplogin' => $ftpAcc,
            'output_format' => 'json',
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Не удалось включить SSH'], 500);
        }
    }

    /**
     * Функция для создания ftp аккаунта.
     */

    public function addFtpAccount($suffix)
    {
        // Проверка длины логина
        $login = $this->apiLogin . '_' . $suffix;
        if (strlen($login) > 17) {
            return [
                'status' => 'error',
                'error_text' => 'Итоговый логин не должен превышать 17 символов.',
            ];
        }

        $homedir =  '/' . $suffix .'.kplatforma.ru/public_html';

        $apiUrl = 'https://api.beget.com/api/ftp/add';
        $password = Str::random(10);
        $inputData = [
            'suffix' => $suffix,
            'homedir' => $homedir,
            'password' => $password,
        ];

        $params = [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'input_format' => 'json',
            'output_format' => 'json',
            'input_data' => json_encode($inputData),
        ];

        $response = Http::get($apiUrl, $params);
        if ($response->ok()) {
            $responseBody = $response->json();
            if ($responseBody['status'] === 'success') {
                return [
                    'status' => 'success',
                    'message' => 'FTP-аккаунт успешно добавлен.',
                    'login' => $login,
                    'password' => $password,
                ];
            } else {
                return [
                    'status' => 'error',
                    'error_text' => 'Ошибка при добавлении FTP-аккаунта: ' . json_encode($responseBody),
                ];
            }
        } else {
            return [
                'status' => 'error',
                'error_text' => 'Ошибка при подключении к API Beget.',
                'http_status' => $response->status(),
                'details' => $response->body(),
            ];
        }
    }

    /**
     * Функция для создания базы данных.
     */

    public function addDatabase($name)
    {
        $apiUrl = 'https://api.beget.com/api/mysql/addDb';

        $login = $name;
        $password = Str::random(8);

        $params = [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'input_format' => 'json',
            'output_format' => 'json',
            'input_data' => json_encode([
                'suffix' => $login,
                'password' => $password
            ]),
        ];

        $response = Http::get($apiUrl, $params);

        if ($response->ok()) {
            return [
                'status' => 'success',
                'login' => 'k1z1nksb_' . $login,
                'password' => $password
            ];
        } else {
            return [
                'status' => 'error',
                'error_text' => 'Ошибка при подключении к API Beget.',
            ];
        }
    }

    public function link($domainId, $siteId)
    {
        $apiUrl = 'https://api.beget.com/api/site/linkDomain';

        $params = [
            'login' => $this->apiLogin,
            'passwd' => $this->apiPassword,
            'input_format' => 'json',
            'output_format' => 'json',
            'input_data' => json_encode([
                'domain_id' => $domainId,
                'site_id' => $siteId
            ]),
        ];

        $response = Http::get($apiUrl, $params);

        if ($response->ok()) {
            return ['status' => 'success'];
        } else {
            return [
                'status' => 'error',
                'error_text' => 'Ошибка при подключении к API Beget.',
            ];
        }
    }

}
